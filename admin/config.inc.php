<?php
    $conexao = mysqli_connect("localhost", "root", "");
    $db = mysqli_select_db($conexao, "tem_quase_tudo_db");

    if (!defined('ADMIN_EMAIL')) {
        define('ADMIN_EMAIL', 'admin@temquasetudo.com');
    }