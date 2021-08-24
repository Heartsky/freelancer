@extends('layouts.page')



@section('breakscrum')
    @include('layouts.bars.form_breakscrum',["list" => [ ["title" => "Upload data" , "link" => "#" ]  ], 'link_add' => ''])
@endsection
@push('js')
    <script src="{{ asset('js/upload.js')}}"></script>
@endpush
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h3 class="mb-0">Upload Data Input</h3>
            </div>
            <!-- Light table -->
            <div class="card-body">
                        <form method="post" action="{{ route('upload_data') }}" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            @method('post')
                            @if (@$status !== '')
                                <div class="alert alert-{{@$status}} alert-dismissible fade show" role="alert">
                                    {{ @$msg }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="exampleDatepicker">Company </label>
                                        <select class="form-control " name="company_id" id="input-company_id" required>
                                            <option>Select</option>
                                            @foreach($companies as $company)
                                                <option value="{{$company->id}}" >{{$company->name}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="exampleDatepicker">Team</label>
                                        <select class="form-control " name="team_id" id="input-team_id" required>
                                            <option>Select</option>

                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-code">File Upload</label>
                                        <input type="file" name="input-data" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="{{ __('customer.code') }}" value="" required autofocus>


                                    </div>
                                </div>
                            </div>

                            <div class="pl-lg-4">

                                    <button type="submit" class="btn btn-success mt-4">{{ __('customer.save') }}</button>
                                    <a href="{{route('home')}}">
                                        <button type="button" class="btn btn-danger mt-4">{{ __('customer.cancel') }}</button>
                                    </a>

                            </div>
                        </form>
                    </div>

        </div>
    </div>
</div>
<input type="hidden" id="ajax-invoice-type", value="{{route('export.customer_invoice_type')}}" />
@endsection
