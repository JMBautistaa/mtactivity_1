@extends('adminlte::page')
@section('content')

    <div id="app">
        <companies-component></companies-component>
    </div>

@stop

@section('js')

    <script src="{{ asset('js/app.js') }}"> </script>

@stop

