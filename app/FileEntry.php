<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 * @package SharedFiles
 */
class FileEntry extends Model
{
    /**
     * File belongs to user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
