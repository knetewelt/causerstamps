<?php

namespace Knetewelt\Causerstamps\Listeners;

use Illuminate\Support\Facades\Auth;

class Updating
{
    public function handle($model)
    {
        if (! $model->isUserstamping() || is_null($model->getUpdatedByColumn()) || is_null(Auth::id())) {
            return;
        }

        $model->{$model->getUpdatedByColumn()} = Auth::id();

        if ($model->isTouchingRelations()) {
            foreach ($model->getTouchedRelations() as $relation) {
                $model->$relation->{$model->getUpdatedByColumn()} = Auth::id();
                $model->$relation->save();
            }
        }
    }
}