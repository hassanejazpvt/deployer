<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" />

<h2 class="display-6 text-center mb-4">Servers</h2>
<div class="text-end mb-4">
    <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#serverModal"><i class="fa fa-plus"></i> Add New Server</a>
</div>
<div class="table-responsive">
    <table class="table text-center" id="dynamic-table">
        <thead>
            <tr>
                <th>Hostname</th>
                <th>Username</th>
                <th>Port</th>
                <th>Identities Only</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th data-sorting="false"></th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<!-- The Modal -->
<div class="modal fade in" id="serverModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php include 'form.php' ?>
        </div>
    </div>
</div>

<div class="modal fade in" id="editServerModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

<script>
    let dt
    let EDIT_SERVER_URL = '<?= route('ServerController', 'edit', ['id' => '-id-']) ?>'
    let VERIFY_SERVER_URL = '<?= route('ServerController', 'verify', ['id' => '-id-']) ?>'
    let DELETE_SERVER_URL = '<?= route('ServerController', 'delete', ['id' => '-id-']) ?>'

    $(() => {
        dt = $('#dynamic-table').DataTable({
            ajax: '<?= route('ServerController', 'index') ?>',
            aoColumnDefs: [{
                "aTargets": [6],
                "mData": null,
                "mRender": function(data, type, full) {
                    let html = ''
                    html += `<a href="javascript:;" class="btn btn-sm btn-info verify-server me-2" data-id="${data.id}">Verify</a>`;
                    html += `<a href="javascript:;" class="btn btn-sm btn-success edit-server me-2" data-id="${data.id}">Edit</a>`;
                    html += `<a href="javascript:;" class="btn btn-sm btn-danger delete-server" data-id="${data.id}">Delete</a>`;
                    return html
                }
            }],
            columns: [{
                    data: 'hostname'
                },
                {
                    data: 'username'
                },
                {
                    data: 'port'
                },
                {
                    data: 'identities_only'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                }
            ],
            order: [
                [5, "desc"]
            ]
        })
    })
</script>