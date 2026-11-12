<?php
require_once "database.php";
require_login();

$pageTitle = "Room Status";
$pageSub = "See every room at a glance and change its status in one click.";
$pageAction = '<a href="add.php" class="btn btn-primary">' . icon('plus', 16) . ' Add Room</a>';
require "header.php";

$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_number");
?>
<div class="card">
    <div class="toolbar">
        <div class="search"><?= icon('search', 16) ?><input type="search" id="roomSearch" class="input" placeholder="Search rooms..." autocomplete="off"></div>
        <select id="typeFilter" class="input">
            <option value="">All types</option>
            <?php foreach (ROOM_TYPES as $type): ?><option><?= e($type) ?></option><?php endforeach; ?>
        </select>
        <select id="statusFilter" class="input">
            <option value="">All statuses</option>
            <?php foreach (ROOM_STATUSES as $status): ?><option><?= e($status) ?></option><?php endforeach; ?>
        </select>
        <button type="button" id="resetFilters" class="btn btn-ghost">Reset</button>
    </div>
    <div class="result-count" id="resultCount"></div>
</div>

<div class="room-grid">
<?php if ($rooms->num_rows === 0): ?>
    <div class="empty">No rooms yet. Add your first room.</div>
<?php endif; ?>
<?php while ($r = $rooms->fetch_assoc()): ?>
    <div class="room-card rc-<?= scls($r['status']) ?>" data-item data-status="<?= e($r['status']) ?>" data-type="<?= e($r['room_type']) ?>"
         data-text="<?= e(strtolower($r['room_number'] . ' ' . $r['room_type'] . ' ' . $r['description'] . ' ' . $r['status'] . ' ' . $r['price_per_night'])) ?>">
        <div class="rc-top">
            <div><span class="muted">Room</span><h3><?= e($r['room_number']) ?></h3></div>
            <?= badge($r['status']) ?>
        </div>
        <div class="rc-meta"><b><?= e($r['room_type']) ?></b><span><?= icon('users', 14) ?> <?= (int)$r['capacity'] ?></span></div>
        <p class="rc-desc"><?= e($r['description']) ?: '&nbsp;' ?></p>
        <div class="rc-price">&#8369;<?= number_format($r['price_per_night'], 2) ?> <small>/ night</small></div>
        <form method="post" action="update_status.php" class="seg-btns">
            <input type="hidden" name="room_id" value="<?= (int)$r['room_id'] ?>">
            <input type="hidden" name="back" value="status.php">
            <?php foreach (['Available' => 'Available', 'Occupied' => 'Occupied', 'Under Maintenance' => 'Maintenance'] as $value => $label): ?>
                <button name="status" value="<?= e($value) ?>" class="<?= $value === $r['status'] ? 'on on-' . scls($value) : '' ?>"><?= e($label) ?></button>
            <?php endforeach; ?>
        </form>
    </div>
<?php endwhile; ?>
</div>
<div class="empty" id="emptyState" style="display:none">No rooms match your search.</div>
<?php require "footer.php"; ?>
