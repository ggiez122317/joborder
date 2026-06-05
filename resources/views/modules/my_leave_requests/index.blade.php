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
            <button type="button" class="btn btn-primary btn-sm" onclick="rowAdd()">
                <span>
                    <i class="bx bx-plus me-sm-1"></i> 
                    <span class="d-none d-sm-inline-block">Add</span>
                </span>
            </button>
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
                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Date Filed" data-field="dateFiled">
                                    <span>Date Filed</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Leave Type" data-field="leaveTypeID">
                                    <span>Leave Type</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center filterSort" data-label="Working Days Applied" data-field="leaveWorkingDays">
                                    <span>Working Days Applied</span>
                                </a>
                            </th>
                            <th class="text-start">
                                <a href="javascript:void(0);" class="d-flex align-items-center justify-content-center filterSort" data-label="Status" data-field="status">
                                    <span>Status</span>
                                </a>
                            </th>
                            <th class="text-center">Action</th>
                        </tr>
                        <tr valign="middle d-none">
                            <th class="text-center">
                                <input type="date" class="form-control" name="dateInserted" >
                            </th>
                            <th class="text-center">
                                <input type="text" class="form-control" name="leaveTypeID" >
                            </th>
                            <th class="text-center">
                                <input type="text" class="form-control" name="leaveWorkingDays" >
                            </th>
                            <th class="text-center">
                                <select name="status" class="form-control">
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

                        <tr><td class="text-start" colspan="6">Loading...</td></tr>

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
    <div class="modal fade" id="modalLeaveRequestAdd" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formLeaveRequestAdd">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLeaveRequestAddTitle">Add Leave Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="form-label">Vacation Leave Credits</label>
                                <input type="text" name="creditsVacation" class="form-control" style="background: #e3e3e3;" readonly>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Sick Leave Credits</label>
                                <input type="text" name="creditsSick" class="form-control" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="leaveTypeID">
                                    <option value="">&nbsp;</option>
                                </select>
                            </div>
                        </div>
                        <div class="row hasLeaveTypeDetail">
                            <div class="col-12 mb-2">
                                <label class="form-label">Leave Type Detail</label>
                                <input type="text" name="typeExt" class="form-control" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>
                        <div class="row isVacationSlp">
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input name="leaveCaseID" class="form-check-input" type="radio" value="1" id="isInPhilippines">
                                    <label class="form-check-label" for="isInPhilippines">Within the Philippines</label>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input name="leaveCaseID" class="form-check-input" type="radio" value="2" id="isInAbroad">
                                    <label class="form-check-label" for="isInAbroad">Abroad</label>
                                </div>
                            </div>
                            <div class="col-12 mb-2 isLeaveCaseDetail">
                                <label class="form-label">Specify Location <span class="text-danger">*</span></label>
                                <input type="text" class="form-control leaveCaseDetail">
                            </div>
                        </div>
                        <div class="row isSick">
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input name="leaveCaseID" class="form-check-input" type="radio" value="3" id="isInHospital">
                                    <label class="form-check-label" for="isInHospital">In Hospital</label>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input name="leaveCaseID" class="form-check-input" type="radio" value="4" id="isOutPatient">
                                    <label class="form-check-label" for="isOutPatient">Out Patient</label>
                                </div>
                            </div>
                            <div class="col-12 mb-2 isLeaveCaseDetail">
                                <label class="form-label">Specify Illness <span class="text-danger">*</span></label>
                                <input type="text" class="form-control leaveCaseDetail">
                            </div>
                        </div>
                        <div class="row isBenefitsForWomen">
                            <div class="col-12 mb-2">
                                <label class="form-label">Specify Illness  <span class="text-danger">*</span></label>
                                <input type="text" class="form-control leaveCaseDetail">
                            </div>
                        </div>
                        <div class="row isStudy">
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input name="leaveCaseID" class="form-check-input" type="radio" value="5" id="isForMastersDegree">
                                    <label class="form-check-label" for="isForMastersDegree">Completion of Master's Degree</label>
                                </div>
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input name="leaveCaseID" class="form-check-input" type="radio" value="6" id="isExamReview">
                                    <label class="form-check-label" for="isExamReview">BAR/Board Examination Review</label>
                                </div>
                            </div>
                            <div class="col-12 mb-2 isLeaveCaseDetail">
                                <label class="form-label">Specify Illness <span class="text-danger">*</span></label>
                                <input type="text" class="form-control leaveCaseDetail">
                            </div>
                        </div>
                        <div class="row hasLeaveDates">
                            <div class="col-6 mb-2">
                                <label class="form-label">Leave Date From <span class="text-danger dateRequired">*</span></label>
                                <input type="date" name="dateFrom" value="<?= date('Y-m-d') ?>" class="form-control datePrior">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Leave Date To <span class="text-danger dateRequired">*</span></label>
                                <input type="date" name="dateTo" value="<?= date('Y-m-d') ?>" class="form-control datePrior">
                            </div>
                        </div>
                        <div class="row hasLeaveDates">
                            <div class="col-12 mb-2">
                                <label class="form-label">Leave Working (Note: <span class="text-primary">8hrs = 1day</span>) <span class="text-danger dateRequired">*</span></label>
                                <div class="row">
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="input-group">
                                            <input type="number" class="form-control dNumberNoArrow" name="leaveDays" min="0" step="1">
                                            <span class="input-group-text">Day(s)</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="input-group">
                                            <input type="number" class="form-control dNumberNoArrow" name="leaveHours" min="0" max="7" step="1">
                                            <span class="input-group-text">Hr(s)&nbsp;&nbsp;</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="input-group">
                                            <input type="number" class="form-control dNumberNoArrow" name="leaveMinutes" min="0" max="59" step="1">
                                            <span class="input-group-text">Min(s)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row isCTO" style="display: none;">
                            <div class="col-6 mb-2">
                                <label class="form-label">Date of Actual Work From <span class="text-danger dateRequired">*</span></label>
                                <input type="date" name="dateServiceFrom" value="<?= date('Y-m-d') ?>" class="form-control datePrior">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Date of Actual Work To <span class="text-danger dateRequired">*</span></label>
                                <input type="date" name="dateServiceTo" value="<?= date('Y-m-d') ?>" class="form-control datePrior">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">CTO Date From <span class="text-danger dateRequired">*</span></label>
                                <input type="date" name="dateCTOFrom" value="<?= date('Y-m-d') ?>" class="form-control datePrior">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">CTO Date To <span class="text-danger dateRequired">*</span></label>
                                <input type="date" name="dateCTOTo" value="<?= date('Y-m-d') ?>" class="form-control datePrior">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="form-label">CTO Working (Note: <span class="text-primary">8hrs = 1day</span>) <span class="text-danger dateRequired">*</span></label>
                                <div class="row">
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="input-group">
                                            <input type="number" class="form-control dNumberNoArrow" name="leaveCTODays" min="0" step="1">
                                            <span class="input-group-text">Day(s)</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="input-group">
                                            <input type="number" class="form-control dNumberNoArrow" name="leaveCTOHours" min="0" max="7" step="1">
                                            <span class="input-group-text">Hr(s)&nbsp;&nbsp;</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="input-group">
                                            <input type="number" class="form-control dNumberNoArrow" name="leaveCTOMinutes" min="0" max="59" step="1">
                                            <span class="input-group-text">Min(s)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row isMonetization" style="display: none;">
                            <div class="col-12 col-sm-3 mb-2">
                                <label class="form-label">Basic Salary</label>
                                <input type="text" name="monetizeCreditsSalary" class="form-control" style="background: #e3e3e3;" readonly>
                            </div>
                            <div class="col-12 col-sm-3 mb-2">
                                <label class="form-label d-flex">Monetizable <span class="d-block d-sm-none">Credits</span></label>
                                <input type="text" name="monetizeCreditsConvertable" class="form-control" style="background: #e3e3e3;" readonly>
                            </div>
                            <div class="col-12 col-sm-3 mb-2">
                                <label class="form-label d-flex"><span class="d-block d-sm-none">Credits to</span> convert&nbsp;</label>
                                <input type="number" name="creditsToMonetize" class="form-control dNumberNoArrow" value="0" min="0" step="0.001" style="background: #e3e3e3;" readonly>
                            </div>
                            <div class="col-12 col-sm-3 mb-2">
                                <label class="form-label">Amount (₱)</label>
                                <input type="text" name="monetizeCreditsAmount" class="form-control" value="0" style="background: #e3e3e3;" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Convert Working (Note: <span class="text-primary">8hrs = 1day</span>) <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="input-group">
                                            <select name="creditsConvertDays" class="form-control creditsConvert">
                                                <option value="">&nbsp;</option>
                                            </select>
                                            <label class="input-group-text">Day(s)</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="input-group">
                                            <select name="creditsConvertHours" class="form-control creditsConvert">
                                                <option value="">&nbsp;</option>
                                            </select>
                                            <label class="input-group-text">Hr(s)&nbsp;&nbsp;</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="input-group">
                                            <select name="creditsConvertMinutes" class="form-control creditsConvert">
                                                <option value="">&nbsp;</option>
                                            </select>
                                            <label class="input-group-text">Min(s)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row isMonetization" style="display: none;">
                            <div class="col-6 mb-2">
                                <label class="form-label">Expected Remaining VL Credits</label>
                                <input type="text" name="creditsToMonetizeVL" class="form-control" value="0" style="background: #e3e3e3;" readonly>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Expected Remaining SL Credits</label>
                                <input type="text" name="creditsToMonetizeSL" class="form-control" value="0" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>
                        <div class="row isTerminal">
                            <div class="col-12 mb-2">
                                <label class="form-label">Saparation Date <span class="text-danger">*</span></label>
                                <input type="date" name="dateSeparate" value="<?= date('Y-m-d', strtotime('+1 month')) ?>" class="form-control" min="<?= date('Y-m-d', strtotime('+1 month')) ?>">
                            </div>
                        </div>
                        <div class="row isTerminal" style="display: none;">
                            <div class="col-12 col-sm-4 mb-2">
                                <label class="form-label">Basic Salary</label>
                                <input type="text" name="terminalCreditsSalary" class="form-control" style="background: #e3e3e3;" readonly>
                            </div>
                            <div class="col-12 col-sm-4 mb-2">
                                <label class="form-label d-flex">Monetizable Credits</label>
                                <input type="text" name="terminalCreditsConvert" class="form-control" style="background: #e3e3e3;" readonly>
                            </div>
                            <div class="col-12 col-sm-4 mb-2">
                                <label class="form-label">Amount (₱)</label>
                                <input type="text" name="terminalCreditsAmount" class="form-control" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>
                        <div class="row isCommutation d-none">
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="commutation" value="1" id="commutation" checked>
                                    <label class="form-check-label" for="commutation">Commutation: Leave Requested By Head/Upper Officers</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label class="form-label">Attachment(s)</label>
                                <input type="file" name="files[]" class="form-control" accept=".png, .jpg, .jpeg, .gif, .xlsx, .xls, .pdf, .ppt, .pptx, .doc, .docx" multiple>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalLeaveRequestView" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formLeaveRequestView">
                    <input type="hidden" name="positionID" value="">
                    <input type="hidden" name="salary" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLeaveRequestViewTitle">View Leave Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Type</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="leaveType" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2 isDetail">
                            <label class="col-sm-3 col-form-label">Details</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="leaveTypeDetail" rows="2" style="background: #e3e3e3;" readonly></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-2 isCto" style="display: none;">
                            <label class="col-sm-3 col-form-label">Actual Work Date</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="dateWorked" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Leave Date</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="date" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Leave Working Days</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="leaveWorkingDays" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2 isAMount" style="display: none;">
                            <label class="col-sm-3 col-form-label">Monetized Amount</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="amount" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Commutation</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="commutation" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="creditsCalculations">
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label">VL Total Earned</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="creditsVacationEarned" style="background: #e3e3e3;" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label">VL Less this App</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="creditsVacationLess" style="background: #e3e3e3;" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label">VL Balance</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="creditsVacationBalance" style="background: #e3e3e3;" readonly>
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label">SL Total Earned</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="creditsSickEarned" style="background: #e3e3e3;" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label">SL Less this App</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="creditsSickLess" style="background: #e3e3e3;" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-sm-3 col-form-label">SL Balance</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="creditsSickBalance" style="background: #e3e3e3;" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Date Filed</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="dateInserted" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Filed By</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="applicant" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Recommended By</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="recommender" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Checked By</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="checker" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Approved By</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="approver" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Approval Type</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="approvalType" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Approval Type Detail</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="approvalTypeDetail" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Disapproved By</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="disapprover" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Disapprove Comment</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="comment" rows="2" style="background: #e3e3e3;" readonly ></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-2" id="dDocumentHead">
                            <label class="col-sm-3 col-form-label">Attachment(s)</label>
                            <div class="col-sm-9 d-flex gap-3"></div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="status" style="background: #e3e3e3;" readonly>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/custom/page_index.js') }}"></script>
    <script>

        let monetizeCreditsTotal        = 0
        let monetizeCreditsSalary       = 0
        let monetizeCreditsConvertable  = 0
        let monetizeConstantFactor      = 0
        let monetizeCreditsAmount       = 0
        let creditsVacation             = 0
        let creditsSick                 = 0
        let creditsToMonetizeVL         = 0
        let creditsToMonetizeSL         = 0

        // let formula = creditsMonetizable*basicSalary*constantFactor

        $(document).on('click', '#formLeaveRequestAdd input[name="creditsToMonetize"]', function() {
            $(this).focus().select()
        })

        $(document).on('keyup paste', '#formLeaveRequestAdd input[name="creditsToMonetize"]', function() {

            let creditsToMonetize  = $(this).val()
            let monetizeCreditsMax      = $(this).prop('max')

            const firstInputted = creditsToMonetize.charAt(0)
            const lastInputted  = creditsToMonetize.charAt(creditsToMonetize.length - 1)

            if (lastInputted != '-') {
                if (creditsToMonetize != '') {
                    if (creditsToMonetize < 0) {
                        creditsToMonetize = parseFloat(creditsToMonetize)
                        $(this).val(0)
                    }
                    if (firstInputted == 0) {
                        if (creditsToMonetize.length > 1) {
                            const secondInputted = creditsToMonetize.charAt(1)
                            if (secondInputted != '.') {
                                creditsToMonetize = parseFloat(creditsToMonetize)
                                $(this).val(creditsToMonetize)
                            }
                        }
                    }
                } else { 
                    creditsToMonetize = 0 
                    $(this).val(creditsToMonetize)
                }
                creditsToMonetize = parseFloat(creditsToMonetize)
                if (creditsToMonetize > parseFloat(monetizeCreditsMax)) {
                    creditsToMonetize = parseFloat(monetizeCreditsMax)
                    $(this).val(creditsToMonetize)
                }
                // not enough credits
                if (monetizeCreditsConvertable < 5 || creditsVacation < 5) {
                    Toast.fire({ icon : "warning", title : "Not enough credits!", html : `VL Credits and Monetizable Credits must be at least 5.000.` })
                    creditsToMonetize = 0
                    $(this).val(creditsToMonetize)
                }
    
                // 
                monetizeCreditsAmount = creditsToMonetize*monetizeCreditsSalary*monetizeConstantFactor
                if (monetizeCreditsAmount == '') monetizeCreditsAmount = 0
                monetizeCreditsAmount = parseFloat(monetizeCreditsAmount)
                if (monetizeCreditsAmount < 0) monetizeCreditsAmount = 0 
                monetizeCreditsAmount = monetizeCreditsAmount.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                })

                $(`#formLeaveRequestAdd input[name="monetizeCreditsAmount"]`).val(monetizeCreditsAmount)

                // 
                creditsDeductableRemaining = creditsToMonetize
                creditsToMonetizeVL = creditsVacation - creditsDeductableRemaining
                creditsToMonetizeSL = creditsSick

                if (creditsToMonetizeVL < 5 && monetizeCreditsConvertable >= 5 && creditsVacation >= 5) {
                    creditsToMonetizeVL = 5.000
                    creditsDeductableRemaining = creditsDeductableRemaining - (creditsVacation - 5)
                    creditsToMonetizeSL = creditsSick - creditsDeductableRemaining
                }

                $(`#formLeaveRequestAdd input[name="creditsToMonetizeVL"]`).val(creditsToMonetizeVL.toFixed(3))
                $(`#formLeaveRequestAdd input[name="creditsToMonetizeSL"]`).val(creditsToMonetizeSL.toFixed(3))
            } else {
                $(this).val(creditsToMonetize.slice(0, -1))
            }

        })

        $(document).on('change', '.creditsConvert', function() {

            let creditsConvertDays    = $(`select[name="creditsConvertDays"] option:selected`).data('value')
            let creditsConvertHours   = $(`select[name="creditsConvertHours"] option:selected`).data('value')
            let creditsConvertMinutes = $(`select[name="creditsConvertMinutes"] option:selected`).data('value')

            if (creditsConvertDays == '') creditsConvertDays = 0
            if (creditsConvertHours == '') creditsConvertHours = 0
            if (creditsConvertMinutes == '') creditsConvertMinutes = 0

            creditsToMonetize = parseFloat(creditsConvertDays) + parseFloat(creditsConvertHours) + parseFloat(creditsConvertMinutes)
            creditsToMonetize = Math.round(creditsToMonetize * 1000) / 1000;

            $(`input[name="creditsToMonetize"]`).val(creditsToMonetize)
            
            monetizeCreditsAmount = creditsToMonetize*monetizeCreditsSalary*monetizeConstantFactor
            if (monetizeCreditsAmount == '') monetizeCreditsAmount = 0
            monetizeCreditsAmount = parseFloat(monetizeCreditsAmount)
            if (monetizeCreditsAmount < 0) monetizeCreditsAmount = 0 
            monetizeCreditsAmount = monetizeCreditsAmount.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })
            $(`#formLeaveRequestAdd input[name="monetizeCreditsAmount"]`).val(monetizeCreditsAmount)

            creditsDeductableRemaining = creditsToMonetize
            creditsToMonetizeVL = creditsVacation - creditsDeductableRemaining
            creditsToMonetizeSL = creditsSick

            if (creditsToMonetizeVL < 5 && monetizeCreditsConvertable >= 5 && creditsVacation >= 5) {
                creditsToMonetizeVL = 5.000
                creditsDeductableRemaining = creditsDeductableRemaining - (creditsVacation - 5)
                creditsToMonetizeSL = creditsSick - creditsDeductableRemaining
            }

            $(`#formLeaveRequestAdd input[name="creditsToMonetizeVL"]`).val(creditsToMonetizeVL.toFixed(3))
            $(`#formLeaveRequestAdd input[name="creditsToMonetizeSL"]`).val(creditsToMonetizeSL.toFixed(3))

        })

        function printIndex()
        {
            popupCenteredWindow(`{{ url("/$controller/print-list/") }}/?${getFilterItems()}/`) 
        }

        function printTravelReport()
        {
            popupCenteredWindow(`{{ url("/$controller/print-travel-report/") }}/`) 
        }

        function rowAdd()
        {

            const formID = 'formLeaveRequestAdd'
            apiCall(`/api/{{ "$controller" }}/page-post/`, 'GET', null, 
                // beforesend
                function() {
                    // $(`#${formID} textarea[name="destination"]`).val('')
                    // $(`#${formID} textarea[name="purpose"]`).val('')
                    // $(`#${formID} textarea[name="note"]`).val('')
                    $(`#${formID} input[name="leaveDays"]`).val('') 
                    $(`#${formID} input[name="leaveHours"]`).val('') 
                    $(`#${formID} input[name="leaveMinutes"]`).val('') 
                    $(`#${formID} input[name="files[]"]`).val('') 
                    $(`#${formID} input[name="creditsToMonetize"]`).val(0) 
                    $(`.isVacationSlp`).slideUp()
                    $(`.isSick`).slideUp()
                    $(`.isBenefitsForWomen`).slideUp()
                    $(`.isStudy`).slideUp()
                    $(`.isLeaveCaseDetail`).slideUp()
                    $(`.hasLeaveDates`).slideUp()
                    $(`.isCTO`).slideUp()
                    $(`.isMonetization`).slideUp()
                    $(`.isTerminal`).slideUp()
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        monetizeCreditsTotal        = res.items.monetizeCreditsTotal
                        monetizeCreditsSalary       = res.items.monetizeCreditsSalary
                        monetizeCreditsConvertable  = res.items.monetizeCreditsConvertable
                        monetizeConstantFactor      = res.items.monetizeConstantFactor 

                        creditsVacation = parseFloat(res.items.creditsVacation)
                        creditsSick     = parseFloat(res.items.creditsSick)

                        $(`#${formID} input[name="creditsToMonetize"]`).attr('max', monetizeCreditsConvertable)

                        html = ''
                        if (res.items.leave_types.length > 0) {
                            html += `<option value="" data-ext="" data-prior=""></option>`
                            for (key in res.items.leave_types) {
                                html += `<option value="${res.items.leave_types[key]['leaveTypeID']}" data-ext="${res.items.leave_types[key]['nameExt']}" data-prior="${res.items.leave_types[key]['daysPrior']}">${res.items.leave_types[key]['name']}</option>`
                            }
                        }
                        $(`#${formID} select[name="leaveTypeID"]`).html(html)

                        $(`#${formID} input[name="creditsVacation"]`).val(res.items.creditsVacation)
                        $(`#${formID} input[name="creditsSick"]`).val(res.items.creditsSick)

                        // monetize
                        $(`#${formID} input[name="creditsToMonetizeVL"]`).val(res.items.creditsVacation)
                        $(`#${formID} input[name="creditsToMonetizeSL"]`).val(res.items.creditsSick)
                        $(`#${formID} input[name="monetizeCreditsSalary"]`).val(
                            monetizeCreditsSalary.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            })
                        )
                        $(`#${formID} input[name="monetizeCreditsConvertable"]`).val(res.items.monetizeCreditsConvertable)

                        // terminal
                        $(`#${formID} input[name="terminalCreditsSalary"]`).val(
                            monetizeCreditsSalary.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            })
                        )
                        $(`#${formID} input[name="terminalCreditsConvert"]`).val(monetizeCreditsTotal)
                        let terminalCreditsAmount = (parseFloat(monetizeCreditsTotal)*parseFloat(monetizeCreditsSalary)*parseFloat(monetizeConstantFactor))
                        terminalCreditsAmount = roundDownToHundredth(terminalCreditsAmount) 
                        $(`#${formID} input[name="terminalCreditsAmount"]`).val(
                            terminalCreditsAmount.toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            })
                        )

                        // 

                        html = '<option value="0" data-value="0">&nbsp;</option>'
                        const temp_monetizeCreditsConvertable = Math.trunc(monetizeCreditsConvertable) 
                        if (temp_monetizeCreditsConvertable > 0) {
                            for (i=temp_monetizeCreditsConvertable; i>0; i--) {
                                html += `<option value="${i}" data-value="${i}">${i}</option>`
                            }
                            for (key in res.items.credit_fractions_hour) {
                            }
                        }
                        $(`#${formID} select[name="creditsConvertDays"]`).html(html)

                        html = '<option value="0" data-value="0">&nbsp;</option>'
                        if (res.items.credit_fractions_hour.length > 0) {
                            for (key in res.items.credit_fractions_hour) {
                                html += `<option value="${res.items.credit_fractions_hour[key].variable}" data-value="${res.items.credit_fractions_hour[key].value}">${res.items.credit_fractions_hour[key].variable}</option>`
                            }
                        }
                        $(`#${formID} select[name="creditsConvertHours"]`).html(html)

                        html = '<option value="0" data-value="0">&nbsp;</option>'
                        if (res.items.credit_fractions_minute.length > 0) {
                            for (key in res.items.credit_fractions_minute) {
                                html += `<option value="${res.items.credit_fractions_minute[key].variable}" data-value="${res.items.credit_fractions_minute[key].value}">${res.items.credit_fractions_minute[key].variable}</option>`
                            }
                        }
                        $(`#${formID} select[name="creditsConvertMinutes"]`).html(html)

                        $('#modalLeaveRequestAdd').modal('show')
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

        const formatDate = (date) => 
        {
            const yyyy = date.getFullYear()
            const mm = String(date.getMonth() + 1).padStart(2, '0')
            const dd = String(date.getDate()).padStart(2, '0')
            return `${yyyy}-${mm}-${dd}`
        }

        $(document).on('change', '#formLeaveRequestAdd select[name="leaveTypeID"]', function() {

            $('#formLeaveRequestAdd input[name="typeExt"]').val($(this).find(':selected').data('ext'))
            $('.leaveCaseDetail').removeAttr('name')
            $('.dateRequired').html('*')
            $('.isCommutation').slideDown()
            $('.isCTO').slideUp()
            $('.isMonetization').slideUp()
            $('.isTerminal').slideUp()

            $(`#formLeaveRequestAdd input[name="creditsTotal"]`).val('')

            const today = new Date()
            const todayStr = formatDate(today)
            const daysPrior = parseInt($(this).find(':selected').data('prior'))

            const leaveTypeID = $(this).val()

            // 
            $('.datePrior').removeAttr('min').removeAttr('max').val(todayStr)
            if (daysPrior) {
                const fiveDaysLater = new Date(today)
                fiveDaysLater.setDate(today.getDate() + daysPrior)
                const minDateStr = formatDate(fiveDaysLater)
                $('.datePrior').attr('min', minDateStr).val(minDateStr)
            }
            if (leaveTypeID == '3') $('.datePrior').attr('max', todayStr).val(todayStr)
            if (leaveTypeID == '4') $('.datePrior').attr('min', todayStr).val(todayStr)

            // 
            $(`.isVacationSlp`).slideUp()
            $(`.isSick`).slideUp()
            $(`.isBenefitsForWomen`).slideUp()
            $(`.isStudy`).slideUp()
            $(`.hasLeaveTypeDetail`).slideUp()
            $(`.hasLeaveDates`).slideUp()

            // 
            if (['1','6'].includes(leaveTypeID)) {
                $(`.isVacationSlp`).slideDown()
                $(`.isVacationSlp`).find('.leaveCaseDetail').attr('name', 'leaveCaseDetail')
            }
            if (['3'].includes(leaveTypeID)) {
                $(`.isSick`).slideDown()
                $(`.isSick`).find('.leaveCaseDetail').attr('name', 'leaveCaseDetail')
            }
            if (['11'].includes(leaveTypeID)) {
                $(`.isBenefitsForWomen`).slideDown()
                $(`.isBenefitsForWomen`).find('.leaveCaseDetail').attr('name', 'leaveCaseDetail')
            }
            if (['8'].includes(leaveTypeID)) {
                $(`.isStudy`).slideDown()
                $(`.isStudy`).find('.leaveCaseDetail').attr('name', 'leaveCaseDetail')
            }
            if (['14'].includes(leaveTypeID)) {
                $(`.isCTO`).slideDown()
            }
            if (['15', '16'].includes(leaveTypeID)) {
                $('.dateRequired').html('')
                $('.isCommutation').slideUp()
                $('.isCommutation').find('input').prop('checked', false)
                if (['15'].includes(leaveTypeID)) $(`.isMonetization`).slideDown()
                if (['16'].includes(leaveTypeID)) $(`.isTerminal`).slideDown()
            }
            if (leaveTypeID < 14) {
                $(`.hasLeaveTypeDetail`).slideDown()
                $(`.hasLeaveDates`).slideDown()
            }

        })

        $(document).on('click', '#formLeaveRequestAdd input[name="leaveCaseID"]', function() {
            $(this).closest('.row').find('.isLeaveCaseDetail').slideUp()
            if (['2', '4'].includes($(this).val())) $(this).closest('.row').find('.isLeaveCaseDetail').slideDown()
        })
        $(document).on('submit', '#formLeaveRequestAdd', function(e) {
            e.preventDefault()

            const formID = 'formLeaveRequestAdd'
            const formData = new FormData($('#'+formID).get(0))

            apiCall(`/api/{{ "$controller" }}/`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `Loading...`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        $('#modalLeaveRequestAdd').modal('hide')
                        getItems()
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
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="8">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        /** filter fields */ 
                        $(`#${formID} input[name="name"]`).val(`${res.items.filters.name}`)

                        // html = ''
                        // if (res.items.cities.length > 0) {
                        //     pName = ''
                        //     html = '<option value="">&nbsp;</option>'
                        //     for (key in res.items.cities) {
                        //         if (pName != res.items.cities[key].pName) {
                        //             if (pName != '') html += '</optgroup>'
                        //             pName = res.items.cities[key].pName
                        //             html += `<optgroup  label="${pName}">`
                        //         }
                        //         html += `<option value="${res.items.cities[key].cityID}" ${res.items.cities[key].cityID==res.items.filters.cityID?'selected':''}>${res.items.cities[key].name}</option>`
                        //     }
                        //     html += '</optgroup>'
                        // }
                        // $(`#${formID} select[name="cityID"]`).html(html)

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
                        $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="8">No Record Found</td></tr>')
                        if (res.items.records.length > 0) {

                            const statusColors = {
                                '-1'    : 'danger', 
                                '0'     : 'info', 
                                '1'     : 'primary', 
                                '2'     : 'primary', 
                                '3'     : 'success', 
                                '4'     : 'secondary', 
                            }
                            const statusNames = {
                                '-1'    : 'Disapproved', 
                                '0'     : 'Pending', 
                                '1'     : 'Checked', 
                                '2'     : 'Recommended', 
                                '3'     : 'Ready', 
                                '4'     : 'Approved', 
                            }

                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.records) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-start">${res.items.records[key].dateInserted}</td>
                                        <td class="text-start">${res.items.records[key].ltName}</td>
                                        <td class="text-start">${res.items.records[key].leaveWorkingDays}</td>
                                        <td class="text-center"><span class="badge bg-${statusColors[res.items.records[key].status]}">${statusNames[res.items.records[key].status]}</span></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-info btn-sm bView" data-id="${res.items.records[key].leaveApplicationID}">
                                                <span>
                                                    <i class="bx bx-show me-sm-1"></i> 
                                                    <span class="d-none d-sm-inline-block">View</span>
                                                </span>
                                            </button>
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

        $(document).on('click', '.bView', function() {

            const formID = 'formLeaveRequestView'

            apiCall(`/api/{{ "$controller" }}/${$(this).data('id')}`, 'GET', null, 
                // beforesend
                function() {
                    $('#dDocumentHead').slideUp()
                    $(`#${formID} input[name="recommender"]`).closest('div.row').css({'display': 'none'})
                    $(`#${formID} input[name="disapprover"]`).closest('div.row').css({'display': 'none'})
                    $(`#${formID} textarea[name="comment"]`).closest('div.row').css({'display': 'none'})
                    $(`#${formID} input[name="approvalType"]`).closest('div.row').css({'display': 'none'})
                    $(`#${formID} input[name="approvalTypeDetail"]`).closest('div.row').css({'display': 'none'})
                    $('.divBtnRecommend').html(``)
                    $('.divBtnCheck').html(``)
                    $('.divBtnApprove').html(``)
                    $('.divBtnDisapprove').html(``)
                    $('.creditsCalculations').css({'display': 'none'})
                    $('.isDetail').slideUp()
                    $('.isCto').slideUp()
                    $('.isAMount').slideUp()
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="3">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        const statusNames = {
                            '-1'    : 'Disapproved', 
                            '0'     : 'Pending', 
                            '1'     : 'Checked', 
                            '2'     : 'Recommended', 
                            '3'     : 'Ready', 
                            '4'     : 'Approved', 
                        }

                        if (res.items.row.leaveTypeID == 14) $('.isCto').slideDown()
                        if (res.items.row.leaveTypeID == 15) $('.isAMount').slideDown()
                        if (![14,15,16].includes(res.items.row.leaveTypeID)) if (res.items.row.leaveTypeDetail != '') $('.isDetail').slideDown()

                        // fields
                        $(`#${formID} input[name="leaveType"]`).val(res.items.row.leaveType)
                        $(`#${formID} textarea[name="leaveTypeDetail"]`).val(res.items.row.leaveTypeDetail)
                        $(`#${formID} input[name="dateWorked"]`).val(res.items.row.dateWorked)
                        $(`#${formID} input[name="date"]`).val(res.items.row.date)
                        $(`#${formID} input[name="leaveWorkingDays"]`).val(res.items.row.leaveWorkingDays)
                        $(`#${formID} input[name="amount"]`).val(res.items.row.amount)
                        $(`#${formID} input[name="commutation"]`).val(res.items.row.commutation)
                        $(`#${formID} input[name="creditsVacationEarned"]`).val(res.items.row.creditsVacationEarned)
                        $(`#${formID} input[name="creditsVacationLess"]`).val(res.items.row.creditsVacationLess)
                        $(`#${formID} input[name="creditsVacationBalance"]`).val(res.items.row.creditsVacationBalance)
                        $(`#${formID} input[name="creditsSickEarned"]`).val(res.items.row.creditsSickEarned)
                        $(`#${formID} input[name="creditsSickLess"]`).val(res.items.row.creditsSickLess)
                        $(`#${formID} input[name="creditsSickBalance"]`).val(res.items.row.creditsSickBalance)
                        $(`#${formID} input[name="dateInserted"]`).val(res.items.row.dateInserted)
                        $(`#${formID} textarea[name="destination"]`).val(res.items.row.destination)
                        $(`#${formID} input[name="applicant"]`).val(res.items.row.applicant)
                        $(`#${formID} input[name="recommender"]`).val(res.items.row.recommender)
                        $(`#${formID} input[name="checker"]`).val(res.items.row.checker)
                        $(`#${formID} input[name="approver"]`).val(res.items.row.approver)
                        $(`#${formID} input[name="approvalType"]`).val(res.items.row.approvalType)
                        $(`#${formID} input[name="approvalTypeDetail"]`).val(res.items.row.approvalTypeDetail)
                        $(`#${formID} input[name="disapprover"]`).val(res.items.row.disapprover)
                        $(`#${formID} textarea[name="comment"]`).val(res.items.row.comment)
                        $(`#${formID} input[name="status"]`).val(statusNames[res.items.row.status])

                        // 
                        if (res.items.row.recommender) $(`#${formID} input[name="recommender"]`).closest('div.row').slideDown()
                        if (res.items.row.disapprover) $(`#${formID} input[name="disapprover"]`).closest('div.row').slideDown()
                        if (res.items.row.disapprover) $(`#${formID} textarea[name="comment"]`).closest('div.row').slideDown()
                        if (res.items.row.approvalType) $(`#${formID} input[name="approvalType"]`).closest('div.row').slideDown()
                        if (res.items.row.approvalType) $(`#${formID} input[name="approvalTypeDetail"]`).closest('div.row').slideDown()
                        if (res.items.row.checker) $(`.creditsCalculations`).slideDown()

                        if (res.items.row.files.length > 0) {
                            $('#dDocumentHead div').html('')
                            for (key in res.items.row.files) {
                                $('#dDocumentHead div').append(`
                                    <a id="dDocument" href="${res.items.row.files[key].url}" target="_blank" download><u>${res.items.row.files[key].name}</u></a>
                                `)
                            }
                            $('#dDocumentHead').slideDown()
                        }

                        $('#modalLeaveRequestView').modal('show')

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

        $(document).ready(function() {
            resetFilterItems(`{{ "$controller" }}`)
            getItems()
        })

    </script>
@endsection