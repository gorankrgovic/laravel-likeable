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

namespace Gox\Laravel\Likeable\Likeable\Models\Traits;

use Gox\Contracts\Likeable\Like\Models\Like as LikeContract;
use Gox\Contracts\Likeable\LikeCounter\Models\LikeCounter as LikeCounterContract;
use Gox\Contracts\Likeable\Likeable\Services\LikeableService as LikeableServiceContract;
use Gox\Laravel\Likeable\Likeable\Observers\LikeableObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

trait Likeable
{
    /**
     * On boot
     */
    public static function bootLikeable()
    {
        static::observe(LikeableObserver::class);
    }

    /**
     * @return mixed
     */
    public function likes()
    {
        return $this->morphMany(app(LikeContract::class), 'likeable');
    }

    /**
     * @return mixed
     */
    public function likesCounter()
    {
        return $this->morphOne(app(LikeCounterContract::class), 'likeable');
    }

    /**
     * @return mixed
     */
    public function collectLikes()
    {
        return app(LikeableServiceContract::class)->collectLikersOf($this);
    }


    /**
     * @return int
     */
    public function getLikesCountAttribute(): int
    {
        return $this->likesCounter ? $this->likesCounter->count : 0;
    }

    /**
     * @return bool
     */
    public function getLikedAttribute(): bool
    {
        return $this->isLikedBy();
    }

    /**
     * @param null $userId
     */
    public function likeBy($userId = null)
    {
        app(LikeableServiceContract::class)->addLikeTo($this, $userId);
    }

    /**
     * @param null $userId
     */
    public function unlikeBy($userId = null)
    {
        app(LikeableServiceContract::class)->removeLikeFrom($this, $userId);
    }

    /**
     * Remove all likes from a model
     */
    public function removeLikes()
    {
        app(LikeableServiceContract::class)->removeModelLikes($this);
    }

    /**
     * @param null $userId
     * @return bool
     */
    public function isLikedBy($userId = null): bool
    {
        return app(LikeableServiceContract::class)->isLiked($this, $userId);
    }


    /**
     * @param Builder $query
     * @param null $userId
     * @return Builder
     */
    public function scopeWhereLikedBy(Builder $query, $userId = null): Builder
    {
        return $this->applyScopeWhereLikedBy($query, $userId);
    }

    /**
     * @param Builder $query
     * @param string $direction
     * @return Builder
     */
    public function scopeOrderByLikesCount(Builder $query, string $direction = 'desc'): Builder
    {
        return $this->applyScopeOrderByLikesCount($query, $direction);
    }

    /**
     * @param Builder $query
     * @param $userId
     * @return Builder
     */
    private function applyScopeWhereLikedBy(Builder $query, $userId): Builder
    {
        $service = app(LikeableServiceContract::class);
        $userId = $service->getLikerUserId($userId);
        return $query->whereHas('likes', function (Builder $innerQuery) use ($userId) {
            $innerQuery->where('user_id', $userId);
        });
    }

    /**
     * @param Builder $query
     * @param string $direction
     * @return Builder
     */
    private function applyScopeOrderByLikesCount(Builder $query, string $direction): Builder
    {
        $likeable = $query->getModel();
        return $query
            ->select($likeable->getTable() . '.*', 'like_counters.count')
            ->leftJoin('like_counters', function (JoinClause $join) use ($likeable) {
                $join
                    ->on('like_counters.likeable_id', '=', "{$likeable->getTable()}.{$likeable->getKeyName()}")
                    ->where('like_counters.likeable_type', '=', $likeable->getMorphClass());
            })
            ->orderBy('likeable_counters.count', $direction);
    }

}
