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
        let sshId = $this.data('ssh-id')
        $('#commandModal').find('[name="ssh_id"]').val(sshId)
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
        popup(route('CommandController', 'execute', { id }), 'Command Output');
    })

    $(document).on('click', '.execute-all-commands', e => {
        e.preventDefault()
        const $this = $(e.currentTarget)
        let sshId = $this.data('ssh-id')
        popup(route('CommandController', 'executeAll', { sshId }), 'Command Output');
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