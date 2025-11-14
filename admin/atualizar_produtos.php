<?php
    require_once("config.inc.php");

    $id = $_GET['id'];

    $query = "SELECT * FROM produtos WHERE id = $id";
    $resultado = mysqli_query($conexao, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Produto</title>
</head>
<body>
<h1>Editar Produto</h1>
<form method="post">
    Produto: <br>
    <input type="text" name="produto" required><br><br>
    Descrição: <br>
    <textarea name="descricao"></textarea><br><br>
    Preço: <br>
    <input type="number" step="0.01" name="preco" required><br><br>
    Estoque: <br>
    <input type="number" name="estoque" required><br><br>
    Desconto (%): <br>
    <input type="number" step="0.01" name="desconto" value="0"><br><br>
    
    <button type="submit">Salvar</button>
</form>
</body>
</html>