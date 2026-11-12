<?php /* Shared by add.php and edit.php. Expects $room, $errors, $buttonLabel */ ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger validation-errors"><div>
        <?php foreach ($errors as $err): ?><div>&bull; <?= e($err) ?></div><?php endforeach; ?>
    </div></div>
<?php endif; ?>
<form method="post" id="roomForm">
    <div class="form-grid">
        <div class="field">
            <label>Room Number</label>
            <input class="input" type="text" name="room_number" placeholder="e.g. 101" maxlength="10" required value="<?= e($room['room_number']) ?>">
        </div>
        <div class="field">
            <label>Room Type</label>
            <select class="input" name="room_type" required>
                <option value="">Select room type</option>
                <?php foreach (ROOM_TYPES as $t): ?><option value="<?= e($t) ?>" <?= $t === $room['room_type'] ? 'selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label>Capacity (guests)</label>
            <select class="input" name="capacity" required>
                <?php for ($i = 1; $i <= 10; $i++): ?><option value="<?= $i ?>" <?= (int)$room['capacity'] === $i ? 'selected' : '' ?>><?= $i ?> guest<?= $i > 1 ? 's' : '' ?></option><?php endfor; ?>
            </select>
        </div>
        <div class="field">
            <label>Price per Night (&#8369;)</label>
            <input class="input" type="number" name="price_per_night" placeholder="e.g. 1500" min="1" step="0.01" required value="<?= e($room['price_per_night']) ?>">
        </div>
        <div class="field full">
            <label>Status</label>
            <select class="input" name="status" required>
                <?php foreach (ROOM_STATUSES as $s): ?><option value="<?= e($s) ?>" <?= $s === $room['status'] ? 'selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="field full">
            <label>Description <span class="muted">(optional)</span></label>
            <textarea class="input" name="description" rows="3" maxlength="255" placeholder="Short description of the room"><?= e($room['description']) ?></textarea>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= e($buttonLabel) ?></button>
        <?php if (!empty($showReset)): ?><button type="button" id="resetRoomForm" class="btn btn-ghost">Reset</button><?php endif; ?>
        <a href="rooms.php" class="btn btn-ghost">Cancel</a>
    </div>
</form>
