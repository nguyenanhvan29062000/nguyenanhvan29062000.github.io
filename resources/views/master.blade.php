<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="google-site-verification" content="Xlh4tGOBL7O6_dWygr9YnS9MF8T3Fmiw54wnZYjlZWU" />
    <meta name="google-site-verification" content="KPcelRaCctFO4eluTPqftTlCfthBW49ph3qp2m8udFk" />
    @yield('title')
    <link rel="icon" type="image/png" href="{{asset('/icons/icon.png')}}" sizes="90x90">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    @yield('headp')
</head>
<body style="min-width: 1280px; min-height: 720px;">
    <video id="bgVideo" autoplay loop muted style="position: fixed; top: 50%; left: 50%; min-width: 100%; min-height: 100%; z-index: -1; transform: translate(-50%, -50%) scaleX(-1);">
        <source src="{{asset('/images/bg.webm')}}" type="video/webm">
    </video>
        @yield('content')
        
</body>
<script>
    $(document).ready(function(){
        $("body").on("contextmenu",function(){
            return false;
        }); 
        $('img').on('dragstart', function(event) { event.preventDefault(); });
    })
</script>
</html>