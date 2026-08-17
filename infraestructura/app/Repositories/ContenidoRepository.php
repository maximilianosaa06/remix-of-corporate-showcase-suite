<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;

class ContenidoRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByClave(string $clave): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, clave, sobre_titulo, sobre_texto, mision_titulo, mision_texto,
                    vision_titulo, vision_texto, objetivos_titulo, objetivos_texto,
                    politicas_titulo, politicas_texto
             FROM contenido_sitio
             WHERE clave = :clave",
            ['clave' => $clave]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT id, clave, sobre_titulo, sobre_texto, mision_titulo, mision_texto,
                    vision_titulo, vision_texto, objetivos_titulo, objetivos_texto,
                    politicas_titulo, politicas_texto
             FROM contenido_sitio
             WHERE id = :id",
            ['id' => $id]
        );
    }

    public function findHome(): ?array
    {
        return $this->findByClave('home');
    }

    public function update(int $id, array $data): int
    {
        return $this->db->update('contenido_sitio', $data, 'id = :id', ['id' => $id]);
    }
}
