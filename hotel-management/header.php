<?php
require_once "database.php";
require_login();
$current = basename($_SERVER['PHP_SELF']);

function nav_link($file, $label, $ico, $also = []) {
    global $current;
    $on = in_array($current, array_merge([$file], $also), true) ? 'active' : '';
    echo '<a class="' . $on . '" href="' . e($file) . '">' . icon($ico) . '<span>' . e($label) . '</span></a>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Horizon') ?> | Horizon Hotel</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo">H</div>
            <div><b>HORIZON</b><small>Hotel Management</small></div>
        </div>
        <div class="nav-label">MENU</div>
        <nav class="nav">
            <?php nav_link('index.php', 'Dashboard', 'home'); ?>
            <?php nav_link('rooms.php', 'All Rooms', 'door', ['edit.php']); ?>
            <?php nav_link('status.php', 'Room Status', 'grid'); ?>
            <?php nav_link('add.php', 'Add Room', 'plus'); ?>
        </nav>
        <div class="side-user">
            <div class="avatar">A</div>
            <div><b>Administrator</b><small>Hotel Admin</small></div>
            <a href="logout.php" title="Logout"><?= icon('logout') ?></a>
        </div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div>
                <h1><?= e($pageTitle ?? '') ?></h1>
                <p><?= e($pageSub ?? '') ?></p>
            </div>
            <?= $pageAction ?? '' ?>
        </header>
        <div class="content">
<?php if ($flash = get_flash()): ?>
            <div class="alert alert-<?= e($flash[0]) ?> auto-dismiss">
                <span><?= e($flash[1]) ?></span>
                <button type="button" class="alert-close">&times;</button>
            </div>
<?php endif; ?>
