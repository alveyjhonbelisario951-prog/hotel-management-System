<?php
require_once "database.php";
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['room_id'] ?? 0);
    $stmt = $conn->prepare("DELETE FROM rooms WHERE room_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    set_flash($stmt->affected_rows ? "success" : "danger", $stmt->affected_rows ? "Room deleted." : "Room not found.");
}
$back = $_POST['back'] ?? '';
header("Location: " . (in_array($back, ['index.php', 'rooms.php', 'status.php'], true) ? $back : 'rooms.php'));
exit;
