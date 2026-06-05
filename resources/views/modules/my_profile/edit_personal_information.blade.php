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

                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <input type="file" id="dpChanger" name="img" class="d-none" onchange="$('#form_dp').submit()" accept=".png, .jpg, .jpeg, .gif">
                        <div class="mx-auto d-flex justify-content-center" style="width: 120px; height: 120px; position: relative;">
                            <img id="croppedImage" src="<?= asset('assets/img/dp.jpg') ?>" alt="user image" class="d-block h-auto me-0 rounded-3 user-profile-img border" style="width: 120px; height: 120px;">
                            <div class="d-flex align-items-center justify-content-center" onclick="$('#dpChanger').click()" style="cursor: pointer; border: 1px solid #ccc; border-radius: 50%; width: 30px; height: 30px; background: #fff; position: absolute; bottom: -10px; box-shadow: 0px 0px 5px 1px #ccc;">
                                <i class="bx bx-camera"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
            
                    <div class="col-md-4">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="fname" class="form-control" value="">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="mname" class="form-control" value="">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="lname" class="form-control" value="">
                    </div>
            
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Civil Status</label>
                        <select name="civilStatus" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Citizenship</label>
                        <input type="text" name="citizenship" class="form-control" value="">
                    </div>
            
                    <div class="col-md-4">
                        <label class="form-label">Birthday <span class="text-danger">*</span></label>
                        <input type="date" name="birthDate" class="form-control" value="">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Birth Place <span class="text-danger">*</span></label>
                        <input type="text" name="birthPlace" class="form-control" value="">
                    </div>
            
                    <div class="col-md-4">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="" placeholder="09xx-xxx-xxxx" maxlength="11">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" value="">
                    </div>
            
                    <div class="col-md-4">
                        <label class="form-label">GSIS</label>
                        <input type="text" name="gsis" class="form-control" value="">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">PAGIBIG</label>
                        <input type="text" name="pagibig" class="form-control" value="">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">PHIL HEALTH</label>
                        <input type="text" name="philhealth" class="form-control" value="">
                    </div>
            
                    <div class="col-md-4">
                        <label class="form-label">SSS</label>
                        <input type="text" name="sss" class="form-control" value="" placeholder="SSS">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">TIN</label>
                        <input type="text" name="tin" class="form-control" value="">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Blood Type</label>
                        <select name="bloodType" class="form-control">
                            <option value="">&nbsp;</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Province</label>
                        <select name="permProvinceID" class="form-control" onchange="getCities()">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">City/Municipality</label>
                        <select name="permCityID" class="form-control" onchange="getBarangays()">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Barangay</label>
                        <select name="permBarangayID" class="form-control">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Street</label>
                        <input type="text" name="permStreet" class="form-control" value="">
                    </div>
            
                </div>
            </div>
        </div>
    </form>
@endsection

@section('modals')
    <div class="modal fade" id="modalDisplayPicture" tabindex="-1" aria-labelledby="modalDisplayPictureLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-lg font-bold" id="modalDisplayPictureLabel">Display Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-base font-medium text-[#1f2937] dark:text-white-dark/70">
                        <div class="mb-0">
                            <div class="space-y-2">
                                <div class="grid grid-cols-1 gap-2">
                                    <div class="flex justify-center">
                                        <img id="preview" src="<?= asset('assets/img/dp.jpg') ?>" class="w-100" style="width: 100%; max-width: 100%;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="cropImageBtn" class="btn btn-primary">Crop</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        const imgPreview    = document.getElementById("preview")
        let permProvinceID  = 0
        let permCityID      = 0
        let permBarangayID  = 0

        // cropper
        $(document).on('hidden.bs.modal', '#modalDisplayPicture', function () {
            if (window.cropper) {
                window.cropper.destroy()
                window.cropper = null
            }
            imgPreview.src = `<?= asset('assets/img/dp.jpg') ?>`
            $('#dpChanger').val('')
        })

        $(document).on('change', '#dpChanger', function(event) {


            if (window.cropper) {
                window.cropper.destroy() 
                window.cropper = null 
            }

            $('#modalDisplayPicture').modal('show')

            setTimeout(() => {
                const file = event.target.files[0]
                if (file) {

                    imgPreview.src = ""
                    imgPreview.onload = null

                    imgPreview.src = URL.createObjectURL(file)
                    imgPreview.onload = function () {
                        window.cropper = new Cropper(imgPreview, {
                            aspectRatio: 1 / 1,
                            viewMode: 1,
                            responsive: true, 
                            autoCropArea: 1, 
                            zoomable: false, 
                        })
            
                    }
                }
            }, 400)

        })

        $('#cropImageBtn').on('click', function() {
            if (window.cropper) {
                const canvas = window.cropper.getCroppedCanvas({
                    width: 300, 
                    height: 300, 
                })

                document.getElementById("croppedImage").src = canvas.toDataURL()
                $('#modalDisplayPicture').modal('hide')
            }
        })

        // queries
        function getProvinces()
        {

            const formID = 'formEdit'
            apiCall(`/api/{{ "$controller" }}/get-provinces/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} select[name="permCityID"]`).html('<option value="">&nbsp;</option>')
                    $(`#${formID} select[name="permBarangayID"]`).html('<option value="">&nbsp;</option>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        // fields
                        html = ''
                        if (res.items.provinces.length > 0) {
                            html += `<option value="">&nbsp;</option>`
                            for (key in res.items.provinces) {
                                html += `<option value="${res.items.provinces[key].provinceID}" ${permProvinceID==res.items.provinces[key].provinceID?'selected':''} >${res.items.provinces[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="permProvinceID"]`).html(html)

                        getCities()


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

        function getCities()
        {

            const formID = 'formEdit'
            apiCall(`/api/{{ "$controller" }}/get-cities/${$(`#${formID} select[name="permProvinceID"]`).val()}/`, 'GET', null, 
                // beforesend
                function() {
                    $(`#${formID} select[name="permBarangayID"]`).html('<option value="">&nbsp;</option>')
                }, 
                // done
                function(res) {

                    if (res.status == 200) {

                        // fields
                        html = ''
                        if (res.items.cities.length > 0) {
                            html += `<option value="">&nbsp;</option>`
                            for (key in res.items.cities) {
                                html += `<option value="${res.items.cities[key].cityID}" ${permCityID==res.items.cities[key].cityID?'selected':''} >${res.items.cities[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="permCityID"]`).html(html)

                        getBarangays()


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

        function getBarangays()
        {

            const formID = 'formEdit'
            apiCall(`/api/{{ "$controller" }}/get-barangays/${$(`#${formID} select[name="permCityID"]`).val()}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    if (res.status == 200) {

                        // fields
                        html = ''
                        if (res.items.barangays.length > 0) {
                            html += `<option value="">&nbsp;</option>`
                            for (key in res.items.barangays) {
                                html += `<option value="${res.items.barangays[key].barangayID}" ${permBarangayID==res.items.barangays[key].barangayID?'selected':''} >${res.items.barangays[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="permBarangayID"]`).html(html)


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

        function getRow()
        {

            const formID = 'formEdit'

            apiCall(`/api/{{ "$controller" }}/page-put-personal-information/{{ "$id" }}/`, 'GET', null, 
                // beforesend
                function() {}, 
                // done
                function(res) {

                    if (res.status == 200) {

                        permProvinceID  = res.items.row.permProvinceID
                        permCityID      = res.items.row.permCityID
                        permBarangayID  = res.items.row.permBarangayID

                        // 
                        $(`#${formID} #croppedImage`).prop('src', res.items.row.avatar) 
                        $(`#${formID} input[name="fname"]`).val(res.items.row.fname)
                        $(`#${formID} input[name="mname"]`).val(res.items.row.mname)
                        $(`#${formID} input[name="lname"]`).val(res.items.row.lname)
                        $(`#${formID} input[name="citizenship"]`).val(res.items.row.citizenship)
                        $(`#${formID} input[name="birthDate"]`).val(res.items.row.birthDate)
                        $(`#${formID} input[name="birthPlace"]`).val(res.items.row.birthPlace)
                        $(`#${formID} input[name="phone"]`).val(res.items.row.phone)
                        $(`#${formID} input[name="email"]`).val(res.items.row.email)
                        $(`#${formID} input[name="gsis"]`).val(res.items.row.gsis)
                        $(`#${formID} input[name="philhealth"]`).val(res.items.row.philhealth)
                        $(`#${formID} input[name="pagibig"]`).val(res.items.row.pagibig)
                        $(`#${formID} input[name="sss"]`).val(res.items.row.sss)
                        $(`#${formID} input[name="tin"]`).val(res.items.row.tin)
                        $(`#${formID} input[name="permStreet"]`).val(res.items.row.permStreet)

                        html = ''
                        if (res.items.genders.length > 0) {
                            for (key in res.items.genders) {
                                html += `<option value="${res.items.genders[key].gender}" ${res.items.row.gender==res.items.genders[key].gender?'selected':''} >${res.items.genders[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="gender"]`).html(html)

                        html = ''
                        if (res.items.blood_types.length > 0) {
                            html += `<option value=""></option>`
                            for (key in res.items.blood_types) {
                                html += `<option value="${res.items.blood_types[key].bloodType}" ${res.items.row.bloodType==res.items.blood_types[key].bloodType?'selected':''} >${res.items.blood_types[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="bloodType"]`).html(html)

                        html = ''
                        if (res.items.civilStatuses.length > 0) {
                            html += `<option value=""></option>`
                            for (key in res.items.civilStatuses) {
                                html += `<option value="${res.items.civilStatuses[key].civilStatus}" ${res.items.row.civilStatus==res.items.civilStatuses[key].civilStatus?'selected':''} >${res.items.civilStatuses[key].name}</option>`
                            }
                        }
                        $(`#${formID} select[name="civilStatus"]`).html(html)

                        getProvinces()
                        
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
            
            const croppedImage = document.getElementById("croppedImage").src
            formData.append("croppedImage", croppedImage)
            if (croppedImage && croppedImage.startsWith('data:image/png;base64,')) {
                const byteString = atob(croppedImage.split(',')[1])
                const arrayBuffer = new ArrayBuffer(byteString.length)
                const uintArray = new Uint8Array(arrayBuffer)
                for (let i = 0; i < byteString.length; i++) { uintArray[i] = byteString.charCodeAt(i) }
                const blob = new Blob([uintArray], { type: 'image/png' })
                formData.append("croppedImage", blob, "croppedImage.png")
            }

            apiCall(`/api/{{ "$controller" }}/personal-information/{{ $id }}/`, 'POST', formData, 
                // beforesend
                function() {
                    btnLoading(`#${formID} button[type="submit"]`, `<span><i class="bx bx-save me-sm-1"></i><span class="d-none d-sm-inline-block">Loading...</span></span>`)
                }, 
                // done
                function(res) {

                    if (res.status == 200) {
                        window.location.href = '{{ url($controller . "/changes-personal-information/0/") }}'
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
