@extends('backend.master')

@section('title', 'Bible API Settings')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Bible API Settings</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                        <li class="breadcrumb-item active">Bible API Settings</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex justify-content-center mt-3 mt-lg-0">
                            <div class="col-lg-8"> <!-- you can change 8 to 6 or 10 depending on desired width -->
                                <form method="post" action="{{ route('settings.third-party-api.update') }}"
                                    enctype="multipart/form-data" class="card p-4 shadow-sm">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row mb-4">
                                        <label for="bible_api_key" class="col-md-3 form-label">BIBLE API KEY</label>
                                        <div class="col-md-9">
                                            <input class="form-control @error('bible_api_key') is-invalid @enderror"
                                                id="bible_api_key" name="bible_api_key"
                                                placeholder="Enter your BIBLE_API_KEY" type="text"
                                                value="{{ env('BIBLE_API_KEY') }}">
                                            @error('bible_api_key')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="bible_api" class="col-md-3 form-label">BIBLE API</label>
                                        <div class="col-md-9">
                                            <input class="form-control @error('bible_api') is-invalid @enderror"
                                                id="bible_api" name="bible_api" placeholder="Enter your host" type="text"
                                                value="{{ env('BIBLE_API') }}">
                                            @error('bible_api')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row justify-content-end">
                                        <div class="col-sm-9">
                                            <button class="btn btn-primary" type="submit">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <!--end col-->
                </div>


            </div>
            <!-- end .h-100-->

        </div>
        <!-- end col -->

    </div>

@endsection


