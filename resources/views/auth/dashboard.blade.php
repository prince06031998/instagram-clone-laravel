@extends('layouts.a')

@section('content')

@if(session()->has('name'))
    <h1>hello ban</h1>  
        
    {{$user}}
        <a href="{{route('auth.logout')}}">Logout</a>
@else
    <h1>cc</h1>
@endif
@endsection