<?php
require_once "database.php";
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['room_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if ($id > 0 && in_array($status, ROOM_STATUSES, true)) {
        $stmt = $conn->prepare("UPDATE rooms SET status = ? WHERE room_id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        set_flash("success", "Room status changed to $status.");
    } else {
        set_flash("danger", "Invalid status update.");
    }
}
$back = $_POST['back'] ?? '';
header("Location: " . (in_array($back, ['index.php', 'rooms.php', 'status.php'], true) ? $back : 'rooms.php'));
exit;
