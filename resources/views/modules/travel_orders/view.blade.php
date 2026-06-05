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
                <label class="col-sm-2 col-form-label">Code</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="code" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="date" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Destination</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="destination" rows="2" style="background: #e3e3e3;" readonly ></textarea>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Purpose</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="purpose" rows="2" style="background: #e3e3e3;" readonly ></textarea>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Appropriation to which travel is charged</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="appropriation" rows="2" style="background: #e3e3e3;" readonly ></textarea>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Remarks</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="remarks" rows="2" style="background: #e3e3e3;" readonly ></textarea>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Filed By</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="applicant" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date Filed</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dateInserted" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Recommended By</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="recommender" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date Recommended</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dateRecommended" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Checked By</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="checker" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date Checked</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dateChecked" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Approved By</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="approver" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date Approved</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dateApproved" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Disapproved By</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="disapprover" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Date Disapproved</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dateDisapproved" style="background: #e3e3e3;" readonly>
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
    <div class="modal fade" id="modalPrintTravel" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalPrintTravelLabel">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form id="formPrintTravel" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalPrintTravelLabel">Print Travel Order</h1>
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

            Swal.fire({
                title               : "Confirmation!",
                text                : "Are you sure you want to check this?",
                icon                : "warning",
                showCancelButton    : true,
                confirmButtonColor  : "#3085d6",
                cancelButtonColor   : "#d33",
                confirmButtonText   : "Yes, check it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    const formData = new FormData()
                    formData.append('_method', 'PUT') 

                    apiCall(`/api/{{ "$controller" }}/{{ $id }}/check`, 'POST', formData, 
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
                    formData.append('comment', result.value) 

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

        function modalPrintTravelOrder()
        {

            $('#modalPrintTravel').modal('show')

        } 
        $(document).on('submit', '#formPrintTravel', function(e) {
            e.preventDefault()
            rowPrintTravelOrder($('#showSignatures').is(':checked')?1:0)
        })
        function rowPrintTravelOrder(showSignatures)
        {

            $('#modalPrintTravel').modal('hide')
            popupCenteredWindow(`{{ url("/$controller/print-travel-order/$id") }}?show=${showSignatures}`) 

        } 

        function getRow()
        {

            const formID = 'formView'

            apiCall(`/api/{{ "$controller/$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} input[name="recommender"]`).closest('div.row').css({'display': 'none'})
                    $(`#${formID} input[name="dateRecommended"]`).closest('div.row').css({'display': 'none'})
                    $(`#${formID} input[name="checker"]`).closest('div.row').css({'display': 'none'})
                    $(`#${formID} input[name="dateChecked"]`).closest('div.row').css({'display': 'none'})
                    $(`#${formID} input[name="approver"]`).closest('div.row').css({'display': 'none'})
                    $(`#${formID} input[name="dateApproved"]`).closest('div.row').css({'display': 'none'})
                    $(`#${formID} input[name="disapprover"]`).closest('div.row').css({'display': 'none'})
                    $(`#${formID} input[name="dateDisapproved"]`).closest('div.row').css({'display': 'none'})
                    $(`#${formID} textarea[name="comment"]`).closest('div.row').css({'display': 'none'})
                    $('.divBtnRecommend').html(``)
                    $('.divBtnCheck').html(``)
                    $('.divBtnApprove').html(``)
                    $('.divBtnDisapprove').html(``)
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="3">Loading...</td></tr>')
                    $('#dDocumentHead').slideUp()
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        /** access buttons */ 
                        if (res.items.hasButtonRecommend && res.items.row.recommender!='' && res.items.row.status==0) {
                            $('.divBtnRecommend').html(`
                                <button type="button" class="btn btn-success btn-sm ms-2" onclick="rowRecommend()">
                                    <span>
                                        <i class="bx bx-check me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Recommend</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonCheck
                            && (
                                (res.items.row.recommender=='' && res.items.row.status==0) || 
                                (res.items.row.recommender!='' && res.items.row.status==1)
                            )
                        ) {
                            $('.divBtnCheck').html(`
                                <button type="button" class="btn btn-success btn-sm ms-2" onclick="rowCheck()">
                                    <span>
                                        <i class="bx bx-check me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Check</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonApprove && res.items.row.status==2) {
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
                            (res.items.hasButtonRecommend && res.items.row.recommender!='' && res.items.row.status==0) || 
                            (res.items.hasButtonCheck
                            && (
                                (res.items.row.recommender=='' && res.items.row.status==0) || 
                                (res.items.row.recommender!='' && res.items.row.status==1)
                            )) || 
                            (res.items.hasButtonApprove && res.items.row.status==2) 
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
                        if (res.items.hasButtonAudit) { 
                            $('.divBtnAudit').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonPrint && res.items.row.dateRecommended) { 
                            $('.divBtnPrint').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="modalPrintTravelOrder()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-printer"></i> 
                                    </span>
                                </button>
                            `)
                        }

                        const statusNames = {
                            '-1'    : 'Disapproved', 
                            '0'     : 'Pending', 
                            '1'     : 'Recommended', 
                            '2'     : 'Checked', 
                            '3'     : 'Approved', 
                        }

                        // fields
                        $(`#${formID} input[name="code"]`).val(res.items.row.code)
                        $(`#${formID} input[name="date"]`).val(res.items.row.date)
                        $(`#${formID} input[name="dateInserted"]`).val(res.items.row.dateInserted)
                        $(`#${formID} textarea[name="destination"]`).val(res.items.row.destination)
                        $(`#${formID} textarea[name="purpose"]`).val(res.items.row.purpose)
                        $(`#${formID} textarea[name="appropriation"]`).val(res.items.row.appropriation)
                        $(`#${formID} textarea[name="remarks"]`).val(res.items.row.remarks)
                        $(`#${formID} input[name="applicant"]`).val(res.items.row.applicant)
                        $(`#${formID} input[name="recommender"]`).val(res.items.row.recommender)
                        $(`#${formID} input[name="dateRecommended"]`).val(res.items.row.dateRecommended)
                        $(`#${formID} input[name="checker"]`).val(res.items.row.checker)
                        $(`#${formID} input[name="dateChecked"]`).val(res.items.row.dateChecked)
                        $(`#${formID} input[name="approver"]`).val(res.items.row.approver)
                        $(`#${formID} input[name="dateApproved"]`).val(res.items.row.dateApproved)
                        $(`#${formID} input[name="disapprover"]`).val(res.items.row.disapprover)
                        $(`#${formID} input[name="dateDisapproved"]`).val(res.items.row.dateDisapproved)
                        $(`#${formID} textarea[name="comment"]`).val(res.items.row.comment)
                        $(`#${formID} input[name="status"]`).val(statusNames[res.items.row.status])

                        // 
                        if (res.items.row.dateRecommended) {
                            $(`#${formID} input[name="recommender"]`).closest('div.row').slideDown()
                            $(`#${formID} input[name="dateRecommended"]`).closest('div.row').slideDown()
                        }
                        if (res.items.row.dateChecked) {
                            $(`#${formID} input[name="checker"]`).closest('div.row').slideDown()
                            $(`#${formID} input[name="dateChecked"]`).closest('div.row').slideDown()
                        }
                        if (res.items.row.dateApproved) {
                            $(`#${formID} input[name="approver"]`).closest('div.row').slideDown()
                            $(`#${formID} input[name="dateApproved"]`).closest('div.row').slideDown()
                        }
                        if (res.items.row.dateDisapproved) {
                            $(`#${formID} input[name="disapprover"]`).closest('div.row').slideDown()
                            $(`#${formID} input[name="dateDisapproved"]`).closest('div.row').slideDown()
                            $(`#${formID} textarea[name="comment"]`).closest('div.row').slideDown()
                        }

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
