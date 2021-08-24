@extends('layouts.page')

@section('breakscrum')
@include('layouts.bars.form_breakscrum',["list" => [ ["title" => 'Bank Report' , "link" => "#" ]  ], 'link_add' => ''  ])
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
                <h3 class="mb-0">Export Bank Report</h3>
            </div>

            <!-- Card body -->
            <div class="card-body">
                <form method="post" action="{{ route('expense.bank_transaction_export') }}" >
                    @csrf
                    @method('post')

{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="form-control-label" >Branch</label>--}}
{{--                                <select class="form-control " name="branch_id" id="input-branch_id" required>--}}
{{--                                    <option value="">{{__('Select')}}</option>--}}
{{--                                    @foreach($branches as $branch)--}}
{{--                                        <option value="{{$branch->id}}" >{{$branch->name}}</option>--}}
{{--                                    @endforeach--}}

{{--                                </select>--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" >BankAccount</label>
                                <select class="form-control " name="bank_account_id" id="input-branch_id" required >
                                    <option value="">{{__('Select')}}</option>
                                    @foreach($accounts as $account)
                                        <option value="{{$account->id}}"  >{{$account->name}} ({{$account->currency}})</option>
                                    @endforeach

                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="row  align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Current Balance</label>
                                <input class="form-control" name="balance" placeholder="Current Balance" type="text" value="" required>
                            </div>
                        </div>
                    </div>

                    <div class="row input-daterange datepicker align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Start date</label>
                                <input class="form-control" name="start_date" placeholder="Start date" type="text" value="{{$start_date?? ''}}" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">End date</label>
                                <input class="form-control" name="end_date" placeholder="End date" type="text" value="{{$end_date?? ''}}" required>
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
