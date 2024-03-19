<?php

namespace Knetewelt\Causerstamps;
trait Causerstamps
{
    protected $causerstamping = true;
    protected $touchRelations = true;

    public static function boot()
    {
        static::addGlobalScope(new CauserstampsScope);
        static::registerListeners();
    }

    public static function registerListeners()
    {
        static::creating('Knetewelt\Causerstamps\Listeners\Creating@handle');
        static::updating('Knetewelt\Causerstamps\Listeners\Updating@handle');

        if (static::usingSoftDeletes()) {
            static::deleting('Knetewelt\Causerstamps\Listeners\Deleting@handle');
            static::restoring('Knetewelt\Causerstamps\Listeners\Restoring@handle');
        }
    }

    public static function usingSoftDeletes()
    {
        static $usingSoftDeletes;

        if (is_null($usingSoftDeletes)) {
            return $usingSoftDeletes = in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive(get_called_class()));
        }

        return $usingSoftDeletes;
    }

    public function creator()
    {
        return $this->belongsTo($this->getUserClass(), $this->getCreatedByColumn());
    }

    public function editor()
    {
        return $this->belongsTo($this->getUserClass(), $this->getUpdatedByColumn());
    }

    public function destroyer()
    {
        return $this->belongsTo($this->getUserClass(), $this->getDeletedByColumn());
    }

    public function getCreatedByColumn()
    {
        return defined('static::CREATED_BY') ? static::CREATED_BY : 'created_by';
    }

    public function getUpdatedByColumn()
    {
        return defined('static::UPDATED_BY') ? static::UPDATED_BY : 'updated_by';
    }

    public function getDeletedByColumn()
    {
        return defined('static::DELETED_BY') ? static::DELETED_BY : 'deleted_by';
    }

    public function isCauserstamping()
    {
        return $this->causerstamping;
    }

    public function enableCauserstamping()
    {
        $this->causerstamping = true;
    }

    public function disableCauserstamping()
    {
        $this->causerstamping = false;
    }

    public function isTouchingRelations()
    {
        return $this->touchRelations;
    }

    public function enableTouchingRelations()
    {
        $this->touchRelations = true;
    }

    public function disableTouchingRelations()
    {
        $this->touchRelations = false;
    }

    protected function getUserClass()
    {
        return config('auth.providers.users.model');
    }
}