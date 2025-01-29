
<script>

    const deleteConfirmPopup = document.getElementById('deleteConfirmPopup');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const successMessage = document.getElementById('successMessage');
    const messageBox = document.getElementById('messageBox');

    let currentEditEmployee = null;
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
        }
        // else if (employee)
        // {
        //     document.getElementById('popupTitle').innerText = 'Employee Details';
        //     document.getElementById('employeeId').value = employee.id;
        //     document.getElementById('name').value = employee.name;
        //     document.getElementById('email').value = employee.email;
        //     document.getElementById('password').value = employee.password;
        //     document.getElementById('position').value = employee.position;
        //     document.getElementById('salary').value = employee.salary;
        // }
        else{
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
     // Clear previous error messages
        document.getElementById('nameError').style.display = 'none';
        document.getElementById('emailError').style.display = 'none';
        document.getElementById('passwordError').style.display = 'none';
        document.getElementById('positionError').style.display = 'none';
        document.getElementById('salaryError').style.display = 'none';

    // // Check if the fields are empty
    // if (!name || !email || !password || !position || !salary) {
    //     alert("All fields are required!");
    //     return;
    // }

    var isValid = true;  // Flag to track form validity

        // Check if Name is empty
        if (!name) {
            document.getElementById('nameError').style.display = 'block';
            isValid = false;
        }

        // Check if Email is in valid format
        var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!email || !emailRegex.test(email)) {
            document.getElementById('emailError').style.display = 'block';
            isValid = false;
        }

        // Check if Password is at least 8 characters and contains letters, numbers, and symbols
        var passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!password || !passwordRegex.test(password)) {
            document.getElementById('passwordError').style.display = 'block';
            isValid = false;
        }

        // Check if Position is selected
        if (!position) {
            document.getElementById('positionError').style.display = 'block';
            isValid = false;
        }

        // Check if Salary is entered
        if (!salary) {
            document.getElementById('salaryError').style.display = 'block';
            isValid = false;
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
</script>