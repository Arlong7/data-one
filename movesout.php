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
    $bornId = $_POST['bornId'];
    $modate = $_POST['modate'];
    $olno = $_POST['olno'];
    $oldata = $_POST['oldata'];
    $pId = $_POST['pId'];
    $yId = $_POST['yId'];
    $moMonth = $_POST['moMonth'];
    $moYear = $_POST['moYear'];
    $reason = $_POST['reason'];
    $moestatus = $_POST['moestatus'];

    try {
        createMemberMoveOut($conn, $bornId, $modate, $olno, $oldata, $pId, $yId, $moMonth, $moYear, $reason, $moestatus);
        redirectToSelf();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to handle member update
function handleUpdate($conn) {
    $bornId = $_POST['bornId'];
    $modate = $_POST['modate'];
    $olno = $_POST['olno'];
    $oldata = $_POST['oldata'];
    $pId = $_POST['pId'];
    $yId = $_POST['yId'];
    $moMonth = $_POST['moMonth'];
    $moYear = $_POST['moYear'];
    $reason = $_POST['reason'];
    $moestatus = $_POST['moestatus'];

    try {
        updateMemberMoveOut($conn, $bornId, $modate, $olno, $oldata, $pId, $yId, $moMonth, $moYear, $reason, $moestatus);
        redirectToSelf();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to handle member deletion
function handleDelete($conn) {
    $bornId = $_POST['bornId'];

    try {
        deleteMemberMoveOut($conn, $bornId);
        redirectToSelf();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Function to create a new member move out record
function createMemberMoveOut($conn, $bornId, $modate, $olno, $oldata, $pId, $yId, $moMonth, $moYear, $reason, $moestatus) {
    $stmt = $conn->prepare("INSERT INTO MemberMovesOut (Born_ID, Modate, OLno, OLdata, P_ID, Y_ID, MOmonth, MOyear, Reason, Moestatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiisiisss", $bornId, $modate, $olno, $oldata, $pId, $yId, $moMonth, $moYear, $reason, $moestatus);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception($stmt->error);
    }
}

// Function to update an existing member move out record
function updateMemberMoveOut($conn, $bornId, $modate, $olno, $oldata, $pId, $yId, $moMonth, $moYear, $reason, $moestatus) {
    $stmt = $conn->prepare("UPDATE MemberMovesOut SET Modate=?, OLno=?, OLdata=?, P_ID=?, Y_ID=?, MOmonth=?, MOyear=?, Reason=?, Moestatus=? WHERE Born_ID=?");
    $stmt->bind_param("siisiisssi", $modate, $olno, $oldata, $pId, $yId, $moMonth, $moYear, $reason, $moestatus, $bornId);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception($stmt->error);
    }
}

// Function to delete an existing member move out record
function deleteMemberMoveOut($conn, $bornId) {
    $stmt = $conn->prepare("DELETE FROM MemberMovesOut WHERE Born_ID=?");
    $stmt->bind_param("i", $bornId);
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

// Function to retrieve member move out records from the database
function getMemberMovesOut($conn) {
    $sql = "SELECT * FROM MemberMovesOut";
    return $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Moves Out CRUD</title>
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
    <h2 class="text-2xl font-bold mb-6">Member Moves Out</h2>
    <button id="open-modal" class="bg-blue-500 text-white px-4 py-2 rounded">Add Member Move Out</button>
    <table class="min-w-full bg-white mt-6">
        <thead>
            <tr>
                <th class="px-4 py-2">Born ID</th>
                <th class="px-4 py-2">Modate</th>
                <th class="px-4 py-2">OLno</th>
                <th class="px-4 py-2">OLdata</th>
                <th class="px-4 py-2">P ID</th>
                <th class="px-4 py-2">Y ID</th>
                <th class="px-4 py-2">MOmonth</th>
                <th class="px-4 py-2">MOyear</th>
                <th class="px-4 py-2">Reason</th>
                <th class="px-4 py-2">Moestatus</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = getMemberMovesOut($conn);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='border px-4 py-2'>" . $row["Born_ID"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Modate"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["OLno"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["OLdata"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["P_ID"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Y_ID"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["MOmonth"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["MOyear"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Reason"] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $row["Moestatus"] . "</td>";
                    echo "<td class='border px-4 py-2'>";
                    echo "<button class='bg-green-500 text-white px-4 py-2 rounded update-btn' data-id='" . $row["Born_ID"] . "' data-modate='" . $row["Modate"] . "' data-olno='" . $row["OLno"] . "' data-olddata='" . $row["OLdata"] . "' data-pid='" . $row["P_ID"] . "' data-yid='" . $row["Y_ID"] . "' data-momonth='" . $row["MOmonth"] . "' data-moyear='" . $row["MOyear"] . "' data-reason='" . $row["Reason"] . "' data-moestatus='" . $row["Moestatus"] . "'>Update</button> ";
                   
                    echo "<button class='bg-red-500 text-white px-4 py-2 rounded delete-btn' data-id='" . $row["Born_ID"] . "'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11' class='border px-4 py-2'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="fixed z-10 inset-0 overflow-y-auto hidden modal" id="crud-modal">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-lg mx-2">
            <h2 class="text-2xl font-bold mb-6" id="modal-title">Member Move Out Form</h2>
            <form action="your_php_file.php" method="POST">
                <input type="hidden" id="action" name="action" value="create">
                <input type="hidden" id="bornId" name="bornId" value="">
                <div class="mb-4">
                    <label for="modate" class="block text-gray-700">Modate:</label>
                    <input type="date" id="modate" name="modate" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="olno" class="block text-gray-700">OLno:</label>
                    <input type="number" id="olno" name="olno" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="oldata" class="block text-gray-700">OLdata:</label>
                    <input type="date" id="oldata" name="oldata" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="pId" class="block text-gray-700">P ID:</label>
                    <input type="number" id="pId" name="pId" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="yId" class="block text-gray-700">Y ID:</label>
                    <input type="number" id="yId" name="yId" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="moMonth" class="block text-gray-700">MOmonth:</label>
                    <input type="number" id="moMonth" name="moMonth" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="moYear" class="block text-gray-700">MOyear:</label>
                    <input type="number" id="moYear" name="moYear" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="reason" class="block text-gray-700">Reason:</label>
                    <input type="text" id="reason" name="reason" required class="w-full border border-gray-300 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="moestatus" class="block text-gray-700">Moestatus:</label>
                    <input type="text" id="moestatus" name="moestatus" required class="w-full border border-gray-300 p-2 rounded">
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
    const bornIdInput = document.getElementById('bornId');
    const modateInput = document.getElementById('modate');
    const olnoInput = document.getElementById('olno');
    const oldataInput = document.getElementById('oldata');
    const pIdInput = document.getElementById('pId');
    const yIdInput = document.getElementById('yId');
    const moMonthInput = document.getElementById('moMonth');
    const moYearInput = document.getElementById('moYear');
    const reasonInput = document.getElementById('reason');
    const moestatusInput = document.getElementById('moestatus');

    const resetModal = () => {
        bornIdInput.value = '';
        modateInput.value = '';
        olnoInput.value = '';
        oldataInput.value = '';
        pIdInput.value = '';
        yIdInput.value = '';
        moMonthInput.value = '';
        moYearInput.value = '';
        reasonInput.value = '';
        moestatusInput.value = '';
    };

    openModalBtn.addEventListener('click', () => {
        resetModal();
        modalTitle.textContent = 'Add Member Move Out';
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
            const modate = button.getAttribute('data-modate');
            const olno = button.getAttribute('data-olno');
            const oldata = button.getAttribute('data-olddata');
            const pId = button.getAttribute('data-pid');
            const yId = button.getAttribute('data-yid');
            const moMonth = button.getAttribute('data-momonth');
            const moYear = button.getAttribute('data-moyear');
            const reason = button.getAttribute('data-reason');
            const moestatus = button.getAttribute('data-moestatus');

            bornIdInput.value = id;
            modateInput.value = modate;
            olnoInput.value = olno;
            oldataInput.value = oldata;
            pIdInput.value = pId;
            yIdInput.value = yId;
            moMonthInput.value = moMonth;
            moYearInput.value = moYear;
            reasonInput.value = reason;
            moestatusInput.value = moestatus;

            modalTitle.textContent = 'Update Member Move Out';
            actionInput.value = 'update';
            modal.classList.remove('hidden');
            document.body.classList.add('modal-active');
        });
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');

            if (confirm(`Are you sure you want to delete member move out record with Born ID ${id}?`)) {
                bornIdInput.value = id;
                actionInput.value = 'delete';
                document.querySelector('form').submit();
            }
        });
    });
</script>

</body>
</html>
