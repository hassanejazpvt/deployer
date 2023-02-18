<form method="post" action="<?= route('CommandController', (isset($command) ? 'update' : 'store')) ?>" class="commandForm">
    <!-- Modal Header -->
    <div class="modal-header">
        <h4 class="modal-title"><?= isset($command) ? 'Edit' : 'Add New' ?> Command</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <!-- Modal body -->
    <div class="modal-body">
        <div class="mb-3">
            <label for="">Name</label>
            <input type="text" name="name" value="<?= @__($command, 'name') ?>" class="form-control" required>
        </div>
        <div>
            <label for="">Command</label>
            <textarea name="command" class="form-control" rows="5" required><?= @__($command, 'command') ?></textarea>
        </div>
    </div>

    <!-- Modal footer -->
    <div class="modal-footer">
        <?php if (isset($command)) { ?>
            <input type="hidden" name="id" value="<?= __($command, 'id') ?>">
        <?php } ?>
        <input type="hidden" name="server_id" value="<?= @__($command, 'server_id') ?>">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>