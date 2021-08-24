@extends('layouts.app', ['title' => __('company.page_title')])
@push('js')
    <script src="{{ asset('js/team_edit.js')}}"></script>
@endpush
@section('content')
    @include('users.partials.header', [
        'title' => 'Team Detail',
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

                            <a href="#" target-form="edit-team" class="btn btn-sm btn-default float-right" id="team-edit-button">{{ __('edit') }}</a>
                        </div>
                    </div>
                    <div class="card-body pt-0 pt-md-4">
                        <div style="margin-top: 30px;padding-top: 30px">
{{--                            <div class="col">--}}
{{--                                <div class="card-profile-stats d-flex justify-content-center mt-md-5">--}}
{{--                                    <div>--}}
{{--                                        <span class="heading">{{ $company->teams()->count() }}</span>--}}
{{--                                        <span class="description">{{ __('Team') }}</span>--}}
{{--                                    </div>--}}
{{--                                    <div>--}}
{{--                                        <span class="heading">{{ $company->staffs()->count() }}</span>--}}
{{--                                        <span class="description">{{ __('staff') }}</span>--}}
{{--                                    </div>--}}
{{--                                    <div>--}}
{{--                                        <span class="heading">{{ $company->customers()->count() }}</span>--}}
{{--                                        <span class="description">{{ __('customer') }}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <p></p>
                        </div>

                        <div class="">
                        <form method="post" id="edit-team" action="{{ route('master.team_update',['id' => $team->id]) }}" autocomplete="off">
                            @csrf
                            @method('post')
                            <div class="pl-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-code">{{ __('team.code') }}</label>
                                    <input type="text" name="code" id="input-code" class="form-control form-control-alternative"  disabled value="{{ $team->code }}"  />

                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="input-name">{{ __('team.name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative"  disabled value="{{ $team->name }}"  />

                                </div>


                                <div class="form-group{{ $errors->has('company_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >{{ __('team.company_id') }}</label>

                                    <select class="form-control "  disabled name="company_id">
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}" {{$team->company_id == $company->id ? 'selected' : ''}}>{{$company->name}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit"  class="btn btn-success mt-4 collapse">{{ __('company.save') }}</button>
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
