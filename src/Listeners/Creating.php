<?php

namespace Knetewelt\Causerstamps\Listeners;

use Illuminate\Support\Facades\Auth;

class Creating
{
    public function handle($model)
    {
        if (! $model->isCauserstamping() || is_null($model->getCreatedByColumn())) {
            return;
        }

        if (is_null($model->{$model->getCreatedByColumn()})) {
            $model->{$model->getCreatedByColumn()} = Auth::id();
        }

        if (is_null($model->{$model->getUpdatedByColumn()}) && ! is_null($model->getUpdatedByColumn())) {
            $model->{$model->getUpdatedByColumn()} = Auth::id();
        }

        if ($model->isTouchingRelations()) {
            foreach ($model->getTouchedRelations() as $relation) {
                $model->$relation->{$model->getUpdatedByColumn()} = Auth::id();
                $model->$relation->save();
            }
        }
    }
}
