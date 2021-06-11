@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Данные для получения Authorisation code') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('sendRequest') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="clientId" class="col-md-4 col-form-label text-md-right">{{ __('clientId') }}</label>

                                <div class="col-md-6">
                                    <input id="clientId" type="text" class="form-control @error('clientId') is-invalid @enderror" name="clientId" value="" required autocomplete="clientId" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="clientSecret" class="col-md-4 col-form-label text-md-right">{{ __('clientSecret') }}</label>

                                <div class="col-md-6">
                                    <input id="clientSecret" type="text" class="form-control @error('clientSecret') is-invalid @enderror" name="clientSecret" value="" required autocomplete="clientSecret" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('code') }}</label>

                                <div class="col-md-6">
                                    <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="" required autocomplete="code" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dealName" class="col-md-4 col-form-label text-md-right">{{ __('dealName') }}</label>

                                <div class="col-md-6">
                                    <input id="dealName" type="text" class="form-control @error('dealName') is-invalid @enderror" name="dealName" value="" required autocomplete="dealName" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dealStage" class="col-md-4 col-form-label text-md-right">{{ __('dealStage') }}</label>

                                <div class="col-md-6">
                                    <input id="dealStage" type="text" class="form-control @error('dealStage') is-invalid @enderror" name="dealStage" value="" required autocomplete="dealStage" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="taskName" class="col-md-4 col-form-label text-md-right">{{ __('taskName') }}</label>

                                <div class="col-md-6">
                                    <input id="taskName" type="text" class="form-control @error('taskName') is-invalid @enderror" name="taskName" value="" required autocomplete="taskName" autofocus>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Send request') }}
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
