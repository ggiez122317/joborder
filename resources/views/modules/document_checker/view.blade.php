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
                                <img src="<?= asset('assets/img/document_checking.jpg') ?>" alt="Avatar">
                            </div>
                            <div class="alert alert-success text-center m-0 mt-2 p-2 w-100" role="alert">
                                {!! $message !!}
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
                                <img src="<?= asset('assets/img/document_checking.jpg') ?>" alt="Avatar">
                            </div>
                            <div class="alert alert-danger text-center m-0 mt-2 p-2 w-100" role="alert">
                                <b>UNKNOWN DOCUMENT</b>
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