<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

class ContactoRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        return $this->db->fetchAll(
            "SELECT id, name, email, phone, subject, message, sent_at, status
             FROM contact_request
             ORDER BY sent_at DESC"
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, name, email, phone, subject, message, sent_at, status
             FROM contact_request
             WHERE id = :id",
            ['id' => $id]
        );
    }

    public function updateStatus(int $id, string $status): int
    {
        return $this->db->update('contact_request', ['status' => $status], 'id = :id', ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('contact_request', 'id = :id', ['id' => $id]);
    }

    public function count(): int
    {
        $result = $this->db->fetchOne("SELECT COUNT(*) AS total FROM contact_request");
        return (int) ($result['total'] ?? 0);
    }

    public function countPending(): int
    {
        $result = $this->db->fetchOne(
            "SELECT COUNT(*) AS total FROM contact_request WHERE status = 'pendiente'"
        );
        return (int) ($result['total'] ?? 0);
    }
}
