<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

class UserRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        return $this->db->fetchAll(
            "SELECT u.id, u.username, u.email, u.active, u.must_change_password,
                    r.id AS role_id, r.name AS role_name
             FROM app_user u
             JOIN role r ON u.role_id = r.id
             ORDER BY u.id"
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
            "SELECT id, username, email, active, must_change_password
             FROM app_user
             WHERE email = :email",
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

    public function toggleActive(int $id): void
    {
        $user = $this->findById($id);
        if ($user) {
            $this->update($id, ['active' => !$user['active']]);
        }
    }

    public function setMustChangePassword(int $id, bool $value): int
    {
        return $this->update($id, ['must_change_password' => $value]);
    }

    public function count(): int
    {
        $result = $this->db->fetchOne("SELECT COUNT(*) AS total FROM app_user");
        return (int) ($result['total'] ?? 0);
    }

    public function countByRole(string $roleName): int
    {
        $result = $this->db->fetchOne(
            "SELECT COUNT(*) AS total
             FROM app_user u
             JOIN role r ON u.role_id = r.id
             WHERE r.name = :role AND u.active = true",
            ['role' => $roleName]
        );
        return (int) ($result['total'] ?? 0);
    }

    public function findAllRoles(): array
    {
        return $this->db->fetchAll("SELECT id, name, description FROM role ORDER BY id");
    }
}
