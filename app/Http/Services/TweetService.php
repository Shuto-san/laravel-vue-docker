<?php

use App\Tweet;
use Illuminate\Support\Facades\Auth;

class TweetService {

    public saveTweet($tweet)
    {
        $user = Auth::user();
        $tweet = new Tweet();
        $tweet->user_id = $user->id;
        $tweet->nickname = $user->name;
        $tweet->tweet = $tweet['tweet'];
        $tweet->save();

        return $tweet->tweet;
    }

}
