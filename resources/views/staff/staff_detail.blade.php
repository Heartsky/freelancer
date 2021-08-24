@extends('layouts.app', ['title' => __('company.page_title')])
@push('js')
    <script src="{{ asset('js/bank_account_edit.js')}}"></script>
@endpush
@section('content')
    @include('users.partials.header', [
        'title' => 'Staff Detail',
        'description' => '',
        'class' => ''
    ])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-8 order-xl-1 mb-5 mb-xl-0">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <a href="#">
                                    <img src="{{ asset('img') }}/brand/blue.png" class="rounded-circle">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class=" justify-content-between">

                            <a href="#" target-form="edit-bank_account" class="btn btn-sm btn-default float-right" id="bank_account-edit-button">{{ __('edit') }}</a>
                        </div>
                    </div>
                    <div class="card-body pt-0 pt-md-4">
                        <div style="margin-top: 30px;padding-top: 30px">

                            <p></p>
                        </div>

                        <div class="">
                        <form method="post" id="edit-bank_account" action="{{ route('master.staff_update',['id' => $staff->id]) }}" autocomplete="off">
                            @csrf
                            @method('post')
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">Code</label>
                                    <input type="text" name="code" id="input-name" disabled class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Code" value="{{ old('name', $staff->code) }}" required autofocus>

                                    @if ($errors->has('code'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">Name</label>
                                    <input type="text" name="name" disabled id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Name" value="{{ old('name', $staff->name) }}" required>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('staff_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-staff_id">Staff ID</label>
                                    <input type="text" name="staff_id" disabled id="input-staff-id" class="form-control form-control-alternative{{ $errors->has('staff_id') ? ' is-invalid' : '' }}" placeholder="Staff ID" value="{{ old('staff_id', $staff->staff_id) }}" required>

                                    @if ($errors->has('staff_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('staff_id') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('position') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-account_number">Position</label>
                                    <input type="text" name="position" disabled id="input-position" class="form-control form-control-alternative{{ $errors->has('position') ? ' is-invalid' : '' }}" placeholder="Position" value="{{ old('position', $staff->position) }}" required>

                                    @if ($errors->has('position'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('position') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('area') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-area">Area</label>
                                    <input type="text" name="area" disabled id="input-area" class="form-control form-control-alternative{{ $errors->has('area') ? ' is-invalid' : '' }}" placeholder="Area" value="{{ old('area', $staff->area) }}" required>

                                    @if ($errors->has('area'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('area') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('company_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >{{ __('team.company_id') }}</label>

                                    <select class="form-control " name="company_id" id="input-company_id" disabled>
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}" {{$staff->company_id == $company->id ? 'selected' : ''}}>{{$company->name}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group{{ $errors->has('team_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Team</label>

                                    <select class="form-control " name="team_id"  id="input-team_id" disabled>
                                        @foreach($teams as $team)
                                            <option value="{{$team->id}}" {{$staff->team_id == $team->id ? 'selected' : ''}}>{{$team->name}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit"  class="btn btn-success mt-4 collapse">{{ __('bank_account.save') }}</button>
                                    <a href="{{route('master.staff_management')}}">
                                        <button type="button" class="btn btn-danger mt-4">{{ __('customer.cancel') }}</button>
                                    </a>
                                </div>

                            </div>

                        </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
