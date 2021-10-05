<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>@yield('title')</title>
</head>
<body>
    <header>
        <div class="header-left">
            @yield('link')
        </div>
        <div class="header-right">
            <span>@yield('title')</span>
        </div>
    </header>

    <div class="wrapper">
        <main>
            <h2>@yield('floor')</h2>
            <div class="main-container">
                @yield('main')
            </div>
        </main>
    </div>
    
    <footer>
        <p class="footer-name">会社名</p>
    </footer>
    
</body>
@yield('js')
</html>