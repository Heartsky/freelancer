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
                <h3 class="mb-0">Company Master Job Management</h3>
            </div>
            <!-- form -->
            <div class="row">

                <div class="col-xl-8 order-xl-1">
                    <div class="card bg-secondary shadow">

                        <div class="card-body">
                            @if( !empty($job) and $job->count() > 0)
                                <form method="post" action="{{ route('master_job_management', ['companyId' => $companyId]) }}" autocomplete="off">
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

                                    <input type="hidden" name="job_id" value="{{$job->id}}">

                                    <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-code">Master Job Name</label>
                                            <input type="text" name="job_master_name" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Master Job Name" value="{{$job->job_master_name}}" required autofocus>
                                        </div>
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input name="is_rank" @if($job->is_rank == 1) checked="checked" @endif class="custom-control-input" id="is_rank" value="1" type="checkbox">
                                            <label class="custom-control-label" for="is_rank">
                                                <span class="text-muted"> Is Rank</span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Rank Code</label>
                                            <input type="text" name="rank_code" id="input-email" class="form-control form-control-alternative{{ $errors->has('rank_code') ? ' is-invalid' : '' }}" placeholder="Rank Code" value="{{$job->rank_code}}" >
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Summary Order</label>
                                            <input type="number" name="summary_order" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Summary Order" value="{{$job->summary_order}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" >Summary Type</label>
                                            <select class="form-control " name="summary_type" id="input-summary_type" required>
                                                @foreach($summaryTypes as $key => $type)
                                                    <option value="{{$key}}" @if($job->summary_type == $key) selected @endif >{{$type}}</option>
                                                @endforeach

                                            </select>

                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" >Summary Group</label>
                                            <select class="form-control " name="summary_group" id="input-summary_group" required>
                                                @foreach($summaryGroup as $key => $type)
                                                    <option value="{{$key}}" @if($job->summary_group == $key) selected @endif >{{$type}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input name="is_enable" @if($job->is_enable == 1) checked="checked" @endif class="custom-control-input" id="is_enable" value="1" type="checkbox">
                                            <label class="custom-control-label" for="is_enable">
                                                <span class="text-muted"> Enable</span>
                                            </label>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('company.save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <form method="post" action="{{ route('master_job_management', ['companyId' => $companyId]) }}" autocomplete="off">
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
                                            <label class="form-control-label" for="input-code">Master Job Name</label>
                                            <input type="text" name="job_master_name" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Master Job Name" value="" required autofocus>
                                        </div>
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input name="is_rank" class="custom-control-input" id="is_rank" value="1" type="checkbox">
                                            <label class="custom-control-label" for="is_rank">
                                                <span class="text-muted"> Is Rank</span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Rank Code</label>
                                            <input type="text" name="rank_code" id="input-email" class="form-control form-control-alternative{{ $errors->has('rank_code') ? ' is-invalid' : '' }}" placeholder="Rank Code" value="" />
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Summary Order</label>
                                            <input type="number" name="summary_order" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Summary Order" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" >Summary Type</label>
                                            <select class="form-control " name="summary_type" id="input-summary_type" required>
                                                @foreach($summaryTypes as $key => $type)
                                                    <option value="{{$key}}" >{{$type}}</option>
                                                @endforeach

                                            </select>

                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" >Summary Group</label>
                                            <select class="form-control " name="summary_group" id="input-summary_group" required>
                                                @foreach($summaryGroup as $key => $type)
                                                    <option value="{{$key}}" >{{$type}}</option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input name="is_enable" class="custom-control-input" id="is_enable" value="1" type="checkbox">
                                            <label class="custom-control-label" for="is_enable">
                                                <span class="text-muted"> Enable </span>
                                            </label>
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
            <div class="row">
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
            </div>
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
                            <th scope="col" class="sort" data-sort="budget">Is Rank</th>
                            <th scope="col" class="sort" data-sort="completion">Last Updated</th>
                            <th scope="col">Summary Order</th>
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
                                    {{ $company->job_master_name }}
                                </td>
                                <td >
                                    {{ $company->is_rank }}
                                </td>
                                <td >
                                    {{ $company->updated_at }}
                                </td>
                                <td class="text">
                                    <!-- <input type="number" name="position[{{$company->id}}][]" value="{{ $company->summary_order }}" size="5" maxlength=5>-->
                                    {{ $company->summary_order }}
                                </td>
                                <td class="text">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('customer_job_management', ['masterId' => $company->id]) }}">Job Category</a>
                                            <a class="dropdown-item" href="{{ route('master_job_rank_list', ['masterId' => $company->id]) }}">Job Rank-Code</a>

                                            <a class="dropdown-item" href="{{ route('master_job_management', ['companyId' => $companyId]) }}?master_id={{$company->id}}">Edit</a>
                                            <a class="dropdown-item" href="{{route('master_job_management_delete',['id' => $company->id, 'companyId' => $companyId])}}">Delete</a>
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
