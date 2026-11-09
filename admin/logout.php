<?php
require_once dirname(__DIR__) . '/auth.php';
logoutUser();
header('Location: login.php');
exit;
?>
