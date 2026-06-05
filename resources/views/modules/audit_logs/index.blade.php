@extends('layouts.app')

@section('title', $title)

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
    <div class="row">
        <div class="col-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-main" aria-controls="navs-pills-top-main" aria-selected="true">
                            Main
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-details" aria-controls="navs-pills-top-details" aria-selected="false" tabindex="-1">
                            Details
                        </button>
                    </li>
                </ul>
                <div class="tab-content p-0">
                    <div class="tab-pane fade show active" id="navs-pills-top-main" role="tabpanel">
                        <div class="card-header p-2 justify-content-end d-flex">
                            <div class="divBtnAdd"></div>
                            <div class="divBtnPrint"></div>
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
                                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Date Inserted" data-field="dateInserted">
                                                    <span>Date Inserted</span>
                                                </a>
                                            </th>
                                            <th class="text-start">
                                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Module" data-field="module">
                                                    <span>Module</span>
                                                </a>
                                            </th>
                                            <th class="text-start">
                                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Action" data-field="action">
                                                    <span>Action</span>
                                                </a>
                                            </th>
                                            <th class="text-start">
                                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="User" data-field="user">
                                                    <span>User</span>
                                                </a>
                                            </th>
                                            <th class="text-start">
                                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="IP Address" data-field="ipAddress">
                                                    <span>IP Address</span>
                                                </a>
                                            </th>
                                            <th class="text-start">
                                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Device Info" data-field="userAgent">
                                                    <span>Device Info</span>
                                                </a>
                                            </th>
                                            <th class="text-start">
                                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Remarks" data-field="remarks">
                                                    <span>Remarks</span>
                                                </a>
                                            </th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        <tr valign="middle">
                                            <th class="text-center">
                                                <input type="text" class="form-control text-center" name="dateInserted" style="min-width: 220px !important;" >
                                            </th>
                                            <th class="text-center">
                                                <select name="appModuleID" class="form-control" style="width: auto;">
                                                    <option value="">&nbsp;</option>
                                                </select>
                                            </th>
                                            <th class="text-center">
                                                <select name="appActionID" class="form-control" style="width: auto;">
                                                    <option value="">&nbsp;</option>
                                                </select>
                                            </th>
                                            <th class="text-center">
                                                <input type="text" class="form-control" name="username" >
                                            </th>
                                            <th class="text-center">
                                                <input type="text" class="form-control" name="ipAddress" >
                                            </th>
                                            <th class="text-center">
                                                <input type="text" class="form-control" name="userAgent" >
                                            </th>
                                            <th class="text-center">
                                                <input type="text" class="form-control" name="remarks" >
                                            </th>
                                            <th class="text-center">
                                                <button type="submit" class="btn btn-secondary btn-sm">
                                                    <span>
                                                        <i class="bx bx-filter-alt"></i> 
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
                    <div class="tab-pane fade" id="navs-pills-top-details" role="tabpanel">
                        <div class="card-header p-2 justify-content-end d-flex">
                            <div class="divBtnAdd"></div>
                            <div class="divBtnPrint2"></div>
                        </div>
                        <form id="formIndex2" method="get"> 
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
                                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Date Inserted" data-field="dateInserted">
                                                    <span>Date Inserted</span>
                                                </a>
                                            </th>
                                            <th class="text-start">
                                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Module" data-field="module">
                                                    <span>Module</span>
                                                </a>
                                            </th>
                                            <th class="text-start">
                                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Action" data-field="action">
                                                    <span>Action</span>
                                                </a>
                                            </th>
                                            <th class="text-start">
                                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="User" data-field="user">
                                                    <span>User</span>
                                                </a>
                                            </th>
                                            <th class="text-start">
                                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Field" data-field="field">
                                                    <span>Field</span>
                                                </a>
                                            </th>
                                            <th class="text-start">
                                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Old Value" data-field="valueOld">
                                                    <span>Old Value</span>
                                                </a>
                                            </th>
                                            <th class="text-start">
                                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="New Value" data-field="valueNew">
                                                    <span>New Value</span>
                                                </a>
                                            </th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        <tr valign="middle">
                                            <th class="text-center">
                                                <input type="text" class="form-control text-center" name="dateInserted" style="min-width: 220px !important;" >
                                            </th>
                                            <th class="text-center">
                                                <select name="appModuleID" class="form-control" style="width: auto;">
                                                    <option value="">&nbsp;</option>
                                                </select>
                                            </th>
                                            <th class="text-center">
                                                <select name="appActionID" class="form-control" style="width: auto;">
                                                    <option value="">&nbsp;</option>
                                                </select>
                                            </th>
                                            <th class="text-center">
                                                <input type="text" class="form-control" name="username" >
                                            </th>
                                            <th class="text-center">
                                                <input type="text" class="form-control" name="field" >
                                            </th>
                                            <th class="text-center">
                                                <input type="text" class="form-control" name="valueOld" >
                                            </th>
                                            <th class="text-center">
                                                <input type="text" class="form-control" name="valueNew" >
                                            </th>
                                            <th class="text-center">
                                                <button type="submit" class="btn btn-secondary btn-sm">
                                                    <span>
                                                        <i class="bx bx-filter-alt"></i> 
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
                                    <div class="dt-info" id="pagingEntries2">Showing 0 to 0 of 0 rows</div>
                                    <div class="dt-length ms-2 d-flex">
                                        <select id="pagingRows2" name="pageRowCount" class="dt-input form-select form-select-sm w-auto" ></select>
                                    </div>
                                </div>
                                <ul id="pagingPages2" class="pagination mb-2"></ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/custom/page_index.js') }}"></script>
    <script>

        function printIndex()
        {
            popupCenteredWindow(`{{ url("/$controller/print-list-main/") }}/?${getFilterItems()}/`) 
        }

        function printIndex2()
        {
            popupCenteredWindow(`{{ url("/$controller/print-list-details/") }}/?${getFilterItems2()}/`) 
        }

        $(document).on('submit', '#formIndex', function(e) {
            e.preventDefault()
            setFilterItems('{{ "$controller" }}', 'formIndex')
            getItems()
        })

        function getItems()
        {

            const formID = 'formIndex'
            apiCall(`/api/{{ "$controller" }}/items-main/?${getFilterItems()}`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="8">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        var picker = flatpickr(`#${formID} input[name="dateInserted"]`, {
                            mode: "range", 
                            dateFormat: 'm/d/Y',
                            disableMobile: "true",
                            defaultDate: [res.items.filters.dateInsertedFrom, res.items.filters.dateInsertedTo], 
                            onReady: function(selectedDates, dateStr, instance) {
                                let clearButton = document.createElement("button")
                                clearButton.innerText = "Clear"
                                clearButton.classList.add("flatpickr-clear")
                                clearButton.type = "button"
                                clearButton.addEventListener("click", function() {
                                    instance.clear()
                                    instance.close()
                                })
                                instance.calendarContainer.appendChild(clearButton)
                            }
                        })

                        /** access buttons */ 
                        if (res.items.hasButtonAdd) {
                            $('.divBtnAdd').html(`
                                <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='{{ url('/'.$controller.'/add/') }}'">
                                    <span>
                                        <i class="bx bx-plus me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Add</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonPrint) {
                            $('.divBtnPrint').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="printIndex()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Print Filtered List">
                                    <span>
                                        <i class="bx bx-printer"></i> 
                                    </span>
                                </button>
                            `)
                        }

                        /** filter fields */ 
                        $(`#${formID} input[name="username"]`).val(`${res.items.filters.username}`)
                        $(`#${formID} input[name="ipAddress"]`).val(`${res.items.filters.ipAddress}`)
                        $(`#${formID} input[name="userAgent"]`).val(`${res.items.filters.userAgent}`)
                        $(`#${formID} input[name="remarks"]`).val(`${res.items.filters.remarks}`)

                        html = ''
                        if (res.items.modules.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.modules) {
                                html += `<option value="${res.items.modules[key].appModuleID}" ${res.items.modules[key].appModuleID==res.items.filters.appModuleID?'selected':''}>${res.items.modules[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="appModuleID"]`).html(html)

                        html = ''
                        if (res.items.actions.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.actions) {
                                html += `<option value="${res.items.actions[key].appActionID}" ${res.items.actions[key].appActionID==res.items.filters.appActionID?'selected':''}>${res.items.actions[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="appActionID"]`).html(html)

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
                                        <td class="text-center">${res.items.records[key].dateInserted}</td>
                                        <td class="text-center">${res.items.records[key].module}</td>
                                        <td class="text-center">${res.items.records[key].action}</td>
                                        <td class="text-center">${res.items.records[key].username}</td>
                                        <td class="text-center">${res.items.records[key].ipAddress}</td>
                                        <td class="text-start text-wrap">${res.items.records[key].userAgent}</td>
                                        <td class="text-start">${res.items.records[key].remarks}</td>
                                        <td class="text-center"></td>
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

        $(document).on('submit', '#formIndex2', function(e) {
            e.preventDefault()
            setFilterItems2('{{ "$controller" }}', 'formIndex2')
            getItems2()
        })

        function getItems2()
        {

            const formID = 'formIndex2'
            apiCall(`/api/{{ "$controller" }}/items-details/?${getFilterItems2()}`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="8">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        var picker = flatpickr(`#${formID} input[name="dateInserted"]`, {
                            mode: "range", 
                            dateFormat: 'm/d/Y',
                            disableMobile: "true",
                            defaultDate: [res.items.filters.dateInsertedFrom, res.items.filters.dateInsertedTo], 
                            onReady: function(selectedDates, dateStr, instance) {
                                let clearButton = document.createElement("button")
                                clearButton.innerText = "Clear"
                                clearButton.classList.add("flatpickr-clear")
                                clearButton.type = "button"
                                clearButton.addEventListener("click", function() {
                                    instance.clear()
                                    instance.close()
                                })
                                instance.calendarContainer.appendChild(clearButton)
                            }
                        })

                        /** access buttons */ 
                        if (res.items.hasButtonAdd) {
                            $('.divBtnAdd').html(`
                                <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='{{ url('/'.$controller.'/add/') }}'">
                                    <span>
                                        <i class="bx bx-plus me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Add</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonPrint) {
                            $('.divBtnPrint2').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="printIndex2()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Print Filtered List">
                                    <span>
                                        <i class="bx bx-printer"></i> 
                                    </span>
                                </button>
                            `)
                        }

                        /** filter fields */ 
                        $(`#${formID} input[name="username"]`).val(`${res.items.filters.username}`)
                        $(`#${formID} input[name="field"]`).val(`${res.items.filters.field}`)
                        $(`#${formID} input[name="valueOld"]`).val(`${res.items.filters.valueOld}`)
                        $(`#${formID} input[name="valueNew"]`).val(`${res.items.filters.valueNew}`)

                        html = ''
                        if (res.items.modules.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.modules) {
                                html += `<option value="${res.items.modules[key].appModuleID}" ${res.items.modules[key].appModuleID==res.items.filters.appModuleID?'selected':''}>${res.items.modules[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="appModuleID"]`).html(html)

                        html = ''
                        if (res.items.actions.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.actions) {
                                html += `<option value="${res.items.actions[key].appActionID}" ${res.items.actions[key].appActionID==res.items.filters.appActionID?'selected':''}>${res.items.actions[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="appActionID"]`).html(html)

                        /** filter paging entries */ 
                        $('#pagingEntries2').html(`Showing ${res.items.filters.row_shown_first} to ${res.items.filters.row_shown_last} of ${res.items.filters.row_total} rows`)

                        /** filter paging limit */ 
                        html = ''
                        for (key in pagingLimits) {
                            html += `<option value="${key}" ${key.trim()==res.items.filters.limit?'selected':''} >${pagingLimits[key]}</option>`
                        }
                        $('#pagingRows2').html(html)

                        /** filter pages */ 
                        $(`#${formID} input[name="page"]`).val(res.items.filters.page)
                        $(`#${formID} input[name="pages"]`).val(res.items.filters.pages)
                        $('#pagingPages2').html(generatePages(res.items.filters.pages, res.items.filters.page))

                        // body
                        $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="8">No Record Found</td></tr>')
                        if (res.items.records.length > 0) {
                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.records) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-center">${res.items.records[key].dateInserted}</td>
                                        <td class="text-center">${res.items.records[key].module}</td>
                                        <td class="text-center">${res.items.records[key].action}</td>
                                        <td class="text-center">${res.items.records[key].username}</td>
                                        <td class="text-start">${res.items.records[key].field}</td>
                                        <td class="text-start">${res.items.records[key].valueOld}</td>
                                        <td class="text-start">${res.items.records[key].valueNew}</td>
                                        <td class="text-center"></td>
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
            getItems2()
        })

    </script>
@endsection