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
    <form id="formAdd">
        <div class="card mb-3">
            <div class="card-header p-2 justify-content-end d-flex">
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href=`{{ url("/$controller") }}`">
                    <span>
                        <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                        <span class="d-none d-sm-inline-block">Back</span>
                    </span>
                </button>
                <div class="divBtnAdd"></div>
            </div>
            <div class="card-body p-2">
    
                <div class="row g-2 mb-2">
                    <div class="alert alert-info mb-0" role="alert">Note: Once submitted, you can only edit logs in <b>Attendances Module</b>.</div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Office <span class="text-danger">*</span></label>
                        <select name="officeID" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">File (from ZKTeco biometric scanner) <span class="text-danger">*</span></label>
                        <input type="file" name="fileUpload" class="form-control" accept=".dat">
                    </div>
                </div>
    
            </div>
        </div>
        <div class="card dCard">
            <ul class="nav nav-tabs tabs-line" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-tabs-line-card-active" aria-controls="navs-tabs-line-card-active" aria-selected="true">
                        Attendance Date
                    </button>
                </li>
            </ul>
            <div class="tab-content border-top p-2">
                <div class="tab-pane fade show active" id="navs-tabs-line-card-active" role="tabpanel">
                    <p class="card-text text-danger">No log found. Please upload .dat file.</p>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('modals')

@endsection

@section('scripts')
    <script>

        function generateLogs(data)
        {
            
            html = '<tr><td class="text-start text-danger" colspan="6">No Record Found</td></tr>'

            curDate = ''

            $(`#formAdd ul`).html('')
            $(`#formAdd .tab-content`).html('')
            if (data.length > 0) {
                html = ''
                for (key in data) {

                    if (curDate != data[key].date) {
                        $(`#formAdd ul`).append(`
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link ${curDate==''?'active':''}" role="tab" data-bs-toggle="tab" data-bs-target="#navs-tabs-${data[key].date}" aria-controls="navs-tabs-${data[key].date}" aria-selected="${curDate==''?'true':'false'}" tabindex="${curDate==''?'':'-1'}">
                                    ${data[key].dateFormat}
                                </button>
                            </li>
                        `)
                        $(`#formAdd .tab-content`).append(`
                            <div class="tab-pane fade ${curDate==''?'show active':''}" id="navs-tabs-${data[key].date}" role="tabpanel">
                                <div class="table-responsive text-nowrap">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-start">Employee</th> 
                                                <th class="text-center">AM Arrival</th> 
                                                <th class="text-center">AM Departure</th> 
                                                <th class="text-center">PM Arrival</th> 
                                                <th class="text-center">PM Departure</th> 
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0 tbody-${data[key].date}">
                                            <tr><td class="text-start text-danger" colspan="6">No Record Found</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `)
                        if (curDate != '') {
                            $(`#formAdd table tbody.tbody-${curDate}`).html(html)
                            html = ''
                        }
                        curDate = data[key].date
                    } 

                    html += `
                        <tr>
                            <td class="text-start ${!data[key].name?'bg-danger text-white':''}">
                                ${data[key].bioID} - ${data[key].name}
                                <input type="hidden" class="form-control" name="userIDs[]" value="${data[key].userID}" />
                                <input type="hidden" class="form-control" name="dates[]" value="${data[key].date}" />
                            </td>
                            <td class="text-center">
                                <input type="time" class="form-control text-center ${!data[key].amIn?'border-danger':''}" name="amIns[]" value="${data[key].amIn}" />
                            </td>
                            <td class="text-center">
                                <input type="time" class="form-control text-center ${!data[key].amOut?'border-danger':''}" name="amOuts[]" value="${data[key].amOut}" />
                            </td>
                            <td class="text-center">
                                <input type="time" class="form-control text-center ${!data[key].pmIn?'border-danger':''}" name="pmIns[]" value="${data[key].pmIn}" />
                            </td>
                            <td class="text-center">
                                <input type="time" class="form-control text-center ${!data[key].pmOut?'border-danger':''}" name="pmOuts[]" value="${data[key].pmOut}" />
                            </td>
                        </tr>
                    `
                }
                $(`#formAdd table tbody.tbody-${curDate}`).html(html)
            }

        } 

        $(document).on('click', '#formAdd input[name="fileUpload"]', function(e) {
            $(`#formAdd .dCard`).html(`
                <ul class="nav nav-tabs tabs-line" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-tabs-line-card-active" aria-controls="navs-tabs-line-card-active" aria-selected="true">
                            Attendance Date
                        </button>
                    </li>
                </ul>
                <div class="tab-content border-top p-2">
                    <div class="tab-pane fade show active" id="navs-tabs-line-card-active" role="tabpanel">
                        <p class="card-text text-danger">No log found. Please upload .dat file.</p>
                    </div>
                </div>
            `)
        }) 

        $(document).on('change', '#formAdd input[name="fileUpload"]', function(e) {
            e.preventDefault()

            const formID = 'formAdd'
            const formData = new FormData($('#'+formID).get(0))
            
            apiCall(`/api/{{ "$controller" }}/get-logs`, 'POST', formData, 
                // beforesend
                function() {
                    $(`#${formID} ul`).html(`
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-tabs-line-card-active" aria-controls="navs-tabs-line-card-active" aria-selected="true">
                                Attendance Date
                            </button>
                        </li>
                    `)
                    $(`#${formID} .tab-content`).html(`
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-active" role="tabpanel">
                            <p class="card-text">Loading data...</p>
                        </div>
                    `)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        if (res.items.logs.length > 0) {
                            generateLogs(res.items.logs)
                        } else {
                            $(`#${formID} .tab-content`).html(`
                                <div class="tab-pane fade show active" id="navs-tabs-line-card-active" role="tabpanel">
                                    <p class="card-text text-danger">No log found. Please upload valid .dat file.</p>
                                </div>
                            `)
                        }
                    } else if (res.status == 401 && res.message == 'Invalid token') {
                        authenticationLogout()
                    } else {
                        Toast.fire({ icon : "warning", title : res.name, html : res.message })
                        setTimeout(() => {
                            $(`#${formID} .tab-content`).html(`
                                <div class="tab-pane fade show active" id="navs-tabs-line-card-active" role="tabpanel">
                                    <p class="card-text text-danger">No log found. Please upload valid .dat file.</p>
                                </div>
                            `)
                        }, 400)
                    }

                }, 
                // always
                function() {}, 
                localStorage.getItem('t') 
            )
        }) 

        $(document).on('submit', '#formAdd', function(e) {
            e.preventDefault()

            const formID = 'formAdd'
            const formData = new FormData($('#'+formID).get(0))

            apiCall(`/api/{{ "$controller" }}/`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `<span><i class="bx bx-save me-sm-1"></i><span class="d-none d-sm-inline-block">Loading...</span></span>`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        window.location.href=`{{ url("/$controller/view") }}/${res.items.id}/`
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

        function getRow()
        {


            apiCall(`/api/{{ "$controller" }}/page-post/`, 'GET', null, 
                // beforesend
                function() {}, 
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

                        html = '<option value="">&nbsp;</option>'
                        if (res.items.offices.length > 0) {
                            for (key in res.items.offices) {
                                html += `<option value="${res.items.offices[key].officeID}">${res.items.offices[key].name}</option>`
                            }
                        }
                        $('#formAdd select[name="officeID"]').html(html)

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
