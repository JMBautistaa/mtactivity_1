@extends('adminlte::page')
@section('content')

    <div id="app">
        <provinces-component
        :regions ="{{json_encode($regions)}}">
    </provinces-component>
    </div>

@stop

@section('js')

    <script src="{{ asset('js/app.js') }}"> </script>

@stop

