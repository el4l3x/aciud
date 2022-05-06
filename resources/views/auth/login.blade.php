@extends('layouts.app')

@section('content')
<div class="container">
    <br>
    <br>
    <br>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12"><div class="media">
            <img src="{{ asset('img\LOGO-ALCALDIA-ANAHIS-PALACIOS-NEGRO.png') }}" height="300" alt="" class="align-self-center mr-3">
            <div class="media-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
    
                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Cargo') }}</label>
    
                        <div class="col-md-6">
                            <select name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required autocomplete="username" autofocus>
                                <option value="Atencion al Ciudadano">Atención al Ciudadano</option>
                                <option value="Supervisor">Supervisor</option>
                                <option value="Director General">Director General</option>
                            </select>
    
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
    
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Clave') }}</label>
    
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
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Entrar') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
