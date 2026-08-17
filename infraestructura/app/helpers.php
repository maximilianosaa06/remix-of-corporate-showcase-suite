<?php

declare(strict_types=1);

function mediaUrl(?string $path, string $fallbackType = ''): string
{
    $fallbacks = [
        'proyecto' => '/assets/images/proyecto-1.jpg',
        'staff'    => '/assets/images/staff-1.jpg',
        'noticia'  => '/assets/images/noticia-1.jpg',
    ];

    if ($path === null || $path === '') {
        return $fallbacks[$fallbackType] ?? '/assets/images/proyecto-1.jpg';
    }

    if (str_starts_with($path, 'http') || str_starts_with($path, '/')) {
        return $path;
    }

    return '/storage/uploads/' . $path;
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
