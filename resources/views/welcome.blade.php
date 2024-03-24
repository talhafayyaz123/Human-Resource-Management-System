<!DOCTYPE html>
<html>

<head>
    @vite('resources/css/app.css')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="icon" href="{{ Vite::asset('resources/js/assets/images/docFav.png') }}" type="image/png">

</head>

<body>
    <div id="app">
        <app-component></app-component>
    </div>
    @vite('resources/js/app.js')
</body>

</html>
