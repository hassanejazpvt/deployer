<form method="post" action="<?= route('ServerController', (isset($ssh) ? 'update' : 'store')) ?>" id="serverForm">
    <!-- Modal Header -->
    <div class="modal-header">
        <h4 class="modal-title"><?= isset($ssh) ? 'Edit' : 'Add New' ?> Server</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <!-- Modal body -->
    <div class="modal-body">
        <div class="mb-3">
            <label for="">Hostname</label>
            <input type="text" name="hostname" value="<?= @__($ssh, 'hostname') ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="">Username</label>
            <input type="text" name="username" value="<?= @__($ssh, 'username') ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="">Port</label>
            <input type="number" name="port" value="<?= @__($ssh, 'port') ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="">Public Key</label>
            <textarea name="public_key" class="form-control" rows="5" required><?= @__($ssh, 'public_key') ?></textarea>
        </div>
        <div class="mb-3">
            <label for="">Private Key</label>
            <textarea name="private_key" class="form-control" rows="5" required><?= @__($ssh, 'private_key') ?></textarea>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="hidden" value="0" name="identities_only">
                <input class="form-check-input" type="checkbox" value="1" <?= @__($ssh, 'identities_only') ? 'checked' : '' ?> name="identities_only"> Identities Only
            </label>
        </div>
    </div>

    <!-- Modal footer -->
    <div class="modal-footer">
        <?php if (isset($ssh)) { ?>
            <input type="hidden" name="id" value="<?= __($ssh, 'id') ?>">
        <?php } ?>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>