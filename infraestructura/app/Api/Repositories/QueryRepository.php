<?php

declare(strict_types=1);

namespace App\Api\Repositories;

use App\Core\Database;

class QueryRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        return $this->db->fetchAll(
            "SELECT id, name, email, phone, subject, message, sent_at, status
             FROM contact_request
             ORDER BY sent_at DESC
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, name, email, phone, subject, message, sent_at, status
             FROM contact_request WHERE id = :id",
            ['id' => $id]
        );
    }

    public function create(array $data): int
    {
        return $this->db->insert('contact_request', $data);
    }

    public function updateStatus(int $id, string $status): int
    {
        return $this->db->update('contact_request', ['status' => $status], 'id = :id', ['id' => $id]);
    }

    public function count(): int
    {
        $r = $this->db->fetchOne("SELECT COUNT(*) AS t FROM contact_request");
        return (int) ($r['t'] ?? 0);
    }

    public function countPending(): int
    {
        $r = $this->db->fetchOne("SELECT COUNT(*) AS t FROM contact_request WHERE status = 'pendiente'");
        return (int) ($r['t'] ?? 0);
    }
}
