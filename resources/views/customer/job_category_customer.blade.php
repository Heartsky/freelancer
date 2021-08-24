@extends('layouts.page')

@section('breakscrum')
    @include('layouts.bars.form_breakscrum',["list" => [ ["title" => "Customer Job Category" , "link" => "#" ]  ], 'link_add' =>
    route('customer_job_category',['customerId' => $customerId]) ])
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h3 class="mb-0">Customer Job Category Management</h3>
            </div>
            <!-- form -->
            <div class="row">

                <div class="col-xl-8 order-xl-1">
                    <div class="card bg-secondary shadow">

                        <div class="card-body">
                            @if( !empty($catJob) and $catJob->count() > 0)
                                <form method="post" action="{{ route('customer_job_category', ['customerId' => $customerId]) }}" autocomplete="off">
                                @csrf
                                    @method('post')
                                    <input type="hidden" name="cat_id" value="{{$catJob->id}}">

                                    <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('company_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" >Company</label>

                                            <select class="form-control " name="company_id" id="input-company_id">
                                                @foreach($companies as $company)
                                                    <option value="{{$company->id}}" {{$catJob->company_id == $company->id ? 'selected' : ''}}>{{$company->name}}</option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label" >Job Master</label>
                                            <input type="hidden" id="job_master_id_edit" name="job_master_id_edit" value="{{$catJob->job_master_id}}">
                                            <select class="form-control " name="job_master_id" id="input-job_master_id">
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label" >Job Category</label>
                                            <input type="hidden" id="job_cat_id_edit" name="job_cat_id_edit" value="{{$catJob->job_category_id}}">
                                            <select class="form-control " name="job_category_id" id="input-job_category_id">
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Invoice Order</label>
                                            <input type="number" name="invoice_order" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Summary Order" value="{{$catJob->invoice_order}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Price Count</label>
                                            <input type="number" name="price_count" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Price Count" value="{{$catJob->price_count}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Square Meters Price</label>
                                            <input type="number" name="price_sqm" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Square Meters Price" value="{{$catJob->price_sqm}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Point</label>
                                            <input type="number" name="point" id="input-email" class="form-control form-control-alternative{{ $errors->has('point') ? ' is-invalid' : '' }}" placeholder="Point" value="{{$catJob->point}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Point check</label>
                                            <input type="number" name="point_check" id="input-email" class="form-control form-control-alternative{{ $errors->has('point_check') ? ' is-invalid' : '' }}" placeholder="Point check" value="{{$catJob->point_check}}" required>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('company.save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <form method="post" action="{{ route('customer_job_category', ['customerId' => $customerId]) }}" autocomplete="off">
                                @csrf
                                    @method('post')
                                    <div class="pl-lg-4">
                                        <div class="form-group{{ $errors->has('company_id') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" >Company</label>

                                            <select class="form-control " name="company_id" id="input-company_id">
                                                @foreach($companies as $company)
                                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label" >Job Master</label>

                                            <select class="form-control " name="job_master_id" id="input-job_master_id">
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label" >Job Category</label>

                                            <select class="form-control " name="job_category_id" id="input-job_category_id">
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Invoice Order</label>
                                            <input type="number" name="invoice_order" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Summary Order" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Price Count</label>
                                            <input type="number" name="price_count" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Price Count" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Square Meters Price</label>
                                            <input type="number" name="price_sqm" id="input-email" class="form-control form-control-alternative{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Square Meters Price" value="" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Point</label>
                                            <input type="number" name="point" id="input-email" class="form-control form-control-alternative{{ $errors->has('point') ? ' is-invalid' : '' }}" placeholder="Point" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Point check</label>
                                            <input type="number" name="point_check" id="input-email" class="form-control form-control-alternative{{ $errors->has('point_check') ? ' is-invalid' : '' }}" placeholder="Point check" value="" required>
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
            <form action="{{ route('customer_job_category', ['customerId' => $customerId]) }}" method="post">
                @csrf
                <input type="hidden" name="save_position" value="1">
                <div class="text-left">
                    <!--<button type="submit" class="btn btn-success mt-4">Save Position</button>-->
                </div>
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="name">Job Category Name</th>
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
                                    {{ $company->getJobCategoryName() }}
                                </td>
                                <td >
                                    {{ $company->getJobMasterName() }}
                                </td>
                                <td >
                                    {{ $company->updated_at }}
                                </td>
                                <td class="text">
                                    <!--<input type="number" name="position[{{$company->id}}][]" value="{{ $company->summary_order }}" size="5" maxlength=5>-->
                                    {{ $company->invoice_order }}
                                </td>
                                <td class="text">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('customer_job_category', ['customerId' => $customerId]) }}?category_id={{$company->id}}">Edit</a>
                                            <a class="dropdown-item" href="{{route('customer_job_category_delete',['id' => $company->id, 'customerId' => $customerId])}}">Delete</a>
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
<input type="hidden" name="url_get_job_by_company" id='url_get_job_by_company' value="{{route('master.job_master_by_company')}}">
<input type="hidden" name="url_get_job_by_master" id='url_get_job_by_master' value="{{route('master.job_by_master')}}">
<script type="text/javascript">
    $(document).ready(function(){
        function getMasterJobByCompany(companyId){
            $('#input-job_master_id option').remove();
            var jobMasterIdEdit = "";
            if( $("#job_master_id_edit") != "undefined"){
                jobMasterIdEdit = $("#job_master_id_edit").val();
            }
            $.ajax({
              url: $("#url_get_job_by_company").val()+"?company-id="+$("#input-company_id").val(),
              success: function(data){
                dataOk = $.parseJSON(data);
                $.each(dataOk, function (i, item) {
                    var selectCheck = false;
                    if(jobMasterIdEdit == item.id){
                        selectCheck = true;
                    }
                    $('#input-job_master_id').append($('<option>', {
                        value: item.id,
                        text : item.job_master_name,
                        selected: selectCheck
                    }));
                });
                getJobByMaster();
              }
            });
        }

        function getJobByMaster(){
            $("#input-job_category_id option").remove();
            var selectCheck = false;
            var jobCategortIdEdit = "";

            if( $("#job_cat_id_edit") != "undefined"){
                jobCategortIdEdit = $("#job_cat_id_edit").val();
            }

            $.ajax({
              url: $("#url_get_job_by_master").val()+"?master-id="+$("#input-job_master_id").val(),
              success: function(data){
                dataOk = $.parseJSON(data);
                $.each(dataOk, function (i, item) {
                    var selectCheck = false;
                    if(jobCategortIdEdit == item.id){
                        selectCheck = true;
                    }
                    $('#input-job_category_id').append($('<option>', {
                        value: item.id,
                        text : item.job_category_name,
                        selected: selectCheck
                    }));
                });
              }
            });
        }

        getMasterJobByCompany();

        $("#input-company_id").change(function(){
            getMasterJobByCompany();
        });

        $("#input-job_master_id").change(function(){
            getJobByMaster();
        });
    })
</script>
@endsection
