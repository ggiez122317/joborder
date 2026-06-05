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
    <div class="card">
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
                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Date" data-field="dateInserted">
                                    <span>Date</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Username" data-field="username">
                                    <span>Username</span>
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
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Status" data-field="status">
                                    <span>Status</span>
                                </a>
                            </th>
                            <th class="text-center">Action</th>
                        </tr>
                        <tr valign="middle">
                            <th class="text-center">
                                <input type="text" class="form-control text-center" name="dateInserted" style="min-width: 220px !important;" >
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
                                <select name="status" class="form-control" style="width: auto;">
                                    <option value="">&nbsp;</option>
                                </select>
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

                        <tr><td class="text-start" colspan="7">Loading...</td></tr>

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

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/custom/page_index.js') }}"></script>
    <script>

        function printIndex()
        {
            popupCenteredWindow(`{{ url("/$controller/print-list/") }}/?${getFilterItems()}/`) 
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
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="7">Loading...</td></tr>')
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
                        if (res.items.statuses.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.statuses) {
                                html += `<option value="${res.items.statuses[key].status}" ${res.items.statuses[key].status === (res.items.filters.status!=''?parseInt(res.items.filters.status):'')?'selected':''}>${res.items.statuses[key].name}</option>`
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
                        $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="7">No Record Found</td></tr>')
                        if (res.items.records.length > 0) {
                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.records) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-center">${res.items.records[key].dateInserted}</td>
                                        <td class="text-center">${res.items.records[key].username}</td>
                                        <td class="text-center">${res.items.records[key].ipAddress}</td>
                                        <td class="text-start text-wrap">${res.items.records[key].userAgent}</td>
                                        <td class="text-start">${res.items.records[key].remarks}</td>
                                        <td class="text-center">${res.items.statusNames[res.items.records[key].status+1]}</td>
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
        })

    </script>
@endsection