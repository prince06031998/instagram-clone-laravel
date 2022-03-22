@extends('layouts.a')
@section('content')
<section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                {{Session::get('mssg')}}
                                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Edit Post</p>
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <form method="POST" action="{{route ('posts.update',$post->id)}}" enctype="multipart/form-data" class="mx-1 mx-md-4">
                                    @csrf

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="text" name="status" value="{{$post->status}}" id="form3Example1c" class="form-control" required autofocus />
                                            <label class="form-label" for="form3Example1c">Your Status</label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <h4>Your Picture</h4>
                                            <div class="container">
                                                @foreach ($post->images as $image)
                                                <div class="row">
                                                    <image class="m-3" src="../../images/{{$image}}" width="160px" height="100px">
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="file" name="images[]" id="form3Example3c" class="form-control" multiple />
                                            <label class="form-label" for="form3Example3c">Choose Your New Pics Here</label>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                                    </div>

                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection