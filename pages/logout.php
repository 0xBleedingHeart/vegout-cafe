<?php
session_start();
session_destroy();
header('Location: /vegout-cafe/index.php');
exit;
