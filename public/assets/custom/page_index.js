const filterSortDown    = `<i class="fa-solid fa-caret-down ms-1"></i>`
const filterSortUp      = `<i class="fa-solid fa-caret-up ms-1"></i>`
const pagingLimits = {
    '10': '10', 
    '20': '20', 
    '50': '50', 
    '100': '100', 
    '200': '200', 
    '500': '500', 
    // ' ': 'All', 
}

function resetFilterItems(current_controller)
{

    current_filter = localStorage.getItem('filter_index_items')
    if (current_filter) {
        current_filter_exploded = current_filter.split("&&&&&")
        if (current_filter_exploded.length > 0) {
            view = current_filter_exploded[0]
            if (view != current_controller) {
                localStorage.setItem('filter_index_items', '')
            }
        }
    }

    current_filter = localStorage.getItem('filter_index_items2')
    if (current_filter) {
        current_filter_exploded = current_filter.split("&&&&&")
        if (current_filter_exploded.length > 0) {
            view = current_filter_exploded[0]
            if (view != current_controller) {
                localStorage.setItem('filter_index_items', '')
            }
        }
    }

} 

function setFilterItems(controller, formID)
{
    let formData = new FormData(document.getElementById(formID))
    let urlParams = new URLSearchParams(formData).toString()
    localStorage.setItem('filter_index_items', `${controller}&&&&&${urlParams}`)
} 
function getFilterItems()
{
    parameters = ''
    if (localStorage.getItem('filter_index_items')) {
        filter_index_items = localStorage.getItem('filter_index_items')
        if (filter_index_items.includes("&&&&&")) {
            filter_index_items = filter_index_items.split('&&&&&')
            if (filter_index_items.length>1) parameters = filter_index_items[1]
        }
        
    }
    return parameters
} 

function setFilterItems2(controller, formID)
{
    let formData = new FormData(document.getElementById(formID))
    let urlParams = new URLSearchParams(formData).toString()
    localStorage.setItem('filter_index_items2', `${controller}&&&&&${urlParams}`)
} 
function getFilterItems2()
{
    parameters = ''
    if (localStorage.getItem('filter_index_items2')) {
        filter_index_items = localStorage.getItem('filter_index_items2')
        if (filter_index_items.includes("&&&&&")) {
            filter_index_items = filter_index_items.split('&&&&&')
            if (filter_index_items.length>1) parameters = filter_index_items[1]
        }
        
    }
    return parameters
} 

$(document).on('click', '.filterSort', function() {
    dis = this
    var value       = $(dis).data('field')
    var sortField   = $(dis).closest('form').find('input[name="sortField"]').val()
    var sortBy      = $(dis).closest('form').find('input[name="sortBy"]').val()
    if (value == sortField) {
        sortBy = sortBy === 'asc' ? 'desc' : 'asc'
    } else {
        sortBy = 'asc'
    }

    // show arrow 
    $(dis).closest('tr').find('th a').each(function() {
        $(this).html(`<span>${$(this).data('label')}</span>`)
    })
    $(dis).html(`<span>${$(dis).data('label')}</span>&nbsp;${sortBy=='asc'?filterSortDown:filterSortUp}`)

    $(dis).closest('form').find('input[name="sortField"]').val(value)
    $(dis).closest('form').find('input[name="sortBy"]').val(sortBy)
    $(dis).closest('form').submit()
})

// paging limit 
$(document).on('change', '#pagingRows', function() {
    $(this).closest('form').find('input[name="limit"]').val($(this).val())
    $(this).closest('form').find('input[name="page"]').val(1)
    $(this).closest('form').submit()
})
$(document).on('change', '#pagingRows2', function() {
    $(this).closest('form').find('input[name="limit"]').val($(this).val())
    $(this).closest('form').find('input[name="page"]').val(1)
    $(this).closest('form').submit()
})

// new page 
$(document).on('click', '.btnNewPage', function() {
    $(this).closest('form').find('input[name="page"]').val($(this).data('page'))
    $(this).closest('form').submit()
})

// back page
$(document).on('click', '.btnPageBack', function() {
    let pageNew = parseInt($(this).closest('form').find('input[name="page"]').val())-1
    if (pageNew <=0) pageNew = 1
    $(this).closest('form').find('input[name="page"]').val(pageNew)
    $(this).closest('form').submit()
})

// next page
$(document).on('click', '.btnPageNext', function() {
    let pageNew = parseInt($(this).closest('form').find('input[name="page"]').val())+1
    if (pageNew > parseInt($(this).closest('form').find('input[name="pages"]').val())) pageNew = parseInt($(this).closest('form').find('input[name="pages"]').val())
    $(this).closest('form').find('input[name="page"]').val(pageNew)
    $(this).closest('form').submit()
})

function generatePages(pages, page)
{

    let pagingPages = '<li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>'
    if (pages > 1) {
        pagingPages = `
            ${pages>5?`<li class="page-item first"><a class="page-link btnNewPage" href="javascript:void(0);" data-page="1"><i class="tf-icon bx bx-chevrons-left"></i></a></li>`:''}
            ${pages>5?`<li class="page-item prev"><a class="page-link btnPageBack" href="javascript:void(0);"><i class="tf-icon bx bx-chevron-left"></i></a></li>`:''}
            ${((parseInt(page)-4)>0 && (parseInt(page)+2)>pages)?`<li class="page-item"><a class="page-link btnNewPage" href="javascript:void(0);" data-page="${(parseInt(page)-4)}">${(parseInt(page)-4)}</a></li>`:''}
            ${((parseInt(page)-3)>0 && (parseInt(page)+1)>pages)?`<li class="page-item"><a class="page-link btnNewPage" href="javascript:void(0);" data-page="${(parseInt(page)-3)}">${(parseInt(page)-3)}</a></li>`:''}
            ${(parseInt(page)-2)>0?`<li class="page-item"><a class="page-link btnNewPage" href="javascript:void(0);" data-page="${(parseInt(page)-2)}">${(parseInt(page)-2)}</a></li>`:''}
            ${(parseInt(page)-1)>0?`<li class="page-item"><a class="page-link btnNewPage" href="javascript:void(0);" data-page="${(parseInt(page)-1)}">${(parseInt(page)-1)}</a></li>`:''}
            <li class="page-item active"><a class="page-link" href="javascript:void(0);">${page}</a></li>
            ${(parseInt(page)+1)<=pages?`<li class="page-item"><a class="page-link btnNewPage" href="javascript:void(0);" data-page="${(parseInt(page)+1)}">${(parseInt(page)+1)}</a></li>`:''}
            ${(parseInt(page)+2)<=pages?`<li class="page-item"><a class="page-link btnNewPage" href="javascript:void(0);" data-page="${(parseInt(page)+2)}">${(parseInt(page)+2)}</a></li>`:''}
            ${((parseInt(page)+3)<=pages && ((parseInt(page)-1)<=0))?`<li class="page-item"><a class="page-link btnNewPage" href="javascript:void(0);" data-page="${(parseInt(page)+3)}">${(parseInt(page)+3)}</a></li>`:''}
            ${((parseInt(page)+4)<=pages && ((parseInt(page)-2)<=0))?`<li class="page-item"><a class="page-link btnNewPage" href="javascript:void(0);" data-page="${(parseInt(page)+4)}">${(parseInt(page)+4)}</a></li>`:''}
            ${pages>5?`<li class="page-item next"><a class="page-link btnPageNext" href="javascript:void(0);"><i class="tf-icon bx bx-chevron-right"></i></a></li>`:''}
            ${pages>5?`<li class="page-item last"><a class="page-link btnNewPage" href="javascript:void(0);"  data-page="${pages}"><i class="tf-icon bx bx-chevrons-right"></i></a></li>`:''}
        `
    } 
    return pagingPages

}