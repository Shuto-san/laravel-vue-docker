<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    /**
     * モデルの配列形態に追加するアクセサ
     *
     * @var array
     */
    protected $appends = ['is_liked', 'is_reported', 'is_viewed'];

    public function getIsLikedAttribute() {
        return $this->attributes['is_liked'];
    }

    public function getIsReportedAttribute() {
        return $this->attributes['is_reported'];
    }

    public function getIsViewedAttribute() {
        return $this->attributes['is_viewed'];
    }
}
