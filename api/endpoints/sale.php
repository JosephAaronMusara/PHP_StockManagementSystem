<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Accept");
header("Content-Type: application/json");
require '../includes/config.php';
include '../core/Sale.php';
require '../core/Database.php';

$method = $_SERVER['REQUEST_METHOD'];
$db = new Database();
$sale = new Sale($db->getConnection());
$response = [];

function returnXML($data)
{
    $xml = new SimpleXMLElement('<response/>');
    array_to_xml($data, $xml);
    return $xml->asXML();
}

function array_to_xml($data, &$xml)
{
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $subnode = $xml->addChild("$key");
            array_to_xml($value, $subnode);
        } else {
            $xml->addChild("$key", htmlspecialchars("$value"));
        }
    }
}

function handleGraphQL($query)
{
    return ['data' => 'GraphQL response for query: ' . $query];
}

// Parse request body for different formats
$requestBody = '';
if (in_array($method, ['POST', 'PUT'])) {
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $requestBody = json_decode(file_get_contents("php://input"), true);
    } elseif ($_SERVER['CONTENT_TYPE'] === 'application/x-www-form-urlencoded') {
        $requestBody = $_POST;
    } elseif ($_SERVER['CONTENT_TYPE'] === 'multipart/form-data') {
        $requestBody = $_POST;
        // Files can be handled from here: $_FILES
    }
}

$acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? 'application/json';

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get sale by ID
            $response['action'] = 'Get Sale';
            $response['received'] = ['id' => $_GET['id']];
            $response['data'] = $sale->getSaleById($_GET['id']);
            $response['success'] = $response['data'] ? true : false;
            $response['message'] = $response['data'] ? '' : 'Sale not found.';
        } elseif (isset($_GET['item_id'])) {
            // Get item details by item_id
            $response['action'] = 'Get Item Details';
            $response['received'] = ['item_id' => $_GET['item_id']];
            $response['data'] = $sale->getItemDetails($_GET['item_id']);
            $response['success'] = $response['data'] ? true : false;
            $response['message'] = $response['data'] ? '' : 'Item not found.';
        }
        elseif (isset($_GET['customer'])) {
            $response['action'] = 'Get customer';
            $response['received'] = ['customer' => true];
            $response['data'] = $sale->getCustomers();
            $response['success'] = $response['data'] ? true : false;
            $response['message'] = $response['data'] ? '' : 'customer not found.';
        }elseif (isset($_GET['fetch_items'])) {
            // Fetch all stock items from the database
            $response['action'] = 'Get All Stock Items';
            $response['data'] = $sale->getAllStockItems();
            $response['success'] = true; 
        }else {
            // Get all sales for the logged-in user
            $response['action'] = 'Get All Sales';
            $response['received'] = [];
            $response['data'] = $sale->getAllSales();
            $response['success'] = true;
        }
        break;

    case 'POST':
        if (isset($_GET['graphql'])) {
            $query = $requestBody['query'] ?? '';
            $response['action'] = 'GraphQL Query';
            $response['received'] = $requestBody;
            $response['data'] = handleGraphQL($query);
            break;
        }
        $response['action'] = 'Add Sale';
        $response['received'] = $requestBody;
        $createResponse = $sale->addSale($requestBody);
        $response = array_merge($response, $createResponse);
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $response = ['success' => false, 'message' => 'Sale ID required.'];
            break;
        }
        $response['action'] = 'Delete Sale';
        $response['received'] = ['id' => $id];
        $deleteResponse = $sale->deleteSale($id);
        $response = array_merge($response, $deleteResponse);
        $response['success'] = true; 
        break;

    default:
        http_response_code(405);
        $response = ['success' => false, 'message' => 'Method Not Allowed'];
        break;
}

if (strpos($acceptHeader, 'application/xml') !== false) {
    header("Content-Type: application/xml");
    echo returnXML($response);
} elseif (strpos($acceptHeader, 'text/html') !== false) {
    header("Content-Type: text/html");
    echo "<html><body><pre>" . htmlspecialchars(print_r($response, true)) . "</pre></body></html>";
} elseif (strpos($acceptHeader, 'application/javascript') !== false) {
    header("Content-Type: application/javascript");
    echo "const response = " . json_encode($response) . ";";
} else {
    echo json_encode($response);
}
