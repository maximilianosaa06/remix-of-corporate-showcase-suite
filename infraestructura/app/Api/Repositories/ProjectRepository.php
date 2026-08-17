<?php

declare(strict_types=1);

namespace App\Api\Repositories;

use App\Core\Database;

class ProjectRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAllActive(int $limit = 20, int $offset = 0): array
    {
        return $this->db->fetchAll(
            "SELECT id, name, description, image, link, active
             FROM project WHERE active = true
             ORDER BY id DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        );
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        return $this->db->fetchAll(
            "SELECT id, name, description, image, link, active
             FROM project
             ORDER BY id DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, name, description, image, link, active
             FROM project WHERE id = :id",
            ['id' => $id]
        );
    }

    public function create(array $data): int
    {
        return $this->db->insert('project', $data);
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('project', $data, 'id = :id', ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('project', 'id = :id', ['id' => $id]);
    }

    public function countActive(): int
    {
        $r = $this->db->fetchOne("SELECT COUNT(*) AS t FROM project WHERE active = true");
        return (int) ($r['t'] ?? 0);
    }

    public function countAll(): int
    {
        $r = $this->db->fetchOne("SELECT COUNT(*) AS t FROM project");
        return (int) ($r['t'] ?? 0);
    }
}
