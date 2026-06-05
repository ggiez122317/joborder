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
    <form id="formAdd">
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
            <div class="card-body p-2">
    
                <div class="row g-2 mb-2">
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
    
            </div>
            <h5 class="card-header p-2 pb-0">Personal Information</h5>
            <div class="card-body p-2 pt-0">
    
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
            <h5 class="card-header p-2 pb-0">Employment Details (<span class="text-danger">LATEST RECORD</span>)</h5>
            <div class="card-body p-2 pt-0">
    
                <div class="row g-2 mb-2">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Appointed Date <span class="text-danger">*</span></label>
                        <input type="date" name="dateAppointed" class="form-control">
                    </div>
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
    
                <div class="row g-2 mb-2">
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
                        <input type="number" name="salaryMonthly" class="form-control" min="1" step=".25" placeholder="o.oo">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Salary Per Annum (<span class="text-primary">FOR SERVICE RECORD</span>) <span class="text-danger">*</span></label>
                        <input type="number" name="salaryYearly" class="form-control" min="1" step=".25" placeholder="o.oo">
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
    </form>
@endsection

@section('modals')

@endsection

@section('scripts')
    <script>

        $(document).on('submit', '#formAdd', function(e) {
            e.preventDefault()

            const formID = 'formAdd'
            const formData = new FormData($('#'+formID).get(0))

            apiCall(`/api/{{ "$controller" }}/`, 'POST', formData, 
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

        function getRow()
        {


            apiCall(`/api/{{ "$controller" }}/page-post/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    if (res.status == 200) {

                        /** access buttons */ 
                        if (res.items.hasButtonAdd) {
                            $('.divBtnAdd').html(`
                                <button type="submit" class="btn btn-success btn-sm ms-2">
                                    <span>
                                        <i class="bx bx-save me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Save</span>
                                    </span>
                                </button>
                            `)
                        }

                        /** selects */
                        html = ''
                        if (res.items.UserTypes.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.UserTypes) {
                                html += `<option value="${res.items.UserTypes[key].userTypeID}">${res.items.UserTypes[key].name}</option>`
                            }
                        }
                        $('#formAdd select[name="userTypeID"]').html(html)

                        html = ''
                        if (res.items.offices.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.offices) {
                                html += `<option value="${res.items.offices[key].officeID}">${res.items.offices[key].name}</option>`
                            }
                        }
                        $('#formAdd select[name="officeID"]').html(html)

                        html = ''
                        if (res.items.JobPositions.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.JobPositions) {
                                html += `<option value="${res.items.JobPositions[key].jobPositionID}">${res.items.JobPositions[key].name}</option>`
                            }
                        }
                        $('#formAdd select[name="jobPositionID"]').html(html)

                        html = ''
                        if (res.items.user_employment_types.length > 0) {
                            pName = ''
                            html = '<option value="">&nbsp;</option>'
                            for (key in res.items.user_employment_types) {
                                html += `<option value="${res.items.user_employment_types[key].userEmploymentTypeID}">${res.items.user_employment_types[key].name}</option>`
                            }
                        }
                        $('#formAdd select[name="userEmploymentTypeID"]').html(html)

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

