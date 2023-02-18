$(() => {
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
                        }
                    })
                    .fail(error => {
                        swal({
                            icon: 'error',
                            text: error.statusText
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
                        text: data.message
                    })
                } else {
                    swal({
                        icon: 'error',
                        text: data.message
                    })
                }
            })
            .fail(error => {
                swal({
                    icon: 'error',
                    text: error.statusText
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