@extends('adminlte::page')
@section('content')

    <div id="app">
        <employees-component
        :companies ="{{json_encode($companies)}}">
    </employees-component>
    </div>

@stop

@section('js')

    <script src="{{ asset('js/app.js') }}"> </script>

@stop

