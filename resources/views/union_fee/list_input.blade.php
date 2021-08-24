@extends('layouts.page')

@section('breakscrum')
    @include('layouts.bars.form_breakscrum',["list" => [ ["title" => "Union Fee" , "link" => "#" ]  ], 'link_add' => 
    route('union_fee') ])
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h3 class="mb-0">Union Fee</h3>
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
                                            <label class="form-control-label" for="input-email">Position</label>
                                            <input type="number" name="position" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Position" value="{{$job->position}}" required>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('company.save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <form method="post" action="{{ route('union_fee') }}" autocomplete="off">
                                    @csrf
                                    @method('post')
                                    <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-code">Fee Name</label>
                                            <input type="text" name="job_master_name" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Master Job Name" value="" required autofocus>
                                        </div>
                                        <div class="custom-control custom-control-alternative custom-checkbox">
                                            <input name="is_rank" class="custom-control-input" id="is_rank" value="1" type="checkbox">
                                            <label class="custom-control-label" for="is_rank">
                                                <span class="text-muted"> Type</span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Amount</label>
                                            <input type="number" name="position" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Position" value="" required>
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
                @csrf
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="name">Name</th>
                            <th scope="col" class="sort" data-sort="budget">Amount</th>
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
                                    {{ $company->job_master_name }}
                                </td>
                                <td >
                                    {{ $company->updated_at }}
                                </td>
                                <td class="text">
                                    <input type="number" name="position[{{$company->id}}][]" value="{{ $company->position }}" size="5" maxlength=5>
                                </td>
                                <td class="text">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
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
            </div>
        </div>
    </div>
</div>
@endsection
