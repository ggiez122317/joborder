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
                        <label class="form-label">Taxable Salary From</label>
                        <input type="number" name="salaryTaxableFrom" class="form-control" min="0" step=".01" placeholder="Taxable Salary From">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Taxable Salary To (Enter <b>0</b> for <b>Salary From+</b>)</label>
                        <input type="number" name="salaryTaxableTo" class="form-control" min="0" step=".01" placeholder="Taxable Salary To">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Base </label>
                        <input type="number" name="base" class="form-control" min="0" step=".01" placeholder="Base">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Percentage Rate (%)</label>
                        <input type="text" name="ratePercentage" class="form-control myNumber" placeholder="(e.g. 30 for 30%)" min="0" step=".01" maxlength="5">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Fixed Rate </label>
                        <input type="number" name="rateFixed" class="form-control" min="0" step=".01" placeholder="Fixed Rate">
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

        $(document).on('input', '.myNumber', function() {
            const maxLength = 5
            let val = $(this).val()
            val = val.replace(/[^0-9.]/g, '')
            val = val.replace(/(\..*)\./g, '$1')
            if (val.length > maxLength) val = val.slice(0, maxLength)
            $(this).val(val)
        })

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
                        $(`#${formID} input[name="salaryTaxableFrom"]`).val(res.items.row.salaryTaxableFrom)
                        $(`#${formID} input[name="salaryTaxableTo"]`).val(res.items.row.salaryTaxableTo)
                        $(`#${formID} input[name="base"]`).val(res.items.row.base)
                        $(`#${formID} input[name="ratePercentage"]`).val(res.items.row.ratePercentage)
                        $(`#${formID} input[name="rateFixed"]`).val(res.items.row.rateFixed)


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
