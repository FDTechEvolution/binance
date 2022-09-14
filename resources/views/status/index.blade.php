@extends('layouts.coreLayout')

@section('title', 'STATUS')

@section('style')
<style>
    .border-bottom {
        border-bottom: 1px solid;
    }
    .fs--18 {
        font-size: 18px;
    }
</style>
@endsection

@section('content')
    <div id="status-app">
        <div class="row">
            <div class="col-12 text-center">
                <h4>Coin Status</h4>
            </div>
            <div class="col-6 text-center">
                <div class="row w-75 mt-3" style="margin: 0 auto;">
                    <div class="col-12 text-right"><small>เฉพาะรายการที่อยู่ในสถานะ Open หรือ Watch เท่านั้น</small></div>
                    <div class="col-md-3 bg-secondary text-light p-2">Coin</div>
                    <div class="col-md-7 text-center bg-secondary text-light p-2">Status</div>
                    <div class="col-md-2 text-center bg-secondary text-light p-2">Action</div>
                    @foreach($coins as $key => $coin)
                    <div class="col-md-12 bg-white border-bottom border-secondary p-2">
                        <div class="row">
                            <div class="col-md-3">{{ $coin['coin'] }}</div>
                            <div class="col-md-7 text-center">
                                @if($coin['status'] == 1)
                                    <i class="mdi mdi-check text-success fs--18"></i> 
                                @else 
                                    <span class="text-danger">{{ $coin['status'] }}</span>
                                @endif
                            </div>
                            <div class="col-md-2 text-center">
                                <a href="{{ route('close-status', ['coin' => $coin['coin']]) }}" class="btn btn-sm btn-danger">CLOSE</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-6 text-center">
            <div class="row w-75 mt-3" style="margin: 0 auto;">
                    <div class="col-12 text-right"><small>ทุกรายการเหรียญ</small></div>
                    <div class="col-md-4 bg-secondary text-light p-2">Coin</div>
                    <div class="col-md-8 text-center bg-secondary text-light p-2">Status</div>
                    @foreach($all as $key => $coin)
                    <div class="col-md-12 bg-white border-bottom border-secondary p-2">
                        <div class="row">
                            <div class="col-md-4">{{ $coin['coin'] }}</div>
                            <div class="col-md-8 text-center">
                                @if($coin['status'] == 1)
                                    <i class="mdi mdi-check text-success fs--18"></i> 
                                @else 
                                    <span class="text-danger">{{ $coin['status'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection