<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>View Product</title>
</head>
<body>
    <div class="container mt-5">
        <h1>{{ $item->name }}</h1>
        <p>{{ $item->description }}</p>
        <p><strong>Price:</strong> ${{ $item->price }}</p>

        @if($item->uploadImage)
            <div class="row">
                @foreach($item->uploadImage as $image)
                    <div class="col-md-4">
                        <img src="{{ asset($image->url) }}" class="img-fluid" alt="Product Image">
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('item.index') }}" class="btn btn-primary mt-3">Back to List</a>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGFiiqUSK3f2+NljO7AQ/0GbgFb0g/2eFc5ZpSi15TBW" crossorigin="anonymous"></script>
</body>
</html>
