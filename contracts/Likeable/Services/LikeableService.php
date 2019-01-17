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

namespace Gox\Contracts\Likeable\Likeable\Services;

use Gox\Contracts\Likeable\Likeable\Models\Likeable as LikeableContract;

interface LikeableService
{

    /**
     * @param LikeableContract $likeable
     * @param $userId
     * @return mixed
     */
    public function addLikeTo(LikeableContract $likeable, $userId);

    /**
     * @param LikeableContract $likeable
     * @param $userId
     * @return mixed
     */
    public function removeLikeFrom(LikeableContract $likeable, $userId);

    /**
     * @param LikeableContract $likeable
     * @param $userId
     * @return bool
     */
    public function isLiked(LikeableContract $likeable, $userId): bool;

    /**
     * @param LikeableContract $likeable
     * @return mixed
     */
    public function decrementLikesCount(LikeableContract $likeable);

    /**
     * @param LikeableContract $likeable
     * @return mixed
     */
    public function incrementLikesCount(LikeableContract $likeable);

    /**
     * @param $likeableType
     * @return mixed
     */
    public function removeLikeCountersOfType($likeableType);

    /**
     * @param LikeableContract $likeable
     * @return mixed
     */
    public function removeModelLikes(LikeableContract $likeable);

    /**
     * @param LikeableContract $likeable
     * @return mixed
     */
    public function collectLikersOf(LikeableContract $likeable);

    /**
     * @param $likeableType
     * @return array
     */
    public function fetchLikesCounters($likeableType): array;
}