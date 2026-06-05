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
    <form id="formEdit" novalidate>
        <div class="card">
            <div class="card-header p-2 justify-content-end d-flex">
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href=`{{ url("/$controller/view/$id/") }}`">
                    <span>
                        <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                        <span class="d-none d-sm-inline-block">Back</span>
                    </span>
                </button>
                <div class="divBtnEdit"></div>
            </div>
            <div class="card-body p-2">
    
                <div class="row g-2 mb-2">
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">Note: <b>Changing the User Type does not affect Access. To update Access, simply tick the checkbox below.</b></div>
                    </div>
                </div>
    
                <div class="row g-2 mb-2 pendingShow d-none">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Username <span class="text-primary">(Temporary)</span> <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="Temporary Username">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Password <span class="text-primary">(Temporary)</span> <span class="text-danger">*</span></label>
                        <input type="text" name="password" class="form-control" placeholder="Temporary Password">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">User Type <span class="text-danger">*</span></label>
                        <select name="userTypeID" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                </div>
                
                <!-- real -->
                <div class="row g-2 mb-2 pendingHide d-none">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Username</label>
                        <input type="text" name="realUsername" class="form-control" placeholder="Username" style="background: #e3e3e3;" readonly>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">User Type <span class="text-danger">*</span></label>
                        <select name="realUserTypeID" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="realStatus" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                </div>

            </div>
            <h5 class="card-header p-2 pb-0 pendingShow d-none">Personal Information</h5>
            <div class="card-body p-2 pt-0 pendingShow d-none">
    
                <div class="row g-2 mb-2">
                    <div class="col-12 col-md-4">
                        <label class="form-label">First Name <span class="text-primary">(Temporary)</span> <span class="text-danger">*</span></label>
                        <input type="text" name="fname" class="form-control" placeholder="Temporary First Name">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Middle Name <span class="text-primary">(Temporary)</span> </label>
                        <input type="text" name="mname" class="form-control" placeholder="Temporary Middle Name">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Last Name <span class="text-primary">(Temporary)</span> <span class="text-danger">*</span></label>
                        <input type="text" name="lname" class="form-control" placeholder="Temporary Last Name">
                    </div>
                </div>
    
            </div>
            <h5 class="card-header p-2 pb-0 pendingShow d-none">Employment Details</h5>
            <div class="card-body p-2 pt-0 pendingShow d-none">
    
                <div class="row g-2 mb-2">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                        <input type="text" name="idNumber" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Employment Type <span class="text-danger">*</span></label>
                        <select name="userEmploymentTypeID" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                </div>
    
                <div class="row g-2 mb-2 pendingShow d-none">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Office <span class="text-danger">*</span></label>
                        <select name="officeID" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Job Position <span class="text-danger">*</span></label>
                        <select name="jobPositionID" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Salary Per Month <span class="text-danger">*</span></label>
                        <input type="number" name="salaryMonthly" value="0" class="form-control" min="1" step=".25" placeholder="o.oo">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Salary Per Annum (<span class="text-info">FOR SERVICE RECORD</span>) <span class="text-danger">*</span></label>
                        <input type="number" name="salaryYearly" value="0" class="form-control" min="1" step=".25" placeholder="o.oo">
                    </div>
                </div>
    
            </div>
            <h5 class="card-header p-2 pb-0">Others</h5>
            <div class="card-body p-2 pt-0">
                <div class="row g-2 mb-2">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Biometric ID Number <span class="text-danger">*</span></label>
                        <input type="text" name="biometricIdNumber" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <h5 class="card-header p-2 pb-0">Accesses</h5>
            <div class="row row-bordered g-0">
                <div class="col-12 p-2">
                    <table class="table table-bordered w-100">
                        <thead>
                            <tr>
                                <th class="text-start">
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input accessAll" type="checkbox" value="0" id="idAll">
                                        <label class="form-check-label ms-2" for="idAll">Modules</label>
                                    </div>
                                </th>
                                <th class="text-start">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableAccesses" class="table-border-bottom-0"><tr><td class="text-start" colspan="2">No Access Found</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('modals')

@endsection

@section('scripts')
    <script>

        function getRow()
        {

            const formID = 'formEdit'

            apiCall(`/api/{{ "$controller" }}/page-put/{{ "$id" }}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    if (res.status == 200) {

                        // hide divs 
                        if (res.items.row.status == 0) {
                            $('.pendingShow').removeClass('d-none')
                        } else {
                            $('.pendingHide').removeClass('d-none')
                        }

                        /** access buttons */ 
                        if (res.items.hasButtonEdit) {
                            $('.divBtnEdit').html(`
                                <button type="submit" class="btn btn-success btn-sm ms-2">
                                    <span> 
                                        <i class="bx bx-save me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Save</span>
                                    </span>
                                </button>
                            `)
                        }

                        // 
                        $(`#${formID} input[name="biometricIdNumber"]`).val(res.items.row.biometricIdNumber)
                        $(`#${formID} input[name="realUsername"]`).val(res.items.row.realUsername)
                        $(`#${formID} input[name="username"]`).val(res.items.row.username)
                        $(`#${formID} input[name="password"]`).val(res.items.row.password)
                        $(`#${formID} input[name="fname"]`).val(res.items.row.fname)
                        $(`#${formID} input[name="mname"]`).val(res.items.row.mname)
                        $(`#${formID} input[name="lname"]`).val(res.items.row.lname)
                        $(`#${formID} input[name="idNumber"]`).val(res.items.row.idNumber)
                        $(`#${formID} input[name="salaryMonthly"]`).val(res.items.row.salaryMonthly)
                        $(`#${formID} input[name="salaryYearly"]`).val(res.items.row.salaryYearly)

                        
                        html = ''
                        if (res.items.UserTypes.length > 0) {
                            pName = ''
                            for (key in res.items.UserTypes) {
                                html += `<option value="${res.items.UserTypes[key].userTypeID}" ${res.items.row.userTypeID==res.items.UserTypes[key].userTypeID?'selected':''} >${res.items.UserTypes[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="realUserTypeID"]`).html(html)
                        
                        html = ''
                        if (res.items.UserTypes.length > 0) {
                            pName = ''
                            for (key in res.items.UserTypes) {
                                html += `<option value="${res.items.UserTypes[key].userTypeID}" ${res.items.row.userTypeID==res.items.UserTypes[key].userTypeID?'selected':''} >${res.items.UserTypes[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="userTypeID"]`).html(html)

                        html = ''
                        if (res.items.offices.length > 0) {
                            pName = ''
                            for (key in res.items.offices) {
                                html += `<option value="${res.items.offices[key].officeID}" ${res.items.row.officeID==res.items.offices[key].officeID?'selected':''} >${res.items.offices[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="officeID"]`).html(html)

                        html = ''
                        if (res.items.JobPositions.length > 0) {
                            pName = ''
                            for (key in res.items.JobPositions) {
                                html += `<option value="${res.items.JobPositions[key].jobPositionID}" ${res.items.row.jobPositionID==res.items.JobPositions[key].jobPositionID?'selected':''} >${res.items.JobPositions[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="jobPositionID"]`).html(html)

                        html = ''
                        if (res.items.user_employment_types.length > 0) {
                            pName = ''
                            for (key in res.items.user_employment_types) {
                                html += `<option value="${res.items.user_employment_types[key].userEmploymentTypeID}" ${res.items.row.userEmploymentTypeID==res.items.user_employment_types[key].userEmploymentTypeID?'selected':''} >${res.items.user_employment_types[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="userEmploymentTypeID"]`).html(html)

                        html = ''
                        html += `<option value="1" ${res.items.row.status==1?'selected':''} >Activate</option>`
                        html += `<option value="-1" ${res.items.row.status==-1?'selected':''} >Deactivate</option>`
                        $(`#${formID} select[name="realStatus"]`).html(html)

                        // others 
                        $(`#tableAccesses`).html('<tr><td class="text-start" colspan="2">No Access Found</td></tr>')
                        if (res.items.modules.length > 0) {
                            $(`#tableAccesses`).html('')
                            for (key in res.items.modules) {

                                hasIndex = 0
                                isModuleCheck = 0
                                moduleActions = ''
                                moduleName = `<td class="text-start" style=" padding-left: 2.25em;">${res.items.modules[key]['module']}</td>`
                                if (res.items.modules[key]['actions'].length > 0) {
                                    if (res.items.modules[key]['actions'][0]['action'] == 'Index') {
                                        if (res.items.accesses.includes(res.items.modules[key]['actions'][0]['id'])) isModuleCheck = 1
                                        hasIndex = 1
                                        moduleName = `
                                            <td class="text-start">
                                                <div class="form-check d-flex align-items-start">
                                                    <input class="form-check-input ${res.items.modules[key]['actions'][0]['isDefault']?'':'accessModule'}" type="checkbox" name="appModuleActionIDs[]" value="${res.items.modules[key]['actions'][0]['id']}" id="action${res.items.modules[key]['actions'][0]['id']}" ${res.items.isAdmin && res.items.modules[key]['actions'][0]['isDefault']? 'style="pointer-events: none; opacity: 0.5;"' : ` ${res.items.modules[key]['actions'][0]['isDefault']?'style="pointer-events: none; opacity: 0.5;" checked':''} ${res.items.accesses.includes(res.items.modules[key]['actions'][0]['id'])?'checked':''}`}>
                                                    <label class="form-check-label ms-2" for="action${res.items.modules[key]['actions'][0]['id']}" ${res.items.modules[key]['actions'][0]['isDefault']?'style="pointer-events: none;"':''}>${res.items.modules[key]['module']}</label>
                                                </div>
                                            </td>
                                        `
                                    }
                                    // module actions
                                    for (key2 in res.items.modules[key]['actions']) {
                                        if (res.items.modules[key]['actions'][key2]['action'] != 'Index') {
                                            if (hasIndex) {
                                                moduleActions += `
                                                    <div class="form-check d-flex align-items-start me-4">
                                                        <input class="form-check-input ${res.items.modules[key]['actions'][key2]['isDefault']?'':'accessModuleAction'}" type="checkbox" name="appModuleActionIDs[]" value="${res.items.modules[key]['actions'][key2]['id']}" id="action${res.items.modules[key]['actions'][key2]['id']}" ${res.items.isAdmin && res.items.modules[key]['actions'][0]['isDefault']? 'style="pointer-events: none; opacity: 0.5;"' : ` ${res.items.modules[key]['actions'][key2]['isDefault']?'style="pointer-events: none; opacity: 0.5;" checked':isModuleCheck?'':'disabled'} ${res.items.accesses.includes(res.items.modules[key]['actions'][key2]['id'])?'checked':''}`}>
                                                        <label class="form-check-label ms-2" for="action${res.items.modules[key]['actions'][key2]['id']}" ${res.items.modules[key]['actions'][key2]['isDefault']?'style="pointer-events: none;" checked':''}>${res.items.modules[key]['actions'][key2]['action']}</label>
                                                    </div>
                                                `
                                            } else {
                                                moduleActions += `
                                                    <div class="form-check d-flex align-items-start me-4">
                                                        <input class="form-check-input" type="checkbox" name="appModuleActionIDs[]" value="${res.items.modules[key]['actions'][key2]['id']}" id="action${res.items.modules[key]['actions'][key2]['id']}" ${res.items.accesses.includes(res.items.modules[key]['actions'][key2]['id'])?'checked':''}>
                                                        <label class="form-check-label ms-2" for="action${res.items.modules[key]['actions'][key2]['id']}">${res.items.modules[key]['actions'][key2]['action']}</label>
                                                    </div>
                                                `
                                            }
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

        $(document).on('submit', '#formEdit', function(e) {
            e.preventDefault()

            const formID = 'formEdit'
            const formData = new FormData($('#'+formID).get(0))

            formData.append('_method', 'PUT') 

            apiCall(`/api/{{ "$controller" }}/{{ $id }}/`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `<span><i class="bx bx-save me-sm-1"></i><span class="d-none d-sm-inline-block">Loading...</span></span>`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        window.location.href=`{{ url("/$controller/view") }}/${res.items.id}/`
                    } else if (res.status == 401 && res.message == 'Invalid token') {
                        authenticationLogout()
                    } else {
                        Toast.fire({ icon : "warning", title : res.name, html : res.message })
                    }

                }, 
                // always
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `<span><i class="bx bx-save me-sm-1"></i><span class="d-none d-sm-inline-block">Save</span></span>`, 0)
                }, 
                localStorage.getItem('t') 
            )
        }) 

        $(document).ready(function() {
            getRow()
        }) 

    </script>
@endsection

