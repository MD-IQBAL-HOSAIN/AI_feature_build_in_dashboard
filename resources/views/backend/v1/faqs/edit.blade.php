@extends('backend.master')

@section('title', 'Dashboard | faq Edit form')

{{-- This css for faq create and edit both of forms --}}
@include('backend.v1.faqs._translation_tabs_assets')

@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <h4 class="mb-sm-0">Edit FAQ</h4>
                    {{-- <a href="{{ route('feature.faq.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </a> --}}
                </div>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">FAQ</a></li>
                        <li class="breadcrumb-item active">Edit FAQ</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card box-shadow-0">
                <div class="card-body">
                    <div class="alert alert-info">
                        Update all language versions of this FAQ from one place. Open only the tab you need, and the editor
                        will load there.
                    </div>

                    <form id="faq-form" action="{{ route('faq.update', $data->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control"
                                value="{{ old('sort_order', $data->sort_order ?? 0) }}" min="0"
                                placeholder="Enter sort order">
                            @error('sort_order')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- Translation Tabs. Here is the code for question Answer and answer --}}
                        @include('backend.v1.faqs._translation_tabs', [
                            'languages' => $languages,
                            'translationsByLanguage' => $translationsByLanguage,
                        ])
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('faq.index') }}" class="btn btn-primary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

@endsection
