@extends('layouts.a')
@section('content')


<div class="container-fluid d-flex">
    <ul class="list-unstyled">
        @foreach($post as $p)

        <li class="media mb-5">
            <div class="head">
                <img class="mr-3 rounded-circle float-left" width="64px" height="64px" src="../images/{{$p->user->avatar}}" alt="Generic placeholder image">
                <h4 class="mt-0 mb-1 float-left">{{$p->user->name}}</h4>
            </div>

            <div class="media-body">
                <p>{{$p->created_at}}</p>
                {{$p->status}}
                <img src="../images/{{$p->images[0]}}" class="d-block w-100" alt="">
                <a href="{{route('posts.show',$p->id)}}" class="btn-danger">Read</a>
            </div>
        </li>
        <hr>
        @endforeach
    </ul>
</div>
@endsection