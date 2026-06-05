@extends('layouts.app')

@section('title', $title)

@section('styles')
    <style>
        #qrcode img {
            cursor: pointer;
        }
    </style>
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
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href=`{{ url("/$controller") }}`">
                <span>
                    <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                    <span class="d-none d-sm-inline-block">Back</span>
                </span>
            </button>
            <div class="mb-xl-0">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm ms-2 dropdown-toggle" type="button" id="dropdownMenuDate" data-bs-toggle="dropdown" aria-expanded="true">
                        <span class="tf-icons bx bx-printer me-1"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuDate" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(8px, 30.4px, 0px);" data-popper-placement="bottom-start">
                        <li class="print-pds"></li>
                        <li class="print-service-record"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link py-1 px-3 active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-personel-information" aria-controls="navs-pills-top-personel-information" aria-selected="false" tabindex="-1">
                            Personal Information
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link py-1 px-3" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-family-background" aria-controls="navs-pills-top-family-background" aria-selected="false" tabindex="-1">
                            Family Background
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link py-1 px-3" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-educational-background" aria-controls="navs-pills-top-educational-background" aria-selected="false" tabindex="-1">
                            Educational Background
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link py-1 px-3" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-civil-service-eligibilities" aria-controls="navs-pills-top-civil-service-eligibilities" aria-selected="false" tabindex="-1">
                            Civil Service Eligibilities
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link py-1 px-3" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-work-experience" aria-controls="navs-pills-top-work-experience" aria-selected="false" tabindex="-1">
                            Work Experiences
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link py-1 px-3" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-training-programs" aria-controls="navs-pills-top-training-programs" aria-selected="false" tabindex="-1">
                            Training Programs
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link py-1 px-3" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-201-files" aria-controls="navs-pills-top-201-files" aria-selected="false" tabindex="-1">
                            201 Files
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link py-1 px-3" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-employments" aria-controls="navs-pills-top-employments" aria-selected="false" tabindex="-1">
                            Employments
                        </button>
                    </li>
                </ul>
                <div class="tab-content p-2">
                    <!-- personal -->
                    <div class="tab-pane fade show active" id="navs-pills-top-personel-information" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <div class="divBtnEditPersonalInformation"></div>
                            <div class="divBtnAuditPersonalInformation"></div>
                            <div class="divBtnChangeRequestPersonalInformation"></div>
                        </div>
                        <form id="formPersonalInformation" class="card-body">

                            <div class="row mb-2 align-items-top">
                                <label class="col-sm-2 col-form-label">QR Code</label>
                                <div class="col-sm-10">
                                    (<span class="text-danger" style="font-size: 9pt;">Click image to Download</span>)
                                    <div id="qrcode"></div>
                                </div>
                                <a type="button" id="downloadLink" href="#" download="qrcode.png" class="d-none">Download QR Code</a>
                            </div>

                            <div class="row mb-2 align-items-top">
                                <label class="col-sm-2 col-form-label">Profile Picture</label>
                                <div class="col-sm-10">
                                    <img src="" alt="User Avatar" class="d-block rounded" height="150" width="150" id="uploadedAvatar">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">First Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="fname" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Middle Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="mname" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Last Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="lname" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Gender</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="gender" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Birthday</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="birthDate" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Birth Place</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="birthPlace" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Citizenship</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="citizenship" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Civil Status</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="civilStatus" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">SGIS</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="gsis" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">PAGIBIG</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="pagibig" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">PhilHealth</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="philhealth" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">SSS</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="sss" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">TIN</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="tin" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Blood Type</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="bloodType" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Phone</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="phone" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="email" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            
                            <div class="row ">
                                <label class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="address" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- family -->
                    <div class="tab-pane fade" id="navs-pills-top-family-background" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <div class="divBtnEditFamilyBackground"></div>
                            <div class="divBtnAuditFamilyBackground"></div>
                            <div class="divBtnChangeRequestFamilyBackground"></div>
                        </div>
                        <form id="formFamilyBackground" class="card-body">
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Spouse's Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="spouseName" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Spouse's Occupation</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="spouseOccupation" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Spouse's Employer/ Business Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="spouseBizName" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Spouse's Business Address</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="spouseBizAddress" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Spouse's Telephone Number</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="spouseTelNo" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Father's Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="father" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label class="col-sm-2 col-form-label">Mother's Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="mother" style="background: #e3e3e3;" readonly="">
                                </div>
                            </div>
                            <div class="row mb-0">
                                <label class="col-12 col-form-label">Childrens</label>
                            </div>
                            <div class="row">
                                <div class="col-12 table-responsive">
                                <table class="table table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Child Name</th>
                                            <th class="text-center">Birthday</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><th class="text-center text-danger" colspan="2">No Record Found</th></tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- educational -->
                    <div class="tab-pane fade" id="navs-pills-top-educational-background" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <div class="divBtnEditEducationalBackground"></div>
                            <div class="divBtnAuditEducationalBackground"></div>
                            <div class="divBtnChangeRequestEducationalBackground"></div>
                        </div>
                        <form id="formEducationalBackground" class="card-body">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped table-bordered mb-0">
                                        <thead>
    
                                            <tr>
                                                <th rowspan="2" class="text-center">Level</th>
                                                <th rowspan="2" class="text-start">Name of School</th>
                                                <th rowspan="2" class="text-center">Basic Education/ Degree/ Course</th>
                                                <th rowspan="1" colspan="2" class="text-center">Period of Attendance</th>
                                                <th rowspan="2" class="text-center">Highest Level/ Units Earned</th>
                                                <th rowspan="2" class="text-center">Year Graduated</th>
                                                <th rowspan="2" class="text-center">Scholarship/ Academic Honors Received</th>
                                            </tr>
    
                                            <tr>
                                                <th class="text-center">From</th>
                                                <th class="text-center">To</th>
                                            </tr>
    
                                        </thead>
                                        <tbody><tr><th class="text-center text-danger" colspan="8">No Record Found</th></tr></tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- civil service -->
                    <div class="tab-pane fade" id="navs-pills-top-civil-service-eligibilities" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <div class="divBtnEditCivilServiceEligibilities"></div>
                            <div class="divBtnAuditCivilServiceEligibilities"></div>
                            <div class="divBtnChangeRequestCivilServiceEligibilities"></div>
                        </div>
                        <form id="formCivilServiceEligibilities" class="card-body">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped table-bordered mb-0">
                                        <thead>
        
                                            <tr>
                                                <th rowspan="2" class="text-start">Career Service/ RA 1080 (Board/Bar) under special LAWS/CES/CSEE barangay eligibility/ Driver's license</th>
                                                <th rowspan="2" class="text-center">Rating</th>
                                                <th rowspan="2" class="text-center">Date of examination/ confinement</th>
                                                <th rowspan="2" class="text-center">Place of examination/ confinement</th>
                                                <th rowspan="1" colspan="2" class="text-center">License</th>
                                            </tr>
        
                                            <tr>
                                                <th class="text-center">Number</th>
                                                <th class="text-center">Date of Validity</th>
                                            </tr>
        
                                        </thead>
                                        <tbody><tr><th class="text-center text-danger" colspan="6">No Record Found</th></tr></tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- work -->
                    <div class="tab-pane fade" id="navs-pills-top-work-experience" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <div class="divBtnEditWorkExperiences"></div>
                            <div class="divBtnAuditWorkExperiences"></div>
                            <div class="divBtnChangeRequestWorkExperiences"></div>
                        </div>
                        <form id="formWorkExperiences" class="card-body">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th rowspan="1" colspan="2" class="text-center">Inclusive Dates</th>
                                                <th rowspan="2" class="text-center">Position Title</th>
                                                <th rowspan="2" class="text-center">Department/ Agency/ Office/ Company</th>
                                                <th rowspan="2" class="text-center">Monthly Salary</th>
                                                <th rowspan="2" class="text-center">Salary/ Job Pay Grade &amp; Step Increment</th>
                                                <th rowspan="2" class="text-center">Status of appointment</th>
                                                <th rowspan="2" class="text-center">Gov't Service</th>
                                                </tr>
    
                                                <tr>
                                                <th class="text-center">From</th>
                                                <th class="text-center">To</th>
                                            </tr>
                                        </thead>
                                        <tbody><tr><th class="text-center text-danger" colspan="8">No Record Found</th></tr></tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- training -->
                    <div class="tab-pane fade" id="navs-pills-top-training-programs" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <div class="divBtnEditTrainingPrograms"></div>
                            <div class="divBtnAuditTrainingPrograms"></div>
                            <div class="divBtnChangeRequestTrainingPrograms"></div>
                        </div>
                        <form id="formTrainingPrograms" class="card-body">
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped table-bordered mb-0">
                                        <thead>
    
                                            <tr>
                                                <th rowspan="2" class="text-center">Title of learning and development interventions/ training programs</th>
                                                <th rowspan="1" colspan="2" class="text-center">Inclusive Dates of Attendance</th>
                                                <th rowspan="2" class="text-center">Number of Hours</th>
                                                <th rowspan="2" class="text-center">Type of LD (Managerial/ Supervisory/ Technical/ etc.)</th>
                                                <th rowspan="2" class="text-center">Conducted/ sponsored by</th>
                                            </tr>
    
                                            <tr>
                                                <th class="text-center">From</th>
                                                <th class="text-center">To</th>
                                            </tr>
    
                                        </thead>
                                        <tbody><tr><th class="text-center text-danger" colspan="6">No Record Found</th></tr></tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- 201-files -->
                    <div class="tab-pane fade" id="navs-pills-top-201-files" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <div class="divBtnAdd201Files"></div>
                            <div class="divBtnAudit201Files"></div>
                        </div>
                        <form id="form201Files" class="card-body table-responsive">

                            <table class="table table-striped table-bordered mb-0">
                                <thead>

                                    <tr>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Files</th>
                                        <th class="text-center">Action</th>
                                    </tr>

                                </thead>
                                <tbody><tr><th class="text-center text-danger" colspan="4">No Record Found</th></tr></tbody>
                            </table>

                        </form>
                    </div>
                    <!-- employment -->
                    <div class="tab-pane fade" id="navs-pills-top-employments" role="tabpanel">
                        <div class="card-header mb-2 justify-content-end d-flex">
                            <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="printLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                <span>
                                    <i class="bx bx-notepad"></i> 
                                </span>
                            </button>
                        </div>
                        <form id="formEmployments" class="card-body">

                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped table-bordered mb-0">
                                        <thead>
    
                                            <tr>
                                                <th class="text-center">Date Appointed</th>
                                                <th class="text-center">Employee ID</th>
                                                <th class="text-center">Office</th>
                                                <th class="text-center">Job Position</th>
                                                <th class="text-center">Employment Type</th>
                                                <th class="text-center">Bank Name</th>
                                                <th class="text-center">Bank Number</th>
                                                <th class="text-center">Salary</th>
                                                <th class="text-center">Status</th>
                                            </tr>
    
    
                                        </thead>
                                        <tbody><tr><th class="text-center text-danger" colspan="11">No Record Found</th></tr></tbody>
                                    </table>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="modal201FileAdd" tabindex="-1" aria-labelledby="modal201FileAddLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form id="form201FileAdd" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal201FileAddLabel">New 201 File</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <label class="form-label">File Type <span class="text-danger">*</span></label>
                            <select name="user201FileTypeID" class="form-control">
                                <option value="">&nbsp;</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Files <span class="text-danger">*</span></label>
                            <input type="file" name="files[]" class="form-control"
                                accept=".txt,.pdf,.docx,.xlsx,.pptx,.odt,.ods,.odp,
                                        .jpg,.jpeg,.png,.gif,.bmp,.svg,.webp,
                                        .zip,.tar,.gz,.7z" multiple>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control">
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
    <div class="modal fade" id="modalServiceRecord" tabindex="-1" aria-labelledby="modalServiceRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered modal-sm">
            <form id="formServiceRecord" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalServiceRecordLabel">Select Checker</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <label class="form-label">Document Checker <span class="text-danger">*</span></label>
                            <select name="userID" class="form-control">
                                <option value="">&nbsp;</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Choose</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script>

        function auditPersonalInformationLogs()
        {
            popupCenteredWindow(`{{ url("/$controller/audit-personal-information/$id/") }}`) 
        }
        function auditFamilyBackgroundLogs()
        {
            popupCenteredWindow(`{{ url("/$controller/audit-family-background/$id/") }}`) 
        }
        function auditEducationalBackgroundLogs()
        {
            popupCenteredWindow(`{{ url("/$controller/audit-educational-background/$id/") }}`) 
        }
        function auditCivilServiceEligibilitiesLogs()
        {
            popupCenteredWindow(`{{ url("/$controller/audit-civil-service-eligibilities/$id/") }}`) 
        }
        function auditWorkExperiencesLogs()
        {
            popupCenteredWindow(`{{ url("/$controller/audit-work-experiences/$id/") }}`) 
        }
        function auditTrainingProgramsLogs()
        {
            popupCenteredWindow(`{{ url("/$controller/audit-training-programs/$id/") }}`) 
        }

        function getPersonalInformation()
        {

            const formID = 'formPersonalInformation'
            apiCall(`/api/{{ "$controller/personal-information/$id" }}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {
                    if (res.status == 200) {

                        // 
                        if (res.items.hasButtonPDS) {
                            $('.print-pds').html(`
                                <a class="dropdown-item" onclick="printPDS()" href="javascript:void(0);">Personal Data Sheet</a>
                            `)
                        }
                        $('.print-service-record').html(`
                            <a class="dropdown-item" onclick="printServiceRecord()" href="javascript:void(0);">Service Record</a>
                        `)
                        if (res.items.hasButtonEdit) {
                            $('.divBtnEditPersonalInformation').html(`
                                <button type="button" class="btn btn-warning btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/edit-personal-information/$id/") }}'">
                                    <span>
                                        <i class="bx bx-pencil me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Edit</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonChangeRequest) {
                            $('.divBtnChangeRequestPersonalInformation').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditPersonalInformationLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonAudit) {
                            $('.divBtnAuditPersonalInformation').html(`
                                <button type="button" class="btn btn-info btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/changes-personal-information/$id/") }}'">
                                    <span>
                                        <i class="bx bx-redo me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Change Requests</span>
                                    </span>
                                </button>
                            `)
                        }

                        // qr code
                        new QRCode(document.getElementById("qrcode"), {
                            text: res.items.row.qrCode,
                            width: 150,
                            height: 150
                        })

                        // fields
                        $(`#${formID} img`).prop('src', res.items.row.avatar)
                        $(`#${formID} input[name="fname"]`).val(res.items.row.fname)
                        $(`#${formID} input[name="mname"]`).val(res.items.row.mname)
                        $(`#${formID} input[name="lname"]`).val(res.items.row.lname)
                        $(`#${formID} input[name="gender"]`).val(res.items.row.gender)
                        $(`#${formID} input[name="birthDate"]`).val(res.items.row.birthDate)
                        $(`#${formID} input[name="birthPlace"]`).val(res.items.row.birthPlace)
                        $(`#${formID} input[name="citizenship"]`).val(res.items.row.citizenship)
                        $(`#${formID} input[name="civilStatus"]`).val(res.items.row.civilStatus)
                        $(`#${formID} input[name="gsis"]`).val(res.items.row.gsis)
                        $(`#${formID} input[name="pagibig"]`).val(res.items.row.pagibig)
                        $(`#${formID} input[name="philhealth"]`).val(res.items.row.philhealth)
                        $(`#${formID} input[name="sss"]`).val(res.items.row.sss)
                        $(`#${formID} input[name="tin"]`).val(res.items.row.tin)
                        $(`#${formID} input[name="bloodType"]`).val(res.items.row.bloodType)
                        $(`#${formID} input[name="phone"]`).val(res.items.row.phone)
                        $(`#${formID} input[name="email"]`).val(res.items.row.email)
                        $(`#${formID} input[name="address"]`).val(res.items.row.address)
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
        function getFamilyBackground()
        {

            const formID = 'formFamilyBackground'
            apiCall(`/api/{{ "$controller/family-background/$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><th class="text-start" colspan="2">Loading...</th></tr>')
                }, 
                // done
                function(res) {
                    if (res.status == 200) {

                        // 
                        if (res.items.hasButtonEdit) {
                            $('.divBtnEditFamilyBackground').html(`
                                <button type="button" class="btn btn-warning btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/edit-family-background/$id/") }}'">
                                    <span>
                                        <i class="bx bx-pencil me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Edit</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonChangeRequest) {
                            $('.divBtnChangeRequestFamilyBackground').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditFamilyBackgroundLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonAudit) {
                            $('.divBtnAuditFamilyBackground').html(`
                                <button type="button" class="btn btn-info btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/changes-family-background/$id/") }}'">
                                    <span>
                                        <i class="bx bx-redo me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Change Requests</span>
                                    </span>
                                </button>
                            `)
                        }

                        // fields
                        $(`#${formID} input[name="spouseName"]`).val(res.items.row.spouseName)
                        $(`#${formID} input[name="spouseOccupation"]`).val(res.items.row.spouseOccupation)
                        $(`#${formID} input[name="spouseBizName"]`).val(res.items.row.spouseBizName)
                        $(`#${formID} input[name="spouseBizAddress"]`).val(res.items.row.spouseBizAddress)
                        $(`#${formID} input[name="spouseTelNo"]`).val(res.items.row.spouseTelNo)
                        $(`#${formID} input[name="father"]`).val(res.items.row.father)
                        $(`#${formID} input[name="mother"]`).val(res.items.row.mother)

                        $(`#${formID} table tbody`).html('<tr><th class="text-center text-danger" colspan="2">No Record Found</th></tr>')
                        if (res.items.row.childrens.length > 0) {
                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.row.childrens) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-start">${res.items.row.childrens[key].name}</td>
                                        <td class="text-center">${res.items.row.childrens[key].birthDate}</td>
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
        function getEducationalBackground()
        {

            const formID = 'formEducationalBackground'
            apiCall(`/api/{{ "$controller/educational-background/$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><th class="text-start" colspan="8">Loading...</th></tr>')
                }, 
                // done
                function(res) {
                    if (res.status == 200) {

                        // 
                        if (res.items.hasButtonEdit) {
                            $('.divBtnEditEducationalBackground').html(`
                                <button type="button" class="btn btn-warning btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/edit-educational-background/$id/") }}'">
                                    <span>
                                        <i class="bx bx-pencil me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Edit</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonChangeRequest) {
                            $('.divBtnChangeRequestEducationalBackground').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditEducationalBackgroundLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonAudit) {
                            $('.divBtnAuditEducationalBackground').html(`
                                <button type="button" class="btn btn-info btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/changes-educational-background/$id/") }}'">
                                    <span>
                                        <i class="bx bx-redo me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Change Requests</span>
                                    </span>
                                </button>
                            `)
                        }
                        
                        $(`#${formID} table tbody`).html('<tr><th class="text-center text-danger" colspan="8">No Record Found</th></tr>')
                        if (res.items.row.educations.length > 0) {
                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.row.educations) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-start">${res.items.row.educations[key].level}</td>
                                        <td class="text-start">${res.items.row.educations[key].schoolName}</td>
                                        <td class="text-center">${res.items.row.educations[key].degree}</td>
                                        <td class="text-center">${res.items.row.educations[key].dateAttendedFrom}</td>
                                        <td class="text-center">${res.items.row.educations[key].dateAttendedTo}</td>
                                        <td class="text-center">${res.items.row.educations[key].highestLevelEarned}</td>
                                        <td class="text-center">${res.items.row.educations[key].yearGraduated}</td>
                                        <td class="text-center">${res.items.row.educations[key].scholarship}</td>
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
        function getCivilServiceEligibilities()
        {

            const formID = 'formCivilServiceEligibilities'
            apiCall(`/api/{{ "$controller/civil-service-eligibilities/$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="6">Loading...</td></tr>')
                }, 
                // done
                function(res) {
                    if (res.status == 200) {

                        // 
                        if (res.items.hasButtonEdit) {
                            $('.divBtnEditCivilServiceEligibilities').html(`
                                <button type="button" class="btn btn-warning btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/edit-civil-service-eligibilities/$id/") }}'">
                                    <span>
                                        <i class="bx bx-pencil me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Edit</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonChangeRequest) {
                            $('.divBtnChangeRequestCivilServiceEligibilities').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditCivilServiceEligibilitiesLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonAudit) {
                            $('.divBtnAuditCivilServiceEligibilities').html(`
                                <button type="button" class="btn btn-info btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/changes-civil-service-eligibilities/$id/") }}'">
                                    <span>
                                        <i class="bx bx-redo me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Change Requests</span>
                                    </span>
                                </button>
                            `)
                        }

                        $(`#${formID} table tbody`).html('<tr><th class="text-center text-danger" colspan="6">No Record Found</th></tr>')
                        if (res.items.row.civilServices.length > 0) {
                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.row.civilServices) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-start">${res.items.row.civilServices[key].name}</td>
                                        <td class="text-center">${res.items.row.civilServices[key].rating}</td>
                                        <td class="text-center">${res.items.row.civilServices[key].dateExamination}</td>
                                        <td class="text-center">${res.items.row.civilServices[key].placeExamination}</td>
                                        <td class="text-center">${res.items.row.civilServices[key].licenseNumber}</td>
                                        <td class="text-center">${res.items.row.civilServices[key].licenseDateValidity}</td>
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
        function getWorkExperiences()
        {

            const formID = 'formWorkExperiences'
            apiCall(`/api/{{ "$controller/work-experiences/$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="8">Loading...</td></tr>')
                }, 
                // done
                function(res) {
                    if (res.status == 200) {

                        // 
                        if (res.items.hasButtonEdit) {
                            $('.divBtnEditWorkExperiences').html(`
                                <button type="button" class="btn btn-warning btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/edit-work-experiences/$id/") }}'">
                                    <span>
                                        <i class="bx bx-pencil me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Edit</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonChangeRequest) {
                            $('.divBtnChangeRequestWorkExperiences').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditWorkExperiencesLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonAudit) {
                            $('.divBtnAuditWorkExperiences').html(`
                                <button type="button" class="btn btn-info btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/changes-work-experiences/$id/") }}'">
                                    <span>
                                        <i class="bx bx-redo me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Change Requests</span>
                                    </span>
                                </button>
                            `)
                        }

                        $(`#${formID} table tbody`).html('<tr><th class="text-center text-danger" colspan="8">No Record Found</th></tr>')
                        if (res.items.row.workExperiences.length > 0) {
                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.row.workExperiences) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-start">${res.items.row.workExperiences[key].dateFrom}</td>
                                        <td class="text-center">${res.items.row.workExperiences[key].dateTo}</td>
                                        <td class="text-center">${res.items.row.workExperiences[key].position}</td>
                                        <td class="text-center">${res.items.row.workExperiences[key].company}</td>
                                        <td class="text-center">${res.items.row.workExperiences[key].salary}</td>
                                        <td class="text-center">${res.items.row.workExperiences[key].salaryGrade}</td>
                                        <td class="text-center">${res.items.row.workExperiences[key].appointmentStatus}</td>
                                        <td class="text-center">${res.items.row.workExperiences[key].isGovt}</td>
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
        function getTrainingPrograms()
        {

            const formID = 'formTrainingPrograms'
            apiCall(`/api/{{ "$controller/training-programs/$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="6">Loading...</td></tr>')
                }, 
                // done
                function(res) {
                    if (res.status == 200) {

                        // 
                        if (res.items.hasButtonEdit) {
                            $('.divBtnEditTrainingPrograms').html(`
                                <button type="button" class="btn btn-warning btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/edit-training-programs/$id/") }}'">
                                    <span>
                                        <i class="bx bx-pencil me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Edit</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonChangeRequest) {
                            $('.divBtnChangeRequestTrainingPrograms').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditTrainingProgramsLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonAudit) {
                            $('.divBtnAuditTrainingPrograms').html(`
                                <button type="button" class="btn btn-info btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/changes-training-programs/$id/") }}'">
                                    <span>
                                        <i class="bx bx-redo me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Change Requests</span>
                                    </span>
                                </button>
                            `)
                        }
                        
                        $(`#${formID} table tbody`).html('<tr><th class="text-center text-danger" colspan="8">No Record Found</th></tr>')
                        if (res.items.row.trainingPrograms.length > 0) {
                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.row.trainingPrograms) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-start">${res.items.row.trainingPrograms[key].trainingName}</td>
                                        <td class="text-center">${res.items.row.trainingPrograms[key].dateFrom}</td>
                                        <td class="text-center">${res.items.row.trainingPrograms[key].dateTo}</td>
                                        <td class="text-center">${res.items.row.trainingPrograms[key].hours}</td>
                                        <td class="text-center">${res.items.row.trainingPrograms[key].ldType}</td>
                                        <td class="text-center">${res.items.row.trainingPrograms[key].sponsor}</td>
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
        function get201Files()
        {

            const formID = 'form201Files'
            apiCall(`/api/{{ "$controller/201-files/$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="4">Loading...</td></tr>')
                }, 
                // done
                function(res) {
                    if (res.status == 200) {
                        
                        if (res.items.hasButtonAdd) {
                            $('.divBtnAdd201Files').html(`
                                <button type="button" class="btn btn-primary btn-sm ms-2" onclick="user201FileAdd()">
                                    <span>
                                        <i class="bx bx-save me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Add</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonAudit) {
                            $('.divBtnAudit201Files').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="printLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }
                        
                        $(`#${formID} table tbody`).html('<tr><th class="text-center text-danger" colspan="4">No Record Found</th></tr>')
                        if (res.items.row.user_201_files.length > 0) {
                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.row.user_201_files) {

                                files201 = ''
                                if (res.items.row.user_201_files[key].fileNames.length > 0) {
                                    for (key2 in res.items.row.user_201_files[key].fileNames) {
                                        files201 += `<a href="${res.items.row.user_201_files[key].fileNames[key2].link}" class="text-start" target="_blank" download><u>${res.items.row.user_201_files[key].fileNames[key2].name}</u></a>`
                                    }
                                }
 
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-center">${res.items.row.user_201_files[key].date}</td>
                                        <td class="text-center">${res.items.row.user_201_files[key].type}</td>
                                        <td class="text-center">
                                            <div class="d-flex gap-3 flex-wrap">
                                                ${files201}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            ${res.items.hasButtonDelete?`
                                                <button type="button" class="btn btn-danger btn-sm remove201File" data-id="${res.items.row.user_201_files[key].user201FileID}"><i class="bx bx-trash ms-0"></i></button>
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
        function getEmployments()
        {

            const formID = 'formEmployments'
            apiCall(`/api/{{ "$controller/employments/$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="9">Loading...</td></tr>')
                }, 
                // done
                function(res) {
                    if (res.status == 200) {

                        statusNames     = ['Inactive', 'Active']
                        statusColors    = ['danger', 'success']
                        
                        $(`#${formID} table tbody`).html('<tr><th class="text-center text-danger" colspan="9">No Record Found</th></tr>')
                        if (res.items.row.employments.length > 0) {
                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.row.employments) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-center">${res.items.row.employments[key].dateAppointed}</td>
                                        <td class="text-center">${res.items.row.employments[key].idNumber}</td>
                                        <td class="text-center">${res.items.row.employments[key].office}</td>
                                        <td class="text-center">${res.items.row.employments[key].position}</td>
                                        <td class="text-center">${res.items.row.employments[key].type}</td>
                                        <td class="text-center">${res.items.row.employments[key].bankName}</td>
                                        <td class="text-center">${res.items.row.employments[key].bankNumber}</td>
                                        <td class="text-center">${res.items.row.employments[key].salaryBasic}</td>
                                        <td class="text-center"><span class="badge bg-${statusColors[res.items.row.employments[key].status]}">${statusNames[res.items.row.employments[key].status]}</span></td>
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
            getPersonalInformation()
            getFamilyBackground()
            getEducationalBackground()
            getCivilServiceEligibilities()
            getWorkExperiences()
            getTrainingPrograms()
            get201Files()
            getEmployments()
        })

        // 201 files 
        function user201FileAdd()
        {

            const formID = 'form201FileAdd'
            apiCall(`/api/{{ "$controller" }}/page-post-201-file/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#tableAccesses`).html('<tr><td class="text-start" colspan="2">Loading...</td></tr>')
                }, 
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

                        html = ''
                        if (res.items.user_201_file_types.length > 0) {
                            html += `<option value=""></option>`
                            for (key in res.items.user_201_file_types) {
                                html += `<option value="${res.items.user_201_file_types[key].user201FileTypeID}" >${res.items.user_201_file_types[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="user201FileTypeID"]`).html(html)
                        $(`#${formID} input[name="files"]`).val('')
                        $(`#${formID} input[name="date"]`).val('')

                        $('#modal201FileAdd').modal('show')
                        
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

        $(document).on('submit', '#form201FileAdd', function(e) {
            e.preventDefault()

            const formID = 'form201FileAdd'
            const formData = new FormData($('#'+formID).get(0))

            apiCall(`/api/{{ "$controller" }}/201-file/{{ $id }}/`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `Loading...`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        get201Files()
                        $('#modal201FileAdd').modal('hide')
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

        $(document).on('click', '.remove201File', function() {

            const user201FileID = $(this).data('id')
            
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

                    apiCall(`/api/{{ "$controller" }}/201-file/${user201FileID}/`, 'DELETE', null, 
                        // beforesend
                        function() {}, 
                        // done
                        function(res) {

                            if (res.status == 200) {
                                Swal.fire({
                                    title   : "Deleted!",
                                    text    : "Record has been deleted.",
                                    icon    : "success"
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        get201Files()
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
            })

        })

        // prints 
        function printPDS()
        {
            popupCenteredWindow(`{{ url("/$controller/print-pds/$id/") }}/`) 
        }
        function printServiceRecord()
        {
            popupCenteredWindow(`{{ url("/$controller/print-service-record/$id/") }}/`) 
        }

        $(document).on('click', '#qrcode img', function() {
            const canvas = document.querySelector("#qrcode canvas")
            const imageUrl = canvas.toDataURL("image/png")
            $('#downloadLink').attr('href', imageUrl)
            $('#downloadLink')[0].click()
        })

    </script>
@endsection
