<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" />

<h2 class="display-6 text-center mb-4">Servers</h2>
<div class="text-end mb-4">
    <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#serverModal"><i class="fa fa-plus"></i> Add New Server</a>
</div>
<div class="table-responsive">
    <table class="table text-center actions-table" id="dynamic-table">
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Hostname</th>
                <th>Username</th>
                <th>Port</th>
                <th>Identities Only</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th data-sorting="false">Actions</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<!-- The Modal -->
<?php include VIEWS_PATH . '/servers/modals.php'; ?>
<?php include VIEWS_PATH . '/commands/modals.php'; ?>

<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

<script>
    let dt
    let lastOpenedRows = localStorage.getItem('lastOpenedRows') || []
</script>