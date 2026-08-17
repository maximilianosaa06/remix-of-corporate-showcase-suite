<?php

declare(strict_types=1);

namespace App\Api\Repositories;

use App\Core\Database;

class UserRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        return $this->db->fetchAll(
            "SELECT u.id, u.username, u.email, u.active, u.must_change_password,
                    r.id AS role_id, r.name AS role_name
             FROM app_user u
             JOIN role r ON u.role_id = r.id
             ORDER BY u.id
             LIMIT :limit OFFSET :offset",
            ['limit' => $limit, 'offset' => $offset]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT u.id, u.username, u.email, u.active, u.must_change_password,
                    r.id AS role_id, r.name AS role_name
             FROM app_user u
             JOIN role r ON u.role_id = r.id
             WHERE u.id = :id",
            ['id' => $id]
        );
    }

    public function findByEmail(string $email): ?array
    {
        return $this->db->fetchOne(
            "SELECT u.id, u.username, u.email, u.password, u.active, u.must_change_password,
                    u.role_id, r.name AS role_name
             FROM app_user u
             JOIN role r ON u.role_id = r.id
             WHERE u.email = :email",
            ['email' => $email]
        );
    }

    public function create(array $data): int
    {
        return $this->db->insert('app_user', $data);
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('app_user', $data, 'id = :id', ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('app_user', 'id = :id', ['id' => $id]);
    }

    public function count(): int
    {
        $r = $this->db->fetchOne("SELECT COUNT(*) AS t FROM app_user");
        return (int) ($r['t'] ?? 0);
    }

    public function findAllRoles(): array
    {
        return $this->db->fetchAll("SELECT id, name, description FROM role ORDER BY id");
    }

    public function findRoleById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, name, description FROM role WHERE id = :id",
            ['id' => $id]
        );
    }
}
