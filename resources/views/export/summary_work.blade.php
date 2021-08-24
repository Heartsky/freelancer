@extends('layouts.page')

@section('breakscrum')
@include('layouts.bars.form_breakscrum',["list" => [ ["title" => __('summary_work.title') , "link" => "#" ]  ], 'link_add' => ''  ])
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">

            <div class="card-header">
                <h3 class="mb-0">{{__('summary_work.title')}}</h3>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="exampleDatepicker">Company </label>
                                <select class="form-control " name="company_id" id="input-company_id">
                                    @foreach($companies as $company)
                                        <option value="-1">{{__('company.all_item')}}</option>
                                        <option value="{{$company->id}}" >{{$company->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="exampleDatepicker">Customer</label>
                                <select class="form-control " name="company_id" id="input-company_id">
                                    @foreach($customers as $customer)
                                        <option value="-1">{{__('customer.all_item')}}</option>
                                        <option value="{{$customer->id}}" >{{$customer->name}}</option>
                                    @endforeach

                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="row input-daterange datepicker align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Start date</label>
                                <input class="form-control" placeholder="Start date" type="text" value="{{$start_date}}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">End date</label>
                                <input class="form-control" placeholder="End date" type="text" value="{{$end_date}}">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
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
@endsection
