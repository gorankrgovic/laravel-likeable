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

namespace Gox\Contracts\Likeable\Liker\Exceptions;

use RuntimeException;

/**
 * Class InvalidLiker
 * @package Gox\Contracts\Likeable\Liker\Exceptions
 */
class InvalidLiker extends RuntimeException
{
    /**
     * @return InvalidLiker
     */
    public static function notDefined()
    {
        return new static('Liker not defined.');
    }
}