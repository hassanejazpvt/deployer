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
<div class="modal fade in" id="serverModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php include VIEWS_PATH . '/servers/form.php' ?>
        </div>
    </div>
</div>

<div class="modal fade in" id="editServerModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>

<div class="modal fade in" id="commandModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php include VIEWS_PATH . '/commands/form.php' ?>
        </div>
    </div>
</div>

<div class="modal fade in" id="editCommandModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

<script>
    let dt
    let lastOpenedRows = localStorage.getItem('lastOpenedRows') || []

    $(() => {
        dt = $('#dynamic-table').DataTable({
            ajax: '<?= route('ServerController', 'index') ?>',
            initComplete: () => {
                if (String(lastOpenedRows)) {
                    rows = lastOpenedRows.split(',')
                    rows.forEach(openedRowId => {
                        lastOpenedRows = rows.filter(rowId => rowId !== openedRowId)
                        $(`.verify-server[data-id="${openedRowId}"]`).closest('tr').find('.dt-control').click()
                    })
                }
            },
            aoColumnDefs: [{
                "aTargets": [7],
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
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                },
                {
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
                [6, "desc"]
            ]
        })

        // Add event listener for opening and closing details
        $('#dynamic-table tbody').on('click', 'td.dt-control', function() {
            var tr = $(this).closest('tr');
            var row = dt.row(tr);

            function format(d) {
                let commands = ''
                d.commands.map(command => {
                    commands +=
                        `<tr>
                            <td>${command.name}</td>
                            <td>${command.command}</td>
                            <td>
                                <a href="javascript:;" class="btn btn-sm btn-warning execute-command me-2" data-id="${command.id}">Execute</a>
                                <a href="javascript:;" class="btn btn-sm btn-success edit-command me-2" data-id="${command.id}">Edit</a>
                                <a href="javascript:;" class="btn btn-sm btn-danger delete-command" data-id="${command.id}">Delete</a>
                            </td>
                        </tr>`
                })
                let allCommandsButton = ''
                if (d.commands.length) {
                    allCommandsButton = `<a href="javascript:;" class="btn btn-warning btn-sm execute-all-commands" data-ssh-id="${d.id}"><i class="fa fa-spinner"></i> Execute All Commands</a>`
                }
                return (
                    `
                    <div class="text-end mb-1">
                        ${allCommandsButton}
                        <a href="javascript:;" class="btn btn-primary btn-sm add-command" data-ssh-id="${d.id}"><i class="fa fa-plus"></i> Add New Command</a>
                    </div>
                    <table class="no-footer table text-start actions-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Command</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>${commands}</tbody>
                    </table>`
                );
            }

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                lastOpenedRows = lastOpenedRows.filter(rowId => rowId !== row.data().id)
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
                lastOpenedRows.push(row.data().id)
            }
            localStorage.setItem('lastOpenedRows', lastOpenedRows)
        });
    })
</script>