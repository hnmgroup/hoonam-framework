<?php

namespace Hoonam\Framework\Domain;

use Hoonam\Framework\Equatable;
use Illuminate\Database\Eloquent\Model;

abstract class Entity extends Model implements Equatable
{
    public function id(): ?int { return $this->getAttributeValue($this->primaryKey); }

    public $incrementing = true;
    protected $guarded = [];
    public $preventsLazyLoading = true;
    protected static $isBroadcasting = false;
    protected static $modelsShouldPreventLazyLoading = true;
    protected static $modelsShouldPreventSilentlyDiscardingAttributes = true;
    protected static $modelsShouldPreventAccessingMissingAttributes = true;
    public static $snakeAttributes = false;
    public $timestamps = false;

    protected function beforeCreating(): void
    {
    }

    protected function afterCreated(): void
    {
    }

    protected function beforeUpdating(): void
    {
    }

    protected function afterUpdated(): void
    {
    }

    protected function afterDeleted(): void
    {
    }

    public function saveAll(): bool
    {
        return $this->save();
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn (Entity $model) => $model->beforeCreating());
        static::created(fn (Entity $model) => $model->afterCreated());
        static::updating(fn (Entity $model) => $model->beforeUpdating());
        static::updated(fn (Entity $model) => $model->afterUpdated());
        static::deleted(fn (Entity $model) => $model->afterDeleted());
    }

    public function equals(mixed $other): bool
    {
        if (!($other instanceof Entity)) return false;
        if (!is_null($this->id()) && !is_null($other->id()) && $this->id() === $other->id()) return true;
        return $this === $other;
    }
}
