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
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href=`{{ url("/$controller/") }}`">
                    <span>
                        <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                        <span class="d-none d-sm-inline-block">Back</span>
                    </span>
                </button>
                <button type="submit" class="btn btn-success btn-sm ms-2">
                    <span> 
                        <i class="bx bx-save me-sm-1"></i> 
                        <span class="d-none d-sm-inline-block">Save</span>
                    </span>
                </button>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-12 table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr valign="middle">
                                    <th rowspan="2" class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm ms-auto" onclick="addEligibility()"><i class="bx bx-plus ms-0"></i></button>
                                    </th>
                                    <th rowspan="2" class="text-center text-nowrap">Career Service/RA 1080 (Board/Bar) Under<br>Special LAWS/CES/CSEE<br>Barangay Eligibility/Driver's License</th>
                                    <th rowspan="2" class="text-center text-nowrap">RATING<br>(If Applicable)</th>
                                    <th rowspan="2" class="text-center text-nowrap">Date of<br>Examination/<br>Conferment</th>
                                    <th rowspan="2" class="text-center text-nowrap">Place of Examination/ Conferment</th>
                                    <th rowspan="1" colspan="2" class="text-center text-nowrap">License (If applicable)</th>
                                </tr>
                                <tr valign="middle">
                                    <th class="text-center">Number</th>
                                    <th class="text-center">Date of Validity</th>
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

        function addEligibility()
        {

            $('#formEdit table tbody').append(`
                <tr class="dRow">
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm removeEligibility"><i class="bx bx-trash ms-0"></i></button>
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="civilServiceIDs[]" value="0">
                        <input type="text" name="names[]" value="" class="form-control">
                    </td>
                    <td class="text-center"><input type="text" name="ratings[]" value="" class="form-control"></td>
                    <td class="text-center"><input type="date" name="dateExaminations[]" value="" class="form-control"></td>
                    <td class="text-center"><input type="text" name="placeExaminations[]" value="" class="form-control"></td>
                    <td class="text-center"><input type="text" name="licenseNumbers[]" value="" class="form-control" style="min-width: 120px;"></td>
                    <td class="text-center"><input type="date" name="licenseDateValiditys[]" value="" class="form-control"></td>
                </tr>
            `)

        }

        $(document).on('click', '.removeEligibility', function() {
            $(this).closest('.dRow').remove()
        })

        function getRow()
        {

            const formID = 'formEdit'

            apiCall(`/api/{{ "$controller" }}/page-put-civil-service-eligibilities/{{ "$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="7">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        $(`#${formID} table tbody`).html('')
                        if (res.items.civil_services.length > 0) {
                            for (key in res.items.civil_services) {
                                if (key != 0) {
                                    $(`#${formID} table tbody`).append(`
                                        <tr class="dRow">
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm removeEligibility"><i class="bx bx-trash ms-0"></i></button>
                                            </td>
                                            <td class="text-center">
                                                <input type="hidden" name="civilServiceIDs[]" value="${res.items.civil_services[key].userCivilServiceID}">
                                                <input type="text" name="names[]" value="${res.items.civil_services[key].name}" class="form-control">
                                            </td>
                                            <td class="text-center"><input type="text" name="ratings[]" value="${res.items.civil_services[key].rating}" class="form-control"></td>
                                            <td class="text-center"><input type="date" name="dateExaminations[]" value="${res.items.civil_services[key].dateExamination}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="placeExaminations[]" value="${res.items.civil_services[key].placeExamination}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="licenseNumbers[]" value="${res.items.civil_services[key].licenseNumber}" class="form-control" style="min-width: 120px;"></td>
                                            <td class="text-center"><input type="date" name="licenseDateValiditys[]" value="${res.items.civil_services[key].licenseDateValidity}" class="form-control"></td>
                                        </tr>
                                    `)
                                } else {
                                    $(`#${formID} table tbody`).append(`
                                        <tr class="dRow">
                                            <td class="text-center"></td>
                                            <td class="text-center">
                                                <input type="hidden" name="civilServiceIDs[]" value="${res.items.civil_services[key].userCivilServiceID}">
                                                <input type="text" name="names[]" value="${res.items.civil_services[key].name}" class="form-control">
                                            </td>
                                            <td class="text-center"><input type="text" name="ratings[]" value="${res.items.civil_services[key].rating}" class="form-control"></td>
                                            <td class="text-center"><input type="date" name="dateExaminations[]" value="${res.items.civil_services[key].dateExamination}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="placeExaminations[]" value="${res.items.civil_services[key].placeExamination}" class="form-control"></td>
                                            <td class="text-center"><input type="text" name="licenseNumbers[]" value="${res.items.civil_services[key].licenseNumber}" class="form-control" style="min-width: 120px;"></td>
                                            <td class="text-center"><input type="date" name="licenseDateValiditys[]" value="${res.items.civil_services[key].licenseDateValidity}" class="form-control"></td>
                                        </tr>
                                    `)
                                }
                            }
                        } else {
                            $(`#${formID} table tbody`).append(`
                                <tr class="dRow">
                                    <td class="text-center"></td>
                                    <td class="text-center">
                                        <input type="hidden" name="civilServiceIDs[]" value="0">
                                        <input type="text" name="names[]" value="" class="form-control">
                                    </td>
                                    <td class="text-center"><input type="text" name="ratings[]" value="" class="form-control"></td>
                                    <td class="text-center"><input type="date" name="dateExaminations[]" value="" class="form-control"></td>
                                    <td class="text-center"><input type="text" name="placeExaminations[]" value="" class="form-control"></td>
                                    <td class="text-center"><input type="text" name="licenseNumbers[]" value="" class="form-control" style="min-width: 120px;"></td>
                                    <td class="text-center"><input type="date" name="licenseDateValiditys[]" value="" class="form-control"></td>
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

            apiCall(`/api/{{ "$controller" }}/civil-service-eligibilities/{{ $id }}/`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `<span><i class="bx bx-save me-sm-1"></i><span class="d-none d-sm-inline-block">Loading...</span></span>`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        window.location.href = '{{ url($controller . "/changes-civil-service-eligibilities/0/") }}'
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
