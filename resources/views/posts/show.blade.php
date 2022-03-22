@extends('layouts.a')
@section('content')

    {{$post}}
    @if(session()->get('id') == $post->user->id)
        edit
    @else 
        back
    @endif
@endsection