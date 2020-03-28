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
      <div id="welcome">
          <welcome-component></welcome-component>
      </div>
        <script src="{{ mix('js/welcome.js') }}"></script>
    </body>
</html>
