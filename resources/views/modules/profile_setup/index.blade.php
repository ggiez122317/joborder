@extends('layouts.blank')

@section('title', $title)

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <style>

        table.table thead tr th, table.table tbody tr td {
            padding: 0.25rem .5rem;
        }

    </style>
@endsection

@section('content')
    <div class="py-5">
        <h4 class="py-3 breadcrumb-wrapper d-flex align-items-center mb-2">
            <div class="d-flex align-items-center">
                <img src="http://127.0.0.1:8000/assets/img/logo.png" class="app-brand-logo demo" alt="Logo" style="width: 35px; height: 35px;"> 
                <span class="text-muted fw-light ms-2">Profile Setup</span>
            </div>
            <button type="button" class="btn btn-secondary btn-sm ms-auto" onclick="authenticationLogout()">
                <span>
                    <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                    <span class="d-none d-sm-inline-block">Back to Login</span>
                </span>
            </button>
        </h4>
        <div class="row">
            <div class="col-12">
                <div class="bs-stepper wizard-numbered vertical wizard-modern wizard-modern-vertical">
                    <div class="bs-stepper-header px-0 py-2">
                        <div class="step active" data-target="#wizard-emloyment">
                            <button type="button" class="step-trigger" aria-selected="true">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Employment</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#wizard-personal-information">
                            <button type="button" class="step-trigger" aria-selected="false">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Personal Information</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content p-0" style="background: transparent; box-shadow: none;">
                        <div class="alert alert-danger" role="alert">Please make sure you <b>enter the correct record</b>. If any <b>problem arises, contact the administrator</b>.</div>
                        <form id="formSubmit">
                            <!-- Employment -->
                            <div id="wizard-emloyment" class="card p-2 content active dstepper-block">
                                <div class="content-header mb-3">
                                    <h6 class="mb-0">Employment</h6>
                                </div>
                                <div class="row g-2">
                                    <div class="col-12 d-flex justify-content-between">
                                        <button type="button" class="btn btn-sm btn-label-secondary btn-prev" disabled="">
                                            <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                            <span class="d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary btn-next">
                                            <span class="d-sm-inline-block d-none me-sm-1">Next</span>
                                            <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" name="username" class="form-control" value="">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" value="">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                        <input type="password" name="passwordCon" class="form-control" value="">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Employee ID</label>
                                        <input type="text" name="idNumber" class="form-control" value="" style="background: #e3e3e3;" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Employment Type</label>
                                        <input type="text" name="uetName" class="form-control" value="" style="background: #e3e3e3;" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Salary Per Month </label>
                                        <input type="text" name="salaryMonthly" class="form-control" value="" style="background: #e3e3e3;" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Salary Per Annum </label>
                                        <input type="text" name="salaryYearly" class="form-control" value="" style="background: #e3e3e3;" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Office</label>
                                        <input type="text" name="office" class="form-control" value="" style="background: #e3e3e3;" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Job Position </label>
                                        <input type="text" name="position" class="form-control" value="" style="background: #e3e3e3;" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Bank Account Name</label>
                                        <input type="text" name="bankAccountName" class="form-control" value="">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Bank Account number</label>
                                        <input type="text" name="bankAccountNumber" class="form-control" value="">
                                    </div>
    
                                    <div class="col-12 d-flex justify-content-between">
                                        <button type="button" class="btn btn-sm btn-label-secondary btn-prev" disabled="">
                                            <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                            <span class="d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary btn-next">
                                            <span class="d-sm-inline-block d-none me-sm-1">Next</span>
                                            <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Personal Information -->
                            <div id="wizard-personal-information" class="card p-2 content">
                                <div class="content-header mb-3">
                                    <h6 class="mb-0">Personal Information</h6>
                                </div>
                                <div class="row g-2">
                                    <div class="col-12 d-flex justify-content-between">
                                        <button type="button" class="btn btn-sm btn-primary btn-prev">
                                            <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                            <span class="d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button type="submit" class="btn btn-sm btn-success btn-submit">Submit</button>
                                    </div>
                            
                                    <div class="col-md-4">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="fname" class="form-control" value="" >
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="mname" class="form-control" value=""  >
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="lname" class="form-control" value=""  >
                                    </div>
                            
                                    <div class="col-md-4">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-control">
                                            <option value="1" selected="">Male</option>
                                            <option value="0">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Civil Status</label>
                                        <select name="civilStatus" class="form-control">
                                            <option value="1" selected="">Single</option>
                                            <option value="2">Married</option>
                                            <option value="3">Separated</option>
                                            <option value="4">Widowed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Citizenship</label>
                                        <input type="text" name="citizenship" class="form-control" value=""  >
                                    </div>
                            
                                    <div class="col-md-4">
                                        <label class="form-label">Birthday <span class="text-danger">*</span></label>
                                        <input type="date" name="birthDate" class="form-control" value="" max="<?= date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Birth Place <span class="text-danger">*</span></label>
                                        <input type="text" name="birthPlace" class="form-control" value=""  >
                                    </div>
                            
                                    <div class="col-md-4">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" value="" placeholder="09xx-xxx-xxxx" maxlength="11">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Email</label>
                                        <input type="text" name="email" class="form-control" value=""  >
                                    </div>
                            
                                    <div class="col-md-4">
                                        <label class="form-label">GSIS</label>
                                        <input type="text" name="gsis" class="form-control" value=""  >
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">PAGIBIG</label>
                                        <input type="text" name="pagibig" class="form-control" value=""  >
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">PHIL HEALTH</label>
                                        <input type="text" name="philhealth" class="form-control" value=""  >
                                    </div>
                            
                                    <div class="col-md-4">
                                        <label class="form-label">SSS</label>
                                        <input type="text" name="sss" class="form-control" value=""  >
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">TIN</label>
                                        <input type="text" name="tin" class="form-control" value=""  >
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Blood Type</label>
                                        <select name="bloodType" class="form-control">
                                            <option value=""></option>
                                            <option value="1">O+</option>
                                            <option value="2">O-</option>
                                            <option value="3">A+</option>
                                            <option value="4">A-</option>
                                            <option value="5">B+</option>
                                            <option value="6">B-</option>
                                            <option value="7">AB+</option>
                                            <option value="8">AB-</option>
                                        </select>
                                    </div>
    
                                    <div class="col-md-3">
                                        <label class="form-label">Province</label>
                                        <select name="permProvinceID" class="form-control" onchange="getCities()">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">City/Municipality</label>
                                        <select name="permCityID" class="form-control" onchange="getBarangays()">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Barangay</label>
                                        <select name="permBarangayID" class="form-control">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Street</label>
                                        <input type="text" name="permStreet" class="form-control" value=""  >
                                    </div>
                                    <div class="col-12 d-flex justify-content-between">
                                        <button type="button" class="btn btn-sm btn-primary btn-prev">
                                            <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                            <span class="d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        <button type="submit" class="btn btn-sm btn-success btn-submit">Submit</button>
                                    </div> 
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/form-wizard-numbered.js') }}"></script>

    <script>

        function getProvinces()
        {

            const formID = 'formSubmit'
            apiCall(`/api/{{ "$controller" }}/get-provinces/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} select[name="permCityID"]`).html('<option value="">&nbsp;</option>')
                    $(`#${formID} select[name="permBarangayID"]`).html('<option value="">&nbsp;</option>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        // fields
                        html = ''
                        if (res.items.provinces.length > 0) {
                            html += `<option value="">&nbsp;</option>`
                            for (key in res.items.provinces) {
                                html += `<option value="${res.items.provinces[key].provinceID}" >${res.items.provinces[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="permProvinceID"]`).html(html)


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

        function getCities()
        {

            const formID = 'formSubmit'
            apiCall(`/api/{{ "$controller" }}/get-cities/${$(`#${formID} select[name="permProvinceID"]`).val()}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} select[name="permBarangayID"]`).html('<option value="">&nbsp;</option>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        // fields
                        html = ''
                        if (res.items.cities.length > 0) {
                            html += `<option value="">&nbsp;</option>`
                            for (key in res.items.cities) {
                                html += `<option value="${res.items.cities[key].cityID}" >${res.items.cities[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="permCityID"]`).html(html)


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

        function getBarangays()
        {

            const formID = 'formSubmit'
            apiCall(`/api/{{ "$controller" }}/get-barangays/${$(`#${formID} select[name="permCityID"]`).val()}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    if (res.status == 200) {

                        // fields
                        html = ''
                        if (res.items.barangays.length > 0) {
                            html += `<option value="">&nbsp;</option>`
                            for (key in res.items.barangays) {
                                html += `<option value="${res.items.barangays[key].barangayID}" >${res.items.barangays[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="permBarangayID"]`).html(html)


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

        function getRow()
        {

            const formID = 'formSubmit'

            apiCall(`/api/{{ "$controller" }}/page-put/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="3">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        // fields
                        $(`#${formID} input[name="idNumber"]`).val(res.items.row.idNumber)
                        $(`#${formID} input[name="fname"]`).val(res.items.row.fname)
                        $(`#${formID} input[name="mname"]`).val(res.items.row.mname)
                        $(`#${formID} input[name="lname"]`).val(res.items.row.lname)
                        $(`#${formID} input[name="uetName"]`).val(res.items.row.uetName)
                        $(`#${formID} input[name="salaryMonthly"]`).val(res.items.row.salaryMonthly)
                        $(`#${formID} input[name="salaryYearly"]`).val(res.items.row.salaryYearly)
                        $(`#${formID} input[name="office"]`).val(res.items.row.office)
                        $(`#${formID} input[name="position"]`).val(res.items.row.position)

                        getProvinces()

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

        $(document).on('submit', '#formSubmit', function(e) {
            e.preventDefault()

            const formID = 'formSubmit'
            const formData = new FormData($('#'+formID).get(0))

            formData.append('_method', 'PUT') 

            apiCall(`/api/{{ "$controller" }}/`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `Loading...`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        Swal.fire({
                            title: "Profile Setup Complete",
                            text: "You can now log in using your new account credentials.",
                            icon: "success",
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "Go to Login Form",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showCancelButton: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                authenticationLogout()
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
                    btnLoading(`#${formID} button[type="submit"]`, `Submit`, 0)
                }, 
                localStorage.getItem('t') 
            )
        }) 

        $(document).ready(function() {
            getRow()
        })

    </script>
@endsection