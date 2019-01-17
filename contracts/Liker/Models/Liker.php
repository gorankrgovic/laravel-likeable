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

namespace Gox\Contracts\Likeable\Liker\Models;

use Gox\Contracts\Likeable\Likeable\Models\Likeable;

/**
 * Interface Liker
 * @package Gox\Contracts\Likeable\Liker\Models
 */
interface Liker
{
    /**
     * @param Likeable $likeable
     * @return mixed
     */
    public function like(Likeable $likeable);

    /**
     * @param Likeable $likeable
     * @return mixed
     */
    public function unlike(Likeable $likeable);

    /**
     * @param Likeable $likeable
     * @return bool
     */
    public function hasLiked(Likeable $likeable): bool;
}
