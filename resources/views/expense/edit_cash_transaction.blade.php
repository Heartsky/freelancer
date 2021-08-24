@extends('layouts.page')

@section('breakscrum')
@include('layouts.bars.form_breakscrum',["list" => [ ["title" => __('Edit  Cash Transaction') , "link" => "#" ]  ], 'link_add' => ''  ])
@endsection
@push('js')
{{--    <script src="{{ asset('js/invoice.js')}}"></script>--}}
@endpush
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Edit Cash Transaction</h3>
            </div>

            <!-- Card body -->
            <div class="card-body">
                <form method="post" action="{{ route('expense.cash_transaction_update', ['id' => $item->id]) }}" >
                    @csrf
                    @method('post')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" >Branch</label>
                                <select class="form-control " name="branch_id" id="input-branch_id" required>
                                    <option value="">{{__('Select')}}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{$branch->id}}" {{$item->branch_id == $branch->id ? 'selected' : ''}} >{{$branch->name}}</option>
                                    @endforeach

                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" >Currency</label>
                                <select class="form-control " name="currency" id="input-currency" required>
                                    <option value="">{{__('Select')}}</option>
                                    @foreach($currency as $cur)
                                        <option value="{{$cur}}" {{$item->currency == $cur ? 'selected' : ''}} >{{$cur}}</option>
                                    @endforeach

                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row  align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Transaction Date</label>
                                <input class="form-control datepicker" name="expense_date" placeholder="Transaction date" type="text" value="{{ $item->expense_date}}" required>
                            </div>
                        </div>
                        <div class="col">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" >Type</label>
                                <select class="form-control " name="type" id="input-type" required>
                                    <option value="">{{__('Select')}}</option>
                                    <option value="debited" {{$item->type == 'debited' ? 'selected' : ''}}>{{__('Debited')}}</option>
                                    <option value="credited" {{$item->type == 'credited' ? 'selected' : ''}} >{{__('Credited')}}</option>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="row  align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Amount</label>
                                <input class="form-control" name="amount" placeholder="Amount" type="text" value="{{$item->amount}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row  align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Exchange Rate</label>
                                <input class="form-control" name="rate" placeholder="Exchange rate" type="text" value="{{$item->rate}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row  align-items-center">
                        <div class="col">

                            <div class="form-group">
                                <label class="form-control-label">Description</label>
                                <textarea class="form-control" name="description" placeholder="Description" type="text" value="{{$item->description}}" >{{$item->description}}</textarea>
                            </div>

                        </div>
                    </div>


                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                    <a href="{{route('expense.cash_transaction_list')}}">
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
