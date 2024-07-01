<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    // Handle Create and Update actions
    if ($action == 'create' || $action == 'update') {
        $name = $conn->real_escape_string($_POST['name']);
        $surname = $conn->real_escape_string($_POST['surname']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $age = (int)$_POST['age'];
        $dob = $conn->real_escape_string($_POST['dob']);
        $address = $conn->real_escape_string($_POST['address']);
        $religion = $conn->real_escape_string($_POST['religion']);
        $ethnicity = $conn->real_escape_string($_POST['ethnicity']);
        $phonenumber = $conn->real_escape_string($_POST['phonenumber']);
        $status = $conn->real_escape_string($_POST['status']);
        $email = $conn->real_escape_string($_POST['email']);

        if ($action == 'create') {
            $sql = "INSERT INTO Employee (Name, Surname, Gender, Age, DateOfBirth, Address, Religion, Ethnicity, PhoneNumber, Status, Email)
                    VALUES ('$name', '$surname', '$gender', $age, '$dob', '$address', '$religion', '$ethnicity', '$phonenumber', '$status', '$email')";
        } else {
            $id = (int)$_POST['E_ID'];
            $sql = "UPDATE Employee SET Name='$name', Surname='$surname', Gender='$gender', Age=$age, DateOfBirth='$dob', Address='$address', Religion='$religion', Ethnicity='$ethnicity', PhoneNumber='$phonenumber', Status='$status', Email='$email' WHERE E_ID=$id";
        }
        
        if ($conn->query($sql) === TRUE) {
            $message = $action == 'create' ? "New employee record created successfully" : "Employee record updated successfully";
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Handle Delete action
    if ($action == 'delete') {
        if (isset($_POST['E_ID'])) {
            $id = (int)$_POST['E_ID'];
            $sql = "DELETE FROM Employee WHERE E_ID=$id";
            if ($conn->query($sql) === TRUE) {
                $message = "Employee record deleted successfully";
                header("Location: {$_SERVER['PHP_SELF']}");
                exit();
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $message = "No ID specified for deletion.";
        }
    }
}

$employees = $conn->query("SELECT * FROM Employee");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Management</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
  <?php include("nav.php");?>

  <div class="container mt-5">
    <h1 class="mb-4 text-center">Employee Management</h1>

    <?php if (isset($message)): ?>
    <div class="alert alert-info">
      <?php echo $message; ?>
    </div>
    <?php endif; ?>

    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#employeeModal" onclick="openModal('create')">
      Add Employee
    </button>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>E_ID</th>
          <th>Name</th>
          <th>Surname</th>
          <th>Gender</th>
          <th>Age</th>
          <th>Date of Birth</th>
          <th>Address</th>
          <th>Religion</th>
          <th>Ethnicity</th>
          <th>Phone Number</th>
          <th>Status</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($employee = $employees->fetch_assoc()): ?>
        <tr>
          <td><?php echo $employee['E_ID']; ?></td>
          <td><?php echo $employee['Name']; ?></td>
          <td><?php echo $employee['Surname']; ?></td>
          <td><?php echo $employee['Gender']; ?></td>
          <td><?php echo $employee['Age']; ?></td>
          <td><?php echo $employee['DateOfBirth']; ?></td>
          <td><?php echo $employee['Address']; ?></td>
          <td><?php echo $employee['Religion']; ?></td>
          <td><?php echo $employee['Ethnicity']; ?></td>
          <td><?php echo $employee['PhoneNumber']; ?></td>
          <td><?php echo $employee['Status']; ?></td>
          <td><?php echo $employee['Email']; ?></td>
          <td>
            <button class="btn btn-warning" data-toggle="modal" data-target="#employeeModal" onclick="openModal('update', <?php echo htmlspecialchars(json_encode($employee)); ?>)">Edit</button>
            <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" onclick="openDeleteModal(<?php echo $employee['E_ID']; ?>)">Delete</button>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Employee Modal -->
  <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="employeeModalLabel">Employee Form</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="employeeForm" method="POST">
            <input type="hidden" name="action" id="employeeAction">
            <input type="hidden" name="E_ID" id="employeeId">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" name="name" id="name" required>
            </div>
            <div class="form-group">
              <label for="surname">Surname</label>
              <input type="text" class="form-control" name="surname" id="surname" required>
            </div>
            <div class="form-group">
              <label for="gender">Gender</label>
              <input type="text" class="form-control" name="gender" id="gender" required>
            </div>
            <div class="form-group">
              <label for="age">Age</label>
              <input type="number" class="form-control" name="age" id="age" required>
            </div>
            <div class="form-group" lang="en">
              <label for="dob" lang="en">Date of Birth</label>
              <input type="date" class="form-control" lang="en" name="dob" id="dob" required>
            </div>
            <div class="form-group">
              <label for="address">Address</label>
              <input type="text" class="form-control" name="address" id="address" required>
            </div>
            <div class="form-group">
              <label for="religion">Religion</label>
              <input type="text" class="form-control" name="religion" id="religion" required>
            </div>
            <div class="form-group">
              <label for="ethnicity">Ethnicity</label>
              <input type="text" class="form-control" name="ethnicity" id="ethnicity" required>
            </div>
            <div class="form-group">
              <label for="phonenumber">Phone Number</label>
              <input type="text" class="form-control" name="phonenumber" id="phonenumber" required>
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <input type="text" class="form-control" name="status" id="status" required>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete Employee</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this employee record?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <form method="post" action="">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="E_ID" id="deleteId">
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function openModal(action, employee = null) {
      if (action === 'create') {
        document.getElementById('employeeModalLabel').innerHTML = 'Add Employee';
        document.getElementById('employeeForm').reset();
        document.getElementById('employeeAction').value = 'create';
      } else if (action === 'update' && employee) {
        document.getElementById('employeeModalLabel').innerHTML = 'Edit Employee';
        document.getElementById('employeeId').value = employee.E_ID;
        document.getElementById('name').value = employee.Name;
        document.getElementById('surname').value = employee.Surname;
        document.getElementById('gender').value = employee.Gender;
        document.getElementById('age').value = employee.Age;
        document.getElementById('dob').value = employee.DateOfBirth;
        document.getElementById('address').value = employee.Address;
        document.getElementById('religion').value = employee.Religion;
        document.getElementById('ethnicity').value = employee.Ethnicity;
        document.getElementById('phonenumber').value = employee.PhoneNumber;
        document.getElementById('status').value = employee.Status;
        document.getElementById('email').value = employee.Email;
        document.getElementById('employeeAction').value = 'update';
      }
    }

    function openDeleteModal(id) {
      document.getElementById('deleteId').value = id;
    }
  </script>

</body>
</html>

