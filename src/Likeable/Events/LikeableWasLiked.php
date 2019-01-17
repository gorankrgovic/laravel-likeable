<?php
/*
 * This file is part of Laravel Subscribe.
 *
 * (c) Goran Krgovic <gorankrgovic1@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Gox\Laravel\Likeable\Likeable\Events;

use Gox\Contracts\Likeable\Likeable\Models\Likeable as LikeableContract;

class LikeableWasLiked
{
    /**
     * @var LikeableContract
     */
    public $likeable;


    /**
     * User id who liked the model
     *
     * @var string
     */
    public $likerId;


    /**
     * LikeableWasLiked constructor.
     * @param LikeableContract $likeable
     * @param $likerId
     */
    public function __construct(LikeableContract $likeable, $likerId)
    {
        $this->likerId = $likerId;
        $this->likeable = $likeable;
    }

}