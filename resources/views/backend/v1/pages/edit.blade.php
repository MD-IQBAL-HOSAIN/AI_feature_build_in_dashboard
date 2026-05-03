@extends('backend.master')

@section('title', 'Edit Dynamic Page')

{{-- Shared styling for the translation tab layout used by create/edit pages. --}}
@include('backend.v1.pages._translation_tabs_assets')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-sm-0">Edit Dynamic Page</h4>
                </div>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dynamic Page</a></li>
                        <li class="breadcrumb-item active">Edit Dynamic Page</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card box-shadow-0">
                <div class="card-body">
                    {{-- Clarify that all saved language versions can be managed from this screen. --}}
                    <div class="alert alert-info">
                        Update all language versions of this page from one place. Only complete translations will be saved.
                    </div>

                    {{-- One form updates the parent page and all translation records together. --}}
                    <form id="dynamic-page-form" action="{{ route('dynamic.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Inject existing translations keyed by language so each tab is prefilled. --}}
                        @include('backend.v1.pages._translation_tabs', [
                            'languages' => $languages,
                            'translationsByLanguage' => $translationsByLanguage,
                        ])

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('dynamic.index') }}" class="btn btn-primary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
