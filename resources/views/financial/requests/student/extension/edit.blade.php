@extends('material.layouts.dashboard')

@push('styles')
    <!-- Spinner Styles -->
    <link href="{{asset('assets/global/plugins/ladda/ladda-themeless.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Select2 Style -->
    <link href="{{ asset('assets/global/plugins/select2material/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/select2material/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/select2material/css/pmd-select2.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('title', trans('financial.extension.title'))

@section('page-title', trans('financial.extension.edit.title'))

@section('page-description', trans('financial.extension.edit.description'))

@section('content')
    <div class="col-md-12" id="app" data-source="{{ isset($id) ? $id : 0}}">
        <student-extension-request-edit></student-extension-request-edit>
    </div>
@endsection


@push('plugins')
    <!-- Max Length Scripts -->
    <script src="{{ asset('assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
    <!-- Validation Scripts -->
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/jquery-validation/js/localization/messages_'.config('app.locale').'.js') }}" type="text/javascript"></script>
    <!-- Spinner Scripts -->
    <script src="{{ asset('assets/global/plugins/ladda/spin.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/ladda/ladda.min.js') }}" type="text/javascript"></script>
    <!-- Select2 Scripts -->
    <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/global/plugins/select2/js/i18n/'.config('app.locale').'.js') }}"></script>
@endpush

@push('functions')
    <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
@endpush