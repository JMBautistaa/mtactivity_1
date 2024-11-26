@extends('adminlte::page')
@section('content')

    <div id="app">
        <datas-component 
        :data = {{json_encode($data)}}>
        </datas-component>
    </div>

@stop

@section('js')

    <script src="{{ asset('js/app.js') }}"> </script>

@stop

