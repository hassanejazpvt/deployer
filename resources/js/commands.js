$(() => {
    $(document).on('click', '.delete-command', e => {
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
                $.post(route('CommandController', 'delete', { id }))
                    .done(data => {
                        if (data.status) {
                            dt.ajax.reload()
                        }
                    })
                    .fail(error => {
                        swal({
                            icon: 'error',
                            text: error.statusText
                        })
                    })
            }
        })
    })

    $(document).on('click', '.add-command', e => {
        e.preventDefault()
        const $this = $(e.currentTarget)
        let serverId = $this.data('server-id')
        $('#commandModal').find('[name="server_id"]').val(serverId)
        $('#commandModal').modal('show')
    })

    $(document).on('click', '.edit-command', e => {
        e.preventDefault()
        const $this = $(e.currentTarget)
        let id = $this.data('id')
        $('#editCommandModal .modal-content').load(route('CommandController', 'edit', { id }), () => {
            $('#editCommandModal').modal('show')
        })
    })

    $(document).on('click', '.execute-command', e => {
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
                popup(route('CommandController', 'execute', { id }), 'Command Output');
            }
        })
    })

    $(document).on('click', '.execute-all-commands', e => {
        e.preventDefault()
        const $this = $(e.currentTarget)
        let serverId = $this.data('server-id')
        swal("Are you sure you want to perform this action?", {
            buttons: {
                cancel: true,
                yes: {
                    text: "Yes",
                },
            },
        }).then((value) => {
            if (value === 'yes') {
                popup(route('CommandController', 'executeAll', { serverId }), 'Command Output');
            }
        })
    })

    $(document).on('change', '.command-checkbox', e => {
        const $this = $(e.currentTarget)
        let serverId = $this.data('server-id')
        $(`.execute-selected-commands[data-server-id="${serverId}"]`).toggle($(`.command-checkbox[data-server-id="${serverId}"]:checked`).length > 0)
    })

    $(document).on('click', '.execute-selected-commands', e => {
        e.preventDefault()
        const $this = $(e.currentTarget)
        let serverId = $this.data('server-id')
        if ($(`.command-checkbox[data-server-id="${serverId}"]:checked`).length) {
            swal("Are you sure you want to perform this action?", {
                buttons: {
                    cancel: true,
                    yes: {
                        text: "Yes",
                    },
                },
            }).then((value) => {
                if (value === 'yes') {
                    let commandIds = []
                    $(`.command-checkbox[data-server-id="${serverId}"]:checked`).each((index, item) => {
                        commandIds.push($(item).data('id'))
                    })
                    popup(route('CommandController', 'execute', { id: commandIds }), 'Command Output');
                }
            })
        } else {
            swal({
                icon: 'error',
                text: 'Select at least 1 command to execute.'
            })
        }
    })

    $(document).ajaxStop(() => {
        setTimeout(() => {
            initFormValidator('.commandForm')
        }, 500)
    })

    initFormValidator('.commandForm')

    $(document).on('submit', '.commandForm', e => {
        e.preventDefault()

        const $this = $(e.currentTarget)
        $.post($this.attr('action'), $this.serialize())
            .done(data => {
                if (data.status) {
                    $this.closest('.modal').on('hidden.bs.modal', () => {
                        $this[0].reset()
                    }).off('hidden.bs.modal')
                    $this.closest('.modal').modal('hide')
                    dt.ajax.reload()
                }
            })
            .fail(error => {
                swal({
                    icon: 'error',
                    text: error.statusText
                })
            })
    })
})