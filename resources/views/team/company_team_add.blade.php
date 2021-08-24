@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Hello') . ' '. auth()->user()->name,
        'description' => __('This is your profile page. You can see the progress you\'ve made with your work and manage your projects or assigned tasks'),
        'class' => 'col-lg-7'
    ])

    <div class="container-fluid mt--7">
        <div class="row">

            <div class="col-xl-8 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">{{ __('company.create_company') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('master.company_create') }}" autocomplete="off">
                            @csrf
                            @method('post')
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif


                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-code">{{ __('company.code') }}</label>
                                    <input type="text" name="code" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="{{ __('company.code') }}" value="{{ old('code', $company->code) }}" required autofocus>

                                    @if ($errors->has('code'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('company.name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('company.name') }}" value="{{ old('name', $company->name) }}" required>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">{{ __('company.email') }}</label>
                                    <input type="email" name="email" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('company.email') }}" value="{{ old('email', $company->email) }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('tax_code') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-tax_code">{{ __('company.tax_code') }}</label>
                                    <input type="text" name="tax_code" id="input-tax_code" class="form-control form-control-alternative{{ $errors->has('tax_code') ? ' is-invalid' : '' }}" placeholder="{{ __('company.tax_code') }}" value="{{ old('tax_code', $company->tax_code) }}" required>

                                    @if ($errors->has('tax_code'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('tax_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-address">{{ __('company.address') }}</label>
                                    <input type="text" name="address" id="input-address" class="form-control form-control-alternative{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('company.address') }}" value="{{ old('address', $company->address) }}" required>

                                    @if ($errors->has('address'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('phone_number') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-phone_number">{{ __('company.phone_number') }}</label>
                                    <input type="text" name="phone_number" id="input-phone_number" class="form-control form-control-alternative{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" placeholder="{{ __('company.phone_number') }}" value="{{ old('phone_number', $company->phone_number) }}" required>

                                    @if ($errors->has('phone_number'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('phone_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('fax') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-fax">{{ __('company.fax') }}</label>
                                    <input type="text" name="fax" id="input-fax" class="form-control form-control-alternative{{ $errors->has('fax') ? ' is-invalid' : '' }}" placeholder="{{ __('company.fax') }}" value="{{ old('fax', $company->fax) }}" required>

                                    @if ($errors->has('fax'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('fax') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('company.save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
