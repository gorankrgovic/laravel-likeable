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

namespace Gox\Contracts\Likeable\Likeable\Models;

interface Likeable
{
    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Get the class name for polymorphic relations.
     *
     * @return string
     */
    public function getMorphClass();

    /**
     * Collection of the likes on this record.
     *
     * @return mixed
     */
    public function likes();

    /**
     * Counter is a record that stores the total likes for the morphed record.
     *
     * @return mixed
     */
    public function likesCounter();


    /**
     * Fetch users who subscribed to the entity.
     *
     * @todo Do we need to rely on the Laravel Collections here?
     * @return \Illuminate\Support\Collection
     */
    public function collectLikers();

    /**
     * Add a like for model by the given user.
     *
     * @param null|string|int $userId If null will use currently logged in user.
     * @return void
     */
    public function likeBy($userId = null);

    /**
     * Remove a like for model by the given user.
     *
     * @param null|string|int $userId If null will use currently logged in user.
     * @return void
     */
    public function unlikeBy($userId = null);


    /**
     * Delete likes related to the current record.
     *
     * @return void
     */
    public function removeLikes();


    /**
     * Has the user already liked likeable model.
     *
     * @param null|string|int $userId
     * @return bool
     */
    public function isLikedBy($userId = null): bool;

}