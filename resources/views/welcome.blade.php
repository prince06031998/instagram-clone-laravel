@extends('layouts.a')

@section('content')
<div class="container">
    <h1 class="text-center text-danger font-weight-bold">{{Session::get('mssg')}}</h1>
</div>
@endsection