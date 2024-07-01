<?php
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null; // New line to fetch ID
    $name = $_POST['name'];
    $position = $_POST['position'];
    $code = $_POST['code'] ?? null;
    $address = $_POST['address'] ?? null;
    $email = $_POST['email'] ?? null;
    $entryDate = $_POST['entryDate'] ?? null;
    $phoneNumber = $_POST['phoneNumber'] ?? null;

    switch ($action) {
        case 'create':
            createMember($conn, $name, $position, $code, $address, $email, $entryDate, $phoneNumber);
            break;
        case 'update':
            updateMember($conn, $id, $name, $position, $code, $address, $email, $entryDate, $phoneNumber); // Updated to include ID
            break;
        case 'delete':
            deleteMember($conn, $id); // Updated to include ID
            break;
    }
}

function createMember($conn, $name, $position, $code, $address, $email, $entryDate, $phoneNumber) {
    $stmt = $conn->prepare("INSERT INTO CompleteMember (Name, Position, Code, Address, Email, EntryDate, PhoneNumber) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $position, $code, $address, $email, $entryDate, $phoneNumber);
    $stmt->execute();
    $stmt->close();
}

function getMembers($conn) {
    $sql = "SELECT * FROM CompleteMember";
    return $conn->query($sql);
}

function updateMember($conn, $id, $name, $position, $code, $address, $email, $entryDate, $phoneNumber) {
    $stmt = $conn->prepare("UPDATE CompleteMember SET Name=?, Position=?, Code=?, Address=?, Email=?, EntryDate=?, PhoneNumber=? WHERE Id=?");
    $stmt->bind_param("sssssssi", $name, $position, $code, $address, $email, $entryDate, $phoneNumber, $id);
    $stmt->execute();
    $stmt->close();
}

function deleteMember($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM CompleteMember WHERE Id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Member CRUD</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .modal {
            transition: opacity 0.25s ease;
        }
        body.modal-active {
            overflow-x: hidden;
            overflow-y: visible !important;
        }
    </style>
</head>
<?php include("nav.php");?>
<body class="bg-gray-100">
   

<div class="container mx-auto p-6">
    <?php if(isset($_POST['action']) && $_POST['action'] === 'update'): ?>
        <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">Record updated successfully</div>
        <script>
            setTimeout(() => {
                document.querySelector('.bg-green-500').remove();
            }, 3000); // 3 seconds
        </script>
    <?php endif; ?>
    <h2 class="text-2xl font-bold mb-6">Member List</h2>
    <button id="open-modal" class="bg-blue-500 text-white px-4 py-2 rounded">Add Member</button>
    <table class="min-w-full bg-white mt-6">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Position</th>
                <th class="px-4 py-2">Code</th>
                <th class="px-4 py-2">Address</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Entry Date</th>
                <th class="px-4 py-2">Phone Number</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = getMembers($conn);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='border px-4 py-2'>" . $row["Id"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Name"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Position"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Code"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Address"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Email"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["EntryDate"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["PhoneNumber"] . "</td>";
                    echo "<td class='border px-4 py-2'>";
                    echo "<button class='bg-green-500 text-white px-4 py-2 rounded update-btn' data-id='" . $row["Id"] . "' data-name='" . $row["Name"] . "' data-position='" . $row["Position"] . "' data-code='" . $row["Code"] . "' data-address='" . $row["Address"] . "' data-email='" . $row["Email"] . "' data-entrydate='" . $row["EntryDate"] . "' data-phonenumber='" . $row["PhoneNumber"] . "'>Update</button> ";
                    echo "<button class='bg-red-500 text-white px-4 py-2 rounded delete-btn' data-id='" . $row["Id"] . "' data-name='" . $row["Name"] . "' data-position='" . $row["Position"] . "'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9' class='border px-4 py-2'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden modal" id="crud-modal">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-lg mx-2">
            <h2 class="text-2xl font-bold mb-6" id="modal-title">Member Form</h2>
            <form action="complete_member.php" method="POST">
                <input type="hidden" id="action" name="action" value="create">
                <input type="hidden" id="id" name="id"> <!-- New hidden input field for ID -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name:</label>
                    <input type="text" id="name" name="name" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="position" class="block text-gray-700">Position:</label>
                    <input type="text" id="position" name="position" required class="w-full border border-gray-300 p-2 rounded">
               
                    </div>
                <div class="mb-4">
                    <label for="code" class="block text-gray-700">Code:</label>
                    <input type="text" id="code" name="code" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-gray-700">Address:</label>
                    <input type="text" id="address" name="address" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email:</label>
                    <input type="email" id="email" name="email" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="entryDate" class="block text-gray-700">Entry Date:</label>
                    <input type="date" id="entryDate" name="entryDate" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="phoneNumber" class="block text-gray-700">Phone Number:</label>
                    <input type="text" id="phoneNumber" name="phoneNumber" class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="flex justify-end">
                    <button type="button" id="close-modal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const openModalBtn = document.getElementById('open-modal');
    const closeModalBtn = document.getElementById('close-modal');
    const modal = document.getElementById('crud-modal');
    const modalTitle = document.getElementById('modal-title');
    const actionInput = document.getElementById('action');
    const idInput = document.getElementById('id'); // New line to get ID
    const nameInput = document.getElementById('name');
    const positionInput = document.getElementById('position');
    const codeInput = document.getElementById('code');
    const addressInput = document.getElementById('address');
    const emailInput = document.getElementById('email');
    const entryDateInput = document.getElementById('entryDate');
    const phoneNumberInput = document.getElementById('phoneNumber');

    const resetModal = () => {
        idInput.value = ''; // Reset ID value
        nameInput.value = '';
        positionInput.value = '';
        codeInput.value = '';
        addressInput.value = '';
        emailInput.value = '';
        entryDateInput.value = '';
        phoneNumberInput.value = '';
    };

    openModalBtn.addEventListener('click', () => {
        resetModal();
        modalTitle.textContent = 'Add Member';
        actionInput.value = 'create';
        modal.classList.remove('hidden');
        document.body.classList.add('modal-active');
    });

    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        document.body.classList.remove('modal-active');
    });

    document.querySelectorAll('.update-btn').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const position = button.getAttribute('data-position');
            const code = button.getAttribute('data-code');
            const address = button.getAttribute('data-address');
            const email = button.getAttribute('data-email');
            const entryDate = button.getAttribute('data-entrydate');
            const phoneNumber = button.getAttribute('data-phonenumber');

            idInput.value = id; // Set ID value
            nameInput.value = name;
            positionInput.value = position;
            codeInput.value = code;
            addressInput.value = address;
            emailInput.value = email;
            entryDateInput.value = entryDate;
            phoneNumberInput.value = phoneNumber;

            modalTitle.textContent = 'Update Member';
            actionInput.value = 'update';
            modal.classList.remove('hidden');
            document.body.classList.add('modal-active');
        });
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const position = button.getAttribute('data-position');

            if (confirm(`Are you sure you want to delete member ${name} with position ${position}?`)) {
                idInput.value = id;
                actionInput.value = 'delete';
                document.querySelector('form').submit();
            }
        });
    });
</script>

</body>
</html>

