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
        
        /* Table and Controls Layout */
        .controls {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0px;
            /* margin-top: 15px; */

        }

        .controls .search-container {
            display: flex;
            gap: 10px;
        }

        .controls  .search-container input, .controls .search-container select {
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .show-container{
            padding: 10px;
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ddd;

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
            margin: 5px 0;
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

           /* Pagination */
           .pagination {
            text-align: right;
            margin-top: 20px;
        }

        .pagination button {
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            cursor: pointer;
            margin: 0 5px;
            font-size: 14px;
        }

        .pagination button:hover {
            background-color: #f0f0f0;
        }

        .pagination .disabled {
            background-color: #ddd;
            cursor: not-allowed;
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
        .error{
            font: 2px;
        }
    </style>

</head>
<body>

<h2>Employee Management </h2>


<div id="messageBox" style="display:none; background-color: #4CAF50; color: white; padding: 10px; margin-top: 10px;">
    Employee action was successful!
</div>



<button id="addEmployeeBtn" class="button">Add Employee ➕</button>

<!-- Controls Section (Search & Show Options) -->
<div class="controls">
    <!-- Show Option on the left side -->
    <div class="show-container">
    <label for="recordsPerPage">Show:</label>
        <select id="recordsPerPage" onchange="renderEmployees()">
        <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="all">All</option>
        </select>
    </div>

    <!-- Search Option on the right side -->
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search by name, email, salary, position">
    </div>
</div>
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
    <tbody>
        
    </tbody>
</table>

<!-- Pagination -->

<!-- Pagination Controls -->
<!-- Pagination Controls -->
<div id="pagination"></div>
<!-- <div class="pagination" id="pagination"></div> -->

<!-- Add/Edit Employee Popup -->
<div class="popup" id="employeePopup">
    <div class="popup-content">
        <span class="close-popup" id="closePopupBtn">×</span>
        <h3 id="popupTitle">Add Employee➕</h3>
        <input type="hidden" id="employeeId" />

        <!-- Employee Name -->
        <input type="text" id="name" placeholder="Employee Name" required>
        <div id="nameError" class="error" style="color: red; display: none;">Name is required.</div>

        <!-- Email -->
        <input type="email" id="email" placeholder="Email" required>
        <div id="emailError" class="error" style="color: red; display: none;">Please enter a valid email address.</div>

        <!-- Password -->
        <input type="password" id="password" placeholder="Password" required>
        <div id="passwordError" class="error" style="color: red; display: none;">Password must be at least 8 characters long and include letters, numbers, and symbols.</div>

        <!-- Position -->
        <select id="position" required>
            <option value="">Select Position</option>
            <option value="Manager">Manager</option>
            <option value="Developer">Developer</option>
            <option value="Designer">Designer</option>
            <option value="HR">HR</option>
            <option value="Sales">Sales</option>
        </select>
        <div id="positionError" class="error" style="color: red; display: none;">Position is required.</div>

        <!-- Salary -->
        <input type="number" id="salary" placeholder="Salary" required>
        <div id="salaryError" class="error" style="color: red; display: none;">Salary is required.</div>

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

    // DOM Elements
const employeeTable = document.getElementById('employeeTable');
const paginationContainer = document.getElementById('pagination');
const searchInput = document.getElementById('searchInput');
const recordsPerPage = document.getElementById('recordsPerPage');
let currentPage = 1;
let totalEmployees = [];
let totalPages = 0;

function renderEmployees() {
    // Fetch employees data from the API
    const searchQuery = searchInput.value.toLowerCase();
    const recordsToShow = parseInt(recordsPerPage.value);

    // Make the API call
    fetch(`../controllers/EmployeeController.php?endpoint=employees&page=${currentPage}&recordsPerPage=${recordsToShow}`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        totalEmployees = data.employees;
        totalPages = data.totalPages;

        // Filter employees based on search query
        const filteredEmployees = totalEmployees.filter(employee =>
            employee.name.toLowerCase().includes(searchQuery) ||
            employee.email.toLowerCase().includes(searchQuery) ||
            employee.position.toLowerCase().includes(searchQuery)
        );

        // Clear the table before rendering new data
        employeeTable.innerHTML = '';

        // Render employee rows
        filteredEmployees.forEach((employee, index) => {
            const row = employeeTable.insertRow();
            row.innerHTML = `
                <td>${(currentPage - 1) * recordsToShow + index + 1}</td>
                <td>${employee.name}</td>
                <td>${employee.email}</td>
                <td>${employee.position}</td>
                <td>${employee.salary}</td>
                <td>${employee.created_at}</td>
                <td>${employee.updated_at}</td>
                
                <td>
                    <button class="editBtn" data-id="${employee.id}">Edit ✏️</button>
                    <button class="deleteBtn" data-id="${employee.id}">Delete 🗑️</button>
                </td>
            `;
        });

        renderPagination();
    });
}

function renderPagination() {
    // Clear existing pagination links
    paginationContainer.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.classList.add('pagination-btn');
        button.onclick = () => {
            currentPage = i;
            renderEmployees();
        };
        paginationContainer.appendChild(button);
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
    renderEmployees();

    // Re-render employees when search input changes
    searchInput.addEventListener('input', renderEmployees);

    // Re-render employees when records per page changes
    recordsPerPage.addEventListener('change', renderEmployees);
});

</script>
</body>
</html>
