<?php
// Include the necessary files
require_once '../models/EmployeeModel.php';

// Get the endpoint parameter from the URL
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : null;
$request_method = $_SERVER['REQUEST_METHOD'];

if ($endpoint === 'employees' && $request_method === 'GET' ) {
    // Create an instance of the EmployeeModel
    $employeeModel = new EmployeeModel();
    
    // Fetch all employees from the model
    $employees = $employeeModel->getAllEmployees();
    
    // Set the content type to JSON
    header('Content-Type: application/json');
    
    // Send the employee data as a JSON response
    echo json_encode($employees);
    
}elseif($endpoint === 'employees' && $request_method === 'POST' ){
        // Create an instance of the EmployeeModel
    $employeeModel = new EmployeeModel();

    // Get the POST data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if the required fields are present
    if (empty($data['name']) || empty($data['email']) || empty($data['position']) || empty($data['salary'])) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    // Add the employee using the model
    $result = $employeeModel->addEmployee($data['name'], $data['email'], $data['password'], $data['position'], $data['salary']);
    
    // Set the content type to JSON
    header('Content-Type: application/json');
    
    // Send the response
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Employee added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add employee']);
    }


}elseif ($endpoint === 'employees' && $request_method === 'PUT') {
    // Create an instance of the EmployeeModel
    $employeeModel = new EmployeeModel();

    // Get the PUT data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if the required fields are present
    if (empty($data['id']) || empty($data['name']) || empty($data['email']) || empty($data['position']) || empty($data['salary'])) {
        echo json_encode(['status' => 'error', 'message' => 'ID, Name, Email, Position, and Salary are required']);
        exit;
    }

    // Update the employee using the model
    $result = $employeeModel->updateEmployee($data['id'], $data['name'], $data['email'], $data['password'], $data['position'], $data['salary']);
    
    // Set the content type to JSON
    header('Content-Type: application/json');
    
    // Send the response
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Employee updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update employee']);
    }

} elseif ($endpoint === 'employees' && $request_method === 'DELETE') {
    // Create an instance of the EmployeeModel
    $employeeModel = new EmployeeModel();

    // Get the DELETE data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if ID is provided for deletion
    if (empty($data['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Employee ID is required']);
        exit;
    }

    // Delete the employee using the model
    $result = $employeeModel->deleteEmployee($data['id']);
    
    // Set the content type to JSON
    header('Content-Type: application/json');
    
    // Send the response
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Employee deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete employee']);
    }

}else {
    // Return a 404 response if the endpoint is not found
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'Endpoint not found']);
}
?>
