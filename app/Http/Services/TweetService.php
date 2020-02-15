<?php

namespace App\Http\Services;

use App\Tweet;
use Illuminate\Support\Facades\Auth;
use App\RedisModel;

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

        $user = Auth::user();
        $tweetIdList = $tweets->pluck('id')->toArray();
        $likedTweetIdList = RedisModel::getActionToTweetPerUser(config('tweet.USER_ACTION.LIKE'), $user->id, $tweetIdList);
        $reportedTweetIdList = RedisModel::getActionToTweetPerUser(config('tweet.USER_ACTION.REPORT'), $user->id, $tweetIdList);
        $showableTweets = [];
        foreach ($tweets as $index => $tweet) {
            if (!in_array($tweet->id, $fetchedTweetIdList)) {
                $tweet->is_liked = $likedTweetIdList[$index];
                $tweet->is_reported = $reportedTweetIdList[$index];
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

    public function updateLikeCount($tweetId, $likePushed)
    {
        $user = Auth::user();

        if ($likePushed) {
            if (RedisModel::setActionToTweetPerUser(config('tweet.USER_ACTION.LIKE'), $user->id, $tweetId, $likePushed)) {
                RedisModel::incrTweetLikeCount($tweetId);
            }
        }
        if (!$likePushed) {
            if (RedisModel::delActionToTweetPerUser(config('tweet.USER_ACTION.LIKE'), $user->id, $tweetId, $likePushed)) {
                RedisModel::decrTweetLikeCount($tweetId);
            }
        }

        return $likePushed;
    }

    public function updateReportCount($tweetId, $reportPushed)
    {
        $user = Auth::user();

        if ($reportPushed) {
            if (RedisModel::setActionToTweetPerUser(config('tweet.USER_ACTION.REPORT'), $user->id, $tweetId, $reportPushed)) {
                Tweet::whereTweetId($tweetId)->increment('report_count');
            }
        }
        if (!$reportPushed) {
            if (RedisModel::delActionToTweetPerUser(config('tweet.USER_ACTION.REPORT'), $user->id, $tweetId, $reportPushed)) {
                Tweet::whereTweetId($tweetId)->decrement('report_count');
            }
        }

    }

}
