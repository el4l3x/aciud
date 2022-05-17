@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row align-items-center justify-content-center">
        <div class="col-md-5">
            <img src="{{ asset('img\LOGO-ALCALDIA-ANAHIS-PALACIOS-NEGRO.png') }}" alt="" class="img-fluid align-self-center mr-3">
        </div>
        
        <div class="col-md-7 align-bottom">

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group row">
                    <label for="username" class="col-md-2 col-form-label text-md-right">{{ __('Cargo') }}</label>

                    <div class="col-md-6">
                        <select name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required autocomplete="username" autofocus>
                            <option value="Natali">Atención al Ciudadano - Natali</option>
                            <option value="Yessica">Atención al Ciudadano - Yessica</option>
                            <option value="Vansuli Mita">Supervisor</option>
                            <option value="Zapata">Director General</option>
                        </select>

                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-2 col-form-label text-md-right">{{ __('Clave') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-2">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Entrar') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#loading').fadeOut();        
    });
</script>
@endpush