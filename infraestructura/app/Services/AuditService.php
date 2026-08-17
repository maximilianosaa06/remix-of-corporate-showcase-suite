<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Auth;
use App\Core\Database;

class AuditService
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function log(string $action, string $entity, int $entityId, string $details = ''): void
    {
        $user = Auth::user();
        $userId = $user ? (int) $user['id'] : 0;
        $ip = $this->getClientIp();

        $this->db->insert('audit_log', [
            'user_id'    => $userId,
            'action'     => $action,
            'entity'     => $entity,
            'entity_id'  => $entityId,
            'details'    => $details,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getRecent(int $limit = 50): array
    {
        return $this->db->fetchAll(
            "SELECT a.id, a.action, a.entity, a.entity_id, a.details, a.created_at,
                    u.username AS user_name
             FROM audit_log a
             LEFT JOIN app_user u ON a.user_id = u.id
             ORDER BY a.created_at DESC
             LIMIT :limit",
            ['limit' => $limit]
        );
    }

    public function findByEntity(string $entity, int $entityId): array
    {
        return $this->db->fetchAll(
            "SELECT a.id, a.action, a.details, a.created_at,
                    u.username AS user_name
             FROM audit_log a
             LEFT JOIN app_user u ON a.user_id = u.id
             WHERE a.entity = :entity AND a.entity_id = :entity_id
             ORDER BY a.created_at DESC",
            ['entity' => $entity, 'entity_id' => $entityId]
        );
    }

    public function count(): int
    {
        $result = $this->db->fetchOne("SELECT COUNT(*) AS total FROM audit_log");
        return (int) ($result['total'] ?? 0);
    }

    private function getClientIp(): string
    {
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = trim(explode(',', $_SERVER[$header])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
