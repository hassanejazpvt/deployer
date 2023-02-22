<form method="post" action="<?= route('ServerController', (isset($server) ? 'update' : 'store')) ?>" class="serverForm">
    <!-- Modal Header -->
    <div class="modal-header">
        <h4 class="modal-title"><?= isset($server) ? 'Edit' : 'Add New' ?> Server</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <!-- Modal body -->
    <div class="modal-body">
        <div class="mb-3">
            <label for="">Name</label>
            <input type="text" name="name" value="<?= @__($server, 'name') ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="">Hostname</label>
            <input type="text" name="hostname" value="<?= @__($server, 'hostname') ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="">Username</label>
            <input type="text" name="username" value="<?= @__($server, 'username') ?>" class="form-control" required>
        </div>
        <div class="mb-3 password-wrapper" style="display: none;">
            <label for="">Password</label>
            <input type="text" name="password" value="<?= @__($server, 'password') ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label for="">Port</label>
            <input type="number" name="port" value="<?= @__($server, 'port') ?>" class="form-control" required>
        </div>
        <div class="mb-3 keys-wrapper">
            <label for="">Public Key</label>
            <textarea name="public_key" required class="form-control" rows="5"><?= @__($server, 'public_key') ?></textarea>
        </div>
        <div class="mb-3 keys-wrapper">
            <label for="">Private Key</label>
            <textarea name="private_key" required class="form-control" rows="5"><?= @__($server, 'private_key') ?></textarea>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="hidden" value="0" name="use_password">
                <input class="form-check-input" type="checkbox" value="1" <?= @__($server, 'use_password') ? 'checked' : '' ?> name="use_password"> Use Password
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="hidden" value="0" name="identities_only">
                <input class="form-check-input" type="checkbox" value="1" <?= @__($server, 'identities_only') ? 'checked' : '' ?> name="identities_only"> Identities Only
            </label>
        </div>
    </div>

    <!-- Modal footer -->
    <div class="modal-footer">
        <?php if (isset($server)) { ?>
            <input type="hidden" name="id" value="<?= __($server, 'id') ?>">
        <?php } ?>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>