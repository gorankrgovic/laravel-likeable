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

namespace Gox\Contracts\Likeable\Likeable\Exceptions;

use RuntimeException;

/**
 * Class InvalidLikeable
 * @package Gox\Contracts\Likeable\Likeable\Exceptions
 */
class InvalidLikeable extends RuntimeException
{

    /**
     * @param string $type
     * @return InvalidLikeable
     */
    public static function notExists(string $type)
    {
        return new static("{$type} class or morph map not found");
    }

    /**
     * @param string $type
     * @return InvalidLikeable
     */
    public static function notImplementInterface(string $type)
    {
        return new static ("[{$type}] must implement `\Gox\Contracts\Likeable\Likeable\Models\Likeable` contract");
    }
}
