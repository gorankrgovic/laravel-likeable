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

namespace Gox\Laravel\Likeable\Likeable\Services;

use Gox\Contracts\Likeable\Likeable\Models\Likeable as LikeableContract;
use Gox\Contracts\Likeable\Likeable\Services\LikeableService as LikeableServiceContract;
use Gox\Contracts\Likeable\Like\Models\Like as LikeContract;
use Gox\Contracts\Likeable\LikeCounter\Models\LikeCounter as LikeCounterContract;
use Gox\Contracts\Likeable\Liker\Models\Liker as LikerContract;
use Gox\Contracts\Likeable\Liker\Exceptions\InvalidLiker;
use Illuminate\Support\Facades\DB;


class LikeableService implements LikeableServiceContract
{

    /**
     * @param LikeableContract $likeable
     * @param $userId
     * @return mixed|void
     */
    public function addLikeTo(LikeableContract $likeable, $userId)
    {
        $userId = $this->getLikerUserId($userId);

        $like = $likeable->likes()->where([
            'user_id' => $userId,
        ])->first();


        if ( !$like ) {
            $likeable->likes()->create([
                'user_id' => $userId
            ]);

            return;
        }

        $like->delete();

        $likeable->likes()->create([
            'user_id' => $userId
        ]);
    }

    /**
     * @param LikeableContract $likeable
     * @param $userId
     * @return mixed|void
     */
    public function removeLikeFrom(LikeableContract $likeable, $userId)
    {
        $like = $likeable->likes()->where([
            'user_id' => $this->getLikerUserId($userId)
        ])->first();

        if ( !$like )
        {
            return;
        }
        $like->delete();
    }

    /**
     * @param LikeableContract $likeable
     * @param $userId
     * @return bool
     */
    public function isLiked(LikeableContract $likeable, $userId): bool
    {
        if ($userId instanceof LikeContract) {
            $userId = $userId->getKey();
        }

        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if ( !$userId )
        {
            return false;
        }


        return $likeable->likes()->where([
            'user_id' => $userId
        ])->exists();
    }

    /**
     * @param LikeableContract $likeable
     * @return mixed|void
     */
    public function incrementLikesCount(LikeableContract $likeable)
    {
        $counter = $likeable->likesCounter()->first();

        if ( !$counter ) {
            $counter = $likeable->likesCounter()->create([
                'count' => 0,
            ]);
        }
        $counter->increment('count');
    }

    /**
     * @param LikeableContract $likeable
     * @return mixed|void
     */
    public function decrementLikesCount(LikeableContract $likeable)
    {
        $counter = $likeable->likesCounter()->first();

        if ( !$counter ) {
            return;
        }
        $counter->decrement('count');
    }

    /**
     * @param $likeableType
     * @return mixed|void
     */
    public function removeLikeCountersOfType($likeableType)
    {
        if (class_exists($likeableType)) {

            $likeable = new $likeableType;
            $likeableType = $likeable->getMorphClass();
        }

        /** @var \Illuminate\Database\Eloquent\Builder $counters */
        $counters = app(LikeCounterContract::class)->where('likeable_type', $likeableType);
        $counters->delete();
    }

    /**
     * @param LikeableContract $likeable
     * @return mixed|void
     */
    public function removeModelLikes(LikeableContract $likeable)
    {
        app(LikeContract::class)->where([
            'likeable_id' => $likeable->getKey(),
            'likeable_type' => $likeable->getMorphClass()
        ])->delete();

        app( LikeCounterContract::class)->where([
            'likeable_id' => $likeable->getKey(),
            'likeable_type' => $likeable->getMorphClass()
        ])->delete();
    }

    /**
     * @param LikeableContract $likeable
     * @return mixed
     */
    public function collectLikersOf(LikeableContract $likeable)
    {
        $userModel = $this->resolveUserModel();

        $likersIds = $likeable->likes->pluck('user_id');

        return $userModel::whereKey($likersIds)->get();
    }

    /**
     * @param $likeableType
     * @return array
     */
    public function fetchLikesCounters($likeableType): array
    {
        /** @var \Illuminate\Database\Eloquent\Builder $likesCount */

        $likesCount = app(LikeContract::class)
            ->select([
                DB::raw('COUNT(*) AS count'),
                'likeable_type',
                'likeable_id'
            ])
            ->where('subscribeable_type', $likeableType);

        $likesCount->groupBy('likeable_id');

        return $likesCount->get()->toArray();
    }

    /**
     * @param $userId
     * @return string
     */
    public function getLikerUserId($userId): string
    {
        if ($userId instanceof LikerContract) {
            return $userId->getKey();
        }
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }
        if (!$userId) {
            throw InvalidLiker::notDefined();
        }
        return $userId;
    }


    /**
     * Fetch the primary ID of the currently logged in user.
     *
     * @return null|string
     */
    protected function loggedInUserId()
    {
        return auth()->id();
    }


    /**
     * Retrieve User's model class name.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    private function resolveUserModel()
    {
        return config('auth.providers.users.model');
    }
}
