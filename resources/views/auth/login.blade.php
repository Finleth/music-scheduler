@extends('layout.master2')

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

    <div class="row w-100 mx-0 auth-page">
        <div class="col-md-6 col-xl-5 mx-auto">
            <div class="card">
                <div class="row">
                <div class="col-md-12">
                    <div class="auth-form-wrapper px-4 py-5 justify-content-center">
                    <h5 class="text-muted font-weight-normal mb-4">Log in to your account.</h5>

                    <form class="forms-sample" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">{{ __('E-Mail Address') }}</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" required placeholder="Email" autofocus data-cy="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">{{ __('Password') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="current-password" required placeholder="Password" data-cy="password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary" data-cy="submit">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

