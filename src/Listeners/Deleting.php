<?php

namespace Knetewelt\Causerstamps\Listeners;

use Illuminate\Support\Facades\Auth;

class Deleting
{
    public function handle($model)
    {
        if (! $model->isUserstamping() || is_null($model->getDeletedByColumn())) {
            return;
        }

        if (is_null($model->{$model->getDeletedByColumn()})) {
            $model->{$model->getDeletedByColumn()} = Auth::id();
        }

        $dispatcher = $model->getEventDispatcher();

        $model->unsetEventDispatcher();

        $model->save();

        $model->setEventDispatcher($dispatcher);

        if ($model->isTouchingRelations()) {
            foreach ($model->getTouchedRelations() as $relation) {
                $model->$relation->{$model->getUpdatedByColumn()} = Auth::id();
                $model->$relation->save();
            }
        }
    }
}
