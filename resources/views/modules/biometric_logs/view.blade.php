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
    <div class="card mb-3">
        <div class="card-header p-2 justify-content-end d-flex">
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href=`{{ url("/$controller") }}`">
                <span>
                    <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                    <span class="d-none d-sm-inline-block">Back</span>
                </span>
            </button>
            <div class="divBtnAdd"></div>
            <div class="divBtnAudit"></div>
        </div>
        <form id="formView" class="card-body p-2">

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Office</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="code" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Inserted By</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date Inserted</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" style="background: #e3e3e3;" readonly>
                </div>
            </div>

        </form>
    </div>
    <div class="card">
        <div class="card-body p-2">

            <div class="row table-responsive">
                <div class="table-responsive text-nowrap">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="text-start">Date</th> 
                                <th class="text-start">Employee</th> 
                                <th class="text-center">AM Arrival</th> 
                                <th class="text-center">AM Departure</th> 
                                <th class="text-center">PM Arrival</th> 
                                <th class="text-center">PM Departure</th> 
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">

                            <tr>
                                <td class="text-start">01/12/2025</td>
                                <td class="text-start">Demata, Rogincel Trimidal</td>
                                <td class="text-center">08:00am</td>
                                <td class="text-center">-</td>
                                <td class="text-center">01:00pm</td>
                                <td class="text-center">05:00pm</td>
                            </tr>
                            <tr><td class="text-start text-danger" colspan="6">No Record Found</td></tr>
                            <tr><td class="text-start" colspan="6">Loading...</td></tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('modals')

@endsection

@section('scripts')
    <script>

        function rowRelease()
        {

            Swal.fire({
                title               : "Release this record?",
                text                : "Once released, the employee's attendance for that date cannot be modified.",
                icon                : "info",
                showCancelButton    : true,
                confirmButtonColor  : "#3085d6",
                cancelButtonColor   : "#d33",
                confirmButtonText   : "Yes, release it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    apiCall(`/api/{{ "$controller" }}/{{ $id }}/`, 'DELETE', null, 
                        // beforesend
                        function() {
                            btnLoading(`.divBtnDelete button[type="button"]`, `<span><i class="bx bx-trash me-sm-1"></i><span class="d-none d-sm-inline-block">Loading...</span></span>`)
                        }, 
                        // done
                        function(res) {

                            if (res.status == 200) {
                                Swal.fire({
                                    title   : "Deleted!",
                                    text    : "Record has been deleted.",
                                    icon    : "success"
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href=`{{ url("/$controller/") }}/`
                                    }
                                })
                            } else if (res.status == 401 && res.message == 'Invalid token') {
                                authenticationLogout()
                            } else {
                                Toast.fire({ icon : "warning", title : res.name, html : res.message })
                            }

                        }, 
                        // always
                        function() {
                            btnLoading(`.divBtnDelete button[type="button"]`, `<span><i class="bx bx-trash me-sm-1"></i><span class="d-none d-sm-inline-block">Delete</span></span>`, 0)
                        }, 
                        localStorage.getItem('t') 
                    )

                }
            });


        }

        function rowCancel()
        {

            Swal.fire({
                title               : "Cancel this record?",
                text                : "You won't be able to revert this!",
                icon                : "warning",
                showCancelButton    : true,
                confirmButtonColor  : "#3085d6",
                cancelButtonColor   : "#d33",
                confirmButtonText   : "Yes, cancel it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    apiCall(`/api/{{ "$controller" }}/{{ $id }}/`, 'DELETE', null, 
                        // beforesend
                        function() {
                            btnLoading(`.divBtnDelete button[type="button"]`, `<span><i class="bx bx-trash me-sm-1"></i><span class="d-none d-sm-inline-block">Loading...</span></span>`)
                        }, 
                        // done
                        function(res) {

                            if (res.status == 200) {
                                Swal.fire({
                                    title   : "Deleted!",
                                    text    : "Record has been deleted.",
                                    icon    : "success"
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href=`{{ url("/$controller/") }}/`
                                    }
                                })
                            } else if (res.status == 401 && res.message == 'Invalid token') {
                                authenticationLogout()
                            } else {
                                Toast.fire({ icon : "warning", title : res.name, html : res.message })
                            }

                        }, 
                        // always
                        function() {
                            btnLoading(`.divBtnDelete button[type="button"]`, `<span><i class="bx bx-trash me-sm-1"></i><span class="d-none d-sm-inline-block">Delete</span></span>`, 0)
                        }, 
                        localStorage.getItem('t') 
                    )

                }
            });


        }

        function auditLogs()
        {

            popupCenteredWindow(`{{ url("/$controller/audit/$id/") }}`) 

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
                        if (res.items.hasButtonAdd) {
                            $('.divBtnAdd').html(`
                                <button type="button" class="btn btn-primary btn-sm ms-2" onclick="window.location.href='{{ url('/'.$controller.'/add/') }}'">
                                    <span>
                                        <i class="bx bx-plus me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Add</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonEdit) {
                            $('.divBtnEdit').html(`
                                <button type="button" class="btn btn-warning btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/edit/$id/") }}'">
                                    <span>
                                        <i class="bx bx-pencil me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Edit</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonRelease) { 
                            $('.divBtnCancel').html(`
                                <button type="button" class="btn btn-danger btn-sm ms-2" onclick="rowCancel()">
                                    <span>
                                        <i class="bx bx-x me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Cancel</span>
                                    </span>
                                </button>
                            `)
                            $('.divBtnRelease').html(`
                                <button type="button" class="btn btn-success btn-sm ms-2" onclick="rowRelease()">
                                    <span>
                                        <i class="bx bx-share me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Release</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonAudit) { 
                            $('.divBtnAudit').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }

                        // fields
                        $(`#${formID} input[name="code"]`).val(res.items.row.code)
                        $(`#${formID} input[name="name"]`).val(res.items.row.name)
                        $(`#${formID} textarea[name="description"]`).val(res.items.row.description)

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
            getRow()
        })

    </script>
@endsection
