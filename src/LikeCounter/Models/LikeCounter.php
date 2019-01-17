<?php
/*
 * This file is part of Laravel Likeable.
 *
 * (c) Goran Krgovic <gorankrgovic1@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Gox\Laravel\Likeable\LikeCounter\Models;

use Gox\Contracts\Likeable\LikeCounter\Models\LikeCounter as LikeCounterContract;
use Gox\Laravel\Likeable\UuidTrait\GenerateUuid;
use Illuminate\Database\Eloquent\Model;

class LikeCounter extends Model implements LikeCounterContract
{
    use GenerateUuid;

    /**
     * @var string
     */
    protected $table = 'like_counters';


    /**
     * Do not increment the id since we are using UUIDs
     *
     * @var bool
     */
    public $incrementing = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'count'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'count' => 'integer'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|mixed
     */
    public function likeable()
    {
        return $this->morphTo();
    }

}

