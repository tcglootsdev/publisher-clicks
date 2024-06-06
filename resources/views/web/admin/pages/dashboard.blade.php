@php
    $pageId = "pg-dashboard";
@endphp

@extends('web.admin.parts.layout')

@push('styles')
    <link href="{{ URL::asset('assets/web/global/plugins/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@section('content')
    <div class="toolbar" id="kt_toolbar">
        <div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
            <div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
                <h1 class="text-gray-900 fw-bold my-1 fs-2">Dashboard</h1>
            </div>
        </div>
    </div>
    <div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
        <div class="container-xxl">
            <div class="row g-xxl-10 mt-5">
                <div class="col-4 mt-0">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column align-items-center">
                            <span class="fs-2hx fw-bold text-gray-800 {{ $pageId }}-clicks">
                                <div class="spinner-border"></div>
                            </span>
                            <span class="fs-6 fw-semibold text-gray-500">Clicks</span>
                        </div>
                    </div>
                </div>
                <div class="col-4 mt-0">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column align-items-center">
                            <span class="fs-2hx fw-bold text-gray-800 {{ $pageId }}-revenue">
{{--                                <div class="spinner-border"></div>--}}
                                0
                            </span>
                            <span class="fs-6 fw-semibold text-gray-500">Revenue</span>
                        </div>
                    </div>
                </div>
                <div class="col-4 mt-0">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column align-items-center">
                            <span class="fs-2hx fw-bold text-gray-800">
{{--                                <div class="spinner-border"></div>--}}
                            </span>
                            <span class="fs-6 fw-semibold text-gray-500"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ URL::asset('assets/web/global/plugins/datatables/datatables.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/web/admin/js/pages/dashboard.js') }}"></script>
@endpush
