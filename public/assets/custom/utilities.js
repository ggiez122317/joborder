
function btnLoading(classOrID, html, status=1)
{
    $(classOrID).html(html).prop('disabled', status?true:false)

}

function apiCall(req_url, req_method, req_formdata, callback_beforeSend, callback_done, callback_always, req_token='')
{

    // common options
    var ajaxOptions = {
        url: BASE_URL_BACKEND + req_url.replace(/\/$/, ""), 
        type: req_method,
        beforeSend: function(xhr) {
            callback_beforeSend()
            // add token if exist
            if (req_token) xhr.setRequestHeader('Authorization', 'Bearer ' + req_token)
            xhr.setRequestHeader('Device-Identifier', localStorage.getItem('fp')?localStorage.getItem('fp'):'')
        }
    }

    // additional options
    if (['POST', 'PUT'].includes(req_method.toUpperCase())) {
        ajaxOptions.data = req_formdata
        ajaxOptions.processData = false // Prevent jQuery from processing the data
        ajaxOptions.contentType = false // Prevent jQuery from setting the content type
    }


    $.ajax(ajaxOptions)
        .done(function(response) {
            callback_done(response)
            console.log(response)
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('Error:', textStatus, errorThrown)
        })
        .always(function() {
            callback_always()
        })

}

function popupCenteredWindow(url='') 
{
    const width = 800
    const height = 600
    const left = (window.screen.width - width) / 2
    const top = (window.screen.height - height) / 2
    window.open(url, "popupWindow", `width=${width},height=${height},left=${left},top=${top},resizable,scrollbars`)
}

function getCurrentDateTime() 
{
    let now = new Date()
    let year = now.getFullYear()
    let month = String(now.getMonth() + 1).padStart(2, '0')
    let day = String(now.getDate()).padStart(2, '0')
    let hour = String(now.getHours()).padStart(2, '0')
    let minute = String(now.getMinutes()).padStart(2, '0')
    let second = String(now.getSeconds()).padStart(2, '0')
    return `${year}${month}${day}${hour}${minute}${second}`
}

function roundDownToHundredth(num) 
{
  return Math.floor(num * 100) / 100;
}

/**
 * CORE
 */

$(document).on('click', '.accessAll', function() {
    $(".accessModule, .accessModuleAction").prop("checked", $(this).prop("checked")).prop("disabled", $(this).prop("checked")?false:true)
    if ($(this).prop("checked") == false) $(".accessModule").prop("disabled", false)
})

$(document).on('click', '.accessModule', function() {
    $(this).closest("tr").find(".accessModuleAction").prop("checked", $(this).prop("checked")).prop("disabled", $(this).prop("checked")?false:true)
})

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }, 
})

function compareValues(a, b) 
{
    return a === "" && b === ""
}
