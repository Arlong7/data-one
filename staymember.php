<?php
// Database connection
require_once 'dbconnection.php';

// CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    // Create
    if ($action == 'create') {
        // Handle form submission for creating StayMember
        // Retrieve and sanitize form inputs
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $surname = mysqli_real_escape_string($conn, $_POST['surname']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $phoneNumber = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
        $idNumber = mysqli_real_escape_string($conn, $_POST['idNumber']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $believeInTheNation = isset($_POST['believeInTheNation']) ? 1 : 0;
        $dayOfEntry = mysqli_real_escape_string($conn, $_POST['dayOfEntry']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $membershipCode = mysqli_real_escape_string($conn, $_POST['membershipCode']);
        $position = mysqli_real_escape_string($conn, $_POST['position']);

        // Perform database insertion using prepared statement
        $sql = "INSERT INTO StayMember (Name, Surname, Gender, Status, PhoneNumber, IDNumber, Email, BelieveInTheNation, DayOfEntry, Address, MembershipCode, Position) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssissss", $name, $surname, $gender, $status, $phoneNumber, $idNumber, $email, $believeInTheNation, $dayOfEntry, $address, $membershipCode, $position);
        if (mysqli_stmt_execute($stmt)) {
            echo "New StayMember created successfully";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
        header("Location:{$_SERVER['PHP_SELF']}");
        exit();
    }

    // Update
    elseif ($action == 'update') {
        // Handle form submission for updating StayMember
        // Retrieve and sanitize form inputs
        $id = $_POST['id'];
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $surname = mysqli_real_escape_string($conn, $_POST['surname']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $phoneNumber = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
        $idNumber = mysqli_real_escape_string($conn, $_POST['idNumber']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $believeInTheNation = isset($_POST['believeInTheNation']) ? 1 : 0;
        $dayOfEntry = mysqli_real_escape_string($conn, $_POST['dayOfEntry']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $membershipCode = mysqli_real_escape_string($conn, $_POST['membershipCode']);
        $position = mysqli_real_escape_string($conn, $_POST['position']);

        // Perform database update using prepared statement
        $sql = "UPDATE StayMember 
                SET Name=?, Surname=?, Gender=?, Status=?, PhoneNumber=?, IDNumber=?, Email=?, BelieveInTheNation=?, DayOfEntry=?, Address=?, MembershipCode=?, Position=?
                WHERE P_ID=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssissssi", $name, $surname, $gender, $status, $phoneNumber, $idNumber, $email, $believeInTheNation, $dayOfEntry, $address, $membershipCode, $position, $id);
        if (mysqli_stmt_execute($stmt)) {
            echo "StayMember updated successfully";
           
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt); header("Location: {$_SERVER['PHP_SELF']}");
            exit();
    }

    // Delete
    elseif ($action == 'delete') {
        // Handle deletion of StayMember
        $id = $_POST['id'];

        // Perform database deletion using prepared statement
        $sql = "DELETE FROM StayMember WHERE P_ID=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            // Check if any row was affected
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "StayMember deleted successfully";
            } else {
                echo "No records found for deletion";
            }
        } else {
            echo "Error deleting StayMember: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StayMember Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <?php include("nav.php"); ?>
    <div class="text-center font-bold mb-4 mt-8">StayMember</div>
    <!-- Button to open modal for adding StayMember -->
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="openModal('create')">
        Add StayMember
    </button>

    <!-- Modal -->
    <div id="myModal" class="modal hidden fixed inset-0 w-full h-full bg-gray-900 bg-opacity-50 flex justify-center items-center container mt-5">
        <div class="modal-content bg-white p-8 rounded">
            <h2 class="text-xl font-bold mb-4">StayMember Details</h2>
            <form action="" method="POST" id="stayMemberForm">
                <input type="hidden" name="action" id="modalAction">
                <input type="hidden" name="id" id="id">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="form-input mt-1 block w-full" required>
                </div>
                <div class="mb-4">
                    <label for="surname" class="block text-sm font-medium text-gray-700">Surname</label>
                    <input type="text" name="surname" id="surname" class="form-input mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender" class="form-select mt-1 block w-full">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <!-- Add more options if needed -->
                    </select>
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <input type="text" name="status" id="status" class="form-input mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="phoneNumber" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="text" name="phoneNumber" id="phoneNumber" class="form-input mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="idNumber" class="block text-sm font-medium text-gray-700">ID Number</label>
                    <input type="text" name="idNumber" id="idNumber" class="form-input mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="form-input mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="believeInTheNation" class="block text-sm font-medium text-gray-700">Believe in the Nation</label>
                    <input type="checkbox" name="believeInTheNation" id="believeInTheNation" class="form-checkbox mt-1">
                </div>
                <div class="mb-4">
                    <label for="dayOfEntry" class="block text-sm font-medium text-gray-700">Day of Entry</label>
                    <input type="date" name="dayOfEntry" id="dayOfEntry" class="form-input mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" id="address" class="form-textarea mt-1 block w-full"></textarea>
                </div>
                <div class="mb-4">
                    <label for="membershipCode" class="block text-sm font-medium text-gray-700">Membership Code</label>
                    <input type="text" name="membershipCode" id="membershipCode" class="form-input mt-1 block w-full">
                </div>
                <div class="mb-4">
                    <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                    <input type="text" name="position" id="position" class="form-input mt-1 block w-full">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Save</button>
                    <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Display StayMembers in a table -->
    <table class="table table-bordered">
        <!-- Table header with column names -->
        <thead>
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Surname</th>
                <th class="px-4 py-2">Gender</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Phone Number</th>
                <th class="px-4 py-2">ID Number</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Believe in the Nation</th>
                <th class="px-4 py-2">Day of Entry</th>
                <th class="px-4 py-2">Address</th>
                <th class="px-4 py-2">Membership Code</th>
                <th class="px-4 py-2">Position</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch StayMembers from the database and display in rows
            $result = $conn->query("SELECT * FROM StayMember");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['Name']) . "</td>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['Surname']) . "</td>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['Gender']) . "</td>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['Status']) . "</td>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['PhoneNumber']) . "</td>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['IDNumber']) . "</td>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['Email']) . "</td>";
                echo "<td class='border px-4 py-2'>" . ($row['BelieveInTheNation'] ? 'Yes' : 'No') . "</td>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['DayOfEntry']) . "</td>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['Address']) . "</td>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['MembershipCode']) . "</td>";
                echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['Position']) . "</td>";
                echo "<td class='border px-4 py-2'>";
                echo "<button class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2' onclick='openModal(\"update\", " . json_encode($row) . ")'>Edit</button>";
                echo "<button class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded' onclick='openModal(\"delete\", " . $row['P_ID'] . ")'>Delete</button>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        // JavaScript to open and close modal
        function openModal(action, data = null) {
            const modal = document.getElementById('myModal');
            const modalAction = document.getElementById('modalAction');
            const stayMemberForm = document.getElementById('stayMemberForm');
            const idInput = document.getElementById('id');
            const nameInput = document.getElementById('name');
            const surnameInput = document.getElementById('surname');
            const genderInput = document.getElementById('gender');
            const statusInput = document.getElementById('status');
            const phoneNumberInput = document.getElementById('phoneNumber');
            const idNumberInput = document.getElementById('idNumber');
            const emailInput = document.getElementById('email');
            const believeInput = document.getElementById('believeInTheNation');
            const dayOfEntryInput = document.getElementById('dayOfEntry');
            const addressInput = document.getElementById('address');
            const membershipCodeInput = document.getElementById('membershipCode');
            const positionInput = document.getElementById('position');

            // Reset form fields
            stayMemberForm.reset();

            if (action === 'create') {
                modalAction.value = 'create';
            } else if (action === 'update' && data) {
                idInput.value = data.P_ID;
                nameInput.value = data.Name;
                surnameInput.value = data.Surname;
                genderInput.value = data.Gender;
                statusInput.value = data.Status;
                phoneNumberInput.value = data.PhoneNumber;
                idNumberInput.value = data.IDNumber;
                emailInput.value = data.Email;
                believeInput.checked = data.BelieveInTheNation === '1';
                dayOfEntryInput.value = data.DayOfEntry;
                addressInput.value = data.Address;
                membershipCodeInput.value = data.MembershipCode;
                positionInput.value = data.Position;
                modalAction.value = 'update';
            } else if (action === 'delete' && data) {
                modalAction.value = 'delete';
                idInput.value = data;
                stayMemberForm.submit();
            }

            modal.classList.remove('hidden');
        }function closeModal() {
    const modal = document.getElementById('myModal');
    modal.classList.add('hidden');
}
    </script>
</body>

</html>
<style>
    .modal-content {
        width: 80%;
        /* Adjust the width as needed */
        max-width: 600px;
        /* Set a maximum width if desired */
        height: auto;
        /* Allow height to adjust based on content */
        max-height: 80%;
        /* Set a maximum height if desired */
        overflow-y: auto;
        /* Enable vertical scrolling if content exceeds modal height */
    }
</style>