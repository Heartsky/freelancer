@extends('layouts.page')

@section('breakscrum')
@include('layouts.bars.form_breakscrum',["list" => [ ["title" => __('Add Bank Transaction') , "link" => "#" ]  ], 'link_add' => ''  ])
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
                <h3 class="mb-0">Add Bank Transaction</h3>
            </div>

            <!-- Card body -->
            <div class="card-body">
                <form method="post" action="{{ route('expense.bank_transaction_save') }}" >
                    @csrf
                    @method('post')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" >Branch</label>
                                <select class="form-control " name="branch_id" id="input-branch_id" required>
                                    <option value="">{{__('Select')}}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{$branch->id}}" >{{$branch->name}}</option>
                                    @endforeach

                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" >BankAccount</label>
                                <select class="form-control " name="bank_account_id" id="input-branch_id" required>
                                    <option value="">{{__('Select')}}</option>
                                    @foreach($accounts as $account)
                                        <option value="{{$account->id}}" >{{$account->name}}</option>
                                    @endforeach

                                </select>

                            </div>
                        </div>
                    </div>


                    <div class="row  align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Transaction Date</label>
                                <input class="form-control datepicker" name="expense_date" placeholder="Transaction date" type="text" value="" required>
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
                                    <option value="debited">{{__('Debited')}}</option>
                                    <option value="credited">{{__('Credited')}}</option>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row  align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Customer</label>
                                <input class="form-control" name="customer_name" placeholder="Customer" type="text" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row  align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Transaction Code</label>
                                <input class="form-control" name="transaction_code" placeholder="Transaction Code" type="text" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row  align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Exchange Rate</label>
                                <input class="form-control" name="rate" placeholder="Exchange rate" type="text" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row  align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Amount</label>
                                <input class="form-control" name="amount" placeholder="Amount" type="text" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row  align-items-center">
                        <div class="col">

                            <div class="form-group">
                                <label class="form-control-label">Description</label>
                                <textarea class="form-control" name="description" placeholder="Description" type="text" value="" ></textarea>
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
