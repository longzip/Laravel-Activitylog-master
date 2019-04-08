<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Activitylog.
 *
 * (c) Lo Long <longlv@noithatzip.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LoLong\Activitylog\Traits;

use LoLong\Activitylog\Models\Activitylog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasActivity
{
    /**
     * @return string
     */
    public function activitylogModel(): string
    {
        return config('laravel-activitylog.model');
    }

    /**
     * @return mixed
     */
    public function activities(): MorphMany
    {
        return $this->morphMany($this->activitylogModel(), 'activitylog');
    }

    /**
     * @param $data
     * @param Model      $creator
     * @param Model|null $parent
     *
     * @return static
     */
    public function comment($data, Model $creator, Model $parent = null)
    {
        $activitylogModel = $this->activitylogModel();

        $comment = (new $activitylogModel())->createComment($this, $data, $creator);

        if (!empty($parent)) {
            $parent->appendNode($comment);
        }

        return $comment;
    }

    /**
     * @param $id
     * @param $data
     * @param Model|null $parent
     *
     * @return mixed
     */
    public function updateActivity($id, $data, Model $parent = null)
    {
        $activitylogModel = $this->activitylogModel();

        $activity = (new $activitylogModel())->updateActivity($id, $data);

        if (!empty($parent)) {
            $parent->appendNode($activity);
        }

        return $activity;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteActivity($id): bool
    {
        $activitylogModel = $this->activitylogModel();

        return (bool) (new $activitylogModel())->deleteActivity($id);
    }

    /**
     * @return mixed
     */
    public function activityCount(): int
    {
        return $this->activities->count();
    }
}
