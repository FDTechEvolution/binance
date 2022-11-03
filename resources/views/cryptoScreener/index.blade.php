@extends('layouts.coreLayout')

@section('title', 'Crypto Screener')

@section('style')
    <script src="{{ asset('app/vue.js') }}"></script>
    <script src="{{ asset('app/axios.min.js') }}"></script>
@endsection

@section('content')
    <div id="crypto-screener-app">
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <h4 class="m-t-0 header-title"><b>Crypto Screener</b></h4>

                    <crypto-screener-list></crypto-screener-list>
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        import cryptoScreenerList from "{{ asset('app/components/cryptoScreenerList.vue.js') }}"

        const app = Vue.createApp({
            components: {
                'crypto-screener-list': cryptoScreenerList
            },
        })
        app.mount('#crypto-screener-app')
    </script>
@endsection