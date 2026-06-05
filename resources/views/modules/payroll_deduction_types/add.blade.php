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
        <div class="card">
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
                    <div class="col-12 col-md-3">
                        <label class="form-label">Deduction Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" placeholder="Code">
                    </div>
                    <div class="col-12 col-md-9">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Name">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-12">
                        <div class="d-flex">
                            <div class="form-check">
                                <input class="form-check-input" name="showInReport" type="checkbox" value="1" id="showInReport" checked>
                                <label class="form-check-label" for="showInReport">Show in report</label>
                            </div>
                        </div>
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
        $(document).on('change', '#isMayor', function() {
            if ($(this).is(':checked')) {
                $('.isMayor').slideUp()
            } else {
                $('.isMayor').slideDown()
            }
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

                        /** selects */
                        html = ''
                        if (res.offices.length > 0) {
                            pName = ''
                            html = '<option value="0">&nbsp;</option>'
                            for (key in res.offices) {
                                html += `<option value="${res.offices[key].officeID}">${res.offices[key].code}${res.offices[key].name?` - ${res.offices[key].name}`:''}</option>`
                            }
                        }
                        $('#formAdd select[name="officeID"]').html(html)

                        html = ''
                        if (res.JobPositions.length > 0) {
                            pName = ''
                            html = '<option value="0">&nbsp;</option>'
                            for (key in res.JobPositions) {
                                html += `<option value="${res.JobPositions[key].jobPositionID}">${res.JobPositions[key].code}${res.JobPositions[key].name?` - ${res.JobPositions[key].name}`:''}</option>`
                            }
                        }
                        $('#formAdd select[name="head_positionID"]').html(html)

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

