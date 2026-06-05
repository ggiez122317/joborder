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
            <div class="card-body p-2">
    
                <div class="row g-2 mb-2">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Job Position Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" placeholder="Code">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Job Position Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Name">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Description"></textarea>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-12">
                        <div class="d-flex">
                            <div class="form-check">
                                <input class="form-check-input isPosChoices" name="isMayor" type="checkbox" value="1" id="isMayor">
                                <label class="form-check-label" for="isMayor">Is Mayor</label>
                            </div>
                            <div class="form-check ms-3">
                                <input class="form-check-input isPosChoices" name="isHrHead" type="checkbox" value="1" id="isHrHead">
                                <label class="form-check-label" for="isHrHead">Is HR Head</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-12 col-md-3 isMayor">
                        <label class="form-label">Immediate Superior <span class="text-danger">*</span></label>
                        <select name="head_positionID" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Office <span class="text-danger">*</span></label>
                        <select name="officeID" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
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

        $(document).on('change', '.isPosChoices', function() {
            $('.isPosChoices').not(this).prop('checked', false) 
            $('.isMayor').slideDown()
            if ($('#isMayor').is(':checked')) $('.isMayor').slideUp()
        })
        $(document).on('click', '#isMayor', function() {
            if ($(this).is(':checked')) {
                $('.isMayor').slideUp()
            } else {
                $('.isMayor').slideDown()
            }
        })

        function getRow()
        {

            const formID = 'formEdit'

            apiCall(`/api/{{ "$controller" }}/page-put/{{ "$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $('.isMayor').slideUp()
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

                        /** selects */
                        html = ''
                        if (res.offices.length > 0) {
                            pName = ''
                            html = '<option value="0">&nbsp;</option>'
                            for (key in res.offices) {
                                html += `<option value="${res.offices[key].officeID}" ${res.offices[key].officeID==res.items.row.officeID?'selected':''} >${res.offices[key].code}${res.offices[key].name?` - ${res.offices[key].name}`:''}</option>`
                            }
                        }
                        $(`#${formID} select[name="officeID"]`).html(html)

                        html = ''
                        if (res.JobPositions.length > 0) {
                            pName = ''
                            html = '<option value="0">&nbsp;</option>'
                            for (key in res.JobPositions) {
                                html += `<option value="${res.JobPositions[key].jobPositionID}" ${res.JobPositions[key].jobPositionID==res.items.row.head_positionID?'selected':''} >${res.JobPositions[key].code}${res.JobPositions[key].name?` - ${res.JobPositions[key].name}`:''}</option>`
                            }
                        }
                        $(`#${formID} select[name="head_positionID"]`).html(html)

                        // 
                        $(`#${formID} input[name="code"]`).val(res.items.row.code)
                        $(`#${formID} input[name="name"]`).val(res.items.row.name)
                        $(`#${formID} textarea[name="description"]`).val(res.items.row.description)
                        $(`#${formID} input[name="isMayor"]`).prop('checked', res.items.row.isMayor?true:false)
                        $(`#${formID} input[name="isHrHead"]`).prop('checked', res.items.row.isHrHead?true:false)

                        if (!res.items.row.isMayor) $('.isMayor').slideDown()


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

            apiCall(`/api/{{ "$controller" }}/{{ $id }}/`, 'POST', formData, 
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

