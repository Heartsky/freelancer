@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Hello') . ' '. auth()->user()->name,
        'description' => __('This is your profile page. You can see the progress you\'ve made with your work and manage your projects or assigned tasks'),
        'class' => 'col-lg-7'
    ])

    <div class="container-fluid mt--7">
        <div class="row">

            <div class="col-xl-8 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">{{ __('customer.create_customer') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('master.customer_create') }}" autocomplete="off">
                            @csrf
                            @method('post')
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif


                            <div class="pl-lg-4">

                                <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-code">{{ __('customer.code') }}</label>
                                    <input type="text" name="code" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="{{ __('customer.code') }}" value="" required autofocus>

                                    @if ($errors->has('code'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('customer.name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('customer.name') }}" value="" required>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input name="is_merchant" class="custom-control-input" id="is_merchant" value="1" type="checkbox">
                                    <label class="custom-control-label" for="is_merchant">
                                        <span class="text-muted"> Is Merchannt</span>
                                    </label>
                                </div>
                                <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">{{ __('customer.address') }}</label>
                                    <input type="text" name="address" id="input-email" class="form-control form-control-alternative{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('customer.address') }}" value="" required>

                                    @if ($errors->has('address'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-email">Company</label>
                                    <select name="company_ids[]" class="form-control" data-toggle="select" multiple data-placeholder="Select multiple options">
                                    @foreach($company as $cp)
                                        <option value="{{$cp->id}}">{{$cp->name}}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="form-group{{ $errors->has('phone_number') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-phone_number">{{ __('customer.phone_number') }}</label>
                                    <input type="text" name="phone_number" id="input-phone_number" class="form-control form-control-alternative{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" placeholder="{{ __('customer.phone_number') }}" value="" required>

                                    @if ($errors->has('phone_number'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('phone_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('fax') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-fax">{{ __('customer.fax') }}</label>
                                    <input type="text" name="fax" id="input-fax" class="form-control form-control-alternative{{ $errors->has('fax') ? ' is-invalid' : '' }}" placeholder="{{ __('customer.fax') }}" value="" required>

                                    @if ($errors->has('fax'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('fax') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                 <div class="form-group{{ $errors->has('price_item') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-price_item">{{ "Price by Item" }}</label>
                                    <input type="text" name="price_item" id="input-price_item" class="form-control form-control-alternative{{ $errors->has('price_item') ? ' is-invalid' : '' }}" placeholder="{{ __('customer.price') }}" value="" required>

                                    @if ($errors->has('price_item'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('price_item') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('price_tsubo') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-price_tsubo">{{ "Price by Tsubo" }}</label>
                                    <input type="text" name="price_tsubo" id="input-price_tsubo" class="form-control form-control-alternative{{ $errors->has('price_tsubo') ? ' is-invalid' : '' }}" placeholder="{{ __('customer.price') }}" value="" required>

                                    @if ($errors->has('price_tsubo'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('price_tsubo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('price_acreage') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-fax">{{ "Price by Acreage" }}</label>
                                    <input type="text" name="price_acreage" id="input-price_acreage" class="form-control form-control-alternative{{ $errors->has('price_acreage') ? ' is-invalid' : '' }}" placeholder="{{ __('customer.price') }}" value="" required>

                                    @if ($errors->has('price_acreage'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('price_acreage') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('type_invoice') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-type_invoice">Type Invoice</label>
                                    <select class="form-control " name="type_invoice" id="input-invoice_type" required>
                                        <option value="">{{__('Select')}}</option>
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}" >{{$type->name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="form-group{{ $errors->has('summary_rank') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-summary_rank">{{ "Summary Rank Code" }}</label>
                                    <input type="text" name="summary_rank" id="input-summary_rank" class="form-control form-control-alternative{{ $errors->has('summary_rank') ? ' is-invalid' : '' }}" placeholder="{{ __('Rank code') }}" value="" >

                                    @if ($errors->has('summary_rank'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('summary_rank') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('customer.save') }}</button>
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

        @include('layouts.footers.auth')
    </div>
@endsection
