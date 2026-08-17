<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

class NoticiaRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findPublished(int $limit = 3): array
    {
        return $this->db->fetchAll(
            "SELECT n.id, n.title, n.subtitle, n.content, n.image,
                    n.publication_date, n.created_at,
                    u.username AS author_name,
                    ns.name AS status_name
             FROM news n
             JOIN news_status ns ON n.status_id = ns.id
             LEFT JOIN app_user u ON n.author_id = u.id
             WHERE ns.name = 'publicada'
             ORDER BY n.created_at DESC
             LIMIT :limit",
            ['limit' => $limit]
        );
    }

    public function findPublishedAll(): array
    {
        return $this->db->fetchAll(
            "SELECT n.id, n.title, n.subtitle, n.content, n.image,
                    n.publication_date, n.created_at,
                    u.username AS author_name
             FROM news n
             JOIN news_status ns ON n.status_id = ns.id
             LEFT JOIN app_user u ON n.author_id = u.id
             WHERE ns.name = 'publicada'
             ORDER BY n.created_at DESC"
        );
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->db->fetchOne(
            "SELECT n.id, n.title, n.subtitle, n.content, n.image,
                    n.publication_date, n.created_at,
                    u.username AS author_name
             FROM news n
             JOIN news_status ns ON n.status_id = ns.id
             LEFT JOIN app_user u ON n.author_id = u.id
             WHERE ns.name = 'publicada' AND n.id = :id",
            ['id' => (int) $slug]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT n.id, n.title, n.subtitle, n.content, n.image,
                    n.publication_date, n.created_at, n.author_id,
                    u.username AS author_name
             FROM news n
             LEFT JOIN app_user u ON n.author_id = u.id
             WHERE n.id = :id",
            ['id' => $id]
        );
    }

    /**
     * Listar noticias para admin.
     * - admin/editor: todas
     * - redactor: solo las suyas
     */
    public function findAllForAdmin(?int $authorId = null, ?string $role = null): array
    {
        $sql = "SELECT n.id, n.title, n.subtitle, n.image, n.created_at, n.updated_at,
                       u.username AS author_name,
                       ns.name AS status_name
                FROM news n
                JOIN news_status ns ON n.status_id = ns.id
                LEFT JOIN app_user u ON n.author_id = u.id";

        $params = [];

        if ($role === 'redactor' && $authorId !== null) {
            $sql .= " WHERE n.author_id = :author_id";
            $params['author_id'] = $authorId;
        }

        $sql .= " ORDER BY n.created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    public function create(array $data): int
    {
        return $this->db->insert('news', $data);
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('news', $data, 'id = :id', ['id' => $id]);
    }

    public function delete(int $id): int
    {
        return $this->db->delete('news', 'id = :id', ['id' => $id]);
    }

    public function getStatusId(string $name): ?int
    {
        $row = $this->db->fetchOne(
            "SELECT id FROM news_status WHERE name = :name",
            ['name' => $name]
        );
        return $row ? (int) $row['id'] : null;
    }

    public function ensureStatuses(): void
    {
        $statuses = ['pendiente', 'publicada', 'archivada'];
        foreach ($statuses as $name) {
            $existing = $this->db->fetchOne(
                "SELECT id FROM news_status WHERE name = :name",
                ['name' => $name]
            );
            if (!$existing) {
                $this->db->insert('news_status', ['name' => $name]);
            }
        }
    }

    public function countByStatus(): array
    {
        $rows = $this->db->fetchAll(
            "SELECT ns.name, COUNT(n.id) AS total
             FROM news_status ns
             LEFT JOIN news n ON n.status_id = ns.id
             GROUP BY ns.name"
        );
        $result = [];
        foreach ($rows as $row) {
            $result[$row['name']] = (int) $row['total'];
        }
        return $result;
    }
}
