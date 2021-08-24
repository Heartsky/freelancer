@extends('layouts.page')

@section('breakscrum')
@include('layouts.bars.form_breakscrum',["list" => [ ["title" => __('company.list_company') , "link" => "#" ]  ], 'link_add' => route('master.company_create')  ])
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Datetimepicker</h3>
            </div>
            <div class="container">
                <div class="row">
                    <div class='col-sm-6 mt-3'>
                        <div class="form-group">
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' class="form-control" />
                                <span class="input-group-addon input-group-append">
                  <button class="btn btn-outline-primary" type="button" id="button-addon2"> <span class="fa fa-calendar"></span></button>
                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-header">
                <h3 class="mb-0">Datepicker</h3>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="exampleDatepicker">Datepicker</label>
                                <input class="form-control datepicker"  placeholder="Select date" type="text" value="06/20/2018">
                            </div>
                        </div>
                    </div>
                    <div class="row input-daterange datepicker align-items-center">
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">Start date</label>
                                <input class="form-control" placeholder="Start date" type="text" value="06/18/2018">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="form-control-label">End date</label>
                                <input class="form-control" placeholder="End date" type="text" value="06/22/2018">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
