@extends('layouts.dashboard')
@extends('layouts.plane')
@section('section')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
    <title>Orders</title>

    <!-- Bootstrap CSS -->


    <style>
        body {

            /*padding: 0px;*/
            ;
        }
    </style>
</head>
<body>

    @yield('content')



@stack('scripts')
</body>
</html>
@endsection
