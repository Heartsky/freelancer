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
                            <h3 class="mb-0">Create Staff</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('master.staff_management_create') }}" autocomplete="off">
                            @csrf
                            @method('post')
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">Code</label>
                                    <input type="text" name="code" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Code" value="{{ old('code', $staff->code) }}" required autofocus>

                                    @if ($errors->has('code'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('account_name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-account_name">Name</label>
                                    <input type="text" name="name" id="input-account_name" class="form-control form-control-alternative{{ $errors->has('account_name') ? ' is-invalid' : '' }}" placeholder="Name" value="{{ old('name', $staff->name) }}" required>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('account_number') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-account_number">Staff ID</label>
                                    <input type="text" name="staff_id" id="input-account_number" class="form-control form-control-alternative{{ $errors->has('account_number') ? ' is-invalid' : '' }}" placeholder="Staff ID" value="{{ old('staff_id', $staff->staff_id) }}" required>

                                    @if ($errors->has('staff_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('staff_id') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('cif') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-cif">Position</label>
                                    <input type="text" name="position" id="input-cif" class="form-control form-control-alternative{{ $errors->has('position') ? ' is-invalid' : '' }}" placeholder="Postion" value="{{ old('cif', $staff->position) }}" required>

                                    @if ($errors->has('position'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('position') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('currency') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-currency">Area</label>
                                    <input type="text" name="area" id="input-currency" class="form-control form-control-alternative{{ $errors->has('area') ? ' is-invalid' : '' }}" placeholder="Area" value="{{ old('area', $staff->area) }}" required>

                                    @if ($errors->has('area'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('area') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('company_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Company</label>

                                    <select class="form-control " name="company_id" id="input-company_id">
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}" {{$staff->company_id == $company->id ? 'selected' : ''}}>{{$company->name}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group{{ $errors->has('team_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Team</label>

                                    <select class="form-control " name="team_id" id="input-team_id">
                                        @foreach($teams as $team)
                                            <option value="{{$team->id}}" {{$staff->id == $team->id ? 'selected' : ''}}>{{$team->name}}</option>
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
