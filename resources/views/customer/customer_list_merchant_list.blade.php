@extends('layouts.page')

@section('breakscrum')
    @include('layouts.bars.form_breakscrum',["list" => [ ["title" => __('customer.list_customer') , "link" => "#" ]  ], 'link_add' => route('master.customer_create')  ])
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h3 class="mb-0">Customer of Merchant </h3>
            </div>
            <!-- Search form -->
            <div class="row">
                <div class="col-xl-8 order-xl-1">
                    <div class="card bg-secondary shadow">
                        <div class="card-body">
                            <form action="{{ route('customer_merchant_list', ['customerId' => $customerId]) }}" method="post">
                                @csrf
                                @method('post')
                                 <div class="pl-lg-4">
                                    <div class="form-group">
                                        <div class="form-group{{ $errors->has('company_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" >Customer</label>

                                            <select class="form-control " name="customer_of_merchant_id" id="input-company_id">
                                                <option value="">Select</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-success mt-4">Add Customer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end -->
            <!-- Light table -->
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="name">Name</th>
                            <th scope="col" class="sort" data-sort="budget">Address</th>
                            <th scope="col" class="sort" data-sort="completion">Last Updated</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @if ($data->count() == 0)
                            <tr>
                                <td >Have No record </td>
                            </tr>

                        @else
                            @foreach((array)$data->items() as $company)
                            <tr>

                                <td class="budget">
                                    {{ $company->name }}
                                </td>
                                <td >
                                    {{ $company->address }}
                                </td>
                                <td >
                                    {{ $company->updated_at }}
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{route('customer_merchant_list_remove',['customerId' => $company->id])}}">Remove customer</a>
                                        </div>
                                    </div>    
                                </td>
                            </tr>
                        @endforeach
                        @endif

                    </tbody>
                </table>
            </div>


            <!-- Card footer -->
            <div class="card-footer py-4">
                <nav aria-label="...">
                    <ul class="pagination justify-content-end mb-0">
                        <li class="page-item ">
                            <a class="page-link" href="{{ $data->previousPageUrl()}}" tabindex="-1">
                                <i class="fas fa-angle-left"></i>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>


                        <li class="page-item  disabled active">
                            <a class="page-link" href="#">{{ $data->currentPage() }}</a>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="{{$data->nextPageUrl()}}">
                                <i class="fas fa-angle-right"></i>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection
