<?php

namespace App\Http\Services;

use App\Tweet;
use Illuminate\Support\Facades\Auth;

class TweetService
{

    public function extractShowTweets()
    {
        return Tweet::all();
    }


    public function saveTweet($tweet)
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
