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
    <form id="formEdit">
        <div class="card">
            <div class="card-header p-2 justify-content-end d-flex">
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href=`{{ url("/$controller/view/$id/") }}`">
                    <span>
                        <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                        <span class="d-none d-sm-inline-block">Back</span>
                    </span>
                </button>
                <div class="divBtnEdit"></div>
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
                            <tbody><tr><td class="text-start" colspan="8">Loading...</td></tr></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('modals')

@endsection

@section('scripts')
    <script>

        function getRow()
        {

            const formID = 'formEdit'

            apiCall(`/api/{{ "$controller" }}/page-put-educational-background/{{ "$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="8">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        /** access buttons */ 
                        if (res.items.hasButtonEdit) {
                            $('.divBtnEdit').html(`
                                <button type="submit" class="btn btn-success btn-sm ms-2">
                                    <span> 
                                        <i class="bx bx-save me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Save</span>
                                    </span>
                                </button>
                            `)
                        }

                        $(`#${formID} table tbody`).html('')
                        if (res.items.educations.length > 0) {
                            for (key in res.items.educations) {
                                $(`#${formID} table tbody`).append(`
                                    <tr>
                                        <td class="text-start">${res.items.educations[key].level}</td>
                                        <td class="text-center">
                                            <input type="hidden" name="userEducationIDs[]" value="${res.items.educations[key].userEducationID}" >
                                            <input type="hidden" name="types[]" value="${res.items.educations[key].type}" >
                                            <input type="text" name="schoolNames[]" value="${res.items.educations[key].schoolName}" class="form-control" style="min-width: 200px;">
                                        </td>
                                        <td class="text-center">
                                            <input type="text" name="degrees[]" value="${res.items.educations[key].degree}" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            <input type="date" name="dateAttendedFroms[]" value="${res.items.educations[key].dateAttendedFrom}" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            <input type="date" name="dateAttendedTos[]" value="${res.items.educations[key].dateAttendedTo}" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            <input type="text" name="highestLevelEarneds[]" value="${res.items.educations[key].highestLevelEarned}" class="form-control">
                                        </td>
                                        <td class="text-center">
                                            <input type="text" name="yearGraduateds[]" value="${res.items.educations[key].yearGraduated}" class="form-control" placeholder="e.g. 1997">
                                        </td>
                                        <td class="text-center">
                                            <input type="text" name="scholarships[]" value="${res.items.educations[key].scholarship}" class="form-control" placeholder="">
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

        $(document).on('submit', '#formEdit', function(e) {
            e.preventDefault()

            const formID = 'formEdit'
            const formData = new FormData($('#'+formID).get(0))

            formData.append('_method', 'PUT') 

            apiCall(`/api/{{ "$controller" }}/educational-background/{{ $id }}/`, 'POST', formData, 
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

        $(document).ready(function() {
            getRow()
        }) 

    </script>
@endsection
