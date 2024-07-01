<?php
include 'dbconnection.php';

// Check if the action parameter is set
if(isset($_POST['action'])) {
    // Define the action to be performed
    $action = $_POST['action'];

    // Perform the corresponding action
    if($action === 'create') {
        handleCreate($conn);
    } elseif($action === 'update') {
        handleUpdate($conn);
    } elseif($action === 'delete') {
        handleDelete($conn);
    } else {
        echo "Invalid action.";
        exit();
    }
}

// Function to handle member creation
function handleCreate($conn) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $code = $_POST['code'] ?? null;
    $address = $_POST['address'] ?? null;
    $email = $_POST['email'] ?? null;
    $entryDate = $_POST['entryDate'] ?? null;
    $phoneNumber = $_POST['phoneNumber'] ?? null;

    try {
        createMember($conn, $name, $position, $code, $address, $email, $entryDate, $phoneNumber);
        redirectToSelf();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to handle member update
function handleUpdate($conn) {
    $memberId = $_POST['memberId'];
    $code = $_POST['code'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $entryDate = $_POST['entryDate'];
    $phoneNumber = $_POST['phoneNumber'];

    try {
        updateMember($conn, $memberId, $code, $address, $email, $entryDate, $phoneNumber);
        redirectToSelf();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to handle member deletion
function handleDelete($conn) {
    $memberId = $_POST['memberId'];

    try {
        deleteMember($conn, $memberId);
        redirectToSelf();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to create a new member
function createMember($conn, $name, $position, $code, $address, $email, $entryDate, $phoneNumber) {
    $stmt = $conn->prepare("INSERT INTO AlternateMember (Name, Position, Code, Address, Email, EntryDate, PhoneNumber) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $position, $code, $address, $email, $entryDate, $phoneNumber);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception($stmt->error);
    }
}

// Function to update an existing member
function updateMember($conn, $memberId, $code, $address, $email, $entryDate, $phoneNumber) {
    $stmt = $conn->prepare("UPDATE AlternateMember SET Code=?, Address=?, Email=?, EntryDate=?, PhoneNumber=? WHERE MemberId=?");
    $stmt->bind_param("sssssi", $code, $address, $email, $entryDate, $phoneNumber, $memberId);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception($stmt->error);
    }
}

// Function to delete an existing member
function deleteMember($conn, $memberId) {
    $stmt = $conn->prepare("DELETE FROM AlternateMember WHERE MemberId=?");
    $stmt->bind_param("i", $memberId);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception($stmt->error);
    }
}

// Function to redirect back to the same page
function redirectToSelf() {
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Function to retrieve members from the database
function getMembers($conn) {
    $sql = "SELECT * FROM AlternateMember";
    return $conn->query($sql);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alternate Member CRUD</title>
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
<body class="bg-gray-100">
    <?php include("nav.php");?>
   
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
                    echo "<td class='border px-4 py-2'>" . $row["Name"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Position"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Code"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Address"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Email"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["EntryDate"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["PhoneNumber"] . "</td>";
                    echo "<td class='border px-4 py-2'>";
                    echo "<button class='bg-green-500 text-white px-4 py-2 rounded update-btn' data-id='" . $row["MemberId"] . "' data-name='" . $row["Name"] . "' data-position='" . $row["Position"] . "' data-code='" . $row["Code"] . "' data-address='" . $row["Address"] . "' data-email='" . $row["Email"] . "' data-entrydate='" . $row["EntryDate"] . "' data-phonenumber='" . $row["PhoneNumber"] . "'>Update</button> ";
                    echo "<button class='bg-red-500 text-white px-4 py-2 rounded delete-btn' data-id='" . $row["MemberId"] . "' data-name='" . $row["Name"] . "' data-position='" . $row["Position"] . "'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='border px-4 py-2'>No records found</td></tr>";
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
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="hidden" id="action" name="action" value="create">
                <input type="hidden" id="memberId" name="memberId" value="">
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
    const memberIdInput = document.getElementById('memberId');
    const nameInput = document.getElementById('name');
    const positionInput = document.getElementById('position');
    const codeInput = document.getElementById('code');
    const addressInput = document.getElementById('address');
    const emailInput = document.getElementById('email');
    const entryDateInput = document.getElementById('entryDate');
    const phoneNumberInput = document.getElementById('phoneNumber');

    const resetModal = () => {
        memberIdInput.value = '';
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

            memberIdInput.value = id;
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
                memberIdInput.value = id;
                nameInput.value = name;
                positionInput.value = position;
                actionInput.value = 'delete';
                document.querySelector('form').submit();
            }
        });
    });
</script>

</body>
</html>

