<?php

namespace Hoonam\Framework\Domain;

use Exception;
use Hoonam\Framework\Equatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Collection as DbCollection;

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
    protected array $deletedRelationItems = [];

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

    protected function tryLoadRelation(string $key): void
    {
        if (!$this->relationLoaded($key) && ($this->$key() instanceof HasMany || $this->$key() instanceof BelongsToMany))
            $this->setRelation($key, new DbCollection());
    }

    protected function getLoadedRelation(string $key): mixed
    {
        $this->tryLoadRelation($key);
        return $this->getRelation($key);
    }

    protected function getLoadedRelationValue(string $key): mixed
    {
        $this->tryLoadRelation($key);
        return $this->getRelationValue($key);
    }

    protected function removeRelationItem(string $key, Entity $entity, bool $markAsDeleted = false): void
    {
        $relationship = $this->$key();
        if ($relationship instanceof HasOne)
            $this->setRelation($key, null);
        else if ($relationship instanceof HasMany || $relationship instanceof BelongsToMany)
            $this->setRelation($key, $this->getLoadedRelationValue($key)->reject($entity));
        else
            throw new Exception('relation type not supported: '.$key);

        if ($markAsDeleted) $this->addToDeletedRelationItems($key, $entity);
    }

    private function addToDeletedRelationItems(string $key, Entity $entity): void
    {
        $this->deletedRelationItems[$key] = array_merge(
            $this->deletedRelationItems[$key] ?? [],
            [$entity],
        );
    }

    public function push(): bool
    {
        if (!$this->save()) return false;

        foreach ($this->relations as $name => $models) {
            $deleteModels = $this->deletedRelationItems[$name] ?? [];
            foreach (array_filter($deleteModels) as $model) {
                if (!$model->delete() === false) return false;
                unset($this->deletedRelationItems[$model]);
            }

            $models = array_filter($models instanceof DbCollection ? $models->all() : [$models]);
            $relationship = $this->$name();

            if ($relationship instanceof BelongsToMany) {
                $ids = array_map(fn (Entity $model) => $model->id(), $models);
                $relationship->sync($ids);
                continue;
            }

            /** @type Entity $model */
            foreach ($models as $model) {
                if ($relationship instanceof HasOneOrMany)
                    $model->setAttribute($relationship->getForeignKeyName(), $relationship->getParentKey());

                if (!$model->push()) return false;
            }
        }

        return true;
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
