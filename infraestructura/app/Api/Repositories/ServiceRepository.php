<?php

declare(strict_types=1);

namespace App\Api\Repositories;

use App\Core\Database;

class ServiceRepository
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
             FROM service WHERE active = true
             ORDER BY id DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        );
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        return $this->db->fetchAll(
            "SELECT id, name, description, image, link, active
             FROM service
             ORDER BY id DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, name, description, image, link, active
             FROM service WHERE id = :id",
            ['id' => $id]
        );
    }

    public function create(array $data): int
    {
        return $this->db->insert('service', $data);
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('service', $data, 'id = :id', ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('service', 'id = :id', ['id' => $id]);
    }

    public function countActive(): int
    {
        $r = $this->db->fetchOne("SELECT COUNT(*) AS t FROM service WHERE active = true");
        return (int) ($r['t'] ?? 0);
    }

    public function countAll(): int
    {
        $r = $this->db->fetchOne("SELECT COUNT(*) AS t FROM service");
        return (int) ($r['t'] ?? 0);
    }
}
