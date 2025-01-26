<?php
// Include the necessary files
require_once '../models/EmployeeModel.php';

class EmployeeController {
    private $employeeModel;

    public function __construct() {
        // Initialize the EmployeeModel instance
        $this->employeeModel = new EmployeeModel();
    }

    // Handle the GET request for fetching employees
    public function getEmployees() {
        $employees = $this->employeeModel->getAllEmployees();
        header('Content-Type: application/json');
        echo json_encode($employees);
    }

    // Handle the POST request for adding a new employee
    public function addEmployee() {
        // Get the data from the request body
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate the input data
        if (empty($data['name']) || empty($data['email']) || empty($data['position']) || empty($data['salary'])) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
            exit;
        }

        // Call the model method to add the employee
        $result = $this->employeeModel->addEmployee($data['name'], $data['email'], $data['password'], $data['position'], $data['salary']);
        
        // Return a JSON response
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Employee added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add employee']);
        }
    }

    // Handle the PUT request for updating an existing employee
    public function updateEmployee() {
        // Get the data from the request body
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate the input data
        if (empty($data['id']) || empty($data['name']) || empty($data['email']) || empty($data['position']) || empty($data['salary'])) {
            echo json_encode(['status' => 'error', 'message' => 'ID, Name, Email, Position, and Salary are required']);
            exit;
        }

        // Call the model method to update the employee
        $result = $this->employeeModel->updateEmployee($data['id'], $data['name'], $data['email'], $data['password'], $data['position'], $data['salary']);
        
        // Return a JSON response
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Employee updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update employee']);
        }
    }

    // Handle the DELETE request for deleting an employee
    public function deleteEmployee() {
        // Get the data from the request body
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate the input data
        if (empty($data['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Employee ID is required']);
            exit;
        }

        // Call the model method to delete the employee
        $result = $this->employeeModel->deleteEmployee($data['id']);
        
        // Return a JSON response
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Employee deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete employee']);
        }
    }
}

// Instantiate the controller and handle the request
$controller = new EmployeeController();
$request_method = $_SERVER['REQUEST_METHOD'];
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : null;

switch ($endpoint) {
    case 'employees':
        switch ($request_method) {
            case 'GET':
                $controller->getEmployees();
                break;
            case 'POST':
                $controller->addEmployee();
                break;
            case 'PUT':
                $controller->updateEmployee();
                break;
            case 'DELETE':
                $controller->deleteEmployee();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
        }
        break;

    default:
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['status' => 'error', 'message' => 'Endpoint not found']);
}
?>
