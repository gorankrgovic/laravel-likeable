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

namespace Gox\Laravel\Likeable\Liker\Models\Traits;

use Gox\Contracts\Likeable\Likeable\Models\Likeable as LikeableContract;
use Gox\Contracts\Likeable\Likeable\Services\LikeableService as LikeableServiceContract;

trait Liker
{

    /**
     * @param LikeableContract $likeable
     */
    public function like(LikeableContract $likeable)
    {
        app(LikeableServiceContract::class)->addLikeTo($likeable, $this);
    }


    /**
     * @param LikeableContract $likeable
     */
    public function unlike(LikeableContract $likeable)
    {
        app(LikeableServiceContract::class)->removeLikeFrom($likeable, $this);
    }

    /**
     * @param LikeableContract $likeable
     * @return bool
     */
    public function hasLiked(LikeableContract $likeable): bool
    {
        return app(LikeableServiceContract::class)->isLiked($likeable, $this);
    }


}