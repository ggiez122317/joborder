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
    <div class="row">
        <div class="col-12 justify-content-end d-flex">
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href=`{{ url("/$controller/view/$id/") }}`">
                <span>
                    <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                    <span class="d-none d-sm-inline-block">Back</span>
                </span>
            </button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-employment" aria-controls="navs-pills-top-employment" aria-selected="false" tabindex="-1">
                            Employment
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-personel-information" aria-controls="navs-pills-top-personel-information" aria-selected="false" tabindex="-1">
                            Personal Information
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-family-background" aria-controls="navs-pills-top-family-background" aria-selected="false" tabindex="-1">
                            Family Background
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-educational-background" aria-controls="navs-pills-top-educational-background" aria-selected="false" tabindex="-1">
                            Educational Background
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-civil-service-eligibility" aria-controls="navs-pills-top-civil-service-eligibility" aria-selected="false" tabindex="-1">
                            Civil Service Eligibility
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-work-experience" aria-controls="navs-pills-top-work-experience" aria-selected="false" tabindex="-1">
                            Work Experience
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-training-programs" aria-controls="navs-pills-top-training-programs" aria-selected="false" tabindex="-1">
                            Training Programs
                        </button>
                    </li>
                </ul>
                <div class="tab-content p-2">
                    <!-- employment -->
                    <div class="tab-pane fade show active" id="navs-pills-top-employment" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <button type="submit" class="btn btn-success btn-sm ms-2">
                                <span>
                                    <i class="bx bx-save me-sm-1"></i> 
                                    <span class="d-none d-sm-inline-block">Save</span>
                                </span>
                            </button>
                        </div>
                        <div class="card-body">

                            <div class="row g-2">
                        
                                <div class="col-md-4">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="phone" class="form-control" value="09272828926" style="background: #e3e3e3;" readonly="">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Employee ID</label>
                                    <input type="text" name="phone" class="form-control" value="09272828926">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Employment Status</label>
                                    <select name="gender" class="form-control">
                                        <option value="1" selected="">Male</option>
                                        <option value="0">Female</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Office</label>
                                    <select name="civilStatus" class="form-control">
                                        <option value="1" selected="">Single</option>
                                        <option value="2">Married</option>
                                        <option value="3">Separated</option>
                                        <option value="4">Widowed</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Position</label>
                                    <select name="civilStatus" class="form-control">
                                        <option value="1" selected="">Single</option>
                                        <option value="2">Married</option>
                                        <option value="3">Separated</option>
                                        <option value="4">Widowed</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Date Appointed</label>
                                    <input type="date" name="citizenship" class="form-control" value="" >
                                </div>
                        
                                <div class="col-md-4">
                                    <label class="form-label">Basic Salary <span class="text-danger">*</span></label>
                                    <input type="date" name="birthday" class="form-control" value="1997-08-16">
                                </div>
                        
                            </div>
                            
                        </div>
                    </div>
                    <!-- personal -->
                    <div class="tab-pane fade" id="navs-pills-top-personel-information" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <button type="submit" class="btn btn-success btn-sm ms-2">
                                <span>
                                    <i class="bx bx-save me-sm-1"></i> 
                                    <span class="d-none d-sm-inline-block">Save</span>
                                </span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">

                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <form id="form_dp" class="d-flex justify-content-center">
                                        <input type="file" id="img" name="img" class="d-none" onchange="$('#form_dp').submit()" accept="image/png, image/gif, image/jpeg">
                                        <div class="ms-sm-4 d-flex justify-content-center" style="width: 120px; height: 120px; position: relative;">
                                        <img id="dImg" src="http://localhost:8080/assets/img/dp.jpg" alt="user image" class="d-block h-auto me-0 rounded-3 user-profile-img border" style="width: 120px; height: 120px;">
                                        <div class="d-flex align-items-center justify-content-center" onclick="$('#img').click()" style="cursor: pointer; border: 1px solid #ccc; border-radius: 50%; width: 30px; height: 30px; background: #fff; position: absolute; bottom: -10px; box-shadow: 0px 0px 5px 1px #ccc;">
                                            <i class="bx bx-camera"></i>
                                        </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4"></div>
                        
                                <div class="col-md-4">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="fname" class="form-control" value="New" placeholder="First Name">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="mname" class="form-control" value="" placeholder="Middle Name">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="lname" class="form-control" value="Account" placeholder="Last Name">
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
                                    <input type="text" name="citizenship" class="form-control" value="Filipino" placeholder="Citizenship">
                                </div>
                        
                                <div class="col-md-4">
                                    <label class="form-label">Birthday <span class="text-danger">*</span></label>
                                    <input type="date" name="birthday" class="form-control" value="1997-08-16">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Birth Place <span class="text-danger">*</span></label>
                                    <input type="text" name="birthPlace" class="form-control" value="Hinatuan, Surigao del Sur" placeholder="Birth Place">
                                </div>
                        
                                <div class="col-md-4">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="09272828926" placeholder="09xx-xxx-xxxx" maxlength="11">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Email</label>
                                    <input type="text" name="email" class="form-control" value="" placeholder="Email">
                                </div>
                        
                                <div class="col-md-4">
                                    <label class="form-label">GSIS</label>
                                    <input type="text" name="gsis" class="form-control" value="" placeholder="GSIS">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">PAGIBIG</label>
                                    <input type="text" name="pagibig" class="form-control" value="" placeholder="PAGIBIG">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">PHIL HEALTH</label>
                                    <input type="text" name="philhealth" class="form-control" value="" placeholder="PHIL HEALTH">
                                </div>
                        
                                <div class="col-md-4">
                                    <label class="form-label">SSS</label>
                                    <input type="text" name="sss" class="form-control" value="" placeholder="SSS">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">TIN</label>
                                    <input type="text" name="tin" class="form-control" value="" placeholder="TIN">
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
                                        <option value="1">Agusan del Sur</option>
                                        <option value="2">Agusan del Norte</option>
                                        <option value="3">Surigao Del Sur</option>
                                        <option value="4">Surigao Del Norte</option>
                                        <option value="5">Bukidnon</option>
                                        <option value="6">Davao City</option>
                                        <option value="7">Davao De Oro</option>
                                        <option value="8">Davao Del Sur</option>
                                        <option value="9">Davao Del Norte</option>
                                        <option value="10">Cagayan de Oro</option>
                                        <option value="11">Lanao Del Sur</option>
                                        <option value="12">Lanao Del Norte</option>
                                        <option value="13">Zamboanga Del Sur</option>
                                        <option value="14">Zamboanga Del Norte</option>
                                        <option value="15">General Santos City</option>
                                        <option value="16">North Cotabato</option>
                                        <option value="17">South Cotabato</option>
                                        <option value="18">Saranggani</option>
                                        <option value="19">Cotabato City</option>
                                        <option value="20">Dinagat Island</option>
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
                                    <input type="text" name="permStreet" class="form-control" value="" placeholder="Street">
                                </div>
                        
                            </div>
                        </div>
                    </div>
                    <!-- family -->
                    <div class="tab-pane fade" id="navs-pills-top-family-background" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <button type="submit" class="btn btn-success btn-sm ms-2">
                                <span>
                                    <i class="bx bx-save me-sm-1"></i> 
                                    <span class="d-none d-sm-inline-block">Save</span>
                                </span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">

                                <div class="col-md-4">
                                    <label class="form-label">Spouse's First Name</label>
                                    <input type="text" name="spouseFname" class="form-control" value="" placeholder="Spouse's First Name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Spouse's Middle Name</label>
                                    <input type="text" name="spouseMname" class="form-control" value="" placeholder="Spouse's Middle Name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Spouse's Last Name</label>
                                    <input type="text" name="spouseLname" class="form-control" value="" placeholder="Spouse's Last Name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Spouse's Occupation</label>
                                    <input type="text" name="spouseOccupation" class="form-control" value="" placeholder="Spouse's Occupation">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Spouse's Employer/Business Name</label>
                                    <input type="text" name="spouseBizName" class="form-control" value="" placeholder="Spouse's Employer/Business Name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Spouse's Business Address</label>
                                    <input type="text" name="spouseBizAddress" class="form-control" value="" placeholder="Spouse's Business Address">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Spouse's Telephone Number</label>
                                    <input type="text" name="spouseTelNo" class="form-control" value="" placeholder="Spouse's Telephone Number">
                                </div>
                                <div class="col-md-8"></div>

                                <div class="col-md-4">
                                    <label class="form-label">Father's First Name</label>
                                    <input type="text" name="fatherFname" class="form-control" value="" placeholder="Father's First Name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Father's Middle Name</label>
                                    <input type="text" name="fatherMname" class="form-control" value="" placeholder="Father's Middle Name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Father's Last Name</label>
                                    <input type="text" name="fatherLname" class="form-control" value="" placeholder="Father's Last Name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Mother's First Name</label>
                                    <input type="text" name="motherFname" class="form-control" value="" placeholder="Mother's First Name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Mother's Middle Name</label>
                                    <input type="text" name="motherMname" class="form-control" value="" placeholder="Mother's Middle Name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Mother's Last Name</label>
                                    <input type="text" name="motherLname" class="form-control" value="" placeholder="Mother's Last Name">
                                </div>

                                <div class="col-12 d-flex align-items-end">
                                    <label class="form-label mb-0">Name of Childrens</label>
                                    <button type="button" class="btn btn-primary btn-sm ms-auto" onclick="addChildren()"><i class="bx bx-plus ms-0"></i></button>
                                </div>

                                <div class="col-12 mt-1 table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                        <tr>
                                            <th class="text-center"></th>
                                            <th class="text-start">Full Name</th>
                                            <th class="text-center">Birthday</th>
                                        </tr>
                                        </thead>
                                        <tbody id="dChildrens">

                                                                <tr class="dRow">
                                                <td class="text-center">-</td>
                                                <td class="text-start">
                                                <input type="text" name="childrenNames[]" class="form-control" style="min-width: 200px;">
                                                </td>
                                                <td class="text-center">
                                                <input type="date" name="childrenBirthdays[]" class="form-control">
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- educational -->
                    <div class="tab-pane fade" id="navs-pills-top-educational-background" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <button type="submit" class="btn btn-success btn-sm ms-2">
                                <span>
                                    <i class="bx bx-save me-sm-1"></i> 
                                    <span class="d-none d-sm-inline-block">Save</span>
                                </span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered table-striped mb-2">
                                        <thead>
                                            <tr valign="middle">
                                                <th rowspan="2" class="text-start text-nowrap">Level</th>
                                                <th rowspan="2" class="text-center text-nowrap">Name of School<br>(Write in full)</th>
                                                <th rowspan="2" class="text-center text-nowrap">Basic Education/Degree/Course<br>(Write in full)</th>
                                                <th rowspan="1" colspan="2" class="text-center text-nowrap">Period of Attendance</th>
                                                <th rowspan="2" class="text-center text-nowrap">Highest Level/<br>Units Earned<br>(if not graduated)</th>
                                                <th rowspan="2" class="text-center text-nowrap">Year Graduated</th>
                                                <th rowspan="2" class="text-center text-nowrap">Scholarship/<br>Academic<br>Honors<br>Received</th>
                                            </tr>
                                            <tr valign="middle">
                                                <th class="text-center">From</th>
                                                <th class="text-center">To</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dEducation">

                                            <tr>
                                                <td class="text-start">ELEMENTARY</td>
                                                <td class="text-center">
                                                <input type="text" name="schoolNames[]" value="" class="form-control" style="min-width: 200px;">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="degrees[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="date" name="dateAttendedFroms[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="date" name="dateAttendedTos[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="highestLevelEarneds[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="yearGraduateds[]" value="" class="form-control" placeholder="e.g. 1997">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="scholarships[]" value="" class="form-control" placeholder="">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">SECONDARY</td>
                                                <td class="text-center">
                                                <input type="text" name="schoolNames[]" value="" class="form-control" style="min-width: 200px;">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="degrees[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="date" name="dateAttendedFroms[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="date" name="dateAttendedTos[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="highestLevelEarneds[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="yearGraduateds[]" value="" class="form-control" placeholder="e.g. 1997">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="scholarships[]" value="" class="form-control" placeholder="">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">VOCATIONAL/<br>TRADE COURSE</td>
                                                <td class="text-center">
                                                <input type="text" name="schoolNames[]" value="" class="form-control" style="min-width: 200px;">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="degrees[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="date" name="dateAttendedFroms[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="date" name="dateAttendedTos[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="highestLevelEarneds[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="yearGraduateds[]" value="" class="form-control" placeholder="e.g. 1997">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="scholarships[]" value="" class="form-control" placeholder="">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">COLLEGE</td>
                                                <td class="text-center">
                                                <input type="text" name="schoolNames[]" value="" class="form-control" style="min-width: 200px;">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="degrees[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="date" name="dateAttendedFroms[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="date" name="dateAttendedTos[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="highestLevelEarneds[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="yearGraduateds[]" value="" class="form-control" placeholder="e.g. 1997">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="scholarships[]" value="" class="form-control" placeholder="">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">GRADUATE STUDIES</td>
                                                <td class="text-center">
                                                <input type="text" name="schoolNames[]" value="" class="form-control" style="min-width: 200px;">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="degrees[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="date" name="dateAttendedFroms[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="date" name="dateAttendedTos[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="highestLevelEarneds[]" value="" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="yearGraduateds[]" value="" class="form-control" placeholder="e.g. 1997">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="scholarships[]" value="" class="form-control" placeholder="">
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- civil service -->
                    <div class="tab-pane fade" id="navs-pills-top-civil-service-eligibility" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <button type="submit" class="btn btn-success btn-sm ms-2">
                                <span>
                                    <i class="bx bx-save me-sm-1"></i> 
                                    <span class="d-none d-sm-inline-block">Save</span>
                                </span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                            <tr valign="middle">
                                                <th rowspan="2" class="text-center">
                                                    <button type="button" class="btn btn-primary btn-sm ms-auto" onclick="addEligibility()"><i class="bx bx-plus ms-0"></i></button>
                                                </th>
                                                <th rowspan="2" class="text-center text-nowrap">Career Service/RA 1080 (Board/Bar) Under<br>Special LAWS/CES/CSEE<br>Barangay Eligibility/Driver's License</th>
                                                <th rowspan="2" class="text-center text-nowrap">RATING<br>(If Applicable)</th>
                                                <th rowspan="2" class="text-center text-nowrap">Date of<br>Examination/<br>Conferment</th>
                                                <th rowspan="2" class="text-center text-nowrap">Place of Examination/ Conferment</th>
                                                <th rowspan="1" colspan="2" class="text-center text-nowrap">License (If applicable)</th>
                                            </tr>
                                            <tr valign="middle">
                                                <th class="text-center">Number</th>
                                                <th class="text-center">Date of Validity</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dEligibility">

                                            <tr>
                                                <td class="text-center">-</td>
                                                <td class="text-center"><input type="text" name="serviceNames[]" class="form-control"></td>
                                                <td class="text-center"><input type="text" name="ratings[]" class="form-control"></td>
                                                <td class="text-center"><input type="date" name="dateExaminations[]" class="form-control"></td>
                                                <td class="text-center"><input type="text" name="placeExaminations[]" class="form-control"></td>
                                                <td class="text-center"><input type="text" name="licenseNumbers[]" class="form-control" style="min-width: 120px;"></td>
                                                <td class="text-center"><input type="date" name="licenseDateValiditys[]" class="form-control"></td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- work -->
                    <div class="tab-pane fade" id="navs-pills-top-work-experience" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <button type="submit" class="btn btn-success btn-sm ms-2">
                                <span>
                                    <i class="bx bx-save me-sm-1"></i> 
                                    <span class="d-none d-sm-inline-block">Save</span>
                                </span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                            <tr valign="middle">
                                                <th rowspan="2" class="text-center">
                                                    <button type="button" class="btn btn-primary btn-sm ms-auto" onclick="addWork()"><i class="bx bx-plus ms-0"></i></button>
                                                </th>
                                                <th rowspan="1" colspan="2" class="text-center text-nowrap">Inclusive Dates</th>
                                                <th rowspan="2" class="text-center text-nowrap">Position Title<br>(Write in full/ Do not abbreviate)</th>
                                                <th rowspan="2" class="text-center text-nowrap">DEPARTMENT/ AGENCY/ OFFICE/ COMPANY<br>(Write in full/ Do not abbreviate)</th>
                                                <th rowspan="2" class="text-center text-nowrap">MONTHLY<br>Salary</th>
                                                <th rowspan="2" class="text-center text-nowrap">SALARY/ JOB/ PAY GRADE<br>(If applicable)<br>&amp; STEP<br>(Format *00-0*)/<br>INCREMENT</th>
                                                <th rowspan="2" class="text-center text-nowrap">STATUS OF<br>APPOINTMENT</th>
                                                <th rowspan="2" class="text-center text-nowrap">GOV'T<br>SERVICE<br>(Y/N)</th>
                                            </tr>
                                            <tr valign="middle">
                                                <th class="text-center">From</th>
                                                <th class="text-center">To</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dWork">

                                            <tr>
                                                <td class="text-center">-</td>
                                                <td class="text-center">
                                                <input type="date" name="dateFroms[]" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="date" name="dateTos[]" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="positions[]" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="companys[]" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="salarys[]" class="form-control" style="min-width: 90px;">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="salaryGrades[]" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <input type="text" name="appointmentStatuss[]" class="form-control">
                                                </td>
                                                <td class="text-center">
                                                <select name="isGovts[]" class="form-control">
                                                    <option value="">YES</option>
                                                    <option value="">NO</option>
                                                </select>
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- training -->
                    <div class="tab-pane fade" id="navs-pills-top-training-programs" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <button type="submit" class="btn btn-success btn-sm ms-2">
                                <span>
                                    <i class="bx bx-save me-sm-1"></i> 
                                    <span class="d-none d-sm-inline-block">Save</span>
                                </span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                            <tr valign="middle">
                                                <th rowspan="2" class="text-center">
                                                    <button type="button" class="btn btn-primary btn-sm ms-auto" onclick="addLaD()"><i class="bx bx-plus ms-0"></i></button>
                                                </th>
                                                <th rowspan="2" class="text-center text-nowrap">Title of Learning and Development Interventions/Training Programs<br>(Write in full)</th>
                                                <th rowspan="1" colspan="2" class="text-center text-nowrap">Inclusive Dates Of<br>Attendance</th>
                                                <th rowspan="2" class="text-center text-nowrap">NUMBER OF HOURS</th>
                                                <th rowspan="2" class="text-center text-nowrap">TYPE OF LD<br>(Managerial/ Supervisory/<br>Technical/etc)</th>
                                                <th rowspan="2" class="text-center text-nowrap">CONDUCTED/SPONSORED BY<br>(Write in full)</th>
                                            </tr>
                                            <tr valign="middle">
                                                <th class="text-center">From</th>
                                                <th class="text-center">To</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dLaD">

                                            <tr>
                                                <td class="text-center">-</td>
                                                <td class="text-center"><input type="text" name="trainingNames[]" class="form-control"></td>
                                                <td class="text-center"><input type="date" name="dateFromLaDs[]" class="form-control"></td>
                                                <td class="text-center"><input type="date" name="dateToLaDs[]" class="form-control"></td>
                                                <td class="text-center"><input type="text" name="hourss[]" class="form-control"></td>
                                                <td class="text-center"><input type="text" name="ldTypes[]" class="form-control"></td>
                                                <td class="text-center"><input type="text" name="sponsors[]" class="form-control"></td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

@endsection

@section('scripts')
    <script>

        

    </script>
@endsection
