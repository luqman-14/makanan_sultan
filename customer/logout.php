<?php
require_once dirname(__DIR__) . '/auth.php';
logoutUser();
header('Location: index.php');
exit;
?>
