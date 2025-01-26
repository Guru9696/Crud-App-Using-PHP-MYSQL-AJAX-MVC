// Fetch all employees and display them
function fetchEmployees() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'index.php?action=getAll', true);
    xhr.onload = function () {
        if (xhr.status == 200) {
            let employees = JSON.parse(xhr.responseText);
            let output = '<ul>';
            employees.forEach(employee => {
                output += `
                    <li>
                        ${employee.name} - ${employee.position} - $${employee.salary} - ${employee.email}
                        <button onclick="editEmployee(${employee.id})">Edit</button>
                        <button onclick="deleteEmployee(${employee.id})">Delete</button>
                    </li>
                `;
            });
            output += '</ul>';
            document.getElementById('employee-list').innerHTML = output;
        }
    };
    xhr.send();
}

// Add new employee
function addEmployee() {
    var name = document.getElementById('employee-name').value;
    var email = document.getElementById('employee-email').value;
    var position = document.getElementById('employee-position').value;
    var salary = document.getElementById('employee-salary').value;

    var formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('position', position);
    formData.append('salary', salary);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'index.php?action=add', true);
    xhr.onload = function () {
        fetchEmployees();
    };
    xhr.send(formData);
}

// Edit employee
function editEmployee(id) {
    var name = prompt('Enter new employee name:');
    var email = prompt('Enter new employee email:');
    var position = prompt('Enter new employee position:');
    var salary = prompt('Enter new employee salary:');

    if (name && email && position && salary) {
        var formData = new FormData();
        formData.append('id', id);
        formData.append('name', name);
        formData.append('email', email);
        formData.append('position', position);
        formData.append('salary', salary);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php?action=update', true);
        xhr.onload = function () {
            fetchEmployees();
        };
        xhr.send(formData);
    }
}

// Delete employee
function deleteEmployee(id) {
    var formData = new FormData();
    formData.append('id', id);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'index.php?action=delete', true);
    xhr.onload = function () {
        fetchEmployees();
    };
    xhr.send(formData);
}

// Initial fetch of employees
fetchEmployees();
