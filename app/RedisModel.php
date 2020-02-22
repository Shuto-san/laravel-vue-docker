<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class RedisModel
{

    public static function setActionToTweetPerUser ($actionType, $userId, $tweetId, $actionPushed)
    {
        $baseKey = implode(':', [config('tweet.TWEET_BASE_KEY'), $userId, $actionType]);
        $expireDays = 60;
        if (is_array($tweetId)) {
            self::msetActionToTweetPerUser($baseKey, $tweetId, $actionPushed);
        } else {
            Redis::hset($baseKey, $tweetId, $actionPushed);
        }
        Redis::expire($baseKey, 60 * 60 * 24 * $expireDays);
    }

    private static function msetActionToTweetPerUser ($baseKey, $tweetIdList, $actionPushed)
    {
        $values = [];
        foreach($tweetIdList as $tweetId) {
            $values[$tweetId] = $actionPushed;
        }
        Redis::hMSet($baseKey, $values);
    }

    public static function getActionToTweetPerUser ($actionType, $userId, $tweetIdList)
    {
        $baseKey = implode(':', [config('tweet.TWEET_BASE_KEY'), $userId, $actionType]);
        return Redis::hMGet($baseKey, $tweetIdList);
    }


    public static function delActionToTweetPerUser ($actionType, $userId, $tweetId)
    {
        $baseKey = implode(':', [config('tweet.TWEET_BASE_KEY'), $userId, $actionType]);
        $expireDays = 60;
        Redis::hdel($baseKey, $tweetId);
    }

    public static function incrTweetLikeCount($tweetId)
    {
        $baseKey = implode(':', [config('tweet.TWEET_BASE_KEY'), config('tweet.USER_ACTION.LIKE')]);
        $expireDays = 60;
        Redis::zincrby($baseKey, 1, $tweetId);
        Redis::expire($baseKey, 60 * 60 * 24 * $expireDays);
    }

    public static function decrTweetLikeCount($tweetId)
    {
        $baseKey = implode(':', [config('tweet.TWEET_BASE_KEY'), config('tweet.USER_ACTION.LIKE')]);
        $expireDays = 60;
        Redis::zincrby($baseKey, -1, $tweetId);
    }

    public static function incrTweetImpressionCount($impressionTweetIdList)
    {
        $baseKey = implode(':', [config('tweet.TWEET_BASE_KEY'), config('tweet.USER_ACTION.IMPRESSION')]);
        $expireDays = 60;
        foreach($impressionTweetIdList as $tweetId) {
            Redis::zincrby($baseKey, 1, $tweetId);
        }
        Redis::expire($baseKey, 60 * 60 * 24 * $expireDays);
    }


}
