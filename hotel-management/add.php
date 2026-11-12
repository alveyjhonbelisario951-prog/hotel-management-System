<?php
require_once "database.php";
require_login();

$room = ['room_number' => '', 'room_type' => '', 'capacity' => 2, 'price_per_night' => '', 'description' => '', 'status' => 'Available'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room = [
        'room_number' => trim($_POST['room_number'] ?? ''),
        'room_type' => $_POST['room_type'] ?? '',
        'capacity' => $_POST['capacity'] ?? '',
        'price_per_night' => $_POST['price_per_night'] ?? '',
        'description' => trim($_POST['description'] ?? ''),
        'status' => $_POST['status'] ?? '',
    ];
    $errors = validate_room($room, $conn);

    if (!$errors) {
        $capacity = (int)$room['capacity'];
        $price = (float)$room['price_per_night'];
        $stmt = $conn->prepare("INSERT INTO rooms (room_number, room_type, capacity, price_per_night, description, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssidss", $room['room_number'], $room['room_type'], $capacity, $price, $room['description'], $room['status']);
        $stmt->execute();
        set_flash("success", "Room {$room['room_number']} was added successfully.");
        header("Location: rooms.php"); exit;
    }
}

$pageTitle = "Add Room";
$pageSub = "Register a new room in the system.";
require "header.php";
?>
<div class="card narrow">
    <?php $buttonLabel = "Save Room"; $showReset = true; include "room_form.php"; ?>
</div>
<?php require "footer.php"; ?>
