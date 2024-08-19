<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ URL::asset('/images/logo.jpeg') }}" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@200;300&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA--R5NRvJgK8pnXII55nHB53hcOEeAJyQ&callback=initMap"> </script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <title>Application Received</title>
    <style>
        /* Materialize CSS embedded styles */
        body {
            font-family: "Mori Gothic", sans-serif; 
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .light-deca {
            font-family: "Lexend Deca", sans-serif;
            font-weight: 200;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: black;
            padding: 20px;
            text-align: center;
            color: white;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            padding: 30px;
            color: #424242;
        }
        .content h1 {
            font-size: 24px;
            color: #212121;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
            color: #757575;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 15px;
            color: #9e9e9e;
        }
        .button {
            background-color: #2196F3;
            color: white;
            padding: 12px 20px;
            text-align: center;
            display: inline-block;
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <div class="container z-depth-1">
        <!-- Header with Company Logo -->
        <div class="header white">
            <img class="responsive-img" src="https://zut.ac.zm/images/logo-white-zuct.png" alt="Company Logo">
        </div>

        @yield('content')

        <!-- Footer -->
        <div class="footer light-deca">
            <p>&copy; {{ date('Y') }} ZUT All rights reserved. <br> 3J4W+J7Q, Kalewa Rd, Ndola, Zambia. <br> <a href="https://zut.ac.zm" style="color: #2196F3; text-decoration: none;">zut.ac.zm</a></p>
        </div>
    </div>
</body>
</html>
