@extends('adminlte::page')

@section('title', 'Controle de Acesso')

@section('plugins.Select2', true)

@section('content_header')
    <h1>Controle de Acesso</h1>
@stop

@section('content')
    @yield('content')
@stop

@section('css')
    @livewireStyles
@stop

@section('js')
    @livewireScripts
    @stack('scripts')
@stop
