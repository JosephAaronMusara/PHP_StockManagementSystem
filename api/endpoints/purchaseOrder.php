<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Accept");
header("Content-Type: application/json");
require '../includes/config.php';
include '../core/Alert.php';
require '../core/Database.php';

$method = $_SERVER['REQUEST_METHOD'];
$db = new Database();
$p_order = new PurchaseOrder($db->getConnection());
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
            $response['action'] = 'Get P-O';
            $response['received'] = ['id' => $_GET['id']];
            $response['data'] = $p_order->getPurchaseOrderById($_GET['id']);
            if ($response['data']) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['message'] = 'P-O not found.';
            }
        } else {
            $response['action'] = 'Get All P-Os';
            $response['received'] = [];
            $response['data'] = $p_order->getAllPurchaseOrders();
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
        $response['action'] = 'Add P-O';
        $response['received'] = $requestBody;
        $createResponse = $p_order->addPurchaseOrder($requestBody);
        $response = array_merge($response, $createResponse);
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $response = ['success' => false, 'message' => 'P-O ID required.'];
            break;
        }
        $response['action'] = 'Delete P-O';
        $response['received'] = ['id' => $id];
        $deleteResponse = $p_order->deletePurchaseOrder($id);
        $response = array_merge($response, $deleteResponse);
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
