<?php

namespace Knetewelt\Causerstamps\Listeners;

class Restoring
{
    public function handle($model)
    {
        if (! $model->isUserstamping() || is_null($model->getDeletedByColumn())) {
            return;
        }

        $model->{$model->getDeletedByColumn()} = null;

        if ($model->isTouchingRelations()) {
            foreach ($model->getTouchedRelations() as $relation) {
                $model->$relation->{$model->getUpdatedByColumn()} = Auth::id();
                $model->$relation->save();
            }
        }
    }
}