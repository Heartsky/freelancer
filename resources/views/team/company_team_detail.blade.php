@extends('layouts.app', ['title' => __('company.page_title')])
@push('js')
    <script src="{{ asset('js/company_edit.js')}}"></script>
@endpush
@section('content')
    @include('users.partials.header', [
        'title' => 'Company Detail',
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
                        <div class="row">
                            <div class="col">
                                <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                    <div>
                                        <span class="heading">{{ $company->teams()->count() }}</span>
                                        <span class="description">{{ __('Team') }}</span>
                                    </div>
                                    <div>
                                        <span class="heading">{{ $company->staffs()->count() }}</span>
                                        <span class="description">{{ __('staff') }}</span>
                                    </div>
                                    <div>
                                        <span class="heading">{{ $company->customers()->count() }}</span>
                                        <span class="description">{{ __('customer') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="">
                        <form method="post" id="edit-company" action="{{ route('master.company_update',['id' => $company->id]) }}" autocomplete="off">
                            @csrf
                            @method('post')
                            <div class="pl-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-code">{{ __('company.code') }}</label>
                                    <input type="text" name="code" id="input-code" class="form-control form-control-alternative"  disabled value="{{ $company->code }}"  />

                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="input-name">{{ __('company.name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative"  disabled value="{{ $company->name }}"  />

                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="input-email">{{ __('company.email') }}</label>
                                    <input type="text" name="email" class="form-control form-control-alternative"  disabled value="{{ $company->email }}"  />

                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="input-tax_code">{{ __('company.tax_code') }}</label>
                                    <input type="text" name="tax_code" id="input-tax_code" class="form-control form-control-alternative" disabled placeholder="{{ __('company.tax_code') }}" />
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-address">{{ __('company.address') }}</label>
                                    <input type="text" name="address" class="form-control form-control-alternative"  disabled value="{{ $company->address }}"  />

                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-phone_number">{{ __('company.phone_number') }}</label>
                                    <input type="text" name="phone_number" class="form-control form-control-alternative"  disabled value="{{ $company->phone_number }}"  />

                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-fax">{{ __('company.fax') }}</label>
                                    <input type="text" name="fax"  class="form-control form-control-alternative"  disabled value="{{ $company->fax }}"  />

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
            <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">

                <div class="card card-profile shadow">
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class="justify-content-between">
                            @if(empty($bankAccount))
                                <a href="#" class="btn btn-sm btn-default float-right">{{ __('bank_account.add') }}</a>
                            @else
                                <a href="#{{$bankAccount->id}}" class="btn btn-sm btn-default float-right">{{ __('bank_account.edit') }}</a>
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

                        <div class="form-group">
                            <label class="form-control-label" for="input-name">{{ __('bank_account.name') }}</label>
                            <input type="text" class="form-control form-control-alternative"  disabled value="{{ @$bankAccount->name }}"  />

                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="input-name">{{ __('bank_account.account_name') }}</label>
                            <input type="text" class="form-control form-control-alternative"  disabled value="{{ @$bankAccount->account_name }}"  />

                        </div>

                        <div class="form-group">
                            <label class="form-control-label" for="input-name">{{ __('bank_account.account_number') }}</label>
                            <input type="text" class="form-control form-control-alternative"  disabled value="{{ @$bankAccount->account_number }}"  />

                        </div>

                        <div class="form-group">
                            <label class="form-control-label" for="input-name">{{ __('bank_account.cif') }}</label>
                            <input type="text" class="form-control form-control-alternative"  disabled value="{{ @$bankAccount->cif }}"  />

                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="input-name">{{ __('bank_account.swift_code') }}</label>
                            <input type="text" class="form-control form-control-alternative"  disabled value="{{ @$bankAccount->swift_code }}"  />

                        </div>
                        <div class="form-group">
                            <label class="form-control-label" for="input-name">{{ __('bank_account.currency') }}</label>
                            <input type="text" class="form-control form-control-alternative"  disabled value="{{ @$bankAccount->currency }}"  />

                        </div>


                    </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection
