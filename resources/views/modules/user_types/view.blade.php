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
    <div class="card">
        <div class="card-header p-2 justify-content-end d-flex">
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href=`{{ url("/$controller") }}`">
                <span>
                    <i class="bx bx-left-arrow-alt me-sm-1"></i> 
                    <span class="d-none d-sm-inline-block">Back</span>
                </span>
            </button>
            <div class="divBtnAdd"></div>
            <div class="divBtnEdit"></div>
            <div class="divBtnDelete"></div>
            <div class="divBtnAudit"></div>
        </div>
        <form id="formView" class="card-body p-2">

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" style="background: #e3e3e3;" readonly>
                </div>
            </div>

            <div class="row mb-2">
                <label class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="description" rows="2" style="background: #e3e3e3;" readonly></textarea>
                </div>
            </div>

        </form>
    </div>

    <div class="card mt-4">
        <h5 class="card-header d-flex align-items-center justify-content-between p-2 pb-0">Accesses
            <div class="divBtnAudit2"></div>
        </h5>
        <div class="row row-bordered g-0">
            <div class="col-12 p-2">
                <table class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th class="text-start">Modules</th>
                            <th class="text-start">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableAccesses" class="table-border-bottom-0"><tr><td class="text-start" colspan="2">No Access Found</td></tr></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('modals')

@endsection

@section('scripts')
    <script>

        function rowDelete()
        {

            Swal.fire({
                title               : "Delete this record?",
                text                : "You won't be able to revert this!",
                icon                : "warning",
                showCancelButton    : true,
                confirmButtonColor  : "#3085d6",
                cancelButtonColor   : "#d33",
                confirmButtonText   : "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    apiCall(`/api/{{ "$controller" }}/{{ $id }}/`, 'DELETE', null, 
                        // beforesend
                        function() {
                            btnLoading(`.divBtnDelete button[type="button"]`, `<span><i class="bx bx-trash me-sm-1"></i><span class="d-none d-sm-inline-block">Loading...</span></span>`)
                        }, 
                        // done
                        function(res) {

                            if (res.status == 200) {
                                Swal.fire({
                                    title   : "Deleted!",
                                    text    : "Record has been deleted.",
                                    icon    : "success"
                                }).then((result) => {
                                    if (result.isConfirmed || result.isDismissed) {
                                        window.location.href=`{{ url("/$controller/") }}/`
                                    }
                                })
                            } else if (res.status == 401 && res.message == 'Invalid token') {
                                authenticationLogout()
                            } else {
                                Toast.fire({ icon : "warning", title : res.name, html : res.message })
                            }

                        }, 
                        // always
                        function() {
                            btnLoading(`.divBtnDelete button[type="button"]`, `<span><i class="bx bx-trash me-sm-1"></i><span class="d-none d-sm-inline-block">Delete</span></span>`, 0)
                        }, 
                        localStorage.getItem('t') 
                    )

                }
            });


        }

        function auditLogs()
        {

            popupCenteredWindow(`{{ url("/$controller/audit/$id/") }}`) 

        }

        function auditLogs2()
        {

            popupCenteredWindow(`{{ url("/$controller/audit2/$id/") }}`) 

        }

        function getRow()
        {

            const formID = 'formView'

            apiCall(`/api/{{ "$controller/$id" }}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} table tbody`).html('<tr><td class="text-start" colspan="3">Loading...</td></tr>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        /** access buttons */ 
                        if (res.items.hasButtonAdd) {
                            $('.divBtnAdd').html(`
                                <button type="button" class="btn btn-primary btn-sm ms-2" onclick="window.location.href='{{ url('/'.$controller.'/add/') }}'">
                                    <span>
                                        <i class="bx bx-plus me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Add</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonEdit && res.items.isSuperAdmin!=1) {
                            $('.divBtnEdit').html(`
                                <button type="button" class="btn btn-warning btn-sm ms-2" onclick="window.location.href='{{ url("/$controller/edit/$id/") }}'">
                                    <span>
                                        <i class="bx bx-pencil me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Edit</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonDelete && res.items.isSuperAdmin!=1) { 
                            $('.divBtnDelete').html(`
                                <button type="button" class="btn btn-danger btn-sm ms-2" onclick="rowDelete()">
                                    <span>
                                        <i class="bx bx-trash me-sm-1"></i> 
                                        <span class="d-none d-sm-inline-block">Delete</span>
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonAudit && res.items.isSuperAdmin!=1) { 
                            $('.divBtnAudit').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditLogs()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }
                        if (res.items.hasButtonAudit && res.items.isSuperAdmin!=1) { 
                            $('.divBtnAudit2').html(`
                                <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="auditLogs2()" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Audit Logs">
                                    <span>
                                        <i class="bx bx-notepad"></i> 
                                    </span>
                                </button>
                            `)
                        }

                        // fields
                        $(`#${formID} input[name="name"]`).val(res.items.row.name)
                        $(`#${formID} textarea[name="description"]`).val(res.items.row.description)

                        // others 
                        $(`#tableAccesses`).html('<tr><td class="text-start" colspan="2">No Access Found</td></tr>')
                        if (res.items.modules.length > 0) {
                            $(`#tableAccesses`).html('')
                            for (key in res.items.modules) {

                                moduleActions = ''
                                moduleName = `<td class="text-start" style="opacity: 0.5; padding-left: 2.25em;">${res.items.modules[key]['module']}</td>`
                                if (res.items.modules[key]['actions'].length > 0) {
                                    if (res.items.modules[key]['actions'][0]['action'] == 'Index') {
                                        moduleName = `
                                            <td class="text-start">
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input" type="checkbox" name="appModuleActionIDs[]" value="${res.items.modules[key]['actions'][0]['id']}" id="action${res.items.modules[key]['actions'][0]['id']}" ${res.items.accesses.includes(res.items.modules[key]['actions'][0]['id'])?'checked':''} disabled>
                                                    <label class="form-check-label ms-2" for="action${res.items.modules[key]['actions'][0]['id']}" ${res.items.modules[key]['actions'][0]['isDefault']?'style="pointer-events: none;"':''}>${res.items.modules[key]['module']}</label>
                                                </div>
                                            </td>
                                        `
                                    }
                                    // module actions
                                    for (key2 in res.items.modules[key]['actions']) {
                                        if (res.items.modules[key]['actions'][key2]['action'] != 'Index') {
                                            moduleActions += `
                                                <div class="form-check d-flex align-items-start me-4">
                                                    <input class="form-check-input" type="checkbox" name="appModuleActionIDs[]" value="${res.items.modules[key]['actions'][key2]['id']}" id="action${res.items.modules[key]['actions'][key2]['id']}" ${res.items.accesses.includes(res.items.modules[key]['actions'][key2]['id'])?'checked':''} disabled>
                                                    <label class="form-check-label ms-2" for="action${res.items.modules[key]['actions'][key2]['id']}" ${res.items.modules[key]['actions'][key2]['isDefault']?'style="pointer-events: none;" checked':''}>${res.items.modules[key]['actions'][key2]['action']}</label>
                                                </div>
                                            `
                                        }
                                    } 
                                }

                                if (res.items.modules[key]['actions'].length > 0) {
                                    $(`#tableAccesses`).append(`
                                        <tr valign="baseline">
                                            ${moduleName}
                                            <td class="text-start">
                                                <div class="d-flex flex-wrap">
                                                    ${moduleActions}
                                                </div>
                                            </td>
                                        </tr>
                                    `)
                                }
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

        $(document).ready(function() {
            getRow()
        })

    </script>
@endsection
