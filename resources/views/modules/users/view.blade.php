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
            <div class="divBtnEdit"></div>
            <div class="divBtnDelete"></div>
            <div class="divBtnChangePassword"></div>
            <div class="divBtnAudit"></div>
        </div>
        <form id="formView" class="card-body p-2">

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="username" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">User Type</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="utName" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date Inserted</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dateInserted" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date Activated</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dateActivated" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date Deactivated</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dateDeactivated" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Biometric ID Number</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="biometricIdNumber" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="status" style="background: #e3e3e3;" readonly>
                </div>
            </div>

        </form>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-accesses" aria-controls="navs-pills-top-accesses" aria-selected="false" tabindex="-1">
                            Accesses
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-starting-information" aria-controls="navs-pills-top-starting-information" aria-selected="false" tabindex="-1">
                            Starting Information
                        </button>
                    </li>
                </ul>
                <div class="tab-content p-2">
                    <!-- accesses -->
                    <div class="tab-pane fade show active" id="navs-pills-top-accesses" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <div class="divBtnAudit2"></div>
                        </div>
                        <div class="row row-bordered g-0">
                            <div class="col-12 p-2">
                                <table class="table table-bordered w-100 mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Modules</th>
                                            <th class="text-start">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableAccesses" class="table-border-bottom-0"><tr><td class="text-start" colspan="2">No Access Found</td></tr></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- starting information -->
                    <div class="tab-pane fade" id="navs-pills-top-starting-information" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <div class="divBtnAudit3"></div>
                        </div>
                        <form id="formStartingInformation" class="card-body">

                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Username <span class="text-primary">(Temporary)</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="username" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Password <span class="text-primary">(Temporary)</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="password" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">First Name <span class="text-primary">(Temporary)</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="fname" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Middle Name <span class="text-primary">(Temporary)</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="mname" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Last Name <span class="text-primary">(Temporary)</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="lname" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Employee ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="idNumber" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Employee Type</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="uetName" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Office</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="office" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Job Position</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="jobPosition" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Salary</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="salaryBasic" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <!-- <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Leave Ledger Period</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="salaryBasic" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Leave Ledger Period</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="salaryBasic" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div> -->

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modals')
    <div class="modal fade" id="modalUserChangePassword" tabindex="-1" aria-labelledby="modalUserChangePasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form id="formUserChangePassword" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalUserChangePasswordLabel">Change Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="passwordNew" class="form-control" placeholder="············">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" name="passwordCon" class="form-control" placeholder="············">
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

        function rowDelete()
        {

            Swal.fire({
                title               : "Delete this record?",
                text                : "You won't be able to revert this!",
                icon                : "warning",
                showCancelButton    : true,
                confirmButtonColor  : "#3085d6",
                cancelButtonColor   : "#d33",
                confirmButtonText   : "Yes, delete it!"
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

        function rowChangePassword()
        {

            $('#formUserChangePassword input[name="passwordNew"]').val('')
            $('#formUserChangePassword input[name="passwordCon"]').val('')
            $('#modalUserChangePassword').modal('show')

        }

        $(document).on('submit', '#formUserChangePassword', function(e) {
            e.preventDefault()

            const formID = 'formUserChangePassword'
            const formData = new FormData($('#'+formID).get(0))

            formData.append('_method', 'PUT') 

            apiCall(`/api/{{ "$controller" }}/change-password/{{ $id }}/`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `Loading...`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        $('#modalUserChangePassword').modal('hide')
                        Toast.fire({ icon : "success", title : "Notification", html : "Password Changed!" })
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

        function auditLogs()
        {

            popupCenteredWindow(`{{ url("/$controller/audit/$id/") }}`) 

        }

        function auditLogs2()
        {

            popupCenteredWindow(`{{ url("/$controller/audit2/$id/") }}`) 

        }

        function auditLogs3()
        {

            popupCenteredWindow(`{{ url("/$controller/audit3/$id/") }}`) 

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
                        if (res.items.hasButtonEdit && !res.items.isSuperAdmin) {
                            $('.divBtnEdit').html(`
                                <button type="button" class="btn btn-warning btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/edit/$id/") }}'">
                                    <span>
                                        <i class="bx bx-pencil me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Edit</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonDelete && res.items.row.status==0) { 
                            $('.divBtnDelete').html(`
                                <button type="button" class="btn btn-danger btn-sm ms-2" onclick="rowDelete()">
                                    <span>
                                        <i class="bx bx-trash me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Delete</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonChangePassword && res.items.row.status!=0) { 
                            $('.divBtnChangePassword').html(`
                                <button type="button" class="btn btn-info btn-sm ms-2" onclick="rowChangePassword()">
                                    <span>
                                        <i class="bx bx-lock me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Change Password</span>
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
                        if (res.items.hasButtonAudit) { 
                            $('.divBtnAudit2').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditLogs2()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonAudit) { 
                            $('.divBtnAudit3').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditLogs3()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }

                        // fields
                        $(`#${formID} input[name="username"]`).val(res.items.row.username)
                        $(`#${formID} input[name="utName"]`).val(res.items.row.utName)
                        $(`#${formID} input[name="dateInserted"]`).val(res.items.row.dateInserted)
                        $(`#${formID} input[name="dateActivated"]`).val(res.items.row.dateActivated)
                        $(`#${formID} input[name="dateDeactivated"]`).val(res.items.row.dateDeactivated)
                        $(`#${formID} input[name="status"]`).val(res.items.row.statusName)
                        $(`#${formID} input[name="biometricIdNumber"]`).val(res.items.row.biometricIdNumber)
                        
                        // formStartingInformation
                        $(`#formStartingInformation input[name="username"]`).val(res.items.startingInformation.username)
                        $(`#formStartingInformation input[name="password"]`).val(res.items.startingInformation.password)
                        $(`#formStartingInformation input[name="fname"]`).val(res.items.startingInformation.fname)
                        $(`#formStartingInformation input[name="mname"]`).val(res.items.startingInformation.mname)
                        $(`#formStartingInformation input[name="lname"]`).val(res.items.startingInformation.lname)
                        $(`#formStartingInformation input[name="idNumber"]`).val(res.items.startingInformation.idNumber)
                        $(`#formStartingInformation input[name="uetName"]`).val(res.items.startingInformation.uetName)
                        $(`#formStartingInformation input[name="office"]`).val(res.items.startingInformation.office)
                        $(`#formStartingInformation input[name="jobPosition"]`).val(res.items.startingInformation.jobPosition)
                        $(`#formStartingInformation input[name="salaryBasic"]`).val(res.items.startingInformation.salaryBasic)

                        $(`#tableAccesses`).html('<tr><td class="text-start" colspan="2">No Access Found</td></tr>')
                        if (res.items.modules.length > 0) {
                            $(`#tableAccesses`).html('')
                            for (key in res.items.modules) {

                                moduleActions = ''
                                moduleName = `<td class="text-start" style="opacity: 0.5; padding-left: 2.25em;">${res.items.modules[key]['module']}</td>`
                                if (res.items.modules[key]['actions'].length > 0) {
                                    if (res.items.modules[key]['actions'][0]['action'] == 'Index') {
                                        moduleName = `
                                            <td class="text-start">
                                                <div class="form-check d-flex align-items-start">
                                                    <input class="form-check-input" type="checkbox" name="appModuleActionIDs[]" value="${res.items.modules[key]['actions'][0]['id']}" id="action${res.items.modules[key]['actions'][0]['id']}" ${res.items.accesses.includes(res.items.modules[key]['actions'][0]['id'])?'checked':''} disabled>
                                                    <label class="form-check-label ms-2" for="action${res.items.modules[key]['actions'][0]['id']}" ${res.items.modules[key]['actions'][0]['isDefault']?'style="pointer-events: none;"':''}>${res.items.modules[key]['module']}</label>
                                                </div>
                                            </td>
                                        `
                                    }
                                    // module actions
                                    for (key2 in res.items.modules[key]['actions']) {
                                        if (res.items.modules[key]['actions'][key2]['action'] != 'Index') {
                                            moduleActions += `
                                                <div class="form-check d-flex align-items-start me-4">
                                                    <input class="form-check-input" type="checkbox" name="appModuleActionIDs[]" value="${res.items.modules[key]['actions'][key2]['id']}" id="action${res.items.modules[key]['actions'][key2]['id']}" ${res.items.accesses.includes(res.items.modules[key]['actions'][key2]['id'])?'checked':''} disabled>
                                                    <label class="form-check-label ms-2" for="action${res.items.modules[key]['actions'][key2]['id']}" ${res.items.modules[key]['actions'][key2]['isDefault']?'style="pointer-events: none;" checked':''}>${res.items.modules[key]['actions'][key2]['action']}</label>
                                                </div>
                                            `
                                        }
                                    } 
                                }

                                if (res.items.modules[key]['actions'].length > 0) {
                                    $(`#tableAccesses`).append(`
                                        <tr valign="baseline">
                                            ${moduleName}
                                            <td class="text-start">
                                                <div class="d-flex flex-wrap">
                                                    ${moduleActions}
                                                </div>
                                            </td>
                                        </tr>
                                    `)
                                }
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
            getRow()
        })

    </script>
@endsection
