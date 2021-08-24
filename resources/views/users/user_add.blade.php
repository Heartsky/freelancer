@extends('layouts.app', ['title' => __('User Profile')])
@push('js')
    <script src="{{ asset('js/user_edit.js')}}"></script>
@endpush
@section('content')
    @include('users.partials.header', [
        'title' => __('Hello') . ' '. $user->name,
        'description' => __('This is your profile page. You can see the progress you\'ve made with your work and manage your projects or assigned tasks'),
        'class' => 'col-lg-7'
    ])

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-8 order-xl-2 mb-5 mb-xl-0">
                <div class="card card-profile shadow">


                    <div class="card-body pt-lg--3 pt-md-4">
                        <div style="margin-top: 30px;padding-top: 30px"><p></p></div>

                        <div class="text-left">
                            <form method="post" id="edit-user" action="{{ route('master.user_create') }}" autocomplete="off">
                                @csrf
                                @method('post')
                                <div class="pl-lg-4">

                                    <div class="form-group">
                                        <label class="form-control-label" for="input-name">{{ __('user.name') }}</label>
                                        <input type="text" name="name" class="form-control form-control-alternative"   value="{{ $user->name }}"  />

                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-name">{{ __('user.email') }}</label>
                                        <input type="text" name="email" class="form-control form-control-alternative"   value="{{ $user->email }}"  />

                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label" for="input-name">{{ __('user.roles') }}</label>
                                        <div style="background: #f5f7ff; padding: 15px">
                                            @foreach($roles as $role)
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input" name="roles[]" value="{{$role->id}}"  id="role-{{$role->id}}" type="checkbox" />
                                                    <label class="custom-control-label" for="role-{{$role->id}}">{{$role->description}}</label>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>

                                    <div class="form-group text-center">
                                        <button type="submit"  class="btn btn-success mt-4 ">{{ __('company.save') }}</button>
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
