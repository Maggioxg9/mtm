<?php

session_start();
unset($_SESSION['adminmode']);
unset($_SESSION['badlogin']);

session_destroy();
header("Location: ../../index.html");
exit();
?>