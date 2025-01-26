
-- create database
CREATE DATABASE company;

-- create table employees
CREATE TABLE Employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    salary DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



-- Insert 10 records

INSERT INTO Employees (name, email, password, position, salary) VALUES
('Alice Smith', 'alice.smith@example.com', 'password123', 'Manager', 85000.00),
('Bob Johnson', 'bob.johnson@example.com', 'password456', 'Developer', 70000.00),
('Charlie Brown', 'charlie.brown@example.com', 'password789', 'Designer', 65000.00),
('David White', 'david.white@example.com', 'password321', 'HR', 60000.00),
('Eva Green', 'eva.green@example.com', 'password654', 'Sales', 55000.00),
('Frank Black', 'frank.black@example.com', 'password987', 'Developer', 72000.00),
('Grace Lee', 'grace.lee@example.com', 'password123', 'Designer', 68000.00),
('Helen Blue', 'helen.blue@example.com', 'password456', 'Manager', 90000.00),
('Ivan Grey', 'ivan.grey@example.com', 'password789', 'HR', 59000.00),
('Jack Red', 'jack.red@example.com', 'password321', 'Sales', 53000.00);


