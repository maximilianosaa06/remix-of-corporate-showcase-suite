<?php

declare(strict_types=1);

namespace App\Api\Repositories;

use App\Core\Database;

class StaffRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        return $this->db->fetchAll(
            "SELECT id, name, \"position\", photo, description
             FROM staff_member
             ORDER BY id DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, name, \"position\", photo, description
             FROM staff_member WHERE id = :id",
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

    public function count(): int
    {
        $r = $this->db->fetchOne("SELECT COUNT(*) AS t FROM staff_member");
        return (int) ($r['t'] ?? 0);
    }
}
