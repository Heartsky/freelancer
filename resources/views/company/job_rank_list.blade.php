@extends('layouts.page')

@section('breakscrum')
    @include('layouts.bars.form_breakscrum',["list" => [ ["title" => "Customer Job Rank" , "link" => "#" ]  ], 'link_add' =>
    route('master_job_rank_create',['masterId' => $masterId]) ])
@endsection
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <h3 class="mb-0">Master Job Rank Management</h3>
            </div>
            <!-- form -->

            <!-- end -->
            <!-- Light table -->
            <div class="table-responsive">

                <div class="text-left">
                    <!--<button type="submit" class="btn btn-success mt-4">Save Position</button>-->
                </div>
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="name">Code</th>
                            <th scope="col" class="sort" data-sort="budget">Comment</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @if (count($data) == 0)
                            <tr>
                                <td >Have No record </td>
                            </tr>

                        @else
                            @foreach($data as $item)
                            <tr>

                                <td class="budget">
                                    {{ $item->rank_code }}
                                </td>

                                <td >
                                    {{ $item->comment }}
                                </td>

                                <td class="text">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('master_job_rank_edit', ['masterId' => $masterId, 'id' => $item->id]) }}">Edit</a>
                                            <a class="dropdown-item" href="{{route('master_job_rank_delete',['id' => $item->id, 'masterId' => $masterId])}}">Delete</a>
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
