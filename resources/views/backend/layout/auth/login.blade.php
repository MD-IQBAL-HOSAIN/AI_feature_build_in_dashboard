@extends('backend.layout.auth.auth-app')

@section('title', 'Sign In | admin')

@section('content')
    <form method="post" action="{{route('auth.login.post')}}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="username"
                name="email" value="{{ old('email') }}"
            placeholder="Enter email">
        </div>

        <div class="mb-3">
            <div class="float-end">
                <a href="{{route('auth.reset.link.get')}}" class="text-muted">Forgot password?</a>
            </div>
            <label class="form-label" for="password-input">Password</label>
            <div class="position-relative auth-pass-inputgroup mb-3">
                <input type="password" class="form-control pe-5 password-input" id="password-input"
                    name="password"
                 placeholder="Enter password">
                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-success w-100" type="submit">Sign In</button>
        </div>

    </form>
@endsection


@push('srcipts-bottom')
    <!-- password-custom logi -->
    <script src="{{asset('assets/js/raihan/password-toggle.js')}}"></script>
@endpush

