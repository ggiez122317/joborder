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
                    <div class="col-12 col-md-4">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Name">
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="form-label">Description </label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Description"></textarea>
                    </div>
                </div>
    
            </div>
        </div>
        <div class="card mt-4">
            <h5 class="card-header p-2 pb-0">Accesses</h5>
            <div class="row row-bordered g-0">
                <div class="col-12 p-2">
                    <table class="table table-bordered w-100">
                        <thead>
                            <tr>
                                <th class="text-start">
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input accessAll" type="checkbox" value="0" id="idAll">
                                        <label class="form-check-label ms-2" for="idAll">Modules</label>
                                    </div>
                                </th>
                                <th class="text-start">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableAccesses" class="table-border-bottom-0"><tr><td class="text-start" colspan="2">No Access Found</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('modals')

@endsection

@section('scripts')
    <script>

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
                function() {
                    $(`#tableAccesses`).html('<tr><td class="text-start" colspan="2">Loading...</td></tr>')
                }, 
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

                        // 
                        $(`#tableAccesses`).html('<tr><td class="text-start" colspan="2">No Access Found</td></tr>')
                        if (res.items.modules.length > 0) {
                            $(`#tableAccesses`).html('')
                            for (key in res.items.modules) {

                                hasIndex = 0
                                moduleActions = ''
                                moduleName = `<td class="text-start" style=" padding-left: 2.25em;">${res.items.modules[key]['module']}</td>`
                                if (res.items.modules[key]['actions'].length > 0) {
                                    if (res.items.modules[key]['actions'][0]['action'] == 'Index') {
                                        hasIndex = 1
                                        moduleName = `
                                            <td class="text-start">
                                                <div class="form-check d-flex align-items-start">
                                                    <input class="form-check-input ${res.items.modules[key]['actions'][0]['isDefault']?'':'accessModule'}" type="checkbox" name="appModuleActionIDs[]" value="${res.items.modules[key]['actions'][0]['id']}" id="action${res.items.modules[key]['actions'][0]['id']}" ${res.items.modules[key]['actions'][0]['isDefault']?'style="pointer-events: none; opacity: 0.5;" checked':''}>
                                                    <label class="form-check-label ms-2" for="action${res.items.modules[key]['actions'][0]['id']}" ${res.items.modules[key]['actions'][0]['isDefault']?'style="pointer-events: none;"':''}>${res.items.modules[key]['module']}</label>
                                                </div>
                                            </td>
                                        `
                                    }
                                    // module actions
                                    for (key2 in res.items.modules[key]['actions']) {
                                        if (res.items.modules[key]['actions'][key2]['action'] != 'Index') {
                                            if (hasIndex) {
                                                moduleActions += `
                                                    <div class="form-check d-flex align-items-start me-4">
                                                        <input class="form-check-input ${res.items.modules[key]['actions'][key2]['isDefault']?'':'accessModuleAction'}" type="checkbox" name="appModuleActionIDs[]" value="${res.items.modules[key]['actions'][key2]['id']}" id="action${res.items.modules[key]['actions'][key2]['id']}"  ${res.items.modules[key]['actions'][key2]['isDefault']?'style="pointer-events: none; opacity: 0.5;" checked':'disabled'}>
                                                        <label class="form-check-label ms-2" for="action${res.items.modules[key]['actions'][key2]['id']}" ${res.items.modules[key]['actions'][key2]['isDefault']?'style="pointer-events: none;" checked':''}>${res.items.modules[key]['actions'][key2]['action']}</label>
                                                    </div>
                                                `
                                            } else {
                                                moduleActions += `
                                                    <div class="form-check d-flex align-items-start me-4">
                                                        <input class="form-check-input" type="checkbox" name="appModuleActionIDs[]" value="${res.items.modules[key]['actions'][key2]['id']}" id="action${res.items.modules[key]['actions'][key2]['id']}" >
                                                        <label class="form-check-label ms-2" for="action${res.items.modules[key]['actions'][key2]['id']}">${res.items.modules[key]['actions'][key2]['action']}</label>
                                                    </div>
                                                `
                                            }
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
