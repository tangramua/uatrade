@extends('core::layouts.master')

@section('content')
    @foreach($entitiesHtmlCollection as $html)
        {!! $html !!}
    @endforeach
@stop
