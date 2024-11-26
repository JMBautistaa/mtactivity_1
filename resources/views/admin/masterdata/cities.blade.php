@extends('adminlte::page')
@section('content')

    <div id="app">
        <cities-component
            :regions ="{{ json_encode($regions) }}"
            :provinces ="{{ json_encode($provinces) }}">
        </cities-component>
    </div>

@stop

@section('js')
    <script src="{{ asset('js/app.js') }}"></script>
@stop
