<?php
require_once __DIR__ . '/../includes/auth.php';

fazerLogout();
header('Location: ' . SITE_URL . '/login.php');
exit;
