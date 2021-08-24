@extends('layouts.app', ['title' => __('User Profile')])
@push('js')
    <script src="{{ asset('js/team_edit.js')}}"></script>
@endpush
@section('content')
    @include('users.partials.header', [
        'title' => '',
        'description' => '',
        'class' => 'col-lg-7'
    ])

    <div class="container-fluid mt--7">
        <div class="row">

            <div class="col-xl-8 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">{{ __('team.create_team') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('master.team_create') }}" autocomplete="off">
                            @csrf
                            @method('post')



                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-code">{{ __('team.code') }}</label>
                                    <input type="text" name="code" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="{{ __('team.code') }}" value="{{ old('code', $team->code) }}" required autofocus>

                                    @if ($errors->has('code'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('team.name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('team.name') }}" value="{{ old('name', $team->name) }}" required>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('company_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >{{ __('team.company_id') }}</label>

                                    <select class="form-control " name="company_id" id="input-company_id">
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}" {{$team->company_id == $company->id ? 'selected' : ''}}>{{$company->name}}</option>
                                        @endforeach

                                    </select>
                                </div>



                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('team.save') }}</button>
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
