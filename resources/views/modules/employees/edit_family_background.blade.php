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

                    <div class="col-md-4">
                        <label class="form-label">Spouse's First Name</label>
                        <input type="text" name="spouseFname" class="form-control" value="" placeholder="Spouse's First Name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Spouse's Middle Name</label>
                        <input type="text" name="spouseMname" class="form-control" value="" placeholder="Spouse's Middle Name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Spouse's Last Name</label>
                        <input type="text" name="spouseLname" class="form-control" value="" placeholder="Spouse's Last Name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Spouse's Occupation</label>
                        <input type="text" name="spouseOccupation" class="form-control" value="" placeholder="Spouse's Occupation">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Spouse's Employer/Business Name</label>
                        <input type="text" name="spouseBizName" class="form-control" value="" placeholder="Spouse's Employer/Business Name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Spouse's Business Address</label>
                        <input type="text" name="spouseBizAddress" class="form-control" value="" placeholder="Spouse's Business Address">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Spouse's Telephone Number</label>
                        <input type="text" name="spouseTelNo" class="form-control" value="" placeholder="Spouse's Telephone Number">
                    </div>
                    <div class="col-md-8"></div>

                    <div class="col-md-4">
                        <label class="form-label">Father's First Name</label>
                        <input type="text" name="fatherFname" class="form-control" value="" placeholder="Father's First Name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Father's Middle Name</label>
                        <input type="text" name="fatherMname" class="form-control" value="" placeholder="Father's Middle Name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Father's Last Name</label>
                        <input type="text" name="fatherLname" class="form-control" value="" placeholder="Father's Last Name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Mother's First Name</label>
                        <input type="text" name="motherFname" class="form-control" value="" placeholder="Mother's First Name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Mother's Middle Name</label>
                        <input type="text" name="motherMname" class="form-control" value="" placeholder="Mother's Middle Name">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Mother's Last Name</label>
                        <input type="text" name="motherLname" class="form-control" value="" placeholder="Mother's Last Name">
                    </div>

                    <div class="col-12 d-flex align-items-end">
                        <label class="form-label mb-0">Name of Childrens</label>
                        <button type="button" class="btn btn-primary btn-sm ms-auto" onclick="addChildren()"><i class="bx bx-plus ms-0"></i></button>
                    </div>

                    <div class="col-12 mt-1 table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-start">Full Name</th>
                                    <th class="text-center">Birthday</th>
                                </tr>
                            </thead>
                            <tbody><tr><td class="text-start" colspan="3">Loading...</td></tr></tbody>
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

        function addChildren()
        {

            html =  ''
            html += '<tr class="dRow">'
            html +=   '<td class="text-center">'
            html +=     '<button type="button" class="btn btn-danger btn-sm removeChild"><i class="bx bx-trash ms-0"></i></button>'
            html +=   '</td>'
            html +=   '<td class="text-start">'
            html +=     '<input type="hidden" name="childrenIDs[]" class="form-control">'
            html +=     '<input type="text" name="childrenNames[]" class="form-control">'
            html +=   '</td>'
            html +=   '<td class="text-center">'
            html +=     '<input type="date" name="childrenBirthdays[]" class="form-control">'
            html +=   '</td>'
            html += '</tr>'
            $(`#formEdit table tbody`).append(html)

        }

        $(document).on('click', '.removeChild', function() {
            $(this).closest('.dRow').remove()
        })

        function getRow()
        {

            const formID = 'formEdit'

            apiCall(`/api/{{ "$controller" }}/page-put-family-background/{{ "$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="3">Loading...</td></tr>')
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

                        // 
                        $(`#${formID} input[name="spouseFname"]`).val(res.items.row.spouseFname)
                        $(`#${formID} input[name="spouseMname"]`).val(res.items.row.spouseMname)
                        $(`#${formID} input[name="spouseLname"]`).val(res.items.row.spouseLname)
                        $(`#${formID} input[name="spouseOccupation"]`).val(res.items.row.spouseOccupation)
                        $(`#${formID} input[name="spouseBizName"]`).val(res.items.row.spouseBizName)
                        $(`#${formID} input[name="spouseBizAddress"]`).val(res.items.row.spouseBizAddress)
                        $(`#${formID} input[name="spouseTelNo"]`).val(res.items.row.spouseTelNo)
                        $(`#${formID} input[name="fatherFname"]`).val(res.items.row.fatherFname)
                        $(`#${formID} input[name="fatherMname"]`).val(res.items.row.fatherMname)
                        $(`#${formID} input[name="fatherLname"]`).val(res.items.row.fatherLname)
                        $(`#${formID} input[name="motherFname"]`).val(res.items.row.motherFname)
                        $(`#${formID} input[name="motherMname"]`).val(res.items.row.motherMname)
                        $(`#${formID} input[name="motherLname"]`).val(res.items.row.motherLname)

                        // 
                        $(`#${formID} table tbody`).html('')
                        if (res.items.childrens.length > 0) {
                            for (key in res.items.childrens) {
                                $(`#${formID} table tbody`).append(`
                                    <tr class="dRow">
                                        <td class="text-center">
                                            ${parseInt(key)>0?`
                                                <button type="button" class="btn btn-danger btn-sm removeChild"><i class="bx bx-trash ms-0"></i></button>
                                            `:''}
                                        </td>
                                        <td class="text-start">
                                            <input type="hidden" name="childrenIDs[]" class="form-control" value="${res.items.childrens[key].userChildrenID}">
                                            <input type="text" name="childrenNames[]" class="form-control" value="${res.items.childrens[key].name}" style="min-width: 200px;">
                                        </td>
                                        <td class="text-center">
                                            <input type="date" name="childrenBirthdays[]" class="form-control" value="${res.items.childrens[key].birthDate}">
                                        </td>
                                    </tr>
                                `)
                            }
                        } else {
                            $(`#${formID} table tbody`).html(`
                                <tr class="dRow">
                                    <td class="text-center">-</td>
                                    <td class="text-start">
                                        <input type="text" name="childrenNames[]" class="form-control" style="min-width: 200px;">
                                    </td>
                                    <td class="text-center">
                                        <input type="date" name="childrenBirthdays[]" class="form-control">
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

            apiCall(`/api/{{ "$controller" }}/family-background/{{ $id }}/`, 'POST', formData, 
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
