<?php
require_once 'db.php'; // Database connection

class EmployeeModel {
    public function getAllEmployees() {
        global $pdo;
        
        // Query to fetch all employees from the database
        $stmt = $pdo->prepare("SELECT * FROM employees");
        $stmt->execute();
        
        // Return the result as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        // Add a new employee
        public function addEmployee($name, $email, $password, $position, $salary) {
            global $pdo;
    
            // Hash password before inserting
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // Prepare and execute the insert query
            $stmt = $pdo->prepare(
                "INSERT INTO employees (name, email, password, position, salary) 
                VALUES (:name, :email, :password, :position, :salary)"
            );
            
            // Execute the statement with the provided data
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':position' => $position,
                ':salary' => $salary
            ]);
            
            // Return the status
            return $stmt->rowCount() > 0;
        }
    
        // Update an existing employee
        public function updateEmployee($id, $name, $email, $password, $position, $salary) {
            global $pdo;
    
            // Hash password before updating
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // Prepare and execute the update query
            $stmt = $pdo->prepare(
                "UPDATE employees 
                SET name = :name, email = :email, password = :password, position = :position, salary = :salary, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id"
            );
    
            // Execute the statement with the provided data
            $stmt->execute([
                ':id' => $id,
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':position' => $position,
                ':salary' => $salary
            ]);
    
            // Return the status
            return $stmt->rowCount() > 0;
        }

                    // Delete an employee
                public function deleteEmployee($id) {
                    global $pdo;
                    $query = "DELETE FROM employees WHERE id = :id";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    return $stmt->execute(); // Return true if deleted successfully
                }
}
?>
