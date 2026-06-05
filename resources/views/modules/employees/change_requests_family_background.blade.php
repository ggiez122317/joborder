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
        <div class="card-header p-2 justify-content-end d-flex gap-2">
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href=`{{ url("/$controller/view/$id/") }}`">
                <span>
                    <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                    <span class="d-none d-sm-inline-block">Back</span>
                </span>
            </button>
        </div>
        <form id="formIndex" method="get"> 
            <div class="table-responsive text-nowrap">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center"><span>Date Inserted</span></th>
                            <th class="text-center"><span>Date Cancelled</span></th>
                            <th class="text-center"><span>Date Approved</span></th>
                            <th class="text-center"><span>Date Denied</span></th>
                            <th class="text-start"><span>Remarks</span></th>
                            <th class="text-center"><span>Status</span></th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <tr><td class="text-start" colspan="7">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="modalViewChangeRequest" tabindex="-1" data-bs-focus="false" aria-labelledby="modalViewChangeRequestLabel">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formViewChangeRequest" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalViewChangeRequestLabel">Change Request Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-start"><span>Label</span></th>
                                            <th class="text-start"><span>Old Value</span></th>
                                            <th class="text-start"><span>New Value</span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <tr><td class="text-start" colspan="3">Loading...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <div id="isPending" class="d-flex gap-2"></div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        $(document).on('click', '#btnDeny', function() {

            const id = $(this).data('id')

            Swal.fire({
                title               : "Deny?",
                input               : "text",
                icon                : "warning",
                inputAttributes     : {
                    autocapitalize: "off"
                },
                inputPlaceholder    : "Deny remarks here...",
                showCancelButton    : true,
                confirmButtonColor  : "#3085d6",
                cancelButtonColor   : "#d33",
                confirmButtonText   : "Yes, deny this!", 
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    const formData = new FormData()

                    formData.append('_method', 'PUT') 
                    formData.append('remarks', result.value) 
                    formData.append('status', -1) 

                    apiCall(`/api/{{ "$controller" }}/changes-family-background/${id}/`, 'POST', formData, 
                        // beforesend
                        function() {
                            btnLoading(`#btnDeny`, `Loading...`)
                            btnLoading(`#btnApprove`, `Loading...`)
                        }, 
                        // done
                        function(res) {

                            if (res.status == 200) {
                                $('#modalViewChangeRequest').modal('hide')
                                getItems()
                            } else if (res.status == 401 && res.message == 'Invalid token') {
                                authenticationLogout()
                            } else {
                                Toast.fire({ icon : "warning", title : res.name, html : res.message })
                            }

                        }, 
                        // always
                        function() {
                            btnLoading(`#btnDeny`, `Deny`, 0)
                            btnLoading(`#btnApprove`, `Approve`, 0)
                        }, 
                        localStorage.getItem('t') 
                    )                    
                    
                }
            })

        })

        $(document).on('click', '#btnApprove', function() {

            const id = $(this).data('id')
            const formData = new FormData()

            formData.append('_method', 'PUT') 
            formData.append('remarks', "") 
            formData.append('status', 1) 

            apiCall(`/api/{{ "$controller" }}/changes-family-background/${id}/`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#btnDeny`, `Loading...`)
                    btnLoading(`#btnApprove`, `Loading...`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        $('#modalViewChangeRequest').modal('hide')
                        getItems()
                    } else if (res.status == 401 && res.message == 'Invalid token') {
                        authenticationLogout()
                    } else {
                        Toast.fire({ icon : "warning", title : res.name, html : res.message })
                    }

                }, 
                // always
                function() {
                    btnLoading(`#btnDeny`, `Deny`, 0)
                    btnLoading(`#btnApprove`, `Approve`, 0)
                }, 
                localStorage.getItem('t') 
            ) 

        })

        function getRow(userPdsChangeRequestID)
        {

            const formID = 'formViewChangeRequest'
            apiCall(`/api/{{ "$controller" }}/changes-family-background/${userPdsChangeRequestID}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="3">Loading...</td></tr>')
                    $('#isPending').html(``)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        
                        if (res.items.isPending == 1) {
                            $('#isPending').html(`
                                <button type="button" id="btnDeny" data-id="${res.items.id}" class="btn btn-sm btn-danger">Deny</button>
                                <button type="submit" id="btnApprove" data-id="${res.items.id}" class="btn btn-sm btn-success">Approve</button>
                            `)
                        }

                        $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="6">No Record Found</td></tr>')
                        if (res.items.records.length > 0) {

                            const actionBadges  = {
                                '-1'    : '<span class="badge p-1 bg-danger">Delete</span>', 
                               '0'      : '', 
                                '1'     : '<span class="badge p-1 bg-success">New</span>', 
                            }

                            const statusNames   = ['Denied', 'Pending', 'Approved']
                            const status_colors = ['danger', 'info', 'success']

                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.records) {
                                valueOld = res.items.records[key].valueOld
                                valueNew = res.items.records[key].valueNew
                                if (res.items.records[key].field == 'picExt') {
                                    if (valueOld) valueOld = `<a href="<?= asset('uploads/users/changes/') ?>/${valueOld}" target="_blank">${valueOld}</a>`
                                    if (valueNew) valueNew = `<a href="<?= asset('uploads/users/changes/') ?>/${valueNew}" target="_blank">${valueNew}</a>`
                                }
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-start text-wrap" style="min-width: 180px;">${res.items.records[key].label} ${actionBadges[res.items.action]}</td>
                                        <td class="text-start">${valueOld}</td>
                                        <td class="text-start">${valueNew}</td>
                                    </tr>
                                `)
                            }
                        }

                        $('#modalViewChangeRequest').modal('show')

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

        function getItems()
        {

            const formID = 'formIndex'
            apiCall(`/api/{{ "$controller" }}/changes-family-backgrounds/{{ $id }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="7">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        // body
                        $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="7">No Record Found</td></tr>')
                        if (res.items.records.length > 0) {

                            const statusNames   = ['Cancelled', 'Denied', 'Pending', 'Approved', 'Administered']
                            const status_colors = ['secondary', 'danger', 'info', 'success', 'primary']

                            $(`#${formID} table tbody`).html('')
                            for (key in res.items.records) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-center">${res.items.records[key].dateInserted}</td>
                                        <td class="text-center">${res.items.records[key].dateCancelled}</td>
                                        <td class="text-center">${res.items.records[key].dateApproved}</td>
                                        <td class="text-center">${res.items.records[key].dateDenied}</td>
                                        <td class="text-start">${res.items.records[key].remarks}</td>
                                        <td class="text-center"><span class="badge bg-${status_colors[res.items.records[key].status+2]}">${statusNames[res.items.records[key].status+2]}</span></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-info btn-sm" onclick="getRow(${res.items.records[key].userPdsChangeRequestID})">
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

        $(document).ready(function() {
            getItems()
        }) 

    </script>
@endsection
