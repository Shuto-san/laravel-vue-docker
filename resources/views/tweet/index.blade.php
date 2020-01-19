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
        <header>
            <div class="contents">
                <section class="submit-form">
                    <input v-model.trim="tweet" v-input="validateTweet" :class="{'is-error': !isValidated.tweet">
                    <button @click="storeTweet" :disabled="!canSubmit">送信</button>
                    <span v-if="!isValidated.tweet" v-text="validationErrorMessage">validation error</span>
                </section>
            </div>
        </header>
        <main>
            <div class="contents">
                <ul>
                    @foreach ($tweets as $tweet)
                    <li>{{ $tweet->tweet }}</li>
                    @endforeach
                </ul>
            </div>
        </main>
        <footer>
        </footer>
    </div>
    <script src="{{ mix('js/tweet.js') }}"></script>
</body>
</html>
