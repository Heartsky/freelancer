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
                    <h3 class="mb-0">Revenue report</h3>
                </div>

                <!-- Card body -->
                <div class="card-body">
                    <form method="post" action="{{ route('revenue.export') }}" >
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
                        <div class="row  align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">Export Cycle</label>
                                    <input class="form-control" name="cycle" placeholder="" type="text" value="{{ date("Y-m-d") }}" required>
                                </div>
                            </div>
                        </div>


{{--                        <div class="row  align-items-center">--}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="form-control-label">Exchange Rate USD</label>--}}
{{--                                    <input class="form-control" name="rate-usd" placeholder="" type="text" value="" required>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="row  align-items-center">--}}
{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label class="form-control-label">Exchange Rate JPY</label>--}}
{{--                                    <input class="form-control" name="rate-jpy" placeholder="" type="text" value="" required>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}


                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success mt-4">{{ __('customer.save') }}</button>
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
