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
                    <input class="form" v-model.trim="tweet" :class="{'is-error': !canSubmit}">
                    <button class="btn" @click="storeTweet" :disabled="!canSubmit">送信</button>
                    <span v-if="!isValidated.tweet" v-text="validationErrorMessage.tweet" :class="{'is-error-message': !isValidated.tweet}"></span>
                </section>
            </div>
        </header>
        <main>
            <div class="contents">
                <div v-for="tweet in tweets" :key="tweet.id" v-cloak>
                    @{{ tweet.tweet }}
                </div>
                <infinite-loading @infinite="fetchTweets"></infinite-loading>
            </div>
        </main>
        <footer>
        </footer>
    </div>
    <script src="{{ mix('js/tweet.js') }}"></script>
</body>
</html>
