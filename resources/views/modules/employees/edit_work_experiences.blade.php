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
                        
                    <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr valign="middle">
                                    <th rowspan="2" class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm ms-auto" onclick="addWork()"><i class="bx bx-plus ms-0"></i></button>
                                    </th>
                                    <th rowspan="1" colspan="2" class="text-center text-nowrap">Inclusive Dates</th>
                                    <th rowspan="2" class="text-center text-nowrap">Position Title<br>(Write in full/ Do not abbreviate)</th>
                                    <th rowspan="2" class="text-center text-nowrap">DEPARTMENT/ AGENCY/ OFFICE/ COMPANY<br>(Write in full/ Do not abbreviate)</th>
                                    <th rowspan="2" class="text-center text-nowrap">MONTHLY<br>Salary</th>
                                    <th rowspan="2" class="text-center text-nowrap">SALARY/ JOB/ PAY GRADE<br>(If applicable)<br>&amp; STEP<br>(Format *00-0*)/<br>INCREMENT</th>
                                    <th rowspan="2" class="text-center text-nowrap">STATUS OF<br>APPOINTMENT</th>
                                    <th rowspan="2" class="text-center text-nowrap">GOV'T<br>SERVICE<br>(Y/N)</th>
                                </tr>
                                <tr valign="middle">
                                    <th class="text-center">From</th>
                                    <th class="text-center">To</th>
                                </tr>
                            </thead>
                            <tbody><tr><td class="text-start" colspan="9">Loading...</td></tr></tbody>
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

        function addWork()
        {

            $('#formEdit table tbody').append(`
                <tr class="dRow">
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm removeWork"><i class="bx bx-trash ms-0"></i></button>
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="workIDs[]" class="form-control">
                        <input type="date" name="dateFroms[]" class="form-control">
                    </td>
                    <td class="text-center"><input type="date" name="dateTos[]" class="form-control"></td>
                    <td class="text-center"><input type="text" name="positions[]" class="form-control"></td>
                    <td class="text-center"><input type="text" name="companys[]" class="form-control"></td>
                    <td class="text-center"><input type="text" name="salarys[]" class="form-control" style="min-width: 90px;"></td>
                    <td class="text-center"><input type="text" name="salaryGrades[]" class="form-control"></td>
                    <td class="text-center"><input type="text" name="appointmentStatuss[]" class="form-control"></td>
                    <td class="text-center">
                        <select name="isGovts[]" class="form-control">
                            <option value="1">YES</option>
                            <option value="0">NO</option>
                        </select>
                    </td>
                </tr>
            `)

        }

        $(document).on('click', '.removeWork', function() {
            $(this).closest('.dRow').remove()
        })

        function getRow()
        {

            const formID = 'formEdit'

            apiCall(`/api/{{ "$controller" }}/page-put-work-experiences/{{ "$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="9">Loading...</td></tr>')
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
                        if (res.items.work_experiences.length > 0) {
                            for (key in res.items.work_experiences) {
                                if (key != 0) {
                                    $(`#${formID} table tbody`).append(`
                                        <tr class="dRow">
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm removeWork"><i class="bx bx-trash ms-0"></i></button>
                                            </td>
                                            <td class="text-center">
                                                <input type="hidden" name="workIDs[]" value="${res.items.work_experiences[key].userWorkID}" class="form-control">
                                                <input type="date" name="dateFroms[]" value="${res.items.work_experiences[key].dateFrom}" class="form-control">
                                            </td>
                                            <td class="text-center"><input type="date" name="dateTos[]" value="${res.items.work_experiences[key].dateTo}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="positions[]" value="${res.items.work_experiences[key].position}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="companys[]" value="${res.items.work_experiences[key].company}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="salarys[]" value="${res.items.work_experiences[key].salary}" class="form-control" style="min-width: 90px;"></td>
                                            <td class="text-center"><input type="text" name="salaryGrades[]" value="${res.items.work_experiences[key].salaryGrade}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="appointmentStatuss[]" value="${res.items.work_experiences[key].appointmentStatus}" class="form-control"></td>
                                            <td class="text-center">
                                                <select name="isGovts[]" class="form-control">
                                                    <option value="1" ${res.items.work_experiences[key].isGovt==1?'selected':''} >YES</option>
                                                    <option value="0" ${res.items.work_experiences[key].isGovt==0?'selected':''} >NO</option>
                                                </select>
                                            </td>
                                        </tr>
                                    `)
                                } else {
                                    $(`#${formID} table tbody`).append(`
                                        <tr class="dRow">
                                            <td class="text-center"></td>
                                            <td class="text-center">
                                                <input type="hidden" name="workIDs[]" value="${res.items.work_experiences[key].userWorkID}" class="form-control">
                                                <input type="date" name="dateFroms[]" value="${res.items.work_experiences[key].dateFrom}" class="form-control">
                                            </td>
                                            <td class="text-center"><input type="date" name="dateTos[]" value="${res.items.work_experiences[key].dateTo}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="positions[]" value="${res.items.work_experiences[key].position}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="companys[]" value="${res.items.work_experiences[key].company}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="salarys[]" value="${res.items.work_experiences[key].salary}" class="form-control" style="min-width: 90px;"></td>
                                            <td class="text-center"><input type="text" name="salaryGrades[]" value="${res.items.work_experiences[key].salaryGrade}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="appointmentStatuss[]" value="${res.items.work_experiences[key].appointmentStatus}" class="form-control"></td>
                                            <td class="text-center">
                                                <select name="isGovts[]" class="form-control">
                                                    <option value="1" ${res.items.work_experiences[key].isGovt==1?'selected':''} >YES</option>
                                                    <option value="0" ${res.items.work_experiences[key].isGovt==0?'selected':''} >NO</option>
                                                </select>
                                            </td>
                                        </tr>
                                    `)
                                }
                            }
                        } else {
                            $(`#${formID} table tbody`).append(`
                                <tr class="dRow">
                                    <td class="text-center"></td>
                                    <td class="text-center">
                                        <input type="hidden" name="workIDs[]" class="form-control">
                                        <input type="date" name="dateFroms[]" class="form-control">
                                    </td>
                                    <td class="text-center"><input type="date" name="dateTos[]" class="form-control"></td>
                                    <td class="text-center"><input type="text" name="positions[]" class="form-control"></td>
                                    <td class="text-center"><input type="text" name="companys[]" class="form-control"></td>
                                    <td class="text-center"><input type="text" name="salarys[]" class="form-control" style="min-width: 90px;"></td>
                                    <td class="text-center"><input type="text" name="salaryGrades[]" class="form-control"></td>
                                    <td class="text-center"><input type="text" name="appointmentStatuss[]" class="form-control"></td>
                                    <td class="text-center">
                                        <select name="isGovts[]" class="form-control">
                                            <option value="1">YES</option>
                                            <option value="0">NO</option>
                                        </select>
                                    </td>
                                </tr>
                            `)
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

            apiCall(`/api/{{ "$controller" }}/work-experiences/{{ $id }}/`, 'POST', formData, 
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
