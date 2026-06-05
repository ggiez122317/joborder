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
                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Name" data-field="lname">
                                    <span>Name</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Username" data-field="username">
                                    <span>Username</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="User Type" data-field="utName">
                                    <span>User Type</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Date Activated" data-field="dateActivated">
                                    <span>Date Activated</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex justify-content-center align-items-center filterSort" data-label="Date Deactivated" data-field="dateDeactivated">
                                    <span>Date Deactivated</span>
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
                                <select name="userID" class="form-control m-auto" style="width: auto;">
                                    <option value="">&nbsp;</option>
                                </select>
                            </th>
                            <th class="text-center">
                                <input type="text" class="form-control" name="username" >
                            </th>
                            <th class="text-center">
                                <select name="userTypeID" class="form-control m-auto" style="width: auto;">
                                    <option value="">&nbsp;</option>
                                </select>
                            </th>
                            <th class="text-center">
                                <input type="date" class="form-control" name="dateActivated" >
                            </th>
                            <th class="text-center">
                                <input type="date" class="form-control" name="dateDeactivated" >
                            </th>
                            <th class="text-center">
                                <select name="status" class="form-control m-auto" style="width: auto;">
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
                        $(`#${formID} input[name="dateActivated"]`).val(`${res.items.filters.dateActivated}`)
                        $(`#${formID} input[name="dateDeactivated"]`).val(`${res.items.filters.dateDeactivated}`)

                        html = ''
                        if (res.items.users.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.users) {
                                html += `<option value="${res.items.users[key].userID}" ${res.items.users[key].userID==res.items.filters.userID?'selected':''}>${res.items.users[key].lname}, ${res.items.users[key].fname} ${res.items.users[key].mname}</option>`
                            }
                        }
                        $(`#${formID} select[name="userID"]`).html(html)

                        html = ''
                        if (res.items.UserTypes.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.UserTypes) {
                                html += `<option value="${res.items.UserTypes[key].userTypeID}" ${res.items.UserTypes[key].userTypeID==res.items.filters.userTypeID?'selected':''}>${res.items.UserTypes[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="userTypeID"]`).html(html)

                        html = ''
                        if (res.items.statuses.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.statuses) {
                                html += `<option value="${res.items.statuses[key].status}" ${res.items.statuses[key].status === res.items.filters.status?'selected':''}>${res.items.statuses[key].name}</option>`
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

                            const statuses      = ['Deactivated', 'Pending', 'Activated']
                            const status_colors = ['danger', 'info', 'success']

                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.records) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-start">${res.items.records[key].user}</td>
                                        <td class="text-center">${res.items.records[key].username}</td>
                                        <td class="text-center">${res.items.records[key].utName}</td>
                                        <td class="text-center">${res.items.records[key].dateActivated}</td>
                                        <td class="text-center">${res.items.records[key].dateDeactivated}</td>
                                        <td class="text-center"><span class="badge bg-${status_colors[res.items.records[key].status+1]}">${statuses[res.items.records[key].status+1]}</span></td>
                                        <td class="text-center">
                                            ${res.items.hasButtonView?`
                                                <button type="button" class="btn btn-info btn-sm" onclick="window.location.href='{{ url("/$controller/view") }}/${res.items.records[key].userID}/'">
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
