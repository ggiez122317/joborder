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
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href=`{{ url("/$controller") }}`">
                <span>
                    <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                    <span class="d-none d-sm-inline-block">Back</span>
                </span>
            </button>
            <div class="divBtnAdd"></div>
        </div>
        <form id="formView" class="card-body p-2">

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Total</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="amount" style="background: #e3e3e3;" readonly>
                </div>
            </div>

        </form>
    </div>

    <div class="card mt-4">
        <h5 class="card-header d-flex align-items-center justify-content-between p-2 pb-0">Deductions
            <div class="divBtnAudit2"></div>
        </h5>
        <div class="row row-bordered g-0">
            <div class="col-12 p-2">
                <table class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th class="text-start">Name</th>
                            <th class="text-start">Code</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableDeduction" class="table-border-bottom-0"><tr><td class="text-start" colspan="4">No Record Found</td></tr></tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('modals')
    <div class="modal fade" id="modalDeductionEdit" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalDeductionEditLabel">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form id="formDeductionEdit" class="modal-content">
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalDeductionEditLabel">Edit Deduction</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control dNumberNoArrow" placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        function auditLogs()
        {

            popupCenteredWindow(`{{ url("/$controller/audit/$id/") }}`) 

        }

        function auditLogDetails(id)
        {

            popupCenteredWindow(`{{ url("/$controller/audit2/") }}/${id}`) 

        }

        function getRow()
        {

            const formID = 'formView'

            apiCall(`/api/{{ "$controller/$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="3">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        /** access buttons */ 

                        // fields
                        $(`#${formID} input[name="name"]`).val(res.items.row.name)
                        $(`#${formID} input[name="amount"]`).val(res.items.row.amount)

                        const tableBodyID = 'tableDeduction'

                        let html = '<tr><td class="text-start" colspan="4">No Record Found</td></tr>'
                        if (res.items.deductions.length > 0) {
                            html = ''
                            for (key in res.items.deductions) {
                                html += `
                                    <tr>
                                        <td class="text-start">${res.items.deductions[key].name}</td>
                                        <td class="text-start">${res.items.deductions[key].code}</td>
                                        <td class="text-end">${res.items.deductions[key].amount}</td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                ${res.items.hasButtonEdit?`
                                                    <button type="button" class="btn btn-warning btn-sm btnDeductionEdit" data-id="${res.items.deductions[key].userPayrollDeductionDetailID}">
                                                        <span>
                                                            <i class="bx bx-pencil"></i> 
                                                        </span>
                                                    </button>
                                                `:''}
                                                ${res.items.hasButtonAudit?`
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="auditLogDetails('${res.items.deductions[key].userPayrollDeductionDetailID}')">
                                                        <span>
                                                            <i class="bx bx-notepad"></i> 
                                                        </span>
                                                    </button>
                                                `:''}
                                            </div>
                                        </td>
                                    </tr>
                                `
                            }
                        }
                        $(`#${tableBodyID}`).html(html)

                    } else if (res.status == 401 && res.message == 'Invalid token') {
                        authenticationLogout()
                    } else {
                        Toast.fire({ icon : "warning", title : res.name, html : res.message })
                    }

                }, 
                // always
                function() { $('#modalDeductionEdit').modal('hide') }, 
                localStorage.getItem('t') 
            )

        }

        $(document).on('click', '.btnDeductionEdit', function() {

            const id = $(this).data('id')

            const formID = 'formDeductionEdit'
            apiCall(`/api/{{ "$controller" }}/page-put/${id}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} input[name="id"]`).val(id)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        /** selects */
                        // 
                        $(`#${formID} input[name="amount"]`).val(res.items.row.amount)
                        $('#modalDeductionEdit').modal('show')

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


        $(document).on('submit', '#formDeductionEdit', function(e) {
            e.preventDefault()

            const formID = 'formDeductionEdit'
            const formData = new FormData($('#'+formID).get(0))

            formData.append('_method', 'PUT') 

            apiCall(`/api/{{ "$controller" }}/${$(`#${formID} input[name="id"]`).val()}/`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `Saving...`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        getRow()
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

        $(document).ready(function() {
            getRow()
            $('#modalDeductionEdit').on('shown.bs.modal', function () {
                $(`#formDeductionEdit input[name="amount"]`).focus().select()
            })
        })

    </script>
@endsection
