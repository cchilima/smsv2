<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
        <link href="{{asset('/css/material.css')}}"
        rel="stylesheet">
        <script src="{{asset('/js/app.js')}}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@200;300&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>


    <!-- Scripts -->
    

    <title>{{ $title ?? '' }}</title>
</head>

<body>

<nav class="z-depth-1">
        <div style="background-color: #020843 !important" class="nav-wrapper ">
            <a href="{{ route('login') }}" class="brand-logo right mx-2"><img width="60" height="60" src="{{asset('images/logo.png')}}" alt=""></a>
            <ul class="right hide-on-med-and-down">
            </ul>
        </div>
    </nav>


    <main>

        {{ $slot }}

    </main>



    


</body>

</html>
