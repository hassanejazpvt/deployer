$(document).ajaxStart(() => {
    $('#loader-wrapper').show()
})
$(document).ajaxStop(() => {
    $('#loader-wrapper').hide()
    setTimeout(() => {
        $('[data-bs-toggle="tooltip"]').tooltip()
    }, 1)
})

window.initFormValidator = (selector) => {
    $(selector).each((index, item) => {
        $(item).validate({
            invalidHandler: function () {
                $(this).addClass('was-validated')
            },
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback').insertAfter(element);
            }
        })
    })
}

window.popup = (url, title) => {
    const newWindow = window.open(url, title,
        `
      scrollbars=yes,
      width=${screen.availWidth}, 
      height=${screen.availHeight}, 
      top=0, 
      left=0
      `
    )

    if (window.focus) newWindow.focus();
}

window.route = (controller, method, data) => {
    data = data || {}
    let params = {
        _c: controller,
        _m: method
    }
    for (let key in data) {
        params[key] = data[key]
    }
    return '?' + (new URLSearchParams(params).toString());
}