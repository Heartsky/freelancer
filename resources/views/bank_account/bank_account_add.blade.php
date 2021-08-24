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
                            <h3 class="mb-0">{{ __('bank_account.create_account') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('master.bank_account_create') }}" autocomplete="off">
                            @csrf
                            @method('post')
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('bank_account.name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('bank_account.name') }}" value="{{ old('name', $bankAccount->name) }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('account_name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-account_name">{{ __('bank_account.account_name') }}</label>
                                    <input type="text" name="account_name" id="input-account_name" class="form-control form-control-alternative{{ $errors->has('account_name') ? ' is-invalid' : '' }}" placeholder="{{ __('bank_account.account_name') }}" value="{{ old('account_name', $bankAccount->account_name) }}" required>

                                    @if ($errors->has('account_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('account_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('account_number') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-account_number">{{ __('bank_account.account_number') }}</label>
                                    <input type="text" name="account_number" id="input-account_number" class="form-control form-control-alternative{{ $errors->has('account_number') ? ' is-invalid' : '' }}" placeholder="{{ __('bank_account.account_number') }}" value="{{ old('account_number', $bankAccount->account_number) }}" required>

                                    @if ($errors->has('account_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('account_name') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('cif') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-cif">{{ __('bank_account.cif') }}</label>
                                    <input type="text" name="cif" id="input-cif" class="form-control form-control-alternative{{ $errors->has('cif') ? ' is-invalid' : '' }}" placeholder="{{ __('bank_account.cif') }}" value="{{ old('cif', $bankAccount->cif) }}" required>

                                    @if ($errors->has('cif'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('cif') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('swift_code') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-swift_code">{{ __('bank_account.swift_code') }}</label>
                                    <input type="text" name="swift_code" id="input-swift_code" class="form-control form-control-alternative{{ $errors->has('swift_code') ? ' is-invalid' : '' }}" placeholder="{{ __('bank_account.swift_code') }}" value="{{ old('swift_code', $bankAccount->swift_code) }}" required>

                                    @if ($errors->has('swift_code'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('swift_code') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('currency') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-currency">{{ __('bank_account.currency') }}</label>
                                    <input type="text" name="currency" id="input-currency" class="form-control form-control-alternative{{ $errors->has('currency') ? ' is-invalid' : '' }}" placeholder="{{ __('bank_account.currency') }}" value="{{ old('currency', $bankAccount->currency) }}" required>

                                    @if ($errors->has('currency'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('currency') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('company_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >{{ __('team.company_id') }}</label>

                                    <select class="form-control " name="company_id" id="input-company_id">
                                        <option value="">Select</option>
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}" {{$bankAccount->company_id == $company->id ? 'selected' : ''}}>{{$company->name}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group{{ $errors->has('company_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Branch</label>

                                    <select class="form-control " name="branch_id" id="input-branch_id">
                                        <option value="">Select</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group{{ $errors->has('customer_id') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >{{ __('bank_account.customer_id') }}</label>

                                    <select class="form-control " name="customer_id" id="input-customer_id">
                                        <option value="">Select</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}" {{$bankAccount->customer_id == $customer->id ? 'selected' : ''}}>{{$customer->name}}</option>
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
<input type="hidden" name="url_get_branch" id='url_get_branch' value="{{route('master.get_branch_by_company')}}">
<script type="text/javascript">
$(document).ready(function(){
        function getBranchByCompany(){
            $('#input-branch_id option').remove();
            
            $('#input-branch_id').append($('<option>', {
                value: "",
                text : "Select",
            }));

            $.ajax({
              url: $("#url_get_branch").val()+"?company-id="+$("#input-company_id").val(),
              success: function(data){
                dataOk = $.parseJSON(data);
                $.each(dataOk, function (i, item) {
                    $('#input-branch_id').append($('<option>', {
                        value: item.id,
                        text : item.name,
                    }));
                });
              }
            });
        }

        $("#input-company_id").change(function(){
            //getBranchByCompany();
        });
});
</script>
@endsection
















