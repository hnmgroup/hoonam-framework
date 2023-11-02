<?php

namespace Hoonam\Framework\Domain;

use Hoonam\Framework\Equatable;
use Hoonam\Framework\NotSupportedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Support\Str;

abstract class Entity extends Model implements Equatable
{
    public $incrementing = true;
    protected $guarded = [];
    public $preventsLazyLoading = true;
    protected static $isBroadcasting = false;
    protected static $modelsShouldPreventLazyLoading = true;
    protected static $modelsShouldPreventSilentlyDiscardingAttributes = true;
    protected static $modelsShouldPreventAccessingMissingAttributes = true;
    public static $snakeAttributes = false;
    public $timestamps = false;
    private array $deletedRelationItems = [];
    private array $definedRelations = [
        'has_one'         => [],
        'has_many'        => [],
        'belongs_to_many' => [],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct(array_merge($this->getDefaults(), $attributes));
    }

    public function id(): ?int { return $this->getAttributeValue($this->primaryKey); }

    protected function getDefaults(): array
    {
        return [];
    }

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

    protected function beforeSaving(): void
    {
    }

    protected function afterSaved(): void
    {
    }

    protected function defineRelations(array $hasOne = [], array $hasMany = [], array $belongsToMany = []): void
    {
        $this->definedRelations['has_one'] = $hasOne;
        $this->definedRelations['has_many'] = $hasMany;
        $this->definedRelations['belongs_to_many'] = $belongsToMany;

        foreach ($hasMany as $name) {
            if (!$this->relationLoaded($name))
                $this->setRelation($name, new DbCollection());
        }

        foreach ($belongsToMany as $name) {
            if (!$this->relationLoaded($name))
                $this->setRelation($name, new DbCollection());
        }
    }

    private function isHasOneRelation(string $name): bool
    {
        return in_array($name, $this->definedRelations['has_one']);
    }

    private function isHasManyOrBelongsToRelation(string $name): bool
    {
        return in_array($name, $this->definedRelations['has_many'])
            || in_array($name, $this->definedRelations['belongs_to_many']);
    }

    protected function tryLoadRelation(string $key): void
    {
        if (!$this->relationLoaded($key) && $this->isHasManyOrBelongsToRelation($key))
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
        if ($this->isHasOneRelation($key))
            $this->setRelation($key, null);
        else if ($this->isHasManyOrBelongsToRelation($key))
            $this->setRelation($key, $this->getLoadedRelationValue($key)->reject($entity));
        else
            throw new NotSupportedException('relation type not supported: '.$key);

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
            /** @type Entity $model */

            $deleteModels = $this->deletedRelationItems[$name] ?? [];
            foreach (array_filter($deleteModels) as $i => $model) {
                if ($model->delete() === false) return false;
                unset($deleteModels[$i]);
                $this->deletedRelationItems[$name] = $deleteModels;
            }

            $models = array_filter($models instanceof DbCollection ? $models->all() : [$models]);
            $relationship = $this->$name();

            if ($relationship instanceof BelongsToMany) {
                $ids = array_map(fn (Entity $model) => $model->id(), $models);
                $relationship->sync($ids);
                continue;
            }

            $isHasOneOrMany = $relationship instanceof HasOneOrMany;
            foreach ($models as $model) {
                if ($isHasOneOrMany)
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
        static::saving(fn (Entity $model) => $model->beforeSaving());
        static::saved(fn (Entity $model) => $model->afterSaved());
    }

    public function equals(mixed $other): bool
    {
        if (!($other instanceof Entity)) return false;
        if (!is_null($this->id()) && !is_null($other->id()) && $this->id() === $other->id()) return true;
        return $this === $other;
    }

    public function getTable(): string
    {
        return $this->table ?? Str::snake(class_basename($this));
    }
}
