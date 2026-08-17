<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

class ProyectoRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAllActive(int $limit = 4): array
    {
        return $this->db->fetchAll(
            "SELECT id, name, description, image, link
             FROM project
             WHERE active = true
             ORDER BY id DESC
             LIMIT :limit",
            ['limit' => $limit]
        );
    }

    public function findAll(): array
    {
        return $this->db->fetchAll(
            "SELECT id, name, description, image, link, active
             FROM project
             ORDER BY id DESC"
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, name, description, image, link, active
             FROM project
             WHERE id = :id",
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

    public function toggleActive(int $id): void
    {
        $project = $this->findById($id);
        if ($project) {
            $this->update($id, ['active' => !$project['active']]);
        }
    }
}
