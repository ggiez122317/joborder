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
                                <a href="javascript:void(0);" class="d-flex align-items-center justify-content-center filterSort" data-label="Date" data-field="name">
                                    <span>Date</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center justify-content-center filterSort" data-label="AM Arr." data-field="amArr">
                                    <span>AM Arr.</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center justify-content-center filterSort" data-label="AM Dep." data-field="amDep">
                                    <span>AM Dep.</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center justify-content-center filterSort" data-label="PM Arr." data-field="pmArr">
                                    <span>PM Arr.</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center justify-content-center filterSort" data-label="PM Dep." data-field="pmDep">
                                    <span>PM Dep.</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center justify-content-center filterSort" data-label="UT Hour" data-field="utHour">
                                    <span>UT Hour</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center justify-content-center filterSort" data-label="UT Minute" data-field="utMin">
                                    <span>UT Minute</span>
                                </a>
                            </th>
                            <th class="text-center">Action</th>
                        </tr>
                        <tr valign="middle">
                            <th class="text-center">
                                <input type="date" class="form-control" name="date" >
                            </th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
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

                        <tr><td class="text-start" colspan="9">Loading...</td></tr>

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
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="9">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        /** access buttons */ 
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
                        $(`#${formID} input[name="code"]`).val(`${res.items.filters.code}`)
                        $(`#${formID} input[name="name"]`).val(`${res.items.filters.name}`)
                        $(`#${formID} input[name="description"]`).val(`${res.items.filters.description}`)

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
                        $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="4">No Record Found</td></tr>')
                        if (res.items.records.length > 0) {
                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.records) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-start">${res.items.records[key].code}</td>
                                        <td class="text-start">${res.items.records[key].name}</td>
                                        <td class="text-start">${res.items.records[key].description}</td>
                                        <td class="text-center">-</td>
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