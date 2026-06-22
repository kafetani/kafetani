<?php
/**
 * auth/register.php — Router tipis (MVC)
 * View ada di: app/views/auth/register.php
 */
session_start();

require_once '../app/config/Database.php';
require_once '../app/models/User.php';
require_once '../app/controllers/RegisterController.php';

$controller = new RegisterController();
$controller->showForm();
