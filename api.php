<?php

require_once "./config.php";
require_once "./Classes.php";
require_once "./functions.php";

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');


$connection = new DatabaseConnector();
$conn = $connection->getConnection();

$incident = new Incident($conn);



// Handle the API request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (strpos($_SERVER["CONTENT_TYPE"], "multipart/form-data") !== false) {
        // Multipart form-data request
        $action = $_POST['action'];

        if (isset($action)) {
            switch ($action) {
                case 'create_incident':
                    $incidentData = array(
                        'datetime' => $_POST['datetime'],
                        'latitude' => $_POST['latitude'],
                        'longitude' => $_POST['longitude'],
                        'altitude' => $_POST['altitude'],
                        'accuracy' => $_POST['accuracy'],
                        'city' => $_POST['city'],
                        'division' => $_POST['division'],
                        'ward' => $_POST['ward'],
                        'cell' => $_POST['cell'],
                        'street' => $_POST['street'],
                        'other_street' => $_POST['other_street'], // Not in formState, add manually if needed
                        'incident_type' => $_POST['incidentType'],
                        'other_incident_type' => $_POST['otherIncidentType'], // Not in formState, add manually if needed
                        'incident_details' => $_POST['incidentDetails'],
                        // 'created_at' => $data['created_at'], // Optional - may be set on server side
                        // 'updated_at' => $data['updated_at'], // Optional - may be set on server side
                    );
                    // var_dump($incidentData);                 
                    echo json_encode($incident->createIncident($incidentData));
                    break;
                default:
                    echo json_encode(array('status' => 'error', 'message' => 'Invalid action first one'));
                    break;
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Action not specified'));
        }
    } else {
        // JSON request
        $data = $_POST;
        // $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                    // Start:: Department CRUDS
                case 'create_incident':
                    $incidentData = array(
                        'datetime' => $data['datetime'],
                        'latitude' => $data['latitude'],
                        'longitude' => $data['longitude'],
                        'altitude' => $data['altitude'],
                        'accuracy' => $data['accuracy'],
                        'city' => $data['city'],
                        'division' => $data['division'],
                        'ward' => $data['ward'],
                        'cell' => $data['cell'],
                        'street' => $data['street'],
                        'other_street' => $data['other_street'], // Not in formState, add manually if needed
                        'incident_type' => $data['incidentType'],
                        'other_incident_type' => $data['other_incident_type'], // Not in formState, add manually if needed
                        'incident_details' => $data['incidentDetails'],
                        // 'created_at' => $data['created_at'], // Optional - may be set on server side
                        // 'updated_at' => $data['updated_at'], // Optional - may be set on server side
                    );
                    // var_dump($incidentData);                 
                    echo json_encode($incident->createIncident($incidentData));
                    break;

                case 'login':
                    echo $incident->login($data['email'], $data['password']);
                    break;

                case 'register':
                    $userdata = array(
                        'username'=> $data['username'],
                        'email' => $data['email'],
                        'password'=> md5($data['password']),
                        'contact'=> $data['contact'],
                    );
                    echo $incident->registeruser($userdata);
                    break;
                
                default:
                    echo json_encode(array('status' => 'error', 'message' => 'Invalid action state here'));
                    break;
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Action not specified'));
        }
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
}
