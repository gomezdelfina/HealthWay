<?php
    require_once(__DIR__ . '/includes/globals.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        $module = 'auth';
        require_once($dirBaseFile . '/includes/html/head.php');
    ?>
</head>
<body>
    <?php
        require_once($dirBaseFile . '/auth/login.php');
    ?>
    <?php
        require_once($dirBaseFile . '/auth/recoveryLogin.php');
    ?>
</body>
</html>