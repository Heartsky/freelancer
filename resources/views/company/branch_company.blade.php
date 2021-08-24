@extends('layouts.page')

@section('breakscrum')
    @include('layouts.bars.form_breakscrum',["list" => [ ["title" => "Company Master Job" , "link" => "#" ]  ], 'link_add' =>
    route('master_job_management',['companyId' => $companyId]) ])
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h3 class="mb-0">Company Branch Management</h3>
            </div>
            <!-- form -->
            <div class="row">

                <div class="col-xl-8 order-xl-1">
                    <div class="card bg-secondary shadow">

                        <div class="card-body">
                            @if( !empty($job) and $job->count() > 0)
                                <form method="post" action="{{ route('master_branch_management', ['companyId' => $companyId]) }}" autocomplete="off">
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

                                    <input type="hidden" name="branch_id_update" value="{{$job->id}}">

                                    <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-code">Name</label>
                                            <input type="text" name="name" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Name" value="{{$job->name}}" required autofocus>
                                        </div>
                                        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-code">Code</label>
                                            <input type="text" name="code" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Code" value="{{$job->code}}" required autofocus>
                                        </div>
                                        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-code">Current Cash Balance</label>
                                            <input type="text" name="current_cash_balance" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Current Cash Balance" value="{{$job->current_cash_balance}}" required autofocus>
                                        </div>
                                        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-code">Current Cycle</label>
                                            <input type="text" name="current_cycle" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Current Cycle" value="{{$job->current_cycle}}" required autofocus>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('company.save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <form method="post" action="{{ route('master_branch_management', ['companyId' => $companyId]) }}" autocomplete="off">
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
                                            <label class="form-control-label" for="input-code">Name</label>
                                            <input type="text" name="name" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Name" value="" required autofocus>
                                        </div>
                                        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-code">Code</label>
                                            <input type="text" name="code" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Code" value="" required autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Current Cash Balance</label>
                                            <input type="number" name="current_cash_balance" id="input-email" class="form-control form-control-alternative{{ $errors->has('current_cash_balance') ? ' is-invalid' : '' }}" placeholder="Current Cash Balance" value="" />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Current Cycle</label>
                                            <input type="number" name="current_cycle" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Current Cycle" value="" required>
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
            <!-- Search form -->
            <!-- <div class="row">
                <div class="col-xl-8 order-xl-1">
                    <div class="card bg-secondary shadow">
                        <div class="card-body">
                            <form action="{{ route('master_job_management', ['companyId' => $companyId]) }}" method="get">
                                 <div class="pl-lg-4">
                                    <div class="form-group">
                                        <input type="text" name="keyword" id="input-code" class="form-control form-control-alternative" placeholder="Master Job Name" value="{{$keyword}}" required autofocus>
                                        <button type="submit" class="btn btn-success mt-4">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- end -->
            <!-- Light table -->
            <div class="table-responsive">
            <form action="{{ route('master_job_management', ['companyId' => $companyId]) }}" method="post">
                @csrf
                <input type="hidden" name="save_position" value="1">
                <!-- <div class="text-left">
                    <button type="submit" class="btn btn-success mt-4">Save Position</button>
                </div>-->
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>  
                            <th scope="col" class="sort" data-sort="name">Name</th>
                            <th scope="col" class="sort" data-sort="name">Code</th>
                            <th scope="col" class="sort" data-sort="name">Company</th>
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
                                    {{ $company->name }}
                                </td>
                                <td >
                                    {{ $company->code }}
                                </td>
                                <td >
                                    {{ $company->company()->get()[0]->name }}
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
                                            <a class="dropdown-item" href="{{ route('master_branch_management', ['companyId' => $companyId]) }}?branch_id={{$company->id}}">Edit</a>
                                            <a class="dropdown-item" href="{{route('master_branch_management_delete',['id' => $company->id, 'companyId' => $companyId])}}">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection
