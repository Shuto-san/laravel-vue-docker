<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tweet;
use App\RedisModel;
use Illuminate\Support\Facades\DB;

class UpdateUserActionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-action:update {--like} {--impression} {--update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update user action to tweet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // extract all tweet count
        $allTweetCount = Tweet::count();

        // chunk tweet user action info
        $chunkSize = 1000;
        $chunkLoopCount = intval($allTweetCount / $chunkSize) + 1;
        for ($chunk_i = 0; $chunk_i < $chunkLoopCount; $chunk_i++) {
            $offset = $chunk_i * $chunkSize;
            $limit = ($chunk_i + 1) * $chunkSize -1;

            $likeTweetMap = RedisModel::zrangeTweetAction(config('tweet.USER_ACTION.LIKE'), $offset, $limit);
            $impressionTweetMap = RedisModel::zrangeTweetAction(config('tweet.USER_ACTION.IMPRESSION'), $offset, $limit);

            // log output
            foreach ($likeTweetMap as $key => $value) {
                echo "tweetId: $key, likeCount: $value \n";
                \Log::info("tweetId: $key, likeCount: $value");
            }

            echo "----------------------------- \n";

            foreach ($impressionTweetMap as $key => $value) {
                echo "tweetId: $key, impressionCount: $value \n";
                \Log::info("tweetId: $key, impressionCount: $value ");
            }

            if (!$this->option('update')) {
                echo "if you add --update with --like and --impression, like and impression info will be updated \n";
                return 0;
            }

            // update like
            if ($this->option('like')) {
                if (count($likeTweetMap)) {
                    $this->bulkUpdate(config('tweet.USER_ACTION.LIKE'), $likeTweetMap);
                }
            }

            // update impression
            if ($this->option('impression')) {
                if(count($impressionTweetMap)) {
                    $this->bulkUpdate(config('tweet.USER_ACTION.IMPRESSION'), $impressionTweetMap);
                }
            }
        }
    }

    private function bulkUpdate($actionType, $tweetMap)
    {
        $idList = "";
        $countList = "";
        foreach ($tweetMap as $tweetId => $count) {
            if (is_null($count)) {
                continue;
            }
            $idList .= "," . $tweetId;
            $countList .= "," . $count;
        }

        $field = sprintf(config('const.BULK_UPDATE.FIELD'), $idList);
        $elt = sprintf(config('const.BULK_UPDATE.ELT'), $field, $countList);
        $query = sprintf(config('tweet.BULK_UPDATE_QUERY.USER_ACTION_COUNT'), $actionType, $elt, ltrim($idList, ","));

        // bulk update
        DB::connection()->getPdo()->query($query);
        \Log::info($query);
    }
}
