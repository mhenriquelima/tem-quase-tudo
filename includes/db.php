<?php
$dbError = true;
$conexao = @mysqli_connect("localhost", "root", "");
if ($conexao) {
    if (@mysqli_select_db($conexao, "tem_quase_tudo_db")) {
        mysqli_set_charset($conexao, 'utf8mb4');
        $dbError = false;
    } else {
    }
} else {
}

?>
