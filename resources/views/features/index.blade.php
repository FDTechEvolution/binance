@extends('layouts.coreLayout')

@section('title', 'FEATURES')

@section('style')
    <script src="{{ asset('app/vue.js') }}"></script>
    <script src="{{ asset('app/axios.min.js') }}"></script>
@endsection

@section('content')
    <div id="feature-app">
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <h4 class="m-t-0 header-title"><b>Features</b></h4>

                    <button type="button" class="btn btn-sm btn-primary mb-2" 
                            data-toggle="modal" data-target="#featureAddModal"
                    >+ ADD</button>

                    <feature-list></feature-list>
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('features.modal.add-feature')
    @include('features.modal.edit-feature')
    @include('features.modal.confirm-delete')
@endsection

@section('script')
    <script src="{{ asset('app/feature.js') }}"></script>
    <script type="module">
        import featureList from "{{ asset('app/components/featureList.vue.js') }}"

        const app = Vue.createApp({
            components: {
                'feature-list': featureList
            },
        })
        app.mount('#feature-app')
    </script>
@endsection