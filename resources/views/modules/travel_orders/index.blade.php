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
            <div class="divBtnAdd"></div>
            <div class="mb-xl-0 dPrints">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm ms-2 dropdown-toggle" type="button" id="dropdownMenuDate" data-bs-toggle="dropdown" aria-expanded="true">
                        <span class="tf-icons bx bx-printer me-1"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuDate" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(8px, 30.4px, 0px);" data-popper-placement="bottom-start">
                        <li class="print-list"></li>
                        <li class="print-travel-report"></li>
                    </ul>
                </div>
            </div>
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
                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Date Inserted" data-field="dateInserted">
                                    <span>Date Inserted</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Code" data-field="code">
                                    <span>Code</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Employee" data-field="lname">
                                    <span>Employee</span>
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
                                <input type="date" class="form-control" name="dateInserted" >
                            </th>
                            <th class="text-center">
                                <input type="text" class="form-control" name="code" >
                            </th>
                            <th class="text-center"></th>
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
    <div class="modal fade" id="modalPrintTravel" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalPrintTravelLabel">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form id="formPrintTravel" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalPrintTravelLabel">Print Travel Report</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <label class="form-label">Office <span class="text-danger">*</span></label>
                            <select name="officeIDs" class="form-control select2" multiple>
                                <option value="">&nbsp;</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Date Inserted <span class="text-danger">*</span></label> 
                            <select name="dateInserted" class="form-control">
                                <option value="">&nbsp;</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Print</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/custom/page_index.js') }}"></script>
    <script src="{{ asset('assets/js/forms-selects.js') }}"></script>
    <script>

        function printIndex() 
        {
            popupCenteredWindow(`{{ url("/$controller/print-list/") }}/?${getFilterItems()}/`) 
        }

        function modalPrintTravelReport()
        {

            const formID = 'formPrintTravel'
            apiCall(`/api/{{ "$controller/" }}print-travel-report-page/`, 'GET', null, 
                // beforesend
                function() { }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        html = '<option value="">&nbsp;</option>'
                        if (res.items.offices.length > 0) {
                            html = ''
                            for (key in res.items.offices) {
                                html += `<option value="${res.items.offices[key].officeID}" selected>${res.items.offices[key].code}</option>`
                            }
                        }
                        $(`#${formID} select[name="officeIDs"]`).html(html)

                        html = '<option value="">&nbsp;</option>'
                        if (res.items.dates.length > 0) {
                            html = ''
                            for (key in res.items.dates) {
                                html += `<option value="${res.items.dates[key].date}">${res.items.dates[key].format}</option>`
                            }
                        }
                        $(`#${formID} select[name="dateInserted"]`).html(html)

                        $('#modalPrintTravel').modal('show')
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
        $(document).on('submit', '#formPrintTravel', function(e) {
            e.preventDefault()

            const formID = 'formPrintTravel'

            let officeIDs       = $(`#${formID} select[name="officeIDs"]`).val()
            let dateInserted    = $(`#${formID} select[name="dateInserted"]`).val()

            let params = []

            if (officeIDs && officeIDs.length > 0) params.push('officeIDs=' + encodeURIComponent(officeIDs.join(',')));
            if (dateInserted) params.push('dateInserted=' + encodeURIComponent(dateInserted));

            printTravelReport(params.join('&'))

            $('#modalPrintTravel').modal('hide')

        })
        function printTravelReport(params)
        {
            popupCenteredWindow(`{{ url("/$controller/print-travel-report") }}?${params}`) 
        }

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
                    $('.dPrints').slideUp()
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        /** access buttons */ 
                        if (res.items.hasButtonPrint) {
                            $('.print-list').html(`
                                <a class="dropdown-item" onclick="printIndex()" href="javascript:void(0);">List</a>
                            `)
                        }
                        if (res.items.hasButtonPrintReport) {
                            $('.print-travel-report').html(`
                                <a class="dropdown-item" onclick="modalPrintTravelReport()" href="javascript:void(0);">Travel Report</a>
                            `)
                        }

                        if (res.items.hasButtonPrintReport || res.items.hasButtonPrint) $('.dPrints').slideDown()

                        /** filter fields */ 
                        $(`#${formID} input[name="name"]`).val(`${res.items.filters.name}`)

                        // html = ''
                        // if (res.items.cities.length > 0) {
                        //     pName = ''
                        //     html = '<option value="">&nbsp;</option>'
                        //     for (key in res.items.cities) {
                        //         if (pName != res.items.cities[key].pName) {
                        //             if (pName != '') html += '</optgroup>'
                        //             pName = res.items.cities[key].pName
                        //             html += `<optgroup  label="${pName}">`
                        //         }
                        //         html += `<option value="${res.items.cities[key].cityID}" ${res.items.cities[key].cityID==res.items.filters.cityID?'selected':''}>${res.items.cities[key].name}</option>`
                        //     }
                        //     html += '</optgroup>'
                        // }
                        // $(`#${formID} select[name="cityID"]`).html(html)

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

                            const statusColors = {
                                '-1'    : 'danger', 
                                '0'     : 'info', 
                                '1'     : 'primary', 
                                '2'     : 'success', 
                                '3'     : 'secondary', 
                            }
                            const statusNames = {
                                '-1'    : 'Disapproved', 
                                '0'     : 'Pending', 
                                '1'     : 'Recommended', 
                                '2'     : 'Checked', 
                                '3'     : 'Approved', 
                            }

                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.records) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-start">${res.items.records[key].dateInserted}</td>
                                        <td class="text-start">${res.items.records[key].code}</td>
                                        <td class="text-start">${res.items.records[key].employee}</td>
                                        <td class="text-start">${res.items.records[key].destination}</td>
                                        <td class="text-start">${res.items.records[key].purpose}</td>
                                        <td class="text-center"><span class="badge bg-${statusColors[res.items.records[key].status]}">${statusNames[res.items.records[key].status]}</span></td>
                                        <td class="text-center">
                                            ${res.items.hasButtonView?`
                                                <button type="button" class="btn btn-info btn-sm" onclick="window.location.href='{{ url("/$controller/view") }}/${res.items.records[key].travelOrderID}/'" style="position: relative;">
                                                    ${res.items.records[key].hasNotif?`<div class="d-notif-btn"></div>`:''}
                                                    <span>
                                                        <i class="bx bx-show me-sm-1"></i> 
                                                        <span class="d-none d-sm-inline-block">View</span>
                                                    </span>
                                                </button>
                                            `:''}
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

        $(document).ready(function() {
            resetFilterItems(`{{ "$controller" }}`)
            getItems()
        })

    </script>
@endsection