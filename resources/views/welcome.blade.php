<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    </head>
    <body>
      <div id="app">
        <header>
            <strong>Hello, World</strong>
        </header>
        <main>
            @{{ vueData }}
        </main>
        <footer>
        </footer>
      </div>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
