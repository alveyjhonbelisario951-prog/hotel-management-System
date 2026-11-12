<?php
session_start();

$conn = new mysqli("localhost", "root", "", "hotel_management");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

const ROOM_TYPES    = ['Single', 'Double', 'Deluxe', 'Suite'];
const ROOM_STATUSES = ['Available', 'Occupied', 'Under Maintenance'];

function e($text) { return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8'); }

function require_login() {
    if (empty($_SESSION['admin'])) { header("Location: login.php"); exit; }
}

function set_flash($type, $msg) { $_SESSION['flash'] = [$type, $msg]; }

function get_flash() {
    if (!empty($_SESSION['flash'])) { $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f; }
    return null;
}

function scls($status) {
    return ['Available' => 'ok', 'Occupied' => 'bad', 'Under Maintenance' => 'warn'][$status] ?? 'ok';
}

function badge($status) {
    return '<span class="badge b-' . scls($status) . '">' . e($status) . '</span>';
}

function icon($name, $size = 18) {
    $paths = [
        'home' => '<path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/>',
        'door' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18M9 4v16"/>',
        'grid' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'logout' => '<path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>',
        'check' => '<path d="M20 6L9 17l-5-5"/>',
        'key' => '<circle cx="8" cy="15" r="4"/><path d="M11 12l9-9M16 7l3 3"/>',
        'alert' => '<circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>',
        'users' => '<circle cx="9" cy="8" r="3.5"/><path d="M2 20c0-3.5 3-6 7-6s7 2.5 7 6M17 4.5a3.5 3.5 0 010 7M22 20c0-2.5-1.5-4.5-4-5.5"/>',
    ];
    return '<svg class="icon" width="' . (int)$size . '" height="' . (int)$size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . ($paths[$name] ?? '') . '</svg>';
}

function validate_room($d, $conn, $id = 0) {
    $errors = [];
    $num = trim($d['room_number'] ?? '');

    if ($num === '') {
        $errors[] = "Room number is required.";
    } elseif (!preg_match('/^[A-Za-z0-9-]{1,10}$/', $num)) {
        $errors[] = "Room number may only contain letters, numbers and dashes (max 10 characters).";
    } else {
        $stmt = $conn->prepare("SELECT room_id FROM rooms WHERE room_number = ? AND room_id <> ?");
        $stmt->bind_param("si", $num, $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) $errors[] = "Room number already exists.";
    }

    if (!in_array($d['room_type'] ?? '', ROOM_TYPES, true)) $errors[] = "Please select a valid room type.";

    $capacity = (string)($d['capacity'] ?? '');
    if (!ctype_digit($capacity) || (int)$capacity < 1 || (int)$capacity > 10) {
        $errors[] = "Capacity must be between 1 and 10 guests.";
    }

    if (!is_numeric($d['price_per_night'] ?? '') || $d['price_per_night'] <= 0) $errors[] = "Price per night must be a number greater than 0.";
    if (mb_strlen(trim($d['description'] ?? '')) > 255) $errors[] = "Description must be 255 characters or less.";
    if (!in_array($d['status'] ?? '', ROOM_STATUSES, true)) $errors[] = "Please select a valid status.";

    return $errors;
}
