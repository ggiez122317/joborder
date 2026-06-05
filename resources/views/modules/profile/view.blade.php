@extends('layouts.blank_no_auth')

@section('title', $title)

@section('styles')

@endsection

@section('content')
    <div class="py-5">
        <h4 class="py-3 breadcrumb-wrapper d-flex align-items-center justify-content-center mb-4">
            <div class="d-flex align-items-center">
                <img src="<?= asset('assets/img/logo.png') ?>" class="app-brand-logo demo" alt="Logo" style="width: 135px; height: 135px;"> 
            </div>
        </h4>
        @if($isActive)
            <div class="row mb-0">
                <div class="col-12 col-lg-4 mb-3"></div>
                <div class="col-12 col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-2" style="flex-direction: column;">
                            <div class="avatar avatar-xl mt-3" style="width: 8rem; height: 8rem;">
                                <img src="{{ $avatar }}" alt="Avatar">
                            </div>
                            <div class="alert alert-success text-center m-0 mt-2 p-2 w-100" role="alert">
                                <b>Mr./Ms. {{ $lname }} is an active Employee of LGU Trento!</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mb-3"></div>
            </div>
            <div class="row mb-0 d-none">
                <div class="col-12 col-lg-4 mb-3"></div>
                <div class="col-12 col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-2" style="flex-direction: column;">
                            <div class="avatar avatar-xl mt-3" style="width: 8rem; height: 8rem;">
                                <img src="{{ $avatar }}" alt="Avatar">
                            </div>
                            <h5 class="card-title mt-4 mb-0">{{ $name }}</h5>
                            <span class="badge bg-danger">{{ $idNumber }}</span>
                            <table class="table table-borderless mt-3 mb-0">
                                <tbody class="table-border-bottom-0">
                                    <tr>
                                        <td class="text-start p-0 text-primary" style="width: 70px;">Office</td>
                                        <td class="text-start p-0">: {{ $office }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start p-0 text-primary" style="width: 70px;">Position</td>
                                        <td class="text-start p-0">: {{ $position }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start p-0 text-primary" style="width: 70px;">Gender</td>
                                        <td class="text-start p-0">: {{ $gender }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start p-0 text-primary" style="width: 70px;">Age</td>
                                        <td class="text-start p-0">: {{ $age }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start p-0 text-primary" style="width: 70px;">Email</td>
                                        <td class="text-start p-0">: {{ $email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start p-0 text-primary" style="width: 70px;">Phone</td>
                                        <td class="text-start p-0">: {{ $phone }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="alert alert-success text-center m-0 mt-2 p-2 w-100" role="alert">
                                <b>EMPLOYEE VERIFIED</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mb-3"></div>
            </div>
        @else 
            <div class="row mb-0">
                <div class="col-12 col-lg-4 mb-3"></div>
                <div class="col-12 col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center p-2" style="flex-direction: column;">
                            <div class="avatar avatar-xl mt-3" style="width: 8rem; height: 8rem;">
                                <img src="<?= asset('assets/img/dp.jpg') ?>" alt="Avatar">
                            </div>
                            <div class="alert alert-danger text-center m-0 mt-2 p-2 w-100" role="alert">
                                <b>INVALID CODE</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mb-3"></div>
            </div>
        @endif 
    </div>
@endsection

@section('scripts')

@endsection