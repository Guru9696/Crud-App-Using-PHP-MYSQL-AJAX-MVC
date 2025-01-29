<?php
// Include the necessary files
require_once '../models/EmployeeModel.php';

class EmployeeController {
    private $employeeModel;

    public function __construct() {
        // Initialize the EmployeeModel instance
        $this->employeeModel = new EmployeeModel();
    }

    // // Handle the GET request for fetching employees
    // public function getEmployees() {
    //     $employees = $this->employeeModel->getAllEmployees();
    //     header('Content-Type: application/json');
    //     echo json_encode($employees);
    // }

     // Handle the employee listing with pagination
    //  public function getEmployees($page = 1, $recordsPerPage = 10) {
    //     $offset = ($page - 1) * $recordsPerPage;

    //     // Get the employees for the current page
    //     $employees = $this->employeeModel->getEmployees($recordsPerPage, $offset);

    //     // Get total employees count for pagination
    //     $totalEmployees = $this->employeeModel->getTotalEmployees();
    //     $totalPages = ceil($totalEmployees / $recordsPerPage);

    //     // Include the view and pass data
    //     require_once '../views/index.php';
    // }


     // Handle the employee listing with pagination via API
     public function getEmployees() {
        // Get the page and records per page from the query parameters (default values)
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $recordsPerPage = isset($_GET['recordsPerPage']) ? (int)$_GET['recordsPerPage'] : 10;
        $offset = ($page - 1) * $recordsPerPage;

        // Get employees and total count
        $employees = $this->employeeModel->getEmployees($recordsPerPage, $offset);
        $totalEmployees = $this->employeeModel->getTotalEmployees();
        $totalPages = ceil($totalEmployees / $recordsPerPage);

        // Prepare response
        $response = [
            'employees' => $employees,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ];

        // Return response as JSON
        header('Content-Type: application/json');
        echo json_encode($response);
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
// Handle the request based on the endpoint
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['endpoint']) && $_GET['endpoint'] === 'employees') {
//     $controller = new EmployeeController();
//     $controller->getEmployees();
// }

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
