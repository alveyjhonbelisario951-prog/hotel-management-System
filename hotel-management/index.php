<?php
require_once "database.php";
require_login();

$pageTitle = "Dashboard";
$pageSub = "Overview of your hotel rooms and their current status.";
$pageAction = '<a href="add.php" class="btn btn-primary">' . icon('plus', 16) . ' Add Room</a>';
require "header.php";

$counts = ['Available' => 0, 'Occupied' => 0, 'Under Maintenance' => 0];
$res = $conn->query("SELECT status, COUNT(*) AS c FROM rooms GROUP BY status");
while ($row = $res->fetch_assoc()) $counts[$row['status']] = (int)$row['c'];
$total = array_sum($counts);
$pct = function ($count) use ($total) { return $total ? round($count / $total * 100) : 0; };
$rooms = $conn->query("SELECT * FROM rooms ORDER BY room_number");
?>
<div class="stats">
    <div class="stat"><div class="stat-ico ico-blue"><?= icon('door', 22) ?></div><div><span>Total Rooms</span><b><?= $total ?></b></div></div>
    <div class="stat"><div class="stat-ico ico-ok"><?= icon('check', 22) ?></div><div><span>Available</span><b><?= $counts['Available'] ?></b></div></div>
    <div class="stat"><div class="stat-ico ico-bad"><?= icon('key', 22) ?></div><div><span>Occupied</span><b><?= $counts['Occupied'] ?></b></div></div>
    <div class="stat"><div class="stat-ico ico-warn"><?= icon('alert', 22) ?></div><div><span>Under Maintenance</span><b><?= $counts['Under Maintenance'] ?></b></div></div>
</div>

<div class="card">
    <div class="card-head">
        <h3>Room Management</h3>
        <span class="muted"><?= $pct($counts['Occupied']) ?>% occupied</span>
    </div>
    <div class="bar">
        <span class="seg-bad" style="width:<?= $pct($counts['Occupied']) ?>%"></span>
        <span class="seg-warn" style="width:<?= $pct($counts['Under Maintenance']) ?>%"></span>
        <span class="seg-ok" style="width:<?= $pct($counts['Available']) ?>%"></span>
    </div>
    <div class="toolbar room-toolbar">
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
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Room ID</th><th>Room</th><th>Type</th><th>Capacity</th><th>Price / Night</th><th>Status</th><th>Change Status</th><th class="right">Actions</th></tr></thead>
            <tbody>
            <?php if ($rooms->num_rows === 0): ?>
                <tr><td colspan="8" class="empty-cell">No rooms yet. Add your first room.</td></tr>
            <?php endif; ?>
            <?php while ($r = $rooms->fetch_assoc()): ?>
                <tr data-item data-status="<?= e($r['status']) ?>" data-type="<?= e($r['room_type']) ?>"
                    data-text="<?= e(strtolower($r['room_number'] . ' ' . $r['room_type'] . ' ' . $r['description'] . ' ' . $r['status'] . ' ' . $r['price_per_night'])) ?>">
                    <td class="muted">#<?= (int)$r['room_id'] ?></td>
                    <td><b><?= e($r['room_number']) ?></b><small class="sub"><?= e($r['description']) ?></small></td>
                    <td><?= e($r['room_type']) ?></td>
                    <td><?= (int)$r['capacity'] ?> guest<?= $r['capacity'] > 1 ? 's' : '' ?></td>
                    <td>&#8369;<?= number_format($r['price_per_night'], 2) ?></td>
                    <td><?= badge($r['status']) ?></td>
                    <td>
                        <form method="post" action="update_status.php">
                            <input type="hidden" name="room_id" value="<?= (int)$r['room_id'] ?>">
                            <input type="hidden" name="back" value="index.php">
                            <select name="status" class="status-select b-<?= scls($r['status']) ?>" onchange="this.form.submit()">
                                <?php foreach (ROOM_STATUSES as $status): ?>
                                    <option value="<?= e($status) ?>" <?= $status === $r['status'] ? 'selected' : '' ?>><?= e($status) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td class="right nowrap">
                        <a href="edit.php?id=<?= (int)$r['room_id'] ?>" class="btn btn-sm btn-edit">Edit</a>
                        <form method="post" action="delete.php" class="inline">
                            <input type="hidden" name="room_id" value="<?= (int)$r['room_id'] ?>">
                            <input type="hidden" name="back" value="index.php">
                            <button class="btn btn-sm btn-del">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <div class="empty" id="emptyState" style="display:none">No rooms match your search.</div>
    </div>
</div>
<?php require "footer.php"; ?>
