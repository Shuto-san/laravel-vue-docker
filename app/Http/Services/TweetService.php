<?php

namespace App\Http\Services;

use App\Tweet;
use Illuminate\Support\Facades\Auth;

class TweetService
{

    public function extractShowTweets($fetchedTweetIdList, $page)
    {
        $limit = 10;
        $offset = $page * $limit;
        $tweets = Tweet::orderBy('created_at', 'desc')->offset($offset)->take($limit)->get();
        if (is_null($tweets)) {
            return [];
        }

        if (is_null($fetchedTweetIdList)) {
            return $tweets;
        }

        $showableTweets = [];
        foreach ($tweets as $tweet) {
            if (!in_array($tweet->id, $fetchedTweetIdList)) {
                $showableTweets[] = $tweet;
            }
        }

        return $showableTweets;
    }


    public function saveTweet($postedTweet)
    {
        $user = Auth::user();
        $tweet = new Tweet();
        $tweet->user_id = $user->id;
        $tweet->nickname = $user->name;
        $tweet->tweet = $postedTweet['tweet'];
        $tweet->save();

        return $tweet->tweet;
    }

}
