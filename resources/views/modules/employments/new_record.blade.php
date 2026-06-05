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
                <button type="button" class="btn btn-secondary btnBack btn-sm" onclick="window.location.href=`{{ url("/$controller/view/$id/") }}`">
                    <span>
                        <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                        <span class="d-none d-sm-inline-block">Back</span>
                    </span>
                </button>
                <div class="divBtnEdit"></div>
            </div>
            <div class="card-body p-2">

                <div class="row g-2 mb-2">
                    <div class="col-6 col-md-3">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select name="userID" class="form-control">
                            <option value="" data-id="">&nbsp;</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                        <input type="text" name="idNumber" class="form-control">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-6 col-md-3">
                        <label class="form-label">Date Appointed <span class="text-danger">*</span></label>
                        <input type="date" name="dateAppointed" class="form-control">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Employment Type <span class="text-danger">*</span></label>
                        <select name="userEmploymentTypeID" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Office <span class="text-danger">*</span></label>
                        <select name="officeID" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Job Position <span class="text-danger">*</span></label>
                        <select name="jobPositionID" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Salary Per Month <span class="text-danger">*</span></label>
                        <input type="number" name="salaryMonthly" class="form-control" min="0" step="0.25">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Salary Per Annum (<span class="text-primary">FOR SERVICE RECORD</span>) <span class="text-danger">*</span></label>
                        <input type="number" name="salaryYearly" class="form-control" min="0" step="0.25">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bankAccountName" class="form-control" placeholder="e.g., LBP">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Bank Account Number</label>
                        <input type="text" name="bankAccountNumber" class="form-control" placeholder="e.g., XXXXXXXXXX">
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

            apiCall(`/api/{{ "$controller" }}/page-put-new/{{ "$id" }}/`, 'GET', null, 
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
                        idNumber = 0
                        html = '<option value="" data-id="">&nbsp;</option>'
                        if (res.items.employees.length > 0) {
                            for (key in res.items.employees) {
                                if (res.items.employees[key].selected) idNumber = res.items.employees[key].idNumber 
                                html += `<option value="${res.items.employees[key].userID}" data-id="${res.items.employees[key].idNumber}" ${idNumber?'selected':''} >${res.items.employees[key].name}</option>` 
                            }
                        }
                        $(`#${formID} select[name="userID"]`).html(html)

                        if (idNumber) $(`#${formID} input[name="idNumber"]`).val(idNumber)
                        if (!idNumber) $(`.btnBack`).on('click', function() { window.location.href=`{{ url("/$controller") }}` })

                        html = '<option value="">&nbsp;</option>'
                        if (res.items.offices.length > 0) {
                            for (key in res.items.offices) {
                                html += `<option value="${res.items.offices[key].officeID}" >${res.items.offices[key].code} - ${res.items.offices[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="officeID"]`).html(html)

                        html = '<option value="">&nbsp;</option>'
                        if (res.items.JobPositions.length > 0) {
                            for (key in res.items.JobPositions) {
                                html += `<option value="${res.items.JobPositions[key].jobPositionID}" >${res.items.JobPositions[key].code} - ${res.items.JobPositions[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="jobPositionID"]`).html(html)

                        html = '<option value="">&nbsp;</option>'
                        if (res.items.user_employment_types.length > 0) {
                            for (key in res.items.user_employment_types) {
                                html += `<option value="${res.items.user_employment_types[key].userEmploymentTypeID}" >${res.items.user_employment_types[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="userEmploymentTypeID"]`).html(html)


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

        $(document).on('change', `#formEdit select[name="userID"]`, function() {
            $(this).find(':selected').data('id')
            $(`#formEdit input[name="idNumber"]`).val($(this).find(':selected').data('id'))
        })

        $(document).on('submit', '#formEdit', function(e) {
            e.preventDefault()

            const formID = 'formEdit'
            const formData = new FormData($('#'+formID).get(0))

            formData.append('_method', 'PUT') 

            apiCall(`/api/{{ "$controller" }}/new/{{ $id }}/`, 'POST', formData, 
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
                    btnLoading(`#${formID} button[type="submit"]`, `<span><i class="bx bx-save me-sm-1"></i><span class="d-none d-sm-inline-block">Rehire</span></span>`, 0)
                }, 
                localStorage.getItem('t') 
            )
        }) 

        $(document).ready(function() {
            getRow()
        }) 

    </script>
@endsection

