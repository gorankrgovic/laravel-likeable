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

namespace Gox\Contracts\Likeable\Like\Models;

/**
 * Interface Like
 * @package Gox\Contracts\Likeable\Like\Models
 */
interface Like
{
    /**
     * @return mixed
     */
    public function likeable();
}
