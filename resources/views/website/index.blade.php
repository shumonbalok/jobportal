<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ env('APP_NAME', 'Laravel') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
    crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"
    defer></script>
</head>

<body>
  <nav class="navbar navbar-expand navbar-light bg-light">
    <div class="container">
      <div class="nav navbar-nav">
        <a class="nav-item nav-link active" href="#">Home <span class="visually-hidden">(current)</span></a>
        @guest
          <a class="nav-item nav-link" href="{{ route('login') }}">Login</a>
          <a class="nav-item nav-link" href="{{ route('register') }}">Register</a>
        @else
          <a class="nav-item nav-link" href="{{ route('dashboard') }}">Dashboard</a>
        @endguest
      </div>
    </div>
  </nav>
</body>

</html>
