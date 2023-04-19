@extends('adminlte::page')

@section('title')
    @yield('title')
@stop

@section('content_header')
    @yield('content_header')
@stop

@section('content')
    @yield('content')
@stop

@section('css')
    {{-- @livewireStyles --}}
@stop

@section('js')
    {{-- @livewireScripts --}}
    @stack('scripts')
@stop
