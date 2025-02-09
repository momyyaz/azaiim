@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('environment_setup'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-4 pb-2">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/system-setting.png')}}" alt="">
            {{\App\CPU\translate('System_Setup')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Inlile Menu -->
    @include('admin-views.business-settings.system-settings-inline-menu')
    <!-- End Inlile Menu -->

   
</div>
@endsection

@push('script')

@endpush
