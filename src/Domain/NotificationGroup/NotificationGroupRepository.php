<?php
declare(strict_types=1);

namespace App\Domain\NotificationGroup;

interface NotificationGroupRepository
{
    /**
     * @return NotificationGroup[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return NotificationGroup
     */
    public function findNotificationGroupOfId(int $id): NotificationGroup;

    /**
     * @param NotificationGroup $group
     * @return bool
     */
    public function save(NotificationGroup $group): void;
}
