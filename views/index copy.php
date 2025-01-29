<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <link href="https://fonts.google.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        /* General Body Style */
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
            margin-bottom: 20px;
        }

        .controls .search-container {
            display: flex;
            gap: 10px;
        }

        .controls .search-container input, .controls .search-container select {
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

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

        /* Table Styles */
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

    </style>

</head>
<body>

<h2>Employee Management</h2>

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
            <th>Email</th>
            <th>Salary</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Pagination -->
<div class="pagination" id="pagination"></div>

<!-- Add/Edit Employee Popup -->
<div class="popup" id="employeePopup">
    <div class="popup-content">
        <span class="close-popup" id="closePopupBtn">×</span>
        <h3 id="popupTitle">Add Employee</h3>
        <input type="hidden" id="employeeId" />
        <input type="text" id="name" placeholder="Employee Name" required>
        <input type="email" id="email" placeholder="Email" required>
        <input type="password" id="password" placeholder="Password" required>
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

<!-- Success Message -->
<div id="successMessage">Record Deleted</div>

<script>
    const employeeTable = document.getElementById('employeeTable').getElementsByTagName('tbody')[0];
    const addEmployeeBtn = document.getElementById('addEmployeeBtn');
    const employeePopup = document.getElementById('employeePopup');
    const closePopupBtn = document.getElementById('closePopupBtn');
    const saveBtn = document.getElementById('saveBtn');
    const closePopup = document.getElementById('closePopup');
    const searchInput = document.getElementById('searchInput');
    const recordsPerPage = document.getElementById('recordsPerPage');
    const pagination = document.getElementById('pagination');

    let currentPage = 1;
    let totalEmployees = [];
    let recordsToShow = 10;
    let searchQuery = '';

    // Fetch all employees from the server
    function renderEmployees() {
        searchQuery = searchInput.value.toLowerCase();
        recordsToShow = parseInt(recordsPerPage.value);

        fetch('../controllers/EmployeeController.php?endpoint=employees', { method: 'GET' })
            .then(response => response.json())
            .then(employees => {
                totalEmployees = employees;

                // Filter employees based on search query
                const filteredEmployees = employees.filter(employee =>
                    employee.name.toLowerCase().includes(searchQuery) ||
                    employee.email.toLowerCase().includes(searchQuery) ||
                    employee.salary.toString().includes(searchQuery) ||
                    employee.position.toLowerCase().includes(searchQuery)
                );

                const startIndex = (currentPage - 1) * recordsToShow;
                const paginatedEmployees = filteredEmployees.slice(startIndex, startIndex + recordsToShow);

                employeeTable.innerHTML = '';
                paginatedEmployees.forEach((employee, index) => {
                    const row = employeeTable.insertRow();
                    row.innerHTML = `
                        <td>${startIndex + index + 1}</td>
                        <td>${employee.name}</td>
                        <td>${employee.position}</td>
                        <td>${employee.email}</td>
                        <td>${employee.salary}</td>
                        <td>
                            <button class="editBtn button" data-id="${employee.id}">Edit ✏️</button>
                            <button class="deleteBtn button button-danger" data-id="${employee.id}">Delete 🗑️</button>
                        </td>
                    `;
                });

                renderPagination(filteredEmployees.length);
                attachActionButtons();
            });
    }

    // Render Pagination
    function renderPagination(totalRecords) {
        const totalPages = Math.ceil(totalRecords / recordsToShow);
        let paginationHTML = '';

        // Previous Page Button
        paginationHTML += `<button class="paginationBtn ${currentPage === 1 ? 'disabled' : ''}" onclick="changePage(currentPage - 1)">Previous</button>`;

        // Page Number Buttons
        for (let i = 1; i <= totalPages; i++) {
            paginationHTML += `<button class="paginationBtn ${i === currentPage ? 'disabled' : ''}" onclick="changePage(${i})">${i}</button>`;
        }

        // Next Page Button
        paginationHTML += `<button class="paginationBtn ${currentPage === totalPages ? 'disabled' : ''}" onclick="changePage(currentPage + 1)">Next</button>`;

        pagination.innerHTML = paginationHTML;
    }

    // Change Page
    function changePage(page) {
        if (page < 1 || page > Math.ceil(totalEmployees.length / recordsToShow)) return;
        currentPage = page;
        renderEmployees();
    }

    // Attach event listeners for edit and delete buttons
    function attachActionButtons() {
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', editEmployee);
        });
        document.querySelectorAll('.deleteBtn').forEach(btn => {
            btn.addEventListener('click', deleteEmployee);
        });
    }

    // Search functionality
    searchInput.addEventListener('input', renderEmployees);

    // Initial rendering of employees
    renderEmployees();
</script>

</body>
</html>
