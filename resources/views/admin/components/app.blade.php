<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard GST</title>
    @yield('head')
</head>
<style>
    .grid-container {
        font-family: 'Popins', sans-serif;
    }

    .content-container {
        margin-left: 34vh;
        margin-top: 5vh;
        font-family: 'Popins', sans-serif;
    }
</style>

<body>
    <div class="grid-container">
        @include('admin.components.sidebar')
    </div>
    <div class="content-container">
        @yield('container')
    </div>
</body>

</html>
