$(document).ajaxStart(() => {
    $('#loader-wrapper').show()
})
$(document).ajaxStop(() => {
    $('#loader-wrapper').hide()
})