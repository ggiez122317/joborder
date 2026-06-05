@extends('layouts.log')

@section('title', $title)

@section('content')
    <div class="container-fluid mt-1">
        <div class="row mt-3">
            <div class="col-12 mb-3">
                <h5 class="p-1 mb-0">
                    <i class="fa-solid fa-clipboard-list"></i> Audit Logs</span>
                </h5>
                <div class="card">
                    <div class="card-body px-1 py-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm mb-1">
                                <thead>
                                    <tr style="font-weight: bold;">
                                        <td class="text-start" nowrap>Date & Time</td>
                                        <td class="text-start" nowrap>Ip Address</td>
                                        <td class="text-start" nowrap>Device Info</td>
                                        <td class="text-start" nowrap>User</td>
                                        <td class="text-start" nowrap>Module</td>
                                        <td class="text-start" nowrap>Action</td>
                                        <td class="text-start" nowrap>Remarks</td>
                                    </tr>
                                </thead>
                                <tbody id="tblAuditLog">
                                    <tr><td colspan="7" class="text-start">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h5 class="p-1 mb-0">
                    <i class="fa-solid fa-clipboard-list"></i> Audit Log Details</span>
                </h5>
                <div class="card">
                    <div class="card-body px-1 py-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm mb-1">
                                <thead>
                                    <tr style="font-weight: bold;">
                                        <td class="text-start" nowrap>Date & Time</td>
                                        <td class="text-start" nowrap>Ip Address</td>
                                        <td class="text-start" nowrap>User</td>
                                        <td class="text-start" nowrap>Action</td>
                                        <td class="text-start" nowrap>Field</td>
                                        <td class="text-start" nowrap>Old Value</td>
                                        <td class="text-start" nowrap>New Value</td>
                                    </tr>
                                </thead>
                                <tbody id="tblAuditLogDetail">
                                    <tr><td colspan="7" class="text-start">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
@endsection

@section('scripts')
    <script>

        function getAudit()
        {

            apiCall(`/api/{{ "$controller/page-audit/$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $('#tblAuditLog').html(`<tr><td colspan="7" class="text-start">Loading...</td></tr>`)
                    $('#tblAuditLogDetail').html(`<tr><td colspan="7" class="text-start">Loading...</td></tr>`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        // log
                        $('#tblAuditLog').html(`<tr><td colspan="7" class="text-center text-danger"><i>No logs found!</i></td></tr>`)
                        if (res.items.AuditLogs.length > 0) {
                            $('#tblAuditLog').html(``)
                            for (key in res.items.AuditLogs) {
                                $('#tblAuditLog').append(`
                                    <tr>
                                        <td class="text-start">${res.items.AuditLogs[key].date}</td>
                                        <td class="text-start">${res.items.AuditLogs[key].ipAddress}</td>
                                        <td class="text-start">${res.items.AuditLogs[key].deviceInfo}</td>
                                        <td class="text-start">${res.items.AuditLogs[key].user}</td>
                                        <td class="text-start">${res.items.AuditLogs[key].module}</td>
                                        <td class="text-start">${res.items.AuditLogs[key].action}</td>
                                        <td class="text-start">${res.items.AuditLogs[key].remarks}</td>
                                    </tr>
                                `)
                            }
                        }
                        
                        // log details 
                        $('#tblAuditLogDetail').html(`<tr><td colspan="7" class="text-center text-danger"><i>No log details found!</i></td></tr>`)
                        if (res.items.AuditLogDetails.length > 0) {

                            $('#tblAuditLogDetail').html(``)
                            for (key in res.items.AuditLogDetails) {

                                valueOld = res.items.AuditLogDetails[key].valueOld
                                valueNew = res.items.AuditLogDetails[key].valueNew

                                if (res.items.AuditLogDetails[key].field == 'Status') {
                                    if (valueOld != '') valueOld = res.items.statuses[parseInt(valueOld)+1]
                                    if (valueNew != '') valueNew = res.items.statuses[parseInt(valueNew)+1]
                                }

                                $('#tblAuditLogDetail').append(`
                                    <tr>
                                        <td class="text-start">${res.items.AuditLogDetails[key].date}</td>
                                        <td class="text-start">${res.items.AuditLogDetails[key].ipAddress}</td>
                                        <td class="text-start">${res.items.AuditLogDetails[key].user}</td>
                                        <td class="text-start">${res.items.AuditLogDetails[key].action}</td>
                                        <td class="text-start">${res.items.AuditLogDetails[key].field}</td>
                                        <td class="text-start">${valueOld}</td>
                                        <td class="text-start">${valueNew}</td>
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
            getAudit()
        })

    </script>
@endsection
