@extends('layouts.coreLayout')

@section('title', 'HISTORY')

@section('content')
    <div id="feature-app">
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <h4 class="m-t-0 header-title"><b>Order History</b></h4>

                    <div class="row mb-0 mt-4">
                        <div class="col-5 border rounded py-2 bg-light">
                            <strong>เลือกรายวัน...</strong>
                            <form method="POST" action="{{ route('get-history') }}" class="d-flex px-2">
                                @csrf
                                <input type="date" name="date" class="form-control border pl-2 bg-white" value="{{ $date_now }}" max="{{ date('Y-m-d', strtotime('now')) }}" required/>

                                <input type="hidden" name="type" value="day">
                                <button type="submit" class="btn btn-sm btn-secondary px-3 ml-3">ยืนยัน</button>
                            </form>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-6 border rounded py-2 bg-light">
                            <strong>เลือกช่วงวัน...</strong>
                            <form method="POST" action="{{ route('get-history') }}" class="d-flex px-2">
                                @csrf
                                <input type="date" name="date_from" class="form-control border pl-2 mr-1 bg-white" value="{{ $date_from }}" max="{{ date('Y-m-d', strtotime('now')) }}" required />
                                <span class="mx-2 mt-2">-</span> 
                                <input type="date" name="date_to" class="form-control border pl-2 ml-1 bg-white" value="{{ $date_to }}" max="{{ date('Y-m-d', strtotime('now')) }}" required />

                                <input type="hidden" name="type" value="some">
                                <button type="submit" class="btn btn-sm btn-secondary px-3 ml-3">ยืนยัน</button>
                            </form>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            @if($type == 'day')
                                <div class="d-flex mt-2">
                                    <p class="mb-1">
                                        <form method="POST" action="{{ route('get-history') }}">
                                        @csrf
                                            <button type="submit" class="btn btn-sm btn-link" style="box-shadow: none; cursor: pointer;">
                                                <small>
                                                    <i class="ion-chevron-left text-primary"></i>
                                                </small> 
                                                วันก่อนหน้า
                                            </button>
                                            <input type="hidden" name="date" value="{{ $date_now }}">
                                            <input type="hidden" name="goto" value="prev">
                                            <input type="hidden" name="type" value="day">
                                        </form>
                                        @if(date('Y-m-d', strtotime('now')) != $date_now)
                                            ...
                                            <form method="POST" action="{{ route('get-history') }}">
                                            @csrf
                                                <button type="submit" class="btn btn-sm btn-link" style="box-shadow: none; cursor: pointer;">
                                                    วันถัดไป 
                                                    <small>
                                                        <i class="ion-chevron-right text-primary"></i>
                                                    </small>
                                                </button>
                                                <input type="hidden" name="date" value="{{ $date_now }}">
                                                <input type="hidden" name="goto" value="next">
                                                <input type="hidden" name="type" value="day">
                                            </form>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <table id="datatable-buttons" class="table table-bordered mb-1">
                        <thead>
                            <tr class="bg-secondary text-light">
                                <th class="text-center">Date</th>
                                <th class="text-center">Coin</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($histories as $key => $history)
                                @if($history['type'] != 'sum')
                                <tr>
                                    <td class="text-center">{{ date("d-m-Y", $history['time']) }}</td>
                                    <td class="text-center">{{ $history['symbol'] }}</td>
                                    <td class="text-center">{{ $history['type'] }}</td>
                                    <td class="text-center @if($history['income'] > 0) text-success @else text-danger @endif">{{ $history['income'] }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td class="text-right bg-light" colspan="3"><strong>SUM of <u>{{ $history['symbol'] }}</u></strong></td>
                                    <td class="text-center history-sum @if($history['sum'] > 0) text-success bg-positive @else text-danger bg-negative @endif">{{ $history['sum'] }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row mb-2">
                        <div class="col-12">
                            @if($type == 'day')
                                <div class="d-flex mt-2">
                                    <p class="mb-1">
                                        <form method="POST" action="{{ route('get-history') }}">
                                        @csrf
                                            <button type="submit" class="btn btn-sm btn-link" style="box-shadow: none; cursor: pointer;">
                                                <small>
                                                    <i class="ion-chevron-left text-primary"></i>
                                                </small> 
                                                วันก่อนหน้า
                                            </button>
                                            <input type="hidden" name="date" value="{{ $date_now }}">
                                            <input type="hidden" name="goto" value="prev">
                                            <input type="hidden" name="type" value="day">
                                        </form>
                                        @if(date('Y-m-d', strtotime('now')) != $date_now)
                                            ...
                                            <form method="POST" action="{{ route('get-history') }}">
                                            @csrf
                                                <button type="submit" class="btn btn-sm btn-link" style="box-shadow: none; cursor: pointer;">
                                                    วันถัดไป 
                                                    <small>
                                                        <i class="ion-chevron-right text-primary"></i>
                                                    </small>
                                                </button>
                                                <input type="hidden" name="date" value="{{ $date_now }}">
                                                <input type="hidden" name="goto" value="next">
                                                <input type="hidden" name="type" value="day">
                                            </form>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr/>

                    <div class="row mt-4">
                        <div class="col-md-6 col-12"></div>
                        <div class="col-md-3 col-5 text-right py-2">
                            <strong>SUM PNL : </strong>
                        </div>
                        <div class="col-md-3 col-7 text-center py-2" id="col-of-sum">
                            <strong id="sum-of-day"></strong><strong> USDT</strong>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script>
        let history_sum = document.querySelectorAll('.history-sum')
        let day_sum = 0
        history_sum.forEach(sum => {
            day_sum += parseFloat(sum.innerHTML)
        })

        if(day_sum < 0) {
            document.querySelector('#col-of-sum').classList.add('bg-negative')
            document.querySelector('#col-of-sum').classList.add('text-danger')
        } else {
            document.querySelector('#col-of-sum').classList.add('bg-positive')
            document.querySelector('#col-of-sum').classList.add('text-success')
        }

        document.querySelector('#sum-of-day').innerHTML = day_sum.toFixed(8)
    </script>
@endsection