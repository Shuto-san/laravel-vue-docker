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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tweets = Tweet::all();
        return view('tweet.index', compact('tweets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tweet  $tweet
     * @return \Illuminate\Http\Response
     */
    public function edit(Tweet $tweet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tweet  $tweet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tweet $tweet)
    {
        //
        $tweet->user_id = Auth::user()->id;
        $tweet->nickname = $request->nickname;
        $tweet->tweet = $request->tweet;
        $tweet->save();
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
