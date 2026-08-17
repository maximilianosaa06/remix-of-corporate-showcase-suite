<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Auth;

class ContactoController
{
    public function index(): void
    {
        $pageTitle       = 'Contacto — TECH HUB ULS';
        $pageDescription = 'Formulario de contacto de TECH HUB ULS.';
        $errors = [];
        $success = false;
        $old = [];

        require dirname(__DIR__) . '/Views/layouts/header.php';
        require dirname(__DIR__) . '/Views/pages/contacto.php';
        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }

    public function store(): void
    {
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $privacyConsent = isset($_POST['privacy_consent']);

        $errors = [];
        if ($name === '')    $errors['name']    = 'El nombre es obligatorio.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Ingrese un correo válido.';
        if ($message === '') $errors['message'] = 'El mensaje es obligatorio.';
        if ($phone !== '' && !preg_match('/^[\d\s\+\-\(\)]{7,30}$/', $phone)) $errors['phone'] = 'Teléfono inválido.';
        if (!$privacyConsent) $errors['privacy'] = 'Debe aceptar el aviso de privacidad para continuar.';

        if (!empty($errors)) {
            $pageTitle       = 'Contacto — TECH HUB ULS';
            $pageDescription = 'Formulario de contacto de TECH HUB ULS.';
            $success = false;
            $old = $_POST;
            $old['privacy_consent'] = $privacyConsent;
            require dirname(__DIR__) . '/Views/layouts/header.php';
            require dirname(__DIR__) . '/Views/pages/contacto.php';
            require dirname(__DIR__) . '/Views/layouts/footer.php';
            return;
        }

        $db = Database::getInstance();
        $db->insert('contact_request', [
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'subject' => $subject,
            'message' => $message,
            'status'  => 'pendiente',
        ]);

        $pageTitle       = 'Contacto — TECH HUB ULS';
        $pageDescription = 'Formulario de contacto de TECH HUB ULS.';
        $errors = [];
        $success = true;
        $old = [];
        require dirname(__DIR__) . '/Views/layouts/header.php';
        require dirname(__DIR__) . '/Views/pages/contacto.php';
        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }
}
