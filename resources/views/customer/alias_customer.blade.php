@extends('layouts.page')

@section('breakscrum')
    @include('layouts.bars.form_breakscrum',["list" => [ ["title" => "Customer Alias Name" , "link" => "#" ]  ], 'link_add' =>
    route('customer_alias_name',['customerId' => $customerId]) ])
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h3 class="mb-0">Customer Alias Name Management</h3>
            </div>
            <!-- form -->
            <div class="row">

                <div class="col-xl-8 order-xl-1">
                    <div class="card bg-secondary shadow">

                        <div class="card-body">
                            @if( !empty($aliasName) and $aliasName->count() > 0)
                                <form method="post" action="{{ route('customer_alias_name', ['customerId' => $customerId]) }}" autocomplete="off">
                                @csrf
                                    @method('post')
                                    <input type="hidden" name="alias_id_edit" value="{{$aliasName->id}}">

                                    <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('alias_name') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-alias_name">Alias Name</label>
                                            <input type="text" name="alias_name" id="input-alias_name" class="form-control form-control-alternative{{ $errors->has('alias_name') ? ' is-invalid' : '' }}" placeholder="Alias Name" value="{{$aliasName->alias_name}}" required autofocus>
                                        </div>
                                        <div class="form-group{{ $errors->has('alias_code') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-alias_code">Alias Code</label>
                                            <input type="text" name="alias_code" id="input-alias_code" class="form-control form-control-alternative{{ $errors->has('alias_code') ? ' is-invalid' : '' }}" placeholder="Alias Name" value="{{$aliasName->alias_code}}" required autofocus>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('company.save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <form method="post" action="{{ route('customer_alias_name', ['customerId' => $customerId]) }}" autocomplete="off">
                                @csrf
                                    @method('post')
                                    <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-code">Alias Name</label>
                                            <input type="text" name="alias_name" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Alias Name" value="" required autofocus>
                                        </div>
                                        <div class="form-group{{ $errors->has('alias_code') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-alias_code">Alias Code</label>
                                            <input type="text" name="alias_code" id="input-alias_code" class="form-control form-control-alternative{{ $errors->has('alias_code') ? ' is-invalid' : '' }}" placeholder="Alias Code" value="" required autofocus>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('company.save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            @endif
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
                            <th scope="col" class="sort" data-sort="completion">Last Updated</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @if (count($data) == 0)
                            <tr>
                                <td >Have No record </td>
                            </tr>

                        @else
                            @foreach($data as $company)
                            <tr>

                                <td class="budget">
                                    {{ $company->alias_name }}
                                </td>
                                <td >
                                    {{ $company->updated_at }}
                                </td>
                                <td class="text">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('customer_alias_name', ['customerId' => $customerId]) }}?alias_id={{$company->id}}">Edit</a>
                                            <a class="dropdown-item" href="{{route('customer_alias_delete',['id' => $company->id, 'customerId' => $customerId])}}">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
