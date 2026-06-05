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
    <div class="card mb-3">
        <div class="card-header p-2 justify-content-end d-flex">
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href=`{{ url("/$controller") }}`">
                <span>
                    <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                    <span class="d-none d-sm-inline-block">Back</span>
                </span>
            </button>
            <div class="divBtnAdd"></div>
            <div class="divBtnEdit"></div>
            <div class="divBtnAudit"></div>
            <div class="mb-xl-0">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm ms-2 dropdown-toggle" type="button" id="dropdownMenuDate" data-bs-toggle="dropdown" aria-expanded="true">
                        <span class="tf-icons bx bx-printer me-1"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuDate" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(8px, 30.4px, 0px);" data-popper-placement="bottom-start">
                        <li class="print-leave-ledger-card"></li>
                    </ul>
                </div>
            </div>
        </div>
        <form id="formView" class="card-body p-2">

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Employee</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="employee" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Vacation Leave Credits</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="vacation" style="background: #e3e3e3;" readonly>
                </div>
            </div>
            
            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Sick Leave Credits</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="sick" style="background: #e3e3e3;" readonly>
                </div>
            </div>

        </form>
    </div>
    <div class="card">
        <div class="card-body p-2">

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr valign="middle">
                                    <th class="text-wrap text-center" rowspan="2">Period</th> 
                                    <th class="text-wrap text-center" rowspan="2">Particulars</th> 
                                    <th class="text-wrap text-center" colspan="4">Vacation Leave</th> 
                                    <th class="text-wrap text-center" colspan="4">Sick Leave</th> 
                                    <th class="text-wrap text-center" rowspan="2">Leave App. Date & Action</th> 
                                </tr>
                                <tr valign="middle">
                                    <th class="text-wrap text-center">Earned</th> 
                                    <th class="text-wrap text-center">Abs. UT w/ Pay</th> 
                                    <th class="text-wrap text-center">Balance</th> 
                                    <th class="text-wrap text-center">Abs. UT w/0 Pay</th> 
                                    <th class="text-wrap text-center">Earned</th> 
                                    <th class="text-wrap text-center">Abs. UT w/ Pay</th> 
                                    <th class="text-wrap text-center">Balance</th> 
                                    <th class="text-wrap text-center">Abs. UT w/0 Pay</th> 
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0" id="dRecords" style="font-size: 10pt;"></tbody>
                        </table>
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

        function printLeaveLedgerCard()
        {
            popupCenteredWindow(`{{ url("/$controller/print-leave-ledger-card/$id/") }}/`) 
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
                    $(`#dRecords`).html('<tr><td class="text-start" colspan="6">Loading...</td></tr>')
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
                        if (res.items.hasButtonEdit) {
                            $('.divBtnEdit').html(`
                                <button type="button" class="btn btn-warning btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/edit/$id/") }}'">
                                    <span>
                                        <i class="bx bx-pencil me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Edit</span>
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

                        if (res.items.hasButtonCard) {
                            $('.print-leave-ledger-card').html(`
                                <a class="dropdown-item" onclick="printLeaveLedgerCard()" href="javascript:void(0);">Leave Ledger Card</a>
                            `)
                        }

                        // fields
                        $(`#${formID} input[name="employee"]`).val(res.items.row.employee)
                        $(`#${formID} input[name="vacation"]`).val(res.items.row.vacation)
                        $(`#${formID} input[name="sick"]`).val(res.items.row.sick)

                        $(`#dRecords`).html('<tr><td class="text-start text-danger" colspan="6">No Record Found</td></tr>')
                        if (res.items.row.records.length > 0) {
                            $(`#dRecords`).html('')
                            for (key in res.items.row.records) {
                                $(`#dRecords`).append(`
                                    <tr>
                                        <td class="text-center">${res.items.row.records[key].period}</td>
                                        <td class="text-start">${res.items.row.records[key].particulars}</td>
                                        <td class="text-center">${res.items.row.records[key].vacationEarned}</td>
                                        <td class="text-center">${res.items.row.records[key].vacationUndertimeWithPay}</td>
                                        <td class="text-center">${res.items.row.records[key].vacationBalance}</td>
                                        <td class="text-center">${res.items.row.records[key].vacationUndertimeWithoutPay}</td>
                                        <td class="text-center">${res.items.row.records[key].sickEarned}</td>
                                        <td class="text-center">${res.items.row.records[key].sickUndertimeWithPay}</td>
                                        <td class="text-center">${res.items.row.records[key].sickBalance}</td>
                                        <td class="text-center">${res.items.row.records[key].sickUndertimeWithoutPay}</td>
                                        <td class="text-center">${res.items.row.records[key].remarks}</td>
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
            getRow()
        })

    </script>
@endsection
