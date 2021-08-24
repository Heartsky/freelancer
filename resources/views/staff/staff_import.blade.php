@extends('layouts.page')

@section('breakscrum')
    @include('layouts.bars.form_breakscrum',["list" => [ ["title" => "Import Staff" , "link" => "#" ]  ], 'link_add' => ''])
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h3 class="mb-0">Import Staff</h3>
            </div>
            <!-- Light table -->
            <div class="card-body">
                        <form method="post" action="{{ route('master.staff_import') }}" enctype="multipart/form-data" autocomplete="off">
                            <div class="pl-lg-4">
                                @csrf
                                @method('post')
                                @if (@$message !== '')
                                    <div class="alert alert-{{@$message['status']}} alert-dismissible fade show" role="alert">
                                        {{@$message['msg']}}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <div class="form-group{{ $errors->has('company_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Company</label>

                                    <select class="form-control " name="company_id" id="input-company_id">
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}"}}>{{$company->name}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-code">File Upload</label>
                                    <input type="file" name="input-data" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="{{ __('customer.code') }}" value="" required autofocus>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('customer.save') }}</button>
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
@endsection
