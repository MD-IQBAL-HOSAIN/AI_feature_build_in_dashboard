@extends('backend.master')

@section('title', 'Create Dynamic Page')

{{-- Shared styling for the translation tab layout used by create/edit pages. --}}
@include('backend.v1.pages._translation_tabs_assets')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-sm-0">Create Dynamic Page</h4>
                </div>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dynamic Page</a></li>
                        <li class="breadcrumb-item active">Create Dynamic Page</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card box-shadow-0">
                <div class="card-body">
                    {{-- Explain the multilingual authoring behavior before the form starts. --}}
                    <div class="alert alert-info">
                        Use the language tabs to create one dynamic page in multiple languages. Empty tabs will be skipped
                        automatically.
                    </div>

                    {{-- One form submits the parent page and every translation tab together. --}}
                    <form id="dynamic-page-form" action="{{ route('dynamic.store') }}" method="POST">
                        @csrf

                        {{-- Reusable translation tabs partial shared with the edit screen. --}}
                        @include('backend.v1.pages._translation_tabs', ['languages' => $languages])

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <a href="{{ route('dynamic.index') }}" class="btn btn-primary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
