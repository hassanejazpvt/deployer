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
                $.post(DELETE_SERVER_URL.replace(/\-id\-/, id))
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
        $('#editServerModal .modal-content').load(EDIT_SERVER_URL.replace(/\-id\-/, id))
        $('#editServerModal').modal('show')
    })

    $(document).on('click', '.verify-server', e => {
        e.preventDefault()
        const $this = $(e.currentTarget)
        let id = $this.data('id')
        $.post(VERIFY_SERVER_URL.replace(/\-id\-/, id))
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

    $(document).on('submit', '#serverForm', e => {
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