<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>
<body>
    <div class="container mt-5">
        @if(Session::has('success'))
            <div class="alert alert-success text-center">
                {{Session::get('success')}}
            </div>
        @endif
        <form  method="POST" action="{{ route('item.update', $item->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group mb-2">
                <label><b>Name</b></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ $item->name }}">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-2">
                <label><b>Description</b></label>
                <textarea name="description" class="form-control  @error('description') is-invalid @enderror" id="description" rows="4">{{ $item->description }}</textarea>

                @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-2">
                <label><b>Price</b></label>
                <input type="price" class="form-control @error('price') is-invalid @enderror" name="price" id="price" value="{{ $item->price }}">

                @error('price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-2">
                <label><b>Uploaded Images</b></label>
                @if($item->uploadImage)
                    @foreach($item->uploadImage as $image)

                            <img src="{{ asset($image->url) }}" width="100" height="100" alt="">
                            <a href="{{ route('image.delete',$image->id) }}" class="btn btn-danger btn-sm ml-2">Delete</a>
                        
                    @endforeach
                @endif
            </div><br>

            <div class="form-group mb-2">
                <label><b>Upload Images</b></label>
                <input class="form-control" type="file" name="images[]" id="images" multiple>
                @error('images')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="d-grid mt-3">
              <input type="submit" value="Submit" class="btn btn-dark btn-block">
            </div>
        </form>
    </div>
</body>
</html>