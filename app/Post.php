<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Get the location associated with the post.
     */
    public function location()
    {
        return $this->hasOne('App\PostLocation');
    }
}
