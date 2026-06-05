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
            <div class="divBtnRecommend"></div>
            <div class="divBtnCheck"></div>
            <div class="divBtnApprove"></div>
            <div class="divBtnDisapprove"></div>
            <div class="divBtnAudit"></div>
            <div class="divBtnPrint"></div>
        </div>
        <form id="formView" class="card-body p-2">

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Type</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="leaveType" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2 hasDetail" style="display: none;">
                <label class="col-sm-2 col-form-label">Details</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="leaveTypeDetail" rows="2" style="background: #e3e3e3;" readonly></textarea>
                </div>
            </div>

            <div class="row mb-2 isCto" style="display: none;">
                <label class="col-sm-2 col-form-label">Actual Work Date</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dateWorked" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Leave Date</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="date" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Leave Working Days</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="leaveWorkingDays" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2 isAmount" style="display: none;">
                <label class="col-sm-2 col-form-label">Monetized Amount</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="amount" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2" style="display: none;">
                <label class="col-sm-2 col-form-label">Commutation</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="commutation" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="creditsCalculations">
                <div class="row mb-2">
                    <label class="col-sm-2 col-form-label">VL Credits Calculation</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="creditsVacation" style="background: #e3e3e3;" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-2 col-form-label">SL Credits Calculation</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="creditsSick" style="background: #e3e3e3;" readonly>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date Filed</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dateInserted" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Filed By</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="applicant" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Recommended By</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="recommender" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Checked By</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="checker" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Approved By</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="approver" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Approval Type</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="approvalType" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Approval Type Detail</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="approvalTypeDetail" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Disapproved By</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="disapprover" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Disapprove Comment</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="comment" rows="2" style="background: #e3e3e3;" readonly ></textarea>
                </div>
            </div>

            <div class="row mb-2" id="dDocumentHead">
                <label class="col-sm-2 col-form-label">Attachment(s)</label>
                <div class="col-sm-10 d-flex gap-3"></div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="status" style="background: #e3e3e3;" readonly>
                </div>
            </div>

        </form>
    </div>

@endsection

@section('modals')
    <div class="modal fade" id="modalLeaveCheck" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalLeaveCheckLabel">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formLeaveCheck" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalLeaveCheckLabel">Check Leave Request Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row editableApplicationDate">
                        <div class="col-6 mb-2">
                            <label class="form-label">Leave Date From <span class="text-danger dateRequired">*</span></label>
                            <input type="date" name="dateFrom" value="<?= date('Y-m-d') ?>" class="form-control">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Leave Date To <span class="text-danger dateRequired">*</span></label>
                            <input type="date" name="dateTo" value="<?= date('Y-m-d') ?>" class="form-control">
                        </div>
                    </div>
                    <div class="row editableApplicationDate">
                        <div class="col-12">
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
                    <div class="row">
                        <div class="col-12 col-md-8 mb-2">
                            <label class="form-label">As of <span class="text-danger">*</span></label>
                            <input type="text" name="creditsStatusAsOfMonth" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4 mb-2">
                            <label class="form-label">VL Earned</label>
                            <input type="text" name="creditsVacationEarned" class="form-control" style="background: #e3e3e3;" readonly>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <label class="form-label">VL Less</label>
                            <input type="text" name="creditsVacationLess" class="form-control" style="background: #e3e3e3;" readonly>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <label class="form-label">VL Estimated Balance</label>
                            <input type="text" name="creditsVacationBalance" class="form-control" style="background: #e3e3e3;" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4 mb-2">
                            <label class="form-label">SL Earned</label>
                            <input type="text" name="creditsSickEarned" class="form-control" style="background: #e3e3e3;" readonly>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <label class="form-label">SL Less</label>
                            <input type="text" name="creditsSickLess" class="form-control" style="background: #e3e3e3;" readonly>
                        </div>
                        <div class="col-12 col-md-4 mb-2">
                            <label class="form-label">SL Estimated Balance</label>
                            <input type="text" name="creditsSickBalance" class="form-control" style="background: #e3e3e3;" readonly>
                        </div>
                    </div>
                    <div class="row isInclusive" style="display: none;">
                        <div class="col-12 mb-2">
                            <label class="form-label">Inclusive Dates <span class="text-danger">*</span></label>
                            <input type="text" name="datesInclusive" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 mb-2">
                            <div class="form-check mt-3">
                                <input class="form-check-input" name="addToLeaveLedger" type="checkbox" value="1" id="addToLeaveLedger" checked>
                                <label class="form-check-label" for="addToLeaveLedger">Add to leave ledger when approved</label>
                            </div>
                        </div>
                    </div>
                    <div class="row isLedgerRecord">
                        <div class="col-12 col-md-4">
                            <h5 class="card-header">Ledger Record</h5>
                        </div>
                    </div>
                    <div class="row isLedgerRecord">
                        <div class="col-12 col-sm-6 mb-2">
                            <label class="form-label">Period <span class="text-danger">*</span></label>
                            <input type="text" name="period" class="form-control">
                        </div>
                        <div class="col-12 col-sm-6 mb-2">
                            <label class="form-label">Particulars <span class="text-danger">*</span></label>
                            <input type="text" name="particulars" class="form-control">
                        </div>
                    </div>
                    <div class="row isLedgerRecord">
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label"><b>VL</b> Absence Undertime <b>with Pay</b></label>
                            <input type="number" name="vacationWithPay" class="form-control dNumberNoArrow" min="0" step="0.001" placeholder="0.000">
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label"><b>VL</b> Absence Undertime <b>without Pay</b></label>
                            <input type="number" name="vacationWithoutPay" class="form-control dNumberNoArrow" min="0" step="0.001" placeholder="0.000">
                        </div>
                    </div>
                    <div class="row isLedgerRecord">
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label"><b>SL</b> Absence Undertime <b>with Pay</b></label>
                            <input type="number" name="sickWithPay" class="form-control dNumberNoArrow" min="0" step="0.001" placeholder="0.000">
                        </div>
                        <div class="col-12 col-md-6 mb-2">
                            <label class="form-label"><b>SL</b> Absence Undertime <b>without Pay</b></label>
                            <input type="number" name="sickWithoutPay" class="form-control dNumberNoArrow" min="0" step="0.001" placeholder="0.000">
                        </div>
                    </div>
                    <div class="row isLedgerRecord">
                        <div class="col-12 mb-2">
                            <label class="form-label">Leave App. Date & Action</label>
                            <input type="text" name="remarks" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save as Checked</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modalPrintLeave" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalPrintLeaveLabel">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form id="formPrintLeave" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalPrintLeaveLabel">Print Leave Application</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="showSignatures">
                                <label class="form-check-label" for="showSignatures">Show Signatures</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Print</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        function rowRecommend()
        {

            Swal.fire({
                title               : "Confirmation!",
                text                : "Are you sure you want to recommend this?",
                icon                : "warning",
                showCancelButton    : true,
                confirmButtonColor  : "#3085d6",
                cancelButtonColor   : "#d33",
                confirmButtonText   : "Yes, recommend it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    const formData = new FormData()
                    formData.append('_method', 'PUT') 

                    apiCall(`/api/{{ "$controller" }}/{{ $id }}/recommend`, 'POST', formData, 
                        // beforesend
                        function() {}, 
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
                        function() {}, 
                        localStorage.getItem('t') 
                    )

                }
            })

        } 

        function rowCheck()
        {

            const formID = 'formLeaveCheck'
            apiCall(`/api/{{ "$controller" }}/{{ $id }}/page-check/`, 'GET', null, 
                // beforesend
                function() {
                    $('.isLedgerRecord').slideDown()
                    $('.isInclusive').slideUp()
                    $('.editableApplicationDate').slideUp()
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        $(`#${formID} input[name="creditsVacationEarned"]`).val(res.items.creditsVacationEarned)
                        $(`#${formID} input[name="creditsVacationLess"]`).val(res.items.creditsVacationLess)
                        $(`#${formID} input[name="creditsVacationBalance"]`).val(res.items.creditsVacationBalance)
                        $(`#${formID} input[name="creditsSickEarned"]`).val(res.items.creditsSickEarned)
                        $(`#${formID} input[name="creditsSickLess"]`).val(res.items.creditsSickLess)
                        $(`#${formID} input[name="creditsSickBalance"]`).val(res.items.creditsSickBalance)
                        $(`#${formID} input[name="datesInclusive"]`).val(res.items.datesInclusive)
                        $(`#${formID} input[name="period"]`).val(res.items.period)
                        $(`#${formID} input[name="particulars"]`).val(res.items.particulars)
                        $(`#${formID} input[name="vacationWithPay"]`).val(res.items.vacationWithPay)
                        $(`#${formID} input[name="vacationWithoutPay"]`).val(res.items.vacationWithoutPay)
                        $(`#${formID} input[name="sickWithPay"]`).val(res.items.sickWithPay)
                        $(`#${formID} input[name="sickWithoutPay"]`).val(res.items.sickWithoutPay)
                        $(`#${formID} input[name="remarks"]`).val(res.items.remarks)

                        $(`#${formID} input[name="dateFrom"]`).val(res.items.dateFrom)
                        $(`#${formID} input[name="dateTo"]`).val(res.items.dateTo)
                        $(`#${formID} input[name="leaveDays"]`).val(res.items.leaveDays)
                        $(`#${formID} input[name="leaveHours"]`).val(res.items.leaveHours)
                        $(`#${formID} input[name="leaveMinutes"]`).val(res.items.leaveMinutes)

                        if (res.items.datesInclusive != ' ') $('.isInclusive').slideDown()
                        if (res.items.editableApplicationDate) $('.editableApplicationDate').slideDown()

                        $('#modalLeaveCheck').modal('show') 

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

        $(document).on('change', '#addToLeaveLedger', function() {
            $('.isLedgerRecord').slideUp()
            if ($(this).is(':checked')) $('.isLedgerRecord').slideDown()
        })
        $(document).on('submit', '#formLeaveCheck', function(e) {
            e.preventDefault()

            const formID = 'formLeaveCheck'
            const formData = new FormData($('#'+formID).get(0))
            formData.append('_method', 'PUT') 

            apiCall(`/api/{{ "$controller" }}/{{ $id }}/check`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `Loading...`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        $('#modalLeaveCheck').modal('hide')
                        getRow()
                    } else if (res.status == 401 && res.message == 'Invalid token') {
                        authenticationLogout()
                    } else {
                        Toast.fire({ icon : "warning", title : res.name, html : res.message })
                    }

                }, 
                // always
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `Save as Checked`, 0)
                }, 
                localStorage.getItem('t') 
            )
        })

        function rowApprove()
        {

            Swal.fire({
                title               : "Confirmation!",
                text                : "Are you sure you want to approve this?",
                icon                : "warning",
                showCancelButton    : true,
                confirmButtonColor  : "#3085d6",
                cancelButtonColor   : "#d33",
                confirmButtonText   : "Yes, approve it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    const formData = new FormData()
                    formData.append('_method', 'PUT') 

                    apiCall(`/api/{{ "$controller" }}/{{ $id }}/approve`, 'POST', formData, 
                        // beforesend
                        function() {}, 
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
                        function() {}, 
                        localStorage.getItem('t') 
                    )

                }
            })

        } 

        function rowDisapprove()
        {

            Swal.fire({
                title: "Disapprove?",
                icon: "warning",
                input: 'textarea',
                inputPlaceholder: 'Type your comment here...',
                inputAttributes: {
                    'aria-label': 'Type your comment here'
                },
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Submit"
            }).then((result) => {
                if (result.isConfirmed) {

                    const formData = new FormData()

                    formData.append('_method', 'PUT') 
                    formData.append('disapproveRemarks', result.value) 

                    apiCall(`/api/{{ "$controller" }}/{{ $id }}/disapprove`, 'POST', formData, 
                        // beforesend
                        function() {}, 
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
                        function() {}, 
                        localStorage.getItem('t') 
                    )

                }
            })

        } 

        function auditLogs()
        {

            popupCenteredWindow(`{{ url("/$controller/audit/$id/") }}`) 

        } 

        function modalLeaveApplication()
        {

            $('#modalPrintLeave').modal('show')

        } 
        $(document).on('submit', '#formPrintLeave', function(e) {
            e.preventDefault()
            rowLeaveApplication($('#showSignatures').is(':checked')?1:0)
        })
        function rowLeaveApplication(showSignatures)
        {

            $('#modalPrintLeave').modal('hide')
            popupCenteredWindow(`{{ url("/$controller/print-leave-application/$id") }}?show=${showSignatures}`) 

        } 

        function getRow()
        {

            const formID = 'formView'

            apiCall(`/api/{{ "$controller/$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} textarea[name="leaveTypeDetail"]`).closest('div.row').css({'display': 'none'})
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
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="3">Loading...</td></tr>')
                    $('#dDocumentHead').slideUp()
                    $('.hasDetail').slideUp()
                    $('.isCto').slideUp()
                    $('.isAmount').slideUp()
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        /** access buttons */ 
                        if (res.items.hasButtonRecommend && res.items.row.recommender!='' && [0,1].includes(res.items.row.status)) {
                            $('.divBtnRecommend').html(`
                                <button type="button" class="btn btn-success btn-sm ms-2" onclick="rowRecommend()">
                                    <span>
                                        <i class="bx bx-check me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Recommend</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonCheck) {
                            $('.divBtnCheck').html(`
                                <button type="button" class="btn btn-success btn-sm ms-2" onclick="rowCheck()">
                                    <span>
                                        <i class="bx bx-check me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Check</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonApprove) {
                            $('.divBtnApprove').html(`
                                <button type="button" class="btn btn-success btn-sm ms-2" onclick="rowApprove()">
                                    <span>
                                        <i class="bx bx-check me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Approve</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (
                            res.items.hasButtonRecommend || 
                            res.items.hasButtonCheck || 
                            res.items.hasButtonApprove
                        ) { 
                            $('.divBtnDisapprove').html(`
                                <button type="button" class="btn btn-danger btn-sm ms-2" onclick="rowDisapprove()">
                                    <span>
                                        <i class="bx bx-x me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Disapprove</span>
                                    </span>
                                </button>
                            `)
                        }
                        // if (res.items.hasButtonAudit) { 
                        //     $('.divBtnAudit').html(`
                        //         <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                        //             <span>
                        //                 <i class="bx bx-notepad"></i> 
                        //             </span>
                        //         </button>
                        //     `)
                        // }
                        if (res.items.hasButtonPrint) { 
                            $('.divBtnPrint').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="modalLeaveApplication()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Print">
                                    <span>
                                        <i class="bx bx-printer"></i> 
                                    </span>
                                </button>
                            `)
                        }

                        const statusNames = {
                            '-1'    : 'Disapproved', 
                            '0'     : 'Pending', 
                            '1'     : 'Checked', 
                            '2'     : 'Recommended', 
                            '3'     : 'Ready', 
                            '4'     : 'Approved', 
                        }
                        const approvalTypes = {
                            '1'     : 'Days with pay', 
                            '2'     : 'Days without pay', 
                            '3'     : 'Others', 
                        }

                        if (res.items.row.leaveTypeID == 14) $('.isCto').slideDown()
                        if (res.items.row.leaveTypeID == 15) $('.isAmount').slideDown()
                        if (res.items.row.leaveTypeDetail != '') $('.hasDetail').slideDown()

                        // fields
                        $(`#${formID} input[name="leaveType"]`).val(res.items.row.leaveType)
                        $(`#${formID} textarea[name="leaveTypeDetail"]`).val(res.items.row.leaveTypeDetail)
                        $(`#${formID} input[name="dateWorked"]`).val(res.items.row.dateWorked)
                        $(`#${formID} input[name="date"]`).val(res.items.row.date)
                        $(`#${formID} input[name="leaveWorkingDays"]`).val(res.items.row.leaveWorkingDays)
                        $(`#${formID} input[name="amount"]`).val(res.items.row.amount)
                        $(`#${formID} input[name="commutation"]`).val(res.items.row.commutation)
                        $(`#${formID} input[name="creditsVacation"]`).val(res.items.row.creditsVacation)
                        $(`#${formID} input[name="creditsSick"]`).val(res.items.row.creditsSick)
                        $(`#${formID} input[name="dateInserted"]`).val(res.items.row.dateInserted)
                        $(`#${formID} textarea[name="destination"]`).val(res.items.row.destination)
                        $(`#${formID} input[name="applicant"]`).val(res.items.row.applicant)
                        $(`#${formID} input[name="recommender"]`).val(res.items.row.recommender)
                        $(`#${formID} input[name="checker"]`).val(res.items.row.checker)
                        $(`#${formID} input[name="approver"]`).val(res.items.row.approver)
                        $(`#${formID} input[name="approvalType"]`).val(res.items.row.approvalType ? approvalTypes[res.items.row.approvalType] : '')
                        $(`#${formID} input[name="approvalTypeDetail"]`).val(res.items.row.approvalTypeDetail)
                        $(`#${formID} input[name="disapprover"]`).val(res.items.row.disapprover)
                        $(`#${formID} textarea[name="comment"]`).val(res.items.row.comment)
                        $(`#${formID} input[name="status"]`).val(statusNames[res.items.row.status])

                        // 
                        if (res.items.row.recommender) $(`#${formID} input[name="recommender"]`).closest('div.row').slideDown()
                        if (res.items.row.disapprover) $(`#${formID} input[name="disapprover"]`).closest('div.row').slideDown()
                        if (res.items.row.disapprover) $(`#${formID} textarea[name="comment"]`).closest('div.row').slideDown()
                        if (res.items.row.leaveTypeDetail) $(`#${formID} textarea[name="leaveTypeDetail"]`).closest('div.row').slideDown()
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

                        authenticationUserNotifications()

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
