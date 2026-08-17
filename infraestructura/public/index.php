<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Core\Router;
use App\Core\Auth;
use App\Core\Middleware;

// Ensure responses are served as UTF-8
header('Content-Type: text/html; charset=utf-8');

Auth::start();

$router = new Router();

// --- Rutas públicas ---
$router->get('/', ['App\Controllers\HomeController', 'index']);
$router->get('/proyectos', ['App\Controllers\ProyectosController', 'index']);
$router->get('/staff', ['App\Controllers\StaffController', 'index']);
$router->get('/noticias', ['App\Controllers\NoticiasController', 'index']);
$router->get('/contacto', ['App\Controllers\ContactoController', 'index']);
$router->post('/contacto', ['App\Controllers\ContactoController', 'store'], ['csrf']);

// --- Auth ---
$router->get('/login', ['App\Controllers\AuthController', 'loginForm'], ['guest']);
$router->post('/login', ['App\Controllers\AuthController', 'login'], ['csrf', 'guest']);
$router->get('/logout', ['App\Controllers\AuthController', 'logout']);

// --- Cambio de contraseña obligatorio ---
$router->get('/cambiar-password', ['App\Controllers\CambiarPasswordController', 'showForm'], ['auth', 'force_password_change']);
$router->post('/cambiar-password', ['App\Controllers\CambiarPasswordController', 'changePassword'], ['auth', 'csrf', 'force_password_change']);

// --- Admin Dashboard ---
$router->get('/admin', ['App\Controllers\Admin\DashboardController', 'index'], ['auth']);

// --- Admin Proyectos (solo admin) ---
$router->get('/admin/proyectos',       ['App\Controllers\Admin\ProyectosController', 'index'],        ['auth']);
$router->get('/admin/proyectos/crear', ['App\Controllers\Admin\ProyectosController', 'create'],       ['auth']);
$router->post('/admin/proyectos/crear',     ['App\Controllers\Admin\ProyectosController', 'store'],   ['auth', 'csrf']);
$router->get('/admin/proyectos/editar',     ['App\Controllers\Admin\ProyectosController', 'edit'],    ['auth']);
$router->post('/admin/proyectos/actualizar',['App\Controllers\Admin\ProyectosController', 'update'],  ['auth', 'csrf']);
$router->post('/admin/proyectos/eliminar',  ['App\Controllers\Admin\ProyectosController', 'delete'],  ['auth', 'csrf']);
$router->post('/admin/proyectos/toggle',    ['App\Controllers\Admin\ProyectosController', 'toggleActive'], ['auth', 'csrf']);

// --- Admin Staff (solo admin) ---
$router->get('/admin/staff',       ['App\Controllers\Admin\StaffController', 'index'],        ['auth']);
$router->get('/admin/staff/crear', ['App\Controllers\Admin\StaffController', 'create'],       ['auth']);
$router->post('/admin/staff/crear',     ['App\Controllers\Admin\StaffController', 'store'],   ['auth', 'csrf']);
$router->get('/admin/staff/editar',     ['App\Controllers\Admin\StaffController', 'edit'],    ['auth']);
$router->post('/admin/staff/actualizar',['App\Controllers\Admin\StaffController', 'update'],  ['auth', 'csrf']);
$router->post('/admin/staff/eliminar',  ['App\Controllers\Admin\StaffController', 'delete'],  ['auth', 'csrf']);

// --- Admin Noticias (admin, editor, redactor) ---
$router->get('/admin/noticias',       ['App\Controllers\Admin\NoticiasController', 'index'],        ['auth']);
$router->get('/admin/noticias/crear', ['App\Controllers\Admin\NoticiasController', 'create'],       ['auth']);
$router->post('/admin/noticias/crear',     ['App\Controllers\Admin\NoticiasController', 'store'],   ['auth', 'csrf']);
$router->get('/admin/noticias/editar',     ['App\Controllers\Admin\NoticiasController', 'edit'],    ['auth']);
$router->post('/admin/noticias/actualizar',['App\Controllers\Admin\NoticiasController', 'update'],  ['auth', 'csrf']);
$router->post('/admin/noticias/eliminar',  ['App\Controllers\Admin\NoticiasController', 'delete'],  ['auth', 'csrf']);
$router->post('/admin/noticias/cambiar-estado', ['App\Controllers\Admin\NoticiasController', 'changeStatus'], ['auth', 'csrf']);

// --- Admin Usuarios (solo admin) ---
$router->get('/admin/usuarios',       ['App\Controllers\Admin\UsuariosController', 'index'],        ['auth']);
$router->get('/admin/usuarios/crear', ['App\Controllers\Admin\UsuariosController', 'create'],       ['auth']);
$router->post('/admin/usuarios/crear',     ['App\Controllers\Admin\UsuariosController', 'store'],   ['auth', 'csrf']);
$router->get('/admin/usuarios/editar',     ['App\Controllers\Admin\UsuariosController', 'edit'],    ['auth']);
$router->post('/admin/usuarios/actualizar',['App\Controllers\Admin\UsuariosController', 'update'],  ['auth', 'csrf']);
$router->post('/admin/usuarios/eliminar',  ['App\Controllers\Admin\UsuariosController', 'delete'],  ['auth', 'csrf']);
$router->post('/admin/usuarios/toggle',    ['App\Controllers\Admin\UsuariosController', 'toggleActive'], ['auth', 'csrf']);

// --- Admin Contenido Institucional (solo admin) ---
$router->get('/admin/contenido', ['App\Controllers\Admin\ContenidoController', 'index'],  ['auth']);
$router->post('/admin/contenido/actualizar', ['App\Controllers\Admin\ContenidoController', 'update'], ['auth', 'csrf']);

// --- Admin Footer (solo admin) ---
$router->get('/admin/footer', ['App\Controllers\Admin\FooterController', 'index'],  ['auth']);
$router->post('/admin/footer/crear',     ['App\Controllers\Admin\FooterController', 'storeLink'],    ['auth', 'csrf']);
$router->post('/admin/footer/eliminar',  ['App\Controllers\Admin\FooterController', 'deleteLink'],   ['auth', 'csrf']);
$router->post('/admin/footer/info',      ['App\Controllers\Admin\FooterController', 'updateInfo'],   ['auth', 'csrf']);

// --- Admin Contacto (admin, editor) ---
$router->get('/admin/contacto',          ['App\Controllers\Admin\AdminContactoController', 'index'],        ['auth']);
$router->get('/admin/contacto/ver',      ['App\Controllers\Admin\AdminContactoController', 'view'],         ['auth']);
$router->post('/admin/contacto/estado',  ['App\Controllers\Admin\AdminContactoController', 'updateStatus'], ['auth', 'csrf']);
$router->post('/admin/contacto/eliminar',['App\Controllers\Admin\AdminContactoController', 'delete'],       ['auth', 'csrf']);

$router->dispatch();
