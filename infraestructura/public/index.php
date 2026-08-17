<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Core\Router;
use App\Core\Auth;
use App\Core\Middleware;

Auth::start();

$router = new Router();

// ══════════════════════════════════════════════
//  API REST — /api/*
// ══════════════════════════════════════════════

// Auth
$router->post('/api/auth/login',  ['App\Api\Controllers\AuthController', 'login'],  ['guest']);
$router->post('/api/auth/logout', ['App\Api\Controllers\AuthController', 'logout'], ['auth']);
$router->get('/api/auth/me',      ['App\Api\Controllers\AuthController', 'me'],     ['auth']);

// News
$router->get('/api/news',                  ['App\Api\Controllers\NewsController', 'index']);
$router->get('/api/news/{id}',             ['App\Api\Controllers\NewsController', 'show']);
$router->post('/api/news',                 ['App\Api\Controllers\NewsController', 'store'],         ['auth']);
$router->put('/api/news/{id}',             ['App\Api\Controllers\NewsController', 'update'],        ['auth']);
$router->patch('/api/news/{id}/status',    ['App\Api\Controllers\NewsController', 'updateStatus'],  ['auth']);

// Projects
$router->get('/api/projects',                  ['App\Api\Controllers\ProjectController', 'index']);
$router->get('/api/projects/{id}',             ['App\Api\Controllers\ProjectController', 'show']);
$router->post('/api/projects',                 ['App\Api\Controllers\ProjectController', 'store'],         ['auth']);
$router->put('/api/projects/{id}',             ['App\Api\Controllers\ProjectController', 'update'],        ['auth']);
$router->patch('/api/projects/{id}/status',    ['App\Api\Controllers\ProjectController', 'updateStatus'],  ['auth']);

// Services
$router->get('/api/services',                  ['App\Api\Controllers\ServiceController', 'index']);
$router->get('/api/services/{id}',             ['App\Api\Controllers\ServiceController', 'show']);
$router->post('/api/services',                 ['App\Api\Controllers\ServiceController', 'store'],         ['auth']);
$router->put('/api/services/{id}',             ['App\Api\Controllers\ServiceController', 'update'],        ['auth']);
$router->patch('/api/services/{id}/status',    ['App\Api\Controllers\ServiceController', 'updateStatus'],  ['auth']);

// Staff
$router->get('/api/staff',              ['App\Api\Controllers\StaffController', 'index']);
$router->get('/api/staff/{id}',         ['App\Api\Controllers\StaffController', 'show']);
$router->post('/api/staff',             ['App\Api\Controllers\StaffController', 'store'],   ['auth']);
$router->put('/api/staff/{id}',         ['App\Api\Controllers\StaffController', 'update'],  ['auth']);
$router->delete('/api/staff/{id}',      ['App\Api\Controllers\StaffController', 'destroy'],['auth']);

// Queries (Contacto)
$router->post('/api/queries',                   ['App\Api\Controllers\QueryController', 'store']);
$router->get('/api/queries',                    ['App\Api\Controllers\QueryController', 'index'],         ['auth']);
$router->get('/api/queries/{id}',               ['App\Api\Controllers\QueryController', 'show'],          ['auth']);
$router->patch('/api/queries/{id}/status',      ['App\Api\Controllers\QueryController', 'updateStatus'],  ['auth']);

// Users
$router->get('/api/users',              ['App\Api\Controllers\UserController', 'index'],   ['auth']);
$router->get('/api/users/{id}',         ['App\Api\Controllers\UserController', 'show'],    ['auth']);
$router->post('/api/users',             ['App\Api\Controllers\UserController', 'store'],   ['auth']);
$router->put('/api/users/{id}',         ['App\Api\Controllers\UserController', 'update'],  ['auth']);
$router->delete('/api/users/{id}',      ['App\Api\Controllers\UserController', 'destroy'],['auth']);

// Roles
$router->get('/api/roles', ['App\Api\Controllers\UserController', 'roles'], ['auth']);

// Tags
$router->get('/api/tags',          ['App\Api\Controllers\TagController', 'index']);
$router->post('/api/tags',         ['App\Api\Controllers\TagController', 'store'],   ['auth']);
$router->delete('/api/tags/{id}',  ['App\Api\Controllers\TagController', 'destroy'],['auth']);

// Audits
$router->get('/api/audits', ['App\Api\Controllers\AuditController', 'index'], ['auth']);

// Footer
$router->get('/api/footer',   ['App\Api\Controllers\FooterApiController', 'show']);
$router->put('/api/footer',   ['App\Api\Controllers\FooterApiController', 'update'], ['auth']);

// ══════════════════════════════════════════════
//  WEB — Rutas públicas
// ══════════════════════════════════════════════

$router->get('/', ['App\Controllers\HomeController', 'index']);
$router->get('/proyectos', ['App\Controllers\ProyectosController', 'index']);
$router->get('/staff', ['App\Controllers\StaffController', 'index']);
$router->get('/noticias', ['App\Controllers\NoticiasController', 'index']);
$router->get('/contacto', ['App\Controllers\ContactoController', 'index']);
$router->post('/contacto', ['App\Controllers\ContactoController', 'store'], ['csrf']);

// Auth
$router->get('/login', ['App\Controllers\AuthController', 'loginForm'], ['guest']);
$router->post('/login', ['App\Controllers\AuthController', 'login'], ['csrf', 'guest']);
$router->get('/logout', ['App\Controllers\AuthController', 'logout']);

// Cambio de contraseña obligatorio
$router->get('/cambiar-password', ['App\Controllers\CambiarPasswordController', 'showForm'], ['auth', 'force_password_change']);
$router->post('/cambiar-password', ['App\Controllers\CambiarPasswordController', 'changePassword'], ['auth', 'csrf', 'force_password_change']);

// ══════════════════════════════════════════════
//  WEB — Admin
// ══════════════════════════════════════════════

$router->get('/admin', ['App\Controllers\Admin\DashboardController', 'index'], ['auth']);

// Proyectos
$router->get('/admin/proyectos',       ['App\Controllers\Admin\ProyectosController', 'index'],        ['auth']);
$router->get('/admin/proyectos/crear', ['App\Controllers\Admin\ProyectosController', 'create'],       ['auth']);
$router->post('/admin/proyectos/crear',     ['App\Controllers\Admin\ProyectosController', 'store'],   ['auth', 'csrf']);
$router->get('/admin/proyectos/editar',     ['App\Controllers\Admin\ProyectosController', 'edit'],    ['auth']);
$router->post('/admin/proyectos/actualizar',['App\Controllers\Admin\ProyectosController', 'update'],  ['auth', 'csrf']);
$router->post('/admin/proyectos/eliminar',  ['App\Controllers\Admin\ProyectosController', 'delete'],  ['auth', 'csrf']);
$router->post('/admin/proyectos/toggle',    ['App\Controllers\Admin\ProyectosController', 'toggleActive'], ['auth', 'csrf']);

// Staff
$router->get('/admin/staff',       ['App\Controllers\Admin\StaffController', 'index'],        ['auth']);
$router->get('/admin/staff/crear', ['App\Controllers\Admin\StaffController', 'create'],       ['auth']);
$router->post('/admin/staff/crear',     ['App\Controllers\Admin\StaffController', 'store'],   ['auth', 'csrf']);
$router->get('/admin/staff/editar',     ['App\Controllers\Admin\StaffController', 'edit'],    ['auth']);
$router->post('/admin/staff/actualizar',['App\Controllers\Admin\StaffController', 'update'],  ['auth', 'csrf']);
$router->post('/admin/staff/eliminar',  ['App\Controllers\Admin\StaffController', 'delete'],  ['auth', 'csrf']);

// Noticias
$router->get('/admin/noticias',       ['App\Controllers\Admin\NoticiasController', 'index'],        ['auth']);
$router->get('/admin/noticias/crear', ['App\Controllers\Admin\NoticiasController', 'create'],       ['auth']);
$router->post('/admin/noticias/crear',     ['App\Controllers\Admin\NoticiasController', 'store'],   ['auth', 'csrf']);
$router->get('/admin/noticias/editar',     ['App\Controllers\Admin\NoticiasController', 'edit'],    ['auth']);
$router->post('/admin/noticias/actualizar',['App\Controllers\Admin\NoticiasController', 'update'],  ['auth', 'csrf']);
$router->post('/admin/noticias/eliminar',  ['App\Controllers\Admin\NoticiasController', 'delete'],  ['auth', 'csrf']);
$router->post('/admin/noticias/cambiar-estado', ['App\Controllers\Admin\NoticiasController', 'changeStatus'], ['auth', 'csrf']);

// Usuarios
$router->get('/admin/usuarios',       ['App\Controllers\Admin\UsuariosController', 'index'],        ['auth']);
$router->get('/admin/usuarios/crear', ['App\Controllers\Admin\UsuariosController', 'create'],       ['auth']);
$router->post('/admin/usuarios/crear',     ['App\Controllers\Admin\UsuariosController', 'store'],   ['auth', 'csrf']);
$router->get('/admin/usuarios/editar',     ['App\Controllers\Admin\UsuariosController', 'edit'],    ['auth']);
$router->post('/admin/usuarios/actualizar',['App\Controllers\Admin\UsuariosController', 'update'],  ['auth', 'csrf']);
$router->post('/admin/usuarios/eliminar',  ['App\Controllers\Admin\UsuariosController', 'delete'],  ['auth', 'csrf']);
$router->post('/admin/usuarios/toggle',    ['App\Controllers\Admin\UsuariosController', 'toggleActive'], ['auth', 'csrf']);

// Contenido
$router->get('/admin/contenido', ['App\Controllers\Admin\ContenidoController', 'index'],  ['auth']);
$router->post('/admin/contenido/actualizar', ['App\Controllers\Admin\ContenidoController', 'update'], ['auth', 'csrf']);

// Footer
$router->get('/admin/footer', ['App\Controllers\Admin\FooterController', 'index'],  ['auth']);
$router->post('/admin/footer/crear',     ['App\Controllers\Admin\FooterController', 'storeLink'],    ['auth', 'csrf']);
$router->post('/admin/footer/eliminar',  ['App\Controllers\Admin\FooterController', 'deleteLink'],   ['auth', 'csrf']);
$router->post('/admin/footer/info',      ['App\Controllers\Admin\FooterController', 'updateInfo'],   ['auth', 'csrf']);

// Contacto
$router->get('/admin/contacto',          ['App\Controllers\Admin\AdminContactoController', 'index'],        ['auth']);
$router->get('/admin/contacto/ver',      ['App\Controllers\Admin\AdminContactoController', 'view'],         ['auth']);
$router->post('/admin/contacto/estado',  ['App\Controllers\Admin\AdminContactoController', 'updateStatus'], ['auth', 'csrf']);
$router->post('/admin/contacto/eliminar',['App\Controllers\Admin\AdminContactoController', 'delete'],       ['auth', 'csrf']);

$router->dispatch();
