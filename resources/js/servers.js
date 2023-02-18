$(() => {
    $(document).on('change', '#import-collection', e => {
        const $this = $(e.currentTarget)
        if ($this[0].files.length) {
            swal("Are you sure you want to perform this action?", {
                buttons: {
                    cancel: true,
                    yes: {
                        text: "Yes",
                    },
                },
            }).then((value) => {
                if (value === 'yes') {
                    let fd = new FormData()
                    fd.append('file', $this[0].files[0])
                    $.ajax({
                        url: route('ServerController', 'import'),
                        type: 'POST',
                        cache: false,
                        processData: false,
                        contentType: false,
                        data: fd,
                        dataType: 'json',
                        success: data => {
                            swal({
                                icon: 'success',
                                text: data.message,
                                timer: 3000
                            })
                            dt.ajax.reload()
                        },
                        error: error => {
                            swal({
                                icon: 'error',
                                message: 'Something went wrong.',
                                timer: 3000
                            })
                        }
                    })
                }
                $this.val('')
            })
        }
    })

    $(document).on('xhr.dt', '#dynamic-table', () => {
        setTimeout(() => {
            if (String(lastOpenedRows)) {
                rows = String(lastOpenedRows).split(',')
                rows.forEach(async openedRowId => {
                    lastOpenedRows = rows.filter(rowId => rowId !== openedRowId)
                    $(`.verify-server[data-id="${openedRowId}"]`).closest('tr').find('.dt-control').click()
                })
            }
        }, 1)
    })

    dt = $('#dynamic-table').DataTable({
        ajax: route('ServerController', 'index'),
        aoColumnDefs: [{
            "aTargets": [8],
            "mData": null,
            "mRender": function (data, type, full) {
                let html = ''
                html += `<a href="javascript:;" data-bs-toggle="tooltip" title="Verify" class="btn btn-sm btn-info verify-server me-2" data-id="${data.id}"><i class="fa fa-chain"></i></a>`;
                if (data.commands.length) {
                    html += `<a href="javascript:;" data-bs-toggle="tooltip" title="Execute All Commands" class="btn btn-warning btn-sm execute-all-commands me-2" data-server-id="${data.id}"><i class="fa fa-spinner"></i></a>`
                }
                html += `<a href="javascript:;" data-bs-toggle="tooltip" title="Edit" class="btn btn-sm btn-success edit-server me-2" data-id="${data.id}"><i class="fa fa-edit"></i></a>`;
                html += `<a href="javascript:;" data-bs-toggle="tooltip" title="Delete" class="btn btn-sm btn-danger delete-server" data-id="${data.id}"><i class="fa fa-trash"></i></a>`;
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
            data: 'name'
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
            [7, "desc"]
        ]
    })

    // Add event listener for opening and closing details
    $('#dynamic-table tbody').on('click', 'td.dt-control', function () {
        var tr = $(this).closest('tr');
        var row = dt.row(tr);

        function format(d) {
            let commands = ''
            d.commands.map(command => {
                commands +=
                    `<tr>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input command-checkbox" data-server-id="${d.id}" data-id="${command.id}">
                            </div>
                        </td>
                        <td>${command.name}</td>
                        <td>${command.command}</td>
                        <td>
                            <a href="javascript:;" data-bs-toggle="tooltip" title="Execute" class="btn btn-sm btn-warning execute-command me-2" data-id="${command.id}"><i class="fa fa-terminal"></i></a>
                            <a href="javascript:;" data-bs-toggle="tooltip" title="Edit" class="btn btn-sm btn-success edit-command me-2" data-id="${command.id}"><i class="fa fa-edit"></i></a>
                            <a href="javascript:;" data-bs-toggle="tooltip" title="Delete" class="btn btn-sm btn-danger delete-command" data-id="${command.id}"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>`
            })
            let allCommandsButton = ''
            if (d.commands.length) {
                allCommandsButton += `<a href="javascript:;" class="btn btn-warning btn-sm execute-selected-commands me-2" style="display: none;" data-server-id="${d.id}"><i class="fa fa-check-square-o"></i> Execute Selected Commands</a>`
                allCommandsButton += `<a href="javascript:;" class="btn btn-warning btn-sm execute-all-commands me-2" data-server-id="${d.id}"><i class="fa fa-spinner"></i> Execute All Commands</a>`
            }
            return (
                `
                <div class="text-end mb-1">
                    ${allCommandsButton}
                    <a href="javascript:;" class="btn btn-primary btn-sm add-command float-end" data-server-id="${d.id}"><i class="fa fa-plus"></i> Add New Command</a>
                </div>
                <table class="no-footer table text-start actions-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th width="50%">Command</th>
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

    $(document).on('click', '.delete-server', e => {
        e.preventDefault()
        const $this = $(e.currentTarget)
        let id = $this.data('id')
        swal("Are you sure you want to perform this action?", {
            buttons: {
                cancel: true,
                yes: {
                    text: "Yes",
                },
            },
        }).then((value) => {
            if (value === 'yes') {
                $.post(route('ServerController', 'delete', { id }))
                    .done(data => {
                        if (data.status) {
                            dt.ajax.reload()
                            swal({
                                icon: 'success',
                                text: data.message,
                                timer: 3000
                            })
                        }
                    })
                    .fail(error => {
                        swal({
                            icon: 'error',
                            text: error.statusText,
                            timer: 3000
                        })
                    })
            }
        });
    });

    $(document).on('click', '.edit-server', e => {
        e.preventDefault()
        const $this = $(e.currentTarget)
        let id = $this.data('id')
        $('#editServerModal .modal-content').load(route('ServerController', 'edit', { id }), () => {
            $('#editServerModal').modal('show')
        })
    })

    $(document).on('click', '.verify-server', e => {
        e.preventDefault()
        const $this = $(e.currentTarget)
        let id = $this.data('id')
        $.post(route('ServerController', 'verify', { id }))
            .done(data => {
                if (data.status) {
                    swal({
                        icon: 'success',
                        text: data.message,
                        timer: 3000
                    })
                } else {
                    swal({
                        icon: 'error',
                        text: data.message,
                        timer: 3000
                    })
                }
            })
            .fail(error => {
                swal({
                    icon: 'error',
                    text: error.statusText,
                    timer: 3000
                })
            })
    })

    initFormValidator('.serverForm')

    $(document).ajaxStop(() => {
        setTimeout(() => {
            initFormValidator('.serverForm')
        }, 500)
    })

    $(document).on('submit', '.serverForm', e => {
        e.preventDefault()

        const $this = $(e.currentTarget)
        $.post($this.attr('action'), $this.serialize())
            .done(data => {
                if (data.status) {
                    $this.closest('.modal').on('hidden.bs.modal', () => {
                        $this[0].reset()
                        $this.closest('.modal').off('hidden.bs.modal')
                    })
                    $this.closest('.modal').modal('hide')
                    dt.ajax.reload()
                    swal({
                        icon: 'success',
                        text: data.message,
                        timer: 3000
                    })
                }
            })
            .fail(error => {
                swal({
                    icon: 'error',
                    text: error.statusText,
                    timer: 3000
                })
            })
    })
})