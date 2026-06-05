@extends('layouts.app')

@section('title', $title)

@section('styles')

@endsection

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ $title }}</a></li>
            <li class="breadcrumb-item {{ $page ? '' : 'd-none' }}"><a href="javascript:void(0);">{{ $page }}</a></li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="card">
        <div class="card-header p-2 justify-content-end d-flex">
            <button type="button" class="btn btn-primary btn-sm" onclick="rowAdd()">
                <span>
                    <i class="bx bx-plus me-sm-1"></i> 
                    <span class="d-none d-sm-inline-block">Add</span>
                </span>
            </button>
        </div>
        <form id="formIndex" method="get"> 
            <input type="hidden" name="limit" value="10" /> 
            <input type="hidden" name="page" value="1" /> 
            <input type="hidden" name="pages" value="1" /> 
            <input type="hidden" name="pageMax" value="1" /> 
            <input type="hidden" name="sortField" value="" /> 
            <input type="hidden" name="sortBy" value="" /> 
            <div class="table-responsive text-nowrap">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Code" data-field="code">
                                    <span>Code</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Date From" data-field="dateFrom">
                                    <span>Date From</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Date To" data-field="dateTo">
                                    <span>Date To</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Destination" data-field="destination">
                                    <span>Destination</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Purpose" data-field="purpose">
                                    <span>Purpose</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center justify-content-center filterSort" data-label="Status" data-field="status">
                                    <span>Status</span>
                                </a>
                            </th>
                            <th class="text-center">Action</th>
                        </tr>
                        <tr valign="middle d-none">
                            <th class="text-center">
                                <input type="text" class="form-control" name="code" >
                            </th>
                            <th class="text-center">
                                <input type="date" class="form-control" name="dateFrom" >
                            </th>
                            <th class="text-center">
                                <input type="date" class="form-control" name="dateTo" >
                            </th>
                            <th class="text-center">
                                <input type="text" class="form-control" name="destination" >
                            </th>
                            <th class="text-center">
                                <input type="text" class="form-control" name="purpose" >
                            </th>
                            <th class="text-center">
                                <select name="status" class="form-control">
                                    <option value="">&nbsp;</option>
                                </select>
                            </th>
                            <th class="text-center">
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    <span>
                                        <i class="bx bx-filter-alt me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Filter</span>
                                    </span>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        <tr><td class="text-start" colspan="8">Loading...</td></tr>

                    </tbody>
                </table>
            </div>
            <div class="px-2 d-flex align-items-center justify-content-between flex-wrap w-100 mt-2">
                <div class="d-flex mb-2 align-items-center">
                    <div class="dt-info" id="pagingEntries">Showing 0 to 0 of 0 rows</div>
                    <div class="dt-length ms-2 d-flex">
                        <select id="pagingRows" name="pageRowCount" class="dt-input form-select form-select-sm w-auto" ></select>
                    </div>
                </div>
                <ul id="pagingPages" class="pagination mb-2"></ul>
            </div>
        </form>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="modalTravelRequestAdd" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formTravelRequestAdd">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTravelRequestAddTitle">Add Travel Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-2">
                                <label class="form-label">Date From <span class="text-danger">*</span></label>
                                <input type="date" name="dateFrom" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" class="form-control">
                            </div>
                            <div class="col-12 col-md-6 mb-2">
                                <label class="form-label">Date To <span class="text-danger">*</span></label>
                                <input type="date" name="dateTo" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-2">
                                <label class="form-label">Recommender <span class="text-danger">*</span></label>
                                <select class="form-control" name="recommendedBy">
                                    <option value="">&nbsp;</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 mb-2">
                                <label class="form-label">Travel Days (<span class="text-primary">Working Days</span>) <span class="text-danger">*</span></label>
                                <input type="number" name="travelWorkingDays" class="form-control" min="0" step=".5">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Destination <span class="text-danger">*</span></label>
                                <div class="row d-flex">
                                    <div class="col-12 col-sm-6 mb-2">
                                        <select class="form-control" name="provinceID">
                                            <option value="" selected hidden>Select Province</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <select class="form-control" name="cityID">
                                            <option value="" selected hidden>Select Municipality</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mb-2">
                                       <input type="text" name="destination" class="form-control" placeholder="Other details (e.g., GSIS Building/ Street/ Barangay)">
                                    </div>
                                </div>
                                <!-- <textarea name="destination" cols="30" rows="2" class="form-control"></textarea> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label class="form-label">Purpose <span class="text-danger">*</span></label>
                                <textarea name="purpose" cols="30" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label class="form-label">Appropriation to which travel is charged</label>
                                <textarea name="appropriation" cols="30" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label class="form-label">Remarks </label>
                                <textarea name="remarks" cols="30" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label class="form-label">Attachment</label>
                                <input type="file" name="files[]" class="form-control" accept=".png, .jpg, .jpeg, .gif, .xlsx, .xls, .pdf, .ppt, .pptx, .doc, .docx" multiple>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalTravelRequestView" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formTravelRequestView">
                    <input type="hidden" name="positionID" value="">
                    <input type="hidden" name="salary" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTravelRequestViewTitle">View Travel Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Code</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="code" value="-" readonly style="background-color: #ececec;">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Date</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="date" value="-" readonly style="background-color: #ececec;">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Destination</label>
                            <div class="col-sm-9">
                                <div class="form-control destination" style="background-color: #ececec; min-height: 37.6px;">-</div>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Purpose</label>
                            <div class="col-sm-9">
                                <div class="form-control purpose" style="background-color: #ececec; min-height: 37.6px;">-</div>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Appropriation</label>
                            <div class="col-sm-9">
                                <div class="form-control appropriation" style="background-color: #ececec; min-height: 37.6px;">-</div>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Remarks</label>
                            <div class="col-sm-9">
                                <div class="form-control remarks" style="background-color: #ececec; min-height: 37.6px;">-</div>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Date Inserted</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="dateInserted" value="-" readonly style="background-color: #ececec;">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Recommended By</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="recommender" value="-" readonly style="background-color: #ececec;">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Checker By</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="checker" value="-" readonly style="background-color: #ececec;">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Approved By</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="approver" value="-" readonly style="background-color: #ececec;">
                            </div>
                        </div>

                        <div class="form-group row mb-2"> 
                            <label class="col-sm-3 col-form-label">Disapproved By</label> 
                            <div class="col-sm-9"> 
                                <input type="text" class="form-control" name="disapprover" value="-" readonly style="background-color: #ececec;">
                            </div> 
                        </div> 
 
                        <div class="form-group row mb-2 dDis"> 
                            <label class="col-sm-3 col-form-label">Disapprove Reason</label> 
                            <div class="col-sm-9"> 
                                <div class="form-control comment" style="background-color: #ececec; min-height: 37.6px;">-</div>
                            </div> 
                        </div> 

                        <div class="form-group row mb-2" id="dDocumentHead">
                            <label class="col-sm-3 col-form-label">Attachment(s)</label>
                            <div class="col-sm-9 d-flex gap-3"></div>
                        </div>

                        <div class="form-group row mb-2"> 
                            <label class="col-sm-3 col-form-label">Status</label> 
                            <div class="col-sm-9"> 
                                <input type="text" class="form-control" name="status" value="-" readonly style="background-color: #ececec;">
                            </div> 
                        </div> 

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/custom/page_index.js') }}"></script>
    <script>

        const statusColor = {
            '-1': 'danger', 
            '0': 'info', 
            '1': 'primary', 
            '2': 'success', 
            '3': 'secondary', 
        }
        const statusName = {
            '-1': 'Disapproved', 
            '0': 'Pending', 
            '1': 'Recommended', 
            '2': 'Checked', 
            '3': 'Approved', 
        }

        function rowAdd()
        { 

            const formID = 'formTravelRequestAdd'

            apiCall(`/api/{{ "$controller" }}/page-post/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} select[name="provinceID"]`).html('<option value="" selected hidden>Select Province</option>')
                    $(`#${formID} select[name="cityID"]`).html('<option value="" selected hidden>Select Municipality</option>')

                    $(`#${formID} textarea[name="destination"]`).val('') 
                    $(`#${formID} textarea[name="purpose"]`).val('') 
                    $(`#${formID} textarea[name="note"]`).val('') 
                    $(`#${formID} textarea[name="remarks"]`).val('') 
                    $(`#${formID} input[name="files[]"]`).val('') 
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        html = '<option value="">&nbsp;</option>'
                        if (res.items.recommenders.length > 0) {
                            html = ''
                            for (key in res.items.recommenders) {
                                html += `<option value="${res.items.recommenders[key]['userID']}">${res.items.recommenders[key]['name']}</option>`
                            }
                        }
                        $(`#${formID} select[name="recommendedBy"]`).html(html)

                        html = '<option value="">&nbsp;</option>'
                        if (res.items.provinces.length > 0) {
                            html = '<option value="" selected hidden>Select Province</option>'
                            for (key in res.items.provinces) {
                                html += `<option value="${res.items.provinces[key]['provinceID']}">${res.items.provinces[key]['name']}</option>`
                            }
                        }
                        $(`#${formID} select[name="provinceID"]`).html(html)

                        $('#modalTravelRequestAdd').modal('show')
                    } else if (res.status == 401 && res.message == 'Invalid token') {
                        authenticationLogout()
                    } else {
                        Toast.fire({ icon : "warning", title : res.name, html : res.message })
                    }

                }, 
                // always
                function() {}, 
                localStorage.getItem('t') 
            )

        }
        $(document).on('change', '#formTravelRequestAdd input[name="dateFrom"]', function() {
            $('#formTravelRequestAdd input[name="dateTo"]').prop('min', $(this).val()).val($(this).val())
        })
        $(document).on('submit', '#formTravelRequestAdd', function(e) {
            e.preventDefault()

            const formID = 'formTravelRequestAdd'
            const formData = new FormData($('#'+formID).get(0))

            apiCall(`/api/{{ "$controller" }}/`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `Loading...`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        $('#modalTravelRequestAdd').modal('hide')
                        getItems()
                    } else if (res.status == 401 && res.message == 'Invalid token') {
                        authenticationLogout()
                    } else {
                        Toast.fire({ icon : "warning", title : res.name, html : res.message })
                    }

                }, 
                // always
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `Save`, 0)
                }, 
                localStorage.getItem('t') 
            )
        })

        $(document).on('change', 'select[name="provinceID"]', function() {

            const formID = 'formTravelRequestAdd'
            apiCall(`/api/{{ "profile-setup/get-cities" }}/${$(this).val()}`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} select[name="cityID"]`).html('<option value="" selected hidden>Select Municipality</option>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        html = '<option value="">&nbsp;</option>'
                        if (res.items.cities.length > 0) {
                            html = '<option value="" selected hidden>Select Municipality</option>'
                            for (key in res.items.cities) {
                                html += `<option value="${res.items.cities[key]['cityID']}">${res.items.cities[key]['name']}</option>`
                            }
                        }
                        $(`#${formID} select[name="cityID"]`).html(html)

                    } else if (res.status == 401 && res.message == 'Invalid token') {
                        authenticationLogout()
                    } else {
                        Toast.fire({ icon : "warning", title : res.name, html : res.message })
                    }

                }, 
                // always
                function() {}, 
                localStorage.getItem('t') 
            )

        })

        $(document).on('submit', '#formIndex', function(e) {
            e.preventDefault()
            setFilterItems('{{ "$controller" }}', 'formIndex')
            getItems()
        })

        function getItems()
        {

            const formID = 'formIndex'
            apiCall(`/api/{{ "$controller" }}/?${getFilterItems()}`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="8">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        /** filter fields */ 
                        $(`#${formID} input[name="code"]`).val(`${res.items.filters.code}`)
                        $(`#${formID} input[name="dateFrom"]`).val(`${res.items.filters.dateFrom}`)
                        $(`#${formID} input[name="dateTo"]`).val(`${res.items.filters.dateTo}`)
                        $(`#${formID} input[name="destination"]`).val(`${res.items.filters.destination}`)
                        $(`#${formID} input[name="purpose"]`).val(`${res.items.filters.purpose}`)

                        html = ''
                        if (res.items.statuses.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.statuses) {
                                html += `<option value="${res.items.statuses[key].status}" ${res.items.statuses[key].status===res.items.filters.status?'selected':''}>${res.items.statuses[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="status"]`).html(html)

                        /** filter paging entries */ 
                        $('#pagingEntries').html(`Showing ${res.items.filters.row_shown_first} to ${res.items.filters.row_shown_last} of ${res.items.filters.row_total} rows`)

                        /** filter paging limit */ 
                        html = ''
                        for (key in pagingLimits) {
                            html += `<option value="${key}" ${key.trim()==res.items.filters.limit?'selected':''} >${pagingLimits[key]}</option>`
                        }
                        $('#pagingRows').html(html)

                        /** filter pages */ 
                        $('#formIndex input[name="page"]').val(res.items.filters.page)
                        $('#formIndex input[name="pages"]').val(res.items.filters.pages)
                        $('#pagingPages').html(generatePages(res.items.filters.pages, res.items.filters.page))

                        // body
                        $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="8">No Record Found</td></tr>')
                        if (res.items.records.length > 0) {
                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.records) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-start">${res.items.records[key].code}</td>
                                        <td class="text-start">${res.items.records[key].dateFrom}</td>
                                        <td class="text-start">${res.items.records[key].dateTo}</td>
                                        <td class="text-start">${res.items.records[key].destination}</td>
                                        <td class="text-start">${res.items.records[key].purpose}</td>
                                        <td class="text-center">
                                            <span class="badge bg-${statusColor[res.items.records[key].status]}">${statusName[res.items.records[key].status]}</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-info btn-sm bView" data-id="${res.items.records[key].travelOrderID}">
                                                <span>
                                                    <i class="bx bx-show me-sm-1"></i> 
                                                    <span class="d-none d-sm-inline-block">View</span>
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                `)
                            }
                        }
                    } else if (res.status == 401 && res.message == 'Invalid token') {
                        authenticationLogout()
                    } else {
                        Toast.fire({ icon : "warning", title : res.name, html : res.message })
                    }

                }, 
                // always
                function() {}, 
                localStorage.getItem('t') 
            )

        }

        $(document).on('click', '.bView', function() {

            const formID = 'formTravelRequestView'

            apiCall(`/api/{{ "$controller" }}/${$(this).data('id')}`, 'GET', null, 
                // beforesend
                function() {
                    $('.dDis').slideUp()
                    $('#dDocumentHead').slideUp()
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        date = res.items.row.dateFrom

                        // fields
                        $(`#${formID} input[name="code"]`).val(res.items.row.code)
                        $(`#${formID} input[name="date"]`).val(res.items.row.date)
                        $(`#${formID} div.destination`).text(res.items.row.destination)
                        $(`#${formID} div.purpose`).text(res.items.row.purpose)
                        $(`#${formID} div.appropriation`).text(res.items.row.appropriation)
                        $(`#${formID} div.remarks`).text(res.items.row.remarks)
                        $(`#${formID} input[name="dateInserted"]`).val(res.items.row.dateInserted)
                        $(`#${formID} input[name="recommender"]`).val(res.items.row.recommender)
                        $(`#${formID} input[name="checker"]`).val(res.items.row.checker)
                        $(`#${formID} input[name="approver"]`).val(res.items.row.approver)
                        $(`#${formID} input[name="disapprover"]`).val(res.items.row.disapprover)
                        $(`#${formID} div.comment`).text(res.items.row.comment)
                        $(`#${formID} input[name="status"]`).val(statusName[res.items.row.status])

                        if (res.items.row.status == -1) $('.dDis').slideDown()

                        if (res.items.row.files.length > 0) {
                            $('#dDocumentHead div').html('')
                            for (key in res.items.row.files) {
                                $('#dDocumentHead div').append(`
                                    <a id="dDocument" href="${res.items.row.files[key].url}" target="_blank" download><u>${res.items.row.files[key].name}</u></a>
                                `)
                            }
                            $('#dDocumentHead').slideDown()
                        }

                        $('#modalTravelRequestView').modal('show')

                    } else if (res.status == 401 && res.message == 'Invalid token') {
                        authenticationLogout()
                    } else {
                        Toast.fire({ icon : "warning", title : res.name, html : res.message })
                    }

                }, 
                // always
                function() {}, 
                localStorage.getItem('t') 
            )

        })

        $(document).ready(function() {
            resetFilterItems(`{{ "$controller" }}`) 
            getItems() 
        })

    </script>
@endsection