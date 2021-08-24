@extends('layouts.page')

@section('breakscrum')
    @include('layouts.bars.form_breakscrum',["list" => [ ["title" => "Customer Job Category" , "link" => "#" ]  ], 'link_add' =>
    route('customer_job_management',['masterId' => $masterId]) ])
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h3 class="mb-0">Job Category Management</h3>
            </div>
            <!-- form -->
            <div class="row">

                <div class="col-xl-8 order-xl-1">
                    <div class="card bg-secondary shadow">

                        <div class="card-body">
                            @if( !empty($catJob) and $catJob->count() > 0)
                                <form method="post" action="{{ route('customer_job_management', ['masterId' => $masterId]) }}" autocomplete="off">
                                @csrf
                                    @method('post')
                                    <input type="hidden" name="cat_id" value="{{$catJob->id}}">

                                    <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-code">Job Category Name</label>
                                            <input type="text" name="job_category_name" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Job Category Name" value="{{$catJob->job_category_name}}" required autofocus>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label" >Job Master</label>

                                            <select class="form-control " name="job_master_id" id="input-customer_id">
                                                @foreach($jobmaster as $master)
                                                    <option value="{{$master->id}}" {{$master->id == $catJob->job_master_id ? 'selected' : ''}}>{{$master->job_master_name}}</option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Summary Order</label>
                                            <input type="number" name="summary_order" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Summary Order" value="{{$catJob->summary_order}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Amount Price</label>
                                            <input type="number" name="price_count" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Price Count" value="{{$catJob->price_count}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Square Meters Price</label>
                                            <input type="number" name="price_sqm" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Square Meters Price" value="{{$catJob->price_sqm}}" required>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('company.save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <form method="post" action="{{ route('customer_job_management', ['masterId' => $masterId]) }}" autocomplete="off">
                                @csrf
                                    @method('post')
                                    <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="input-code">Job Category Name</label>
                                            <input type="text" name="job_category_name" id="input-code" class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Job Category Name" value="" required autofocus>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label" >Job Master</label>

                                            <select class="form-control " name="job_master_id" id="job_master_id">
                                                @foreach($jobmaster as $master)
                                                    <option value="{{$master->id}}" {{$master->id == $masterId ? 'selected' : ''}}>{{$master->job_master_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Position</label>
                                            <input type="number" name="summary_order" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Summary Order" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Amount Price</label>
                                            <input type="number" name="price_count" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Price Count" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Square Meters Price</label>
                                            <input type="number" name="price_sqm" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Square Meters Price" value="" required>
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
            <form action="{{ route('customer_job_management', ['masterId' => $masterId]) }}" method="post">
                @csrf
                <input type="hidden" name="save_position" value="1">
                <div class="text-left">
                    <!--<button type="submit" class="btn btn-success mt-4">Save Position</button>-->
                </div>
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="name">Name</th>
                            <th scope="col" class="sort" data-sort="budget">Master Job Name</th>
                            <th scope="col" class="sort" data-sort="completion">Last Updated</th>
                            <th scope="col">Position</th>
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
                                    {{ $company->job_category_name }}
                                </td>
                                <td >
                                    {{ $company->jobMaster()->get()[0]->job_master_name }}
                                </td>
                                <td >
                                    {{ $company->updated_at }}
                                </td>
                                <td class="text">
                                    <!--<input type="number" name="position[{{$company->id}}][]" value="{{ $company->summary_order }}" size="5" maxlength=5>-->
                                    {{ $company->summary_order }}
                                </td>
                                <td class="text">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('customer_job_management', ['masterId' => $masterId]) }}?category_id={{$company->id}}">Edit</a>
                                            <a class="dropdown-item" href="{{route('category_job_delete',['id' => $company->id, 'masterId' => $masterId])}}">Delete</a>
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
