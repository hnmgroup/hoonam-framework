<?php

namespace Hoonam\Framework\Domain;

use DateTime;
use Illuminate\Support\Facades\Date;
use Hoonam\Framework\Application\UserIdentity;

abstract class AuditableEntity extends Entity
{
    public function createdBy(): int { return $this->getAttributeValue(static::CREATED_BY); }
    protected function setCreatedBy(int $value): void { $this->setAttribute(static::CREATED_BY, $value); }

    public function createdAt(): DateTime { return Date::parse($this->getAttributeValue(static::CREATED_AT)); }
    // public function setCreatedAt(DateTime $value): void { $this->setAttribute(static::CREATED_AT, $value); }

    public function updatedBy(): int { return $this->getAttributeValue(static::UPDATED_BY); }
    protected function setUpdatedBy(int $value): void { $this->setAttribute(static::UPDATED_BY, $value); }

    public function updatedAt(): DateTime { return Date::parse($this->getAttributeValue(static::UPDATED_AT)); }
    // public function setUpdatedAt(DateTime $value): void { $this->setAttribute(static::UPDATED_AT, $value); }

    protected function beforeCreating(): void
    {
        parent::beforeCreating();

        $userId = $this->resolveUserId() ?? 0;
        $now = now();
        if ($this->isClean(static::CREATED_AT)) $this->setCreatedAt($now);
        if ($this->isClean(static::CREATED_BY)) $this->setCreatedBy($userId);
        if ($this->isClean(static::UPDATED_AT)) $this->setUpdatedAt($now);
        if ($this->isClean(static::UPDATED_BY)) $this->setUpdatedBy($userId);
    }

    protected function beforeUpdating(): void
    {
        parent::beforeUpdating();

        $userId = $this->resolveUserId() ?? 0;
        $now = now();
        if ($this->isClean(static::UPDATED_AT)) $this->setUpdatedAt($now);
        if ($this->isClean(static::UPDATED_BY)) $this->setUpdatedBy($userId);
    }

    private function resolveUserId(): ?int
    {
        /** @type UserIdentity $user */
        $user = app()->make(UserIdentity::class);

        return $user->isAuthenticated() ? $user->id() : null;
    }

    const CREATED_BY = 'created_by';
    const CREATED_AT = 'created_at';
    const UPDATED_BY = 'updated_by';
    const UPDATED_AT = 'updated_at';
}
