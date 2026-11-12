<?php
require_once "database.php";
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();

if (!$room) {
    set_flash("danger", "Room not found.");
    header("Location: rooms.php"); exit;
}

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
    $errors = validate_room($room, $conn, $id);

    if (!$errors) {
        $capacity = (int)$room['capacity'];
        $price = (float)$room['price_per_night'];
        $stmt = $conn->prepare("UPDATE rooms SET room_number = ?, room_type = ?, capacity = ?, price_per_night = ?, description = ?, status = ? WHERE room_id = ?");
        $stmt->bind_param("ssidssi", $room['room_number'], $room['room_type'], $capacity, $price, $room['description'], $room['status'], $id);
        $stmt->execute();
        set_flash("success", "Room {$room['room_number']} was updated.");
        header("Location: rooms.php"); exit;
    }
}

$pageTitle = "Edit Room";
$pageSub = "Update the details of room " . $room['room_number'] . ".";
require "header.php";
?>
<div class="card narrow">
    <?php $buttonLabel = "Update Room"; include "room_form.php"; ?>
</div>
<?php require "footer.php"; ?>
