<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <link href="https://fonts.googleEmployeeControllers.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://ajax.googleEmployeeControllers.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* / General Body Style / */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            font-size: 2em;
            margin-bottom: 30px;
        }

        /* / Button Styles / */
        .button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #0056b3;
        }

        .cancel-btn {
            background-color: #dc3545;
        }

        .cancel-btn:hover {
            background-color: #c82333;
        }

        /* / Table Styles / */
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }

        /* / Button for "Add Employee" - Position it to top-right / */
        #addEmployeeBtn {
            position: absolute;
            top: 70px;
            right: 20px;
            font-size: 14px;
        }

        /* / Popup Styles / */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .popup h3 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        .popup input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .popup select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .popup button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .popup button:hover {
            background-color: #0056b3;
        }
        .popup .cancel-btn {
            background-color: #dc3545;
        }
        .popup .cancel-btn:hover {
            background-color: #c82333;
        }

        .close-popup {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }

         .deleteBtn {
            background-color: #dc3545;
        }
        .deleteBtn:hover {
            background-color: #c82333;
        }

        /* / Delete Confirmation Popup Styles / */
        #deleteConfirmPopup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        #deleteConfirmContent {
            background: white;
            padding: 30px;
            border-radius: 8px;
            width: 350px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        #deleteConfirmContent button {
            width: 45%;
            margin: 5px;
        }

        /* / Success message for record deleted / */
        #successMessage {
            display: none;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }

        /* / Flex for Buttons in Popup / */
        .popup-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        .popup-footer button {
            width: 48%;
        }
    </style>

</head>
<body>

<h2>Employee Management </h2>


<div id="messageBox" style="display:none; background-color: #4CAF50; color: white; padding: 10px; margin-top: 10px;">
    Employee action was successful!
</div>



<button id="addEmployeeBtn" class="button">Add Employee ➕</button>
<!-- Table to display employees -->
<table id="employeeTable">
    <thead>
        <tr>
            <th>Sr.No</th>
            <th>Name</th>
            
            <th>Position</th>
            <th>Created Date</th>
            <th>Updated Date</th>

            <th>Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>


<!-- Add/Edit Employee Popup -->
<div class="popup" id="employeePopup">
    <div class="popup-content">
        <span class="close-popup" id="closePopupBtn">×</span>
        <h3 id="popupTitle">Add Employee➕</h3>
        <input type="hidden" id="employeeId" />
        <input type="text" id="name" placeholder="Employee Name" required>
        <input type="email" id="email" placeholder="Email" required>
        <input type="password" id="password" placeholder="Password" required>
        <!-- <input type="text" id="position" placeholder="Position" required> -->
        <select id="position" required>
    <option value="">Select Position</option>
    <option value="Manager">Manager</option>
    <option value="Developer">Developer</option>
    <option value="Designer">Designer</option>
    <option value="HR">HR</option>
    <option value="Sales">Sales</option>
</select>


        <input type="number" id="salary" placeholder="Salary" required>
       

        <div class="popup-footer">
            <button id="saveBtn">Save</button>
            <button id="closePopup" class="cancel-btn">Cancel</button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Popup -->
<div id="deleteConfirmPopup">
    <div id="deleteConfirmContent">
        <p>Are you sure you want to delete this employee?</p>
        <div class="popup-footer">
            <button id="confirmDeleteBtn" class="button">Yes</button>
            <button id="cancelDeleteBtn" class="button cancel-btn">No</button>
        </div>
    </div>
</div>

<!-- Success Message -->
<div id="successMessage">Record Deleted</div>

<script>
    const employeeTable = document.getElementById('employeeTable').getElementsByTagName('tbody')[0];
    const addEmployeeBtn = document.getElementById('addEmployeeBtn');
    const employeePopup = document.getElementById('employeePopup');
    const closePopupBtn = document.getElementById('closePopupBtn');
    const saveBtn = document.getElementById('saveBtn');
    const closePopup = document.getElementById('closePopup');
    const deleteConfirmPopup = document.getElementById('deleteConfirmPopup');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const successMessage = document.getElementById('successMessage');
    const messageBox = document.getElementById('messageBox');

    let currentEditEmployee = null;

    // Fetch all employees from the server
    function renderEmployees() {
        fetch('../controllers/EmployeeController.php?endpoint=employees', { method: 'GET' })
            .then(response => response.json())
            .then(employees => {
                employeeTable.innerHTML = '';
                employees.forEach((employee, index)=> {
                    const row = employeeTable.insertRow();
                    row.innerHTML = `
                        <td>${index+1}</td>
                        <td>${employee.name}</td>
                        <td>${employee.position}</td>
                        <td>${employee.created_at}</td>
                        <td>${employee.updated_at}</td>
                        <td>
                            <button class="editBtn button" data-id="${employee.id}">Edit ✏️</button>
                            <button class="deleteBtn button button-danger" data-id="${employee.id}">Delete 🗑️</button>
                        </td>
                    `;
                });

                // Add event listeners for edit and delete buttons
                document.querySelectorAll('.editBtn').forEach(btn => {
                    btn.addEventListener('click', editEmployee);
                });
                document.querySelectorAll('.deleteBtn').forEach(btn => {
                    btn.addEventListener('click', deleteEmployee);
                });
            });
    }

    // Open the popup to add or edit an employee
    function openEmployeePopup(employee = null) {
        if (employee) {
            document.getElementById('popupTitle').innerText = 'Edit Employee';
            document.getElementById('employeeId').value = employee.id;
            document.getElementById('name').value = employee.name;
            document.getElementById('email').value = employee.email;
            document.getElementById('password').value = employee.password;
            document.getElementById('position').value = employee.position;
            document.getElementById('salary').value = employee.salary;
            currentEditEmployee = employee;
        } else {
            document.getElementById('popupTitle').innerText = 'Add Employee';
            document.getElementById('employeeId').value = '';
            document.getElementById('name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
            document.getElementById('position').value = '';
            document.getElementById('salary').value = '';
           
            currentEditEmployee = null;
        }
        employeePopup.style.display = 'flex';
    }

// Using ajax Save the employee (add or update)
function saveEmployee() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const position = document.getElementById('position').value;
    const salary = document.getElementById('salary').value;
   
    const id = document.getElementById('employeeId').value;

    // Check if the fields are empty
    if (!name || !email || !password || !position || !salary) {
        alert("All fields are required!");
        return;
    }

    // Define the request method (POST for add, PUT for update)
    const method = currentEditEmployee ? 'PUT' : 'POST';
    const url = '../controllers/EmployeeController.php?endpoint=employees';
    
    // Prepare the data object
    const data = { name, email, password, position, salary };
    if (currentEditEmployee) {
        data.id = id;  // Include the id for updating
    }

    // Send AJAX request
    $.ajax({
        url: url,
        type: method,
        contentType: 'application/json',
        data: JSON.stringify(data), // Convert the data object to JSON string
        success: function(response) {
            if (response.status === 'success') {
                renderEmployees();  // Refresh the employee list
                employeePopup.style.display = 'none';  // Close the popup

                  // Show success message
                  const messageBox = document.getElementById('messageBox');
                if (currentEditEmployee) {
                    messageBox.textContent = 'Employee edited successfully!';
                } else {
                    messageBox.textContent = 'Employee added successfully!';
                }

                // Show the success message
                messageBox.style.display = 'block';

                // Hide the success message after 5 seconds
                setTimeout(function() {
                    messageBox.style.display = 'none';
                }, 5000);

                // Reset the current edit flag
                currentEditEmployee = false;
            } else {
                alert('Error: ' + response.message);  // Show error message
            }
        },
        error: function(xhr, status, error) {
            alert('Request failed: ' + error);  // Handle any errors
        }
    });
}


  // Edit an employee
  function editEmployee(event) {
        const employeeId = event.target.getAttribute('data-id');
        fetch('../controllers/EmployeeController.php?endpoint=employees', { method: 'GET' })
        .then(response => response.json())
        .then(employees => {
            const employee = employees.find(emp => emp.id == employeeId);
            openEmployeePopup(employee);
        });
    }

    // Save the employee (add or update)
    // function saveEmployee() {
    //     const name = document.getElementById('name').value;
    //     const email = document.getElementById('email').value;
    //     const password = document.getElementById('password').value;
    //     const position = document.getElementById('position').value;
    //     const salary = document.getElementById('salary').value;
   
    //     const id = document.getElementById('employeeId').value;

    //     const method = currentEditEmployee ? 'PUT' : 'POST';
    //     const url = '../controllers/EmployeeController.php?endpoint=employees';
    //     const data = { name, email, password, position, salary };

    //     if (currentEditEmployee) {
    //         data.id = id;
    //     }

    //     fetch(url, {
    //         method,
    //         headers: { 'Content-Type': 'application/json' },
    //         body: JSON.stringify(data)
    //     })
    //     .then(response => response.json())
    //     .then(response => {
    //         if (response.status === 'success') {
    //             renderEmployees();
    //             employeePopup.style.display = 'none';
    //         } else {
    //             alert('Error: ' + response.message);
    //         }
    //     });
    // }


   // Delete an employee
   function deleteEmployee(event) {
        const id = event.target.getAttribute('data-id');
        deleteConfirmPopup.style.display = 'flex';

        const method = 'DELETE';
        const url = '../controllers/EmployeeController.php?endpoint=employees';
        const data = {id };

        confirmDeleteBtn.onclick = function() {
            fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(response => {
                if (response.status === 'success') {
                    renderEmployees();
                    deleteConfirmPopup.style.display = 'none';
                    // successMessage.style.display = 'block';
                    // setTimeout(() => successMessage.style.display = 'none', 2000);

                  // Show success message
                   const messageBox = document.getElementById('messageBox');
                   messageBox.textContent = 'Employee delete successfully!';
            
                  // Show the success message
                     messageBox.style.display = 'block';
                 // Hide the success message after 2 seconds
                    setTimeout(() => messageBox.style.display = 'none', 2000);

         

                } else {
                    alert('Error: ' + response.message);
                }
            });
        };

        cancelDeleteBtn.onclick = function() {
            deleteConfirmPopup.style.display = 'none';
        };
    }

     

    // Close the employee popup
    closePopupBtn.addEventListener('click', function() {
        employeePopup.style.display = 'none';
    });

    // Close the employee popup (cancel button)
    closePopup.addEventListener('click', function() {
        employeePopup.style.display = 'none';
    });

    // Save employee (add or update)
    saveBtn.addEventListener('click', saveEmployee);

    // Initial rendering of employees
    renderEmployees();

    // Open the add employee popup when the button is clicked
    addEmployeeBtn.addEventListener('click', function() {
        openEmployeePopup();
    });
</script>

</body>
</html>
