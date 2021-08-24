@extends('layouts.page')

@section('breakscrum')
@include('layouts.bars.form_breakscrum',["list" => [ ["title" => __('invoice_customer.title') , "link" => "#" ]  ], 'link_add' => ''  ])
@endsection
@push('js')
    <script src="{{ asset('js/invoice.js')}}"></script>
@endpush
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Export Customer Invoice</h3>
            </div>

            <!-- Card body -->
            <div class="card-body">
                <form method="post" action="{{ route('export.export_customer_invoice') }}" >
                    @csrf
                    @method('post')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="exampleDatepicker">Company</label>
                                <select class="form-control " name="company_id" id="input-company_id" required>
                                    <option value="">{{__('Select')}}</option>
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
                                <label class="form-control-label" for="exampleDatepicker">Customer</label>
                                <select class="form-control " name="customer_id" id="input-customer_id" required>
                                    <option value="">{{__('Select')}}</option>
{{--                                    @foreach($customers as $customer)--}}

{{--                                        <option value="{{$customer->id}}" >{{$customer->name}}</option>--}}
{{--                                    @endforeach--}}

                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="exampleDatepicker">Invoice Type </label>
                                <select class="form-control " name="invoice_type" id="input-invoice_type" required>
                                    <option value="">{{__('Select')}}</option>
                                    @foreach($types as $type)
                                        <option value="{{$type->id}}" >{{$type->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row input-daterange datepicker align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Start date</label>
                                <input class="form-control" name="start_date" placeholder="Start date" type="text" value="{{$start_date}}" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">End date</label>
                                <input class="form-control" name="end_date" placeholder="End date" type="text" value="{{$end_date}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row  align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Export Date</label>
                                <input class="form-control datepicker" name="export_date" placeholder="Export date" type="text" value="" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Order Date</label>
                                <input class="form-control datepicker" name="order_date" placeholder="Order date" type="text" value="" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Ship Date</label>
                                <input class="form-control datepicker" name="ship_date" placeholder="Ship date" type="text" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('customer.export') }}</button>
                                    <a href="{{route('home')}}">
                                        <button type="button" class="btn btn-danger mt-4">{{ __('customer.cancel') }}</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
    <input type="hidden" id="ajax-invoice-type", value="{{route('export.customer_invoice_type')}}" />
@endsection
