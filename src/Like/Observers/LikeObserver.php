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

namespace Gox\Laravel\Likeable\Like\Observers;

use Gox\Contracts\Likeable\Like\Models\Like as LikeContract;
use Gox\Contracts\Likeable\Likeable\Services\LikeableService as LikeableServiceContract;
use Gox\Laravel\Likeable\Likeable\Events\LikeableWasUnliked;
use Gox\Laravel\Likeable\Likeable\Events\LikeableWasLiked;

/**
 * Class LikeObserver
 * @package Gox\Laravel\Likeable\Like\Observers
 */
class LikeObserver
{
    /**
     * @param LikeContract $like
     */
    public function created(LikeContract $like)
    {
        event(new LikeableWasLiked($like->likeable, $like->user_id));
        app(LikeableServiceContract::class)->incrementLikesCount($like->likeable);
    }

    /**
     * @param LikeContract $like
     */
    public function deleted(LikeContract $like)
    {
        event(new LikeableWasUnliked($like->likeable, $like->user_id));
        app(LikeableServiceContract::class)->decrementLikesCount($like->likeable);
    }
}
