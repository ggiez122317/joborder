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
                                        <button type="button" class="btn btn-primary btn-sm ms-auto" onclick="addLaD()"><i class="bx bx-plus ms-0"></i></button>
                                    </th>
                                    <th rowspan="2" class="text-center text-nowrap">Title of Learning and Development Interventions/Training Programs<br>(Write in full)</th>
                                    <th rowspan="1" colspan="2" class="text-center text-nowrap">Inclusive Dates Of<br>Attendance</th>
                                    <th rowspan="2" class="text-center text-nowrap">NUMBER OF HOURS</th>
                                    <th rowspan="2" class="text-center text-nowrap">TYPE OF LD<br>(Managerial/ Supervisory/<br>Technical/etc)</th>
                                    <th rowspan="2" class="text-center text-nowrap">CONDUCTED/SPONSORED BY<br>(Write in full)</th>
                                </tr>
                                <tr valign="middle">
                                    <th class="text-center">From</th>
                                    <th class="text-center">To</th>
                                </tr>
                            </thead>
                            <tbody><tr><td class="text-start" colspan="7">Loading...</td></tr></tbody>
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

        function addLaD()
        {

            $('#formEdit table tbody').append(`
                <tr class="dRow">
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm removeLaD"><i class="bx bx-trash ms-0"></i></button>
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="userTrainingIDs[]" class="form-control">
                        <input type="text" name="trainingNames[]" class="form-control">
                    </td>
                    <td class="text-center"><input type="date" name="dateFroms[]" class="form-control"></td>
                    <td class="text-center"><input type="date" name="dateTos[]" class="form-control"></td>
                    <td class="text-center"><input type="text" name="hourss[]" class="form-control"></td>
                    <td class="text-center"><input type="text" name="ldTypes[]" class="form-control"></td>
                    <td class="text-center"><input type="text" name="sponsors[]" class="form-control"></td>
                </tr>
            `)

        }

        $(document).on('click', '.removeLaD', function() {
            $(this).closest('.dRow').remove()
        })

        function getRow()
        {

            const formID = 'formEdit'

            apiCall(`/api/{{ "$controller" }}/page-put-training-programs/{{ "$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="7">Loading...</td></tr>')
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
                        if (res.items.training_programs.length > 0) {
                            for (key in res.items.training_programs) {
                                if (key != 0) {
                                    $(`#${formID} table tbody`).append(`
                                        <tr class="dRow">
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm removeLaD"><i class="bx bx-trash ms-0"></i></button>
                                            </td>
                                            <td class="text-center">
                                                <input type="hidden" name="userTrainingIDs[]" value="${res.items.training_programs[key].userTrainingID}" class="form-control">
                                                <input type="text" name="trainingNames[]" value="${res.items.training_programs[key].trainingName}" class="form-control">
                                            </td>
                                            <td class="text-center"><input type="date" name="dateFroms[]" value="${res.items.training_programs[key].dateFrom}" class="form-control"></td>
                                            <td class="text-center"><input type="date" name="dateTos[]" value="${res.items.training_programs[key].dateTo}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="hourss[]" value="${res.items.training_programs[key].hours}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="ldTypes[]" value="${res.items.training_programs[key].ldType}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="sponsors[]" value="${res.items.training_programs[key].sponsor}" class="form-control"></td>
                                        </tr>
                                    `)
                                } else {
                                    $(`#${formID} table tbody`).append(`
                                        <tr class="dRow">
                                            <td class="text-center"></td>
                                            <td class="text-center">
                                                <input type="hidden" name="userTrainingIDs[]" value="${res.items.training_programs[key].userTrainingID}" class="form-control">
                                                <input type="text" name="trainingNames[]" value="${res.items.training_programs[key].trainingName}" class="form-control">
                                            </td>
                                            <td class="text-center"><input type="date" name="dateFroms[]" value="${res.items.training_programs[key].dateFrom}" class="form-control"></td>
                                            <td class="text-center"><input type="date" name="dateTos[]" value="${res.items.training_programs[key].dateTo}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="hourss[]" value="${res.items.training_programs[key].hours}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="ldTypes[]" value="${res.items.training_programs[key].ldType}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="sponsors[]" value="${res.items.training_programs[key].sponsor}" class="form-control"></td>
                                        </tr>
                                    `)
                                }
                            }
                        } else {
                            $(`#${formID} table tbody`).append(`
                                <tr class="dRow">
                                    <td class="text-center"></td>
                                    <td class="text-center">
                                        <input type="hidden" name="userTrainingIDs[]" class="form-control">
                                        <input type="text" name="trainingNames[]" class="form-control">
                                    </td>
                                    <td class="text-center"><input type="date" name="dateFroms[]" class="form-control"></td>
                                    <td class="text-center"><input type="date" name="dateTos[]" class="form-control"></td>
                                    <td class="text-center"><input type="text" name="hourss[]" class="form-control"></td>
                                    <td class="text-center"><input type="text" name="ldTypes[]" class="form-control"></td>
                                    <td class="text-center"><input type="text" name="sponsors[]" class="form-control"></td>
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

            apiCall(`/api/{{ "$controller" }}/training-programs/{{ $id }}/`, 'POST', formData, 
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
