<?php

declare(strict_types=1);

namespace App\Api\Repositories;

use App\Core\Database;

class FooterRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findLinks(): array
    {
        return $this->db->fetchAll(
            "SELECT id, grupo, etiqueta, url, orden
             FROM enlaces_footer ORDER BY grupo, orden"
        );
    }

    public function findInfo(): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, email, phone, address, copyright_text,
                    social_facebook, social_linkedin, social_twitter,
                    social_instagram, social_youtube
             FROM footer_info ORDER BY id LIMIT 1"
        );
    }

    public function updateInfo(int $id, array $data): int
    {
        return $this->db->update('footer_info', $data, 'id = :id', ['id' => $id]);
    }

    public function findContenido(): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, clave, sobre_titulo, sobre_texto,
                    mision_titulo, mision_texto, vision_titulo, vision_texto,
                    objetivos_titulo, objetivos_texto, politicas_titulo, politicas_texto
             FROM contenido_sitio WHERE clave = 'home'"
        );
    }

    public function updateContenido(int $id, array $data): int
    {
        return $this->db->update('contenido_sitio', $data, 'id = :id', ['id' => $id]);
    }
}
