@extends('adminlte::page')
@section('content')

    <div id="app">
        <departments-component
        :companies ="{{json_encode($companies)}}">
    </departments-component>
    </div>

@stop

@section('js')

    <script src="{{ asset('js/app.js') }}"> </script>

@stop

