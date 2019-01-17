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

namespace Gox\Laravel\Likeable\Console\Commands;

use Gox\Contracts\Likeable\Likeable\Exceptions\InvalidLikeable;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Events\Dispatcher;
use Gox\Contracts\Likeable\Likeable\Services\LikeableService as LikeableServiceContract;
use Gox\Contracts\Likeable\Like\Models\Like as LikeContract;
use Gox\Contracts\Likeable\LikeCounter\Models\LikeCounter as LikeCounterContract;
use Gox\Contracts\Likeable\Likeable\Models\Likeable as LikeableContract;
use Illuminate\Support\Facades\DB;


class RecountCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'golike:recount {model?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recount likes of the likeable models';


    /**
     * @var
     */
    protected $service;


    /**
     * @param Dispatcher $events
     */
    public function handle(Dispatcher $events)
    {
        $model = $this->argument('model');
        $this->service = app(LikeableServiceContract::class);
        if (empty($model)) {
            $this->recountLikesOfAllModelTypes();
        } else {
            $this->recountLikesOfModelType($model);
        }
    }


    /**
     * Recount all model types.
     *
     * @return void
     */
    protected function recountLikesOfAllModelTypes()
    {
        $likeTypes = app(LikeContract::class)->groupBy('likeable_type')->get();
        foreach ($likeTypes as $like) {
            $this->recountLikesOfModelType($like->likeable_type);
        }
    }


    /**
     * @param string $modelType
     */
    protected function recountLikesOfModelType(string $modelType)
    {
        $modelType = $this->normalizeModelType($modelType);
        $counters = $this->service->fetchLikesCounters($modelType);
        $this->service->removeLikeCountersOfType($modelType);
        $sCounterTable = app(LikeCounterContract::class)->getTable();
        DB::table($sCounterTable)->insert($counters);
        $this->info('All [' . $modelType . '] records likes has been recounted.');
    }

    /**
     * @param string $modelType
     * @return string
     */
    protected function normalizeModelType(string $modelType): string
    {
        $model = $this->newModelFromType($modelType);
        $modelType = $model->getMorphClass();
        if (!$model instanceof LikeableContract) {
            throw InvalidLikeable::notImplementInterface($modelType);
        }
        return $modelType;
    }

    /**
     * @param string $modelType
     * @return mixed
     */
    private function newModelFromType(string $modelType)
    {
        if (class_exists($modelType)) {
            return new $modelType;
        }
        $morphMap = Relation::morphMap();
        if (!isset($morphMap[$modelType])) {
            throw InvalidLikeable::notExists($modelType);
        }
        $modelClass = $morphMap[$modelType];
        return new $modelClass;
    }
}