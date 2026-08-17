<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

class FooterRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(): array
    {
        return $this->db->fetchAll(
            "SELECT id, grupo, etiqueta, url, orden
             FROM enlaces_footer
             ORDER BY grupo, orden"
        );
    }

    public function findFooterInfo(): ?array
    {
        $info = $this->db->fetchOne(
            "SELECT id, email, phone, address,
                    copyright_text, social_facebook, social_linkedin,
                    social_twitter, social_instagram, social_youtube
             FROM footer_info
             ORDER BY id
             LIMIT 1"
        );

        if (!$info) {
            $id = $this->db->insert('footer_info', [
                'email'   => 'techhub@ulser.cl',
                'phone'   => '',
                'address' => 'Universidad de La Serena',
            ]);
            $info = $this->db->fetchOne(
                "SELECT id, email, phone, address,
                        copyright_text, social_facebook, social_linkedin,
                        social_twitter, social_instagram, social_youtube
                 FROM footer_info
                 WHERE id = :id",
                ['id' => $id]
            );
        }

        return $info;
    }

    public function createLink(array $data): int
    {
        return $this->db->insert('enlaces_footer', $data);
    }

    public function deleteLink(int $id): int
    {
        return $this->db->delete('enlaces_footer', 'id = :id', ['id' => $id]);
    }

    public function updateFooterInfo(int $id, array $data): int
    {
        return $this->db->update('footer_info', $data, 'id = :id', ['id' => $id]);
    }
}
