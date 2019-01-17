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

namespace Gox\Laravel\Likeable\Like\Models;

use Gox\Contracts\Likeable\Like\Models\Like as LikeContract;
use Gox\Laravel\Likeable\UuidTrait\GenerateUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Like
 * @package Gox\Laravel\Likeable\Like\Models
 */
class Like extends Model implements LikeContract
{
    use GenerateUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'likes';


    /**
     * Since we are using the UUID as the ID we are not incrementing the model
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
        'user_id'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|mixed
     */
    public function likeable()
    {
        return $this->morphTo();
    }
}

