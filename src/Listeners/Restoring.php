<?php

namespace Knetewelt\Causerstamps\Listeners;

class Restoring
{
    public function handle($model)
    {
        if (! $model->isCauserstamping() || is_null($model->getDeletedByColumn())) {
            return;
        }

        $model->{$model->getDeletedByColumn()} = null;

        if ($model->isTouchingRelations()) {
            foreach ($model->getTouchedRelations() as $relation) {
                $model->$relation->{$model->getUpdatedByColumn()} = Auth::id() ?? ($model->hasDefaultUser() ? $model->getDefaultUserId() : null);
                $model->$relation->save();
            }
        }
    }
}