<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

class StaffRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAllActive(int $limit = 4): array
    {
        return $this->db->fetchAll(
            "SELECT id, name, \"position\", photo, description
             FROM staff_member
             ORDER BY id ASC
             LIMIT :limit",
            ['limit' => $limit]
        );
    }

    public function findAll(): array
    {
        return $this->db->fetchAll(
            "SELECT id, name, \"position\", photo, description
             FROM staff_member
             ORDER BY id ASC"
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, name, \"position\", photo, description
             FROM staff_member
             WHERE id = :id",
            ['id' => $id]
        );
    }

    public function create(array $data): int
    {
        return $this->db->insert('staff_member', $data);
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('staff_member', $data, 'id = :id', ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('staff_member', 'id = :id', ['id' => $id]);
    }
}
