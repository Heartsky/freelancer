@extends('layouts.app', ['title' => __('User Profile')])
@push('js')
    <script src="{{ asset('js/user_edit.js')}}"></script>
@endpush
@section('content')
    @include('users.partials.header', [
        'title' => '',
        'description' =>'',
        'class' => 'col-lg-7'
    ])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-8 order-xl-2 mb-5 mb-xl-0">
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
                        <div class="justify-content-between">
                            <a href="#" id="user-edit-button" target-form="edit-user"  class="btn btn-sm btn-default float-right">{{ __('user.edit') }}</a>
                        </div>
                    </div>
                    <div class="card-body pt-lg--3 pt-md-4">
                        <div style="margin-top: 30px;padding-top: 30px"><p></p></div>
{{--                        <div class="row">--}}
{{--                            <div class="col">--}}
{{--                                <div class="card-profile-stats d-flex justify-content-center mt-md-5">--}}
{{--                                    <div>--}}
{{--                                        <span class="heading">22</span>--}}
{{--                                        <span class="description">{{ __('Friends') }}</span>--}}
{{--                                    </div>--}}
{{--                                    <div>--}}
{{--                                        <span class="heading">10</span>--}}
{{--                                        <span class="description">{{ __('Photos') }}</span>--}}
{{--                                    </div>--}}
{{--                                    <div>--}}
{{--                                        <span class="heading">89</span>--}}
{{--                                        <span class="description">{{ __('Comments') }}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="text-left">
                            <form method="post" id="edit-user" action="{{ route('master.user_edit',['id' => $user->id]) }}" autocomplete="off">
                                @csrf
                                @method('post')
                                <div class="pl-lg-4">

                                    <div class="form-group">
                                        <label class="form-control-label" for="input-name">{{ __('user.name') }}</label>
                                        <input type="text" name="name" class="form-control form-control-alternative"  disabled value="{{ $user->name }}"  />

                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-name">{{ __('user.email') }}</label>
                                        <input type="text" name="email" class="form-control form-control-alternative"  disabled value="{{ $user->email }}"  />

                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="input-name">{{ __('user.roles') }}</label>
                                        <div style="background: #f5f7ff; padding: 15px">
                                            @foreach($roles as $role)
                                                <div class="custom-control custom-checkbox mb-3">
                                                    @if(in_array($role->id,$currentRole))
                                                        <input class="custom-control-input" name="roles[]" value="{{$role->id}}" checked disabled id="role-{{$role->id}}" type="checkbox" />
                                                    @else
                                                        <input class="custom-control-input" name="roles[]" value="{{$role->id}}" disabled id="role-{{$role->id}}" type="checkbox" />
                                                    @endif
                                                    <label class="custom-control-label" for="role-{{$role->id}}">{{$role->description}}</label>
                                                </div>
                                            @endforeach
                                        </div>
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
