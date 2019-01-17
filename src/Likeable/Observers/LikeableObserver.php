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

namespace Gox\Laravel\Likeable\Likeable\Observers;

use Gox\Contracts\Likeable\Likeable\Models\Likeable as LikeableContract;

class LikeableObserver {


    /**
     * @param LikeableContract $likeable
     */
    public function deleted(LikeableContract $likeable)
    {
        if ( !$this->removeLikesOnDelete($likeable) ) {
            return;
        }

        $likeable->removeLikes();
    }

    /**
     * @param LikeableContract $likeable
     * @return bool
     */
    private function removeLikesOnDelete(LikeableContract $likeable): bool
    {
        return $likeable->removeLikesOnDelete ?? true;
    }
}
