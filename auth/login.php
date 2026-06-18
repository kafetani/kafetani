<?php
/**
 * auth/login.php — Router tipis (MVC)
 * View ada di: app/views/auth/login.php
 */
session_start();

require_once '../app/config/Database.php';
require_once '../app/models/User.php';
require_once '../app/controllers/LoginController.php';

$controller = new LoginController();
$controller->showForm();
