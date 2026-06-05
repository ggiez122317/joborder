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
                    <div class="col-12 col-md-4">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Name">
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

            apiCall(`/api/{{ "$controller" }}/page-put/{{ "$id" }}/`, 'GET', null, 
                // beforesend
                function() {}, 
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

                        // 
                        $(`#${formID} input[name="name"]`).val(res.items.row.name)


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
