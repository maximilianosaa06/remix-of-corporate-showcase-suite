<?php

declare(strict_types=1);

namespace App\Api\Repositories;

use App\Core\Database;

class TagRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        return $this->db->fetchAll("SELECT id, name FROM tag ORDER BY id");
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, name FROM tag WHERE id = :id",
            ['id' => $id]
        );
    }

    public function create(array $data): int
    {
        return $this->db->insert('tag', $data);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('tag', 'id = :id', ['id' => $id]);
    }
}
