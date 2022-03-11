@extends('layouts.a')
@section('content')
{{Session::get('mssg')}}
<table class="table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Status</th>
            <th scope="col">Picture</th>
            <th scope="col">Created_at</th>
            <th scope="col">Updated_at</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($posts as $post)
        <tr>
            <th scope="row">{{$post->id}}</th>
            <td>{{$post->status}}</td>
            <td>
                <image src="../images/{{$post->images[0]}}" width="64px" height="64px"> ...
            </td>
            <td>{{$post->created_at}}</td>               
            <td>{{$post->updated_at}}</td>
            <td>
                <a href="{{route('posts.show',$post->id)}}" class="button btn-success">View</a>
                <a href="{{route('posts.edit',$post->id)}}" class="button btn-primary">Edit</a>
                <a href="" class="button btn-danger">Delete</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection