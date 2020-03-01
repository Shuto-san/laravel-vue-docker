<?php

return [

    'TWEET_BASE_KEY' => 'shoot_tweet',
    'USER_ACTION' => [
        'LIKE' => 'like',
        'REPORT' => 'report',
        'IMPRESSION' => 'impression'
    ],
    'BULK_UPDATE_QUERY' => [
        'USER_ACTION_COUNT' => 'UPDATE tweets SET %s_count = %s WHERE id IN (%s)',
    ]
];
