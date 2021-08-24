@extends('layouts.app', ['title' => __('company.page_title')])
@push('js')
    <script src="{{ asset('js/company_edit.js')}}"></script>
@endpush
@section('content')
    @include('users.partials.header', [
        'title' => 'Customer Detail',
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

                            <a href="#" target-form="edit-company" class="btn btn-sm btn-default float-right" id="company-edit-button">{{ __('edit') }}</a>
                        </div>
                    </div>
                    <div class="card-body pt-0 pt-md-4">

                        <div class="">
                        <form method="post" id="edit-company" action="{{ route('master.customer_detail',['id' => $customer->id]) }}" autocomplete="off">
                            @csrf
                            @method('post')
                            <div class="pl-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-code">{{ __('company.code') }}</label>
                                    <input type="text" name="code" id="input-code" class="form-control form-control-alternative"  disabled value="{{ $customer->code }}"  />

                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="input-name">{{ __('company.name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative"  disabled value="{{ $customer->name }}"  />

                                </div>
                                @if(empty($customer->merchant_id))
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input name="is_merchant" @if($customer->is_merchant == 1) checked="checked" @endif class="custom-control-input" id="is_merchant" value="1" type="checkbox">
                                    <label class="custom-control-label" for="is_merchant">
                                        <span class="text-muted"> Is Merchant</span>
                                    </label>
                                </div>
                                @endif

                                <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">Company</label>
                                    <select name="company_ids[]" class="form-control" data-toggle="select" multiple data-placeholder="Select multiple options">
                                    @foreach($companyAll as $cp)
                                        <option {{in_array($cp->id, $customerCompanyArr) ? "selected" : ""}} value="{{$cp->id}}">{{$cp->name}}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="input-address">{{ __('company.address') }}</label>
                                    <input type="text" name="address" class="form-control form-control-alternative"  disabled value="{{ $customer->address }}"  />

                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-tax_code">{{ __('customer.fax') }}</label>
                                    <input type="text" name="fax" id="input-tax_code" class="form-control form-control-alternative" disabled placeholder="{{ __('customer.fax') }}" value="{{ $customer->fax }}"  />
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="input-phone_number">{{ __('customer.phone_number') }}</label>
                                    <input type="text" name="phone_number" class="form-control form-control-alternative"  disabled value="{{ $customer->phone_number }}"  />

                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="input-price_item">Price by Item</label>
                                    <input type="text" name="price_item" class="form-control form-control-alternative"  disabled value="{{ $customer->price_item }}"  />

                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-price_tsubo">Price by Tsubo</label>
                                    <input type="text" name="price_tsubo" id="input-price_tsubo" class="form-control form-control-alternative" disabled placeholder="" value="{{ $customer->price_tsubo }}"  />
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="input-phone_number">Price by Acreage</label>
                                    <input type="text" name="price_acreage" class="form-control form-control-alternative"  disabled value="{{ $customer->price_acreage }}"  />

                                </div>
                                <div class="form-group{{ $errors->has('type_invoice') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-type_invoice">Type Invoice</label>
                                    <select class="form-control " name="type_invoice" id="input-invoice_type" disabled required>
                                        <option value="">{{__('Select')}}</option>
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}" @if($customer->type_invoice == $type->id) selected @endif >{{$type->name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-phone_number">Summary Rank Code</label>
                                    <input type="text" name="summary_rank" class="form-control form-control-alternative"  disabled value="{{ $customer->summary_rank }}"  />

                                </div>

                                <div class="form-group text-center">
                                    <button type="submit"  class="btn btn-success mt-4 collapse">{{ __('customer.save') }}</button>
                                    <a href="{{route('master.customer_management')}}">
                                        <button type="button" class="btn btn-danger mt-4">{{ __('customer.cancel') }}</button>
                                    </a>
                                </div>

                            </div>

                        </form>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">

                <div class="card card-profile shadow">
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class="justify-content-between">
                            @if(empty($bankAccount))
                                <a href="#" target-form="edit-customer-account" class="btn btn-sm btn-default float-right" id="account-edit-button">{{ __('bank_account.add') }}</a>
                            @else
                                <a href="#" target-form="edit-customer-account" class="btn btn-sm btn-default float-right" id="account-edit-button">{{ __('bank_account.edit') }}</a>
                            @endif
                        </div>
                    </div>
                    <p></p>
                    <div class="text-center">
                        <h3>
                            Bank Account Infomation
                        </h3>
                    </div>
                    <div class="card-body pt-0 pt-md-4">
                        <form method="post" id="edit-customer-account" action="{{ route('master.customer_update_bank_account',['id' => $customer->id]) }}" autocomplete="off">
                            @csrf
                            @method('post')
                            <div class="form-group">
                                <label class="form-control-label" for="input-name">{{ __('bank_account.name') }}</label>
                                <input type="text"  name="name" class="form-control form-control-alternative"  disabled value="{{ @$bankAccount->name }}"  />

                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="input-name">{{ __('bank_account.account_name') }}</label>
                                <input type="text" name="account_name"  class="form-control form-control-alternative"  disabled value="{{ @$bankAccount->account_name }}"  />

                            </div>

                            <div class="form-group">
                                <label class="form-control-label" for="input-name">{{ __('bank_account.account_number') }}</label>
                                <input type="text" name="account_number" class="form-control form-control-alternative"  disabled value="{{ @$bankAccount->account_number }}"  />

                            </div>

                            <div class="form-group">
                                <label class="form-control-label" for="input-name">{{ __('bank_account.cif') }}</label>
                                <input type="text" name="cif" class="form-control form-control-alternative"  disabled value="{{ @$bankAccount->cif }}"  />

                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="input-name">{{ __('bank_account.swift_code') }}</label>
                                <input type="text" name="swift_code" class="form-control form-control-alternative"  disabled value="{{ @$bankAccount->swift_code }}"  />

                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="input-name">{{ __('bank_account.currency') }}</label>
                                <input type="text" name="currency" class="form-control form-control-alternative"  disabled value="{{ @$bankAccount->currency }}"  />

                            </div>
                            <div class="form-group text-center">
                                <button type="submit"  class="btn btn-success mt-4 collapse">{{ __('company.save') }}</button>
                            </div>
                        </form>

                    </div>
                    </div>
                </div>

        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
