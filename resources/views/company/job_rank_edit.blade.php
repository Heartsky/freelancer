@extends('layouts.page')

@section('breakscrum')
    @include('layouts.bars.form_breakscrum',["list" => [ ["title" => "Job Rank" , "link" => "#" ]  ], 'link_add' =>'' ])
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h3 class="mb-0">Job Rank Create</h3>
            </div>
            <!-- form -->
            <div class="row">

                <div class="col-xl-8 order-xl-1">
                    <div class="card bg-secondary shadow">

                        <div class="card-body">

                                <form method="post" action="{{ route('master_job_rank_update', ['masterId' => $masterId, 'id'=> $item->id]) }}" autocomplete="off">
                                @csrf
                                    @method('post')
                                    <div class="pl-lg-4">



                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Code</label>
                                            <input type="text" name="rank_code"  class="form-control form-control-alternative{{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="Rank Code" value="{{ $item->rank_code }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Comment</label>
                                            <input type="text" name="comment" class="form-control form-control-alternative{{ $errors->has('comment') ? ' is-invalid' : '' }}" placeholder="Comment" value="{{$item->comment}}">
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success mt-4">{{ __('company.save') }}</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
