<?php

namespace App\Http\Controllers;

use App\Http\Services\TweetService;
use Illuminate\Http\Request;
use App\Http\Requests\TweetRequest;

class TweetController extends Controller
{

    protected $tweetService;

    public function __construct(TweetService $tweet_service)
    {
        $this->middleware('auth')->only(['index']);
        $this->tweetService = $tweet_service;
    }

    /**
     * Display a tweet timeline.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tweet.index');
    }

    /**
     * Fetch tweets from db and redis
     *
     * @param  Request $request
     * @return Json
     */
    public function fetch(Request $request) {
        $decodedFetchedTweetIdList = json_decode($request->fetchedTweetIdList, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['errorMessage' => json_last_error_msg()], 500);
        }

        $tweets = $this->tweetService->extractShowTweets($decodedFetchedTweetIdList, $request->page);

        return response()->json(['tweets' => $tweets], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TweetRequest $request)
    {
        // validate posted tweet
        $validatedTweet = $request->validated();

        // save tweet in DB
        $tweet = $this->tweetService->saveTweet($validatedTweet);

        return $tweet;
    }

    /**
     * Store like per user in redis
     * @param  Request $request tweetId, likePushed
     * @return Json           tweetId
     */
    public function postLike(Request $request)
    {
        $this->tweetService->updateLikeCount($request->tweetId, $request->likePushed);
        return $request->tweetId;
    }

    /**
     * Store report per user in redis and db
     * @param  Request $request tweetId, likePushed
     * @return Json           tweetId
     */
    public function postReport(Request $request)
    {
        $this->tweetService->updateReportCount($request->tweetId, $request->reportPushed);
        return $request->tweetId;
    }

    /**
     * [postImpression description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postImpression(Request $request)
    {
        $decodedImpressionTweetIdList = json_decode($request->impressionTweetIdList, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['errorMessage' => json_last_error_msg()], 500);
        }

        $this->tweetService->updateImpressionCount($decodedImpressionTweetIdList);
        return $request->impressionTweetIdList;

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Tweet  $tweet
     * @return \Illuminate\Http\Response
     */
    public function show(Tweet $tweet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tweet  $tweet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tweet $tweet)
    {
        //
        Tweet::destroy($tweet->id);
    }
}
