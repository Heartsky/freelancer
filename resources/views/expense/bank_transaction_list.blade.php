@extends('layouts.page')

@section('breakscrum')
    @include('layouts.bars.form_breakscrum',["list" => [ ["title" => "Bank Transaction" , "link" => "#" ]  ], 'link_add' => route('expense.bank_transaction_add')  ])
@endsection
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Bank Transaction Management</h3>

                </div>
                <!-- Light table -->
                <div class="table-responsive">

                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" >Transaction date</th>
                            <th scope="col" >Amount</th>
                            <th scope="col">Description</th>
                            <th scope="col">Type</th>
                            <th scope="col">Customer</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @if ($data->count() == 0)
                            <tr>
                                <td >Have No record </td>
                            </tr>

                        @else
                            @foreach((array)$data->items() as $account)
                                <tr>

                                    <td class="budget">
                                        {{ $account->expense_date }}
                                    </td>
                                    <td >
                                        {{ $account->amount }}
                                    </td>
                                    <td class="budget">
                                        {{ $account->description }}
                                    </td>
                                    <td class="budget">
                                        {{ $account->type }}
                                    </td>


                                    <td >
                                        {{ $account->customer_name }}
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" href="{{route('expense.bank_transaction_edit',['id' => $account->id])}}">Detail</a>

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
