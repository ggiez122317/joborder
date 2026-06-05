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
        <div class="card-header p-2 justify-content-end d-flex gap-2" style="flex-wrap: wrap;">
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href=`{{ url("/$controller") }}`">
                <span>
                    <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                    <span class="d-none d-sm-inline-block">Back</span>
                </span>
            </button>
            <div class="isRecordInactive divBtnLatestPage"></div>
            <div class="isRecordActive divBtnEdit"></div>
            <div class="isRecordActive divBtnReassign"></div>
            <div class="isRecordActive divBtnDemote"></div>
            <div class="isRecordActive divBtnPromote"></div>
            <div class="isRecordActive divBtnTerminate"></div>
            <div class="isRecordActive divBtnRehire"></div>
            <div class="isRecordActive divBtnDismiss"></div>
            <div class="isRecordActive divBtnNewRecord"></div>
            <div class="divBtnAudit"></div>
        </div>
        <form id="formView" class="card-body p-2">

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date Appointed</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dateAppointed" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date Dismissed</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dateDismissed" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Employee ID</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="idNumber" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Employee Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="employee" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Office</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="office" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Job Position</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="jobPosition" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Employment Type</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="type" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Salary Per Month</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="salaryMonthly" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Salary Per Annum</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="salaryYearly" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Bank Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="bankAccountName" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Bank Account Number</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="bankAccountNumber" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Remarks</label>
                <div class="col-sm-10">
                    <textarea name="remarks" rows="2" class="form-control" style="background: #e3e3e3;" readonly></textarea>
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

@endsection

@section('modals')
    <div class="modal fade" id="modalEmploymentDismiss" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalEmploymentDismissLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <form id="formEmploymentDismiss" class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalEmploymentDismissLabel">Dismiss Current Service Record</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row g-2 mb-2">
              <div class="col-12">
                <label class="form-label">Date Dismissed <span class="text-danger">*</span></label>
                <input type="date" name="dateDismissed" class="form-control" max="<?= date('Y-m-d', strtotime('+30days')) ?>" >
              </div>
              <div class="col-12">
                <label class="form-label">Type <span class="text-danger">*</span></label>
                <select name="type" id="dType" class="form-control">
                    <option value="1">Separation</option>
                    <option value="0">Others</option>
                </select>
              </div>
              <div class="col-12 dCause">
                <label class="form-label">Cause <span class="text-danger">*</span></label>
                <select name="dismissalType" class="form-control">
                    <option value="1">End of Term</option>
                    <option value="2">Optional</option>
                    <option value="3">Mandatory</option>
                    <option value="4">Death</option>
                    <option value="5">Resignation</option>
                </select>
              </div>
              <div class="col-12 dRemarks" style="display: none;">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" class="form-control" rows="2"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Dismiss</button>
          </div>
        </form>
      </div>
    </div>
@endsection

@section('scripts')
    <script>

        $(document).on('submit', '#formEmploymentDismiss', function(e) {
            e.preventDefault()

            const formID = 'formEmploymentDismiss'
            const formData = new FormData($('#'+formID).get(0))

            formData.append('_method', 'PUT') 

            apiCall(`/api/{{ "$controller" }}/dismiss/{{ $id }}/`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `<span><i class="bx bx-save me-sm-1"></i><span class="d-none d-sm-inline-block">Loading...</span></span>`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        getRow()
                        $('#modalEmploymentDismiss').modal('hide')
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

        $(document).on('change', '#dType', function() {
            if ($(this).val() == 1) {
                $('.dCause').slideDown()
                $('.dRemarks').slideUp()
            } else {
                $('.dCause').slideUp()
                $('.dRemarks').slideDown()
            }
        })

        function rowDismiss()
        {

            $('#modalEmploymentDismiss').modal('show')

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
                    $('.isRecordInactive').addClass('d-none')
                    $('.isRecordActive').addClass('d-none')
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="3">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        /** access buttons */ 
                        if (res.items.hasButtonEdit) {
                            $('.divBtnEdit').html(`
                                <button type="button" class="btn btn-warning btn-sm" onclick="window.location.href='{{ url("/$controller/edit/$id/") }}'">
                                    <span>
                                        <i class="bx bx-pencil me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Edit</span>
                                    </span>
                                </button>
                            `).closest('div').removeClass('d-none')
                        }
                        if (res.items.isLatestEmploymentRecord==1) {
                            if (!res.items.row.dateDismissed && res.items.row.status) {
                                $('.divBtnDismiss').html(`
                                    <button type="button" class="btn btn-danger btn-sm" onclick="rowDismiss()">
                                        <span>
                                            <i class="bx bx-x me-sm-1"></i> 
                                            <span class="d-none d-sm-inline-block">Dismiss</span>
                                        </span>
                                    </button>
                                `).closest('div').removeClass('d-none')
                                // if (res.items.hasButtonReassign) {
                                //     $('.divBtnReassign').html(`
                                //         <button type="button" class="btn btn-info btn-sm" onclick="window.location.href='{{ url("/$controller/reassign/$id/") }}'">
                                //             <span>
                                //                 <i class="bx bx-transfer me-sm-1"></i> 
                                //                 <span class="d-none d-sm-inline-block">Re-assign</span>
                                //             </span>
                                //         </button>
                                //     `).closest('div').removeClass('d-none')
                                // }
                                // if (res.items.hasButtonDemote) {
                                //     $('.divBtnDemote').html(`
                                //         <button type="button" class="btn btn-danger btn-sm" onclick="window.location.href='{{ url("/$controller/demote/$id/") }}'">
                                //             <span>
                                //                 <i class="bx bx-trending-down me-sm-1"></i> 
                                //                 <span class="d-none d-sm-inline-block">Demote</span>
                                //             </span>
                                //         </button>
                                //     `).closest('div').removeClass('d-none')
                                // }
                                // if (res.items.hasButtonPromote) {
                                //     $('.divBtnPromote').html(`
                                //         <button type="button" class="btn btn-success btn-sm" onclick="window.location.href='{{ url("/$controller/promote/$id/") }}'">
                                //             <span>
                                //                 <i class="bx bx-trending-up me-sm-1"></i> 
                                //                 <span class="d-none d-sm-inline-block">Promote</span>
                                //             </span>
                                //         </button>
                                //     `).closest('div').removeClass('d-none')
                                // }
                                // if (res.items.hasButtonTerminate) {
                                //     $('.divBtnTerminate').html(`
                                //         <button type="button" class="btn btn-dark btn-sm" onclick="window.location.href='{{ url("/$controller/terminate/$id/") }}'">
                                //             <span>
                                //                 <i class="bx bx-user-x me-sm-1"></i> 
                                //                 <span class="d-none d-sm-inline-block">Terminate</span>
                                //             </span>
                                //         </button>
                                //     `).closest('div').removeClass('d-none')
                                // }
                            } else {
                                $('.divBtnNewRecord').html(`
                                    <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='{{ url("/$controller/new/$id/") }}'">
                                        <span>
                                            <i class="bx bx-plus me-sm-1"></i> 
                                            <span class="d-none d-sm-inline-block">New Record</span>
                                        </span>
                                    </button>
                                `).closest('div').removeClass('d-none')
                            }
                        } else {
                            if (res.items.latestEmploymentRecordID) {
                                $('.isRecordInactive').removeClass('d-none')
                                $('.divBtnLatestPage').html(`
                                    <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='{{ url("/$controller/view/") }}/${res.items.latestEmploymentRecordID}/'">
                                        <span>
                                            <i class="bx bx-transfer-alt me-sm-1"></i> 
                                            <span class="d-sm-inline-block">Go To Latest Employment Record</span>
                                        </span>
                                    </button>
                                `)
                            }
                        }
                        
                        if (res.items.hasButtonAudit) { 
                            $('.divBtnAudit').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="auditLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }
                        
                        // fields
                        $(`#${formID} input[name="dateAppointed"]`).val(res.items.row.dateAppointed)
                        $(`#${formID} input[name="dateDismissed"]`).val(res.items.row.dateDismissed)
                        $(`#${formID} input[name="idNumber"]`).val(res.items.row.idNumber)
                        $(`#${formID} input[name="employee"]`).val(res.items.row.employee)
                        $(`#${formID} input[name="office"]`).val(res.items.row.office)
                        $(`#${formID} input[name="jobPosition"]`).val(res.items.row.jobPosition)
                        $(`#${formID} input[name="type"]`).val(res.items.row.type)
                        $(`#${formID} input[name="salaryMonthly"]`).val(res.items.row.salaryMonthly)
                        $(`#${formID} input[name="salaryYearly"]`).val(res.items.row.salaryYearly)
                        $(`#${formID} input[name="bankAccountName"]`).val(res.items.row.bankAccountName)
                        $(`#${formID} input[name="bankAccountNumber"]`).val(res.items.row.bankAccountNumber)
                        $(`#${formID} textarea[name="remarks"]`).val(res.items.row.remarks)
                        $(`#${formID} input[name="status"]`).val(res.items.row.status?'Active':'Inactive')

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
