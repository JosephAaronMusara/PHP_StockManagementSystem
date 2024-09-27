<?php
include '../includes/headers.php';
require '../includes/config.php';
include '../core/User.php';
require '../core/Database.php';

$method = $_SERVER['REQUEST_METHOD'];
$user = new User();
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
    case 'POST':
        if (isset($_GET['action']) && $_GET['action'] === 'login') {
            // Handle login request
            $response['action'] = 'Login User';
            $response['received'] = $requestBody;
            $loginResponse = $user->loginUser($requestBody);
            $response = array_merge($response, $loginResponse);
            if ($loginResponse['success']) {
                if ($loginResponse['role'] === 'admin') {
                    $response['redirect'] = 'http://localhost/StockManagementSystem/frontend/dashboard/admin.html';  // Redirect to admin dashboard
                } else {
                    $response['redirect'] = 'http://localhost/StockManagementSystem/frontend/dashboard/user.html';  // Redirect to user dashboard
                }
            }
        } elseif (isset($_GET['action']) && $_GET['action'] === 'logout') {
            // Handle logout request
            $response['action'] = 'Logout User';
            $logoutResponse = $user->logoutUser();
            $response = array_merge($response, $logoutResponse);
        } else {
            // Handle registration
            $response['action'] = 'Create User';
            $response['received'] = $requestBody;
            $createResponse = $user->createUser($requestBody);
            $response = array_merge($response, $createResponse);
        }
        break;
    case 'PUT':
        if (isset($_GET['action']) && $_GET['action'] === 'update') {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                $response = ['success' => false, 'message' => 'User ID required.'];
                break;
            }
            $response['action'] = 'Update User Details';
            $response['received'] = array_merge(['id' => $id], $requestBody);
            $updateResponse = $user->updateUserDetails($id, $requestBody);
            $response = array_merge($response, $updateResponse);
        }
        break;
    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $response = ['success' => false, 'message' => 'User ID required.'];
            break;
        }
        $response['action'] = 'Delete User';
        $response['received'] = ['id' => $id];
        $deleteResponse = $user->deleteUser($id);
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
    echo "const response = " . json_encode($response, JSON_UNESCAPED_SLASHES) . ";";
} else {
    header("Content-Type: application/json");
    echo json_encode($response, JSON_UNESCAPED_SLASHES);
}
