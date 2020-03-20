<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link rel="stylesheet" href="{{ mix('/css/tweet.css') }}">
</head>
<body>
    <div id="tweet">
            <tweet-component></tweet-component>
    </div>
    <script src="{{ mix('js/tweet.js') }}"></script>
</body>
</html>
