@extends('layouts.app')

@section('content')

    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <!-- Card stats -->
                <div class="row">
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col" style="text-align: center;">
                                        <a href="{{ route('upload_data') }}" class="btn btn-primary btn-lg" >Import Data</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
{{--                    <div class="col-xl-3 col-lg-6">--}}
{{--                        <div class="card card-stats mb-4 mb-xl-0">--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col" style="text-align: center;">--}}
{{--                                        <button class="btn btn-info btn-lg" type="button">Export Task</button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-xl-3 col-lg-6">--}}
{{--                        <div class="card card-stats mb-4 mb-xl-0">--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col" style="text-align: center;">--}}
{{--                                        <button class="btn btn-success btn-lg" type="button">Export Invoice</button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-xl-3 col-lg-6">--}}
{{--                        <div class="card card-stats mb-4 mb-xl-0">--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col" style="text-align: center;">--}}
{{--                                        <button class="btn btn-warning btn-lg" type="button">Export Finance</button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
