<?php
// Dependencias
require_once('inc/api_funcoes.php');
require_once('inc/config.php');
require_once('inc/funcoes.php');

$resultados = api_request('get_totals', 'GET')['data']['resultados'];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Consumidora :D</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="shortcut icon" href="../recursos/favicon/tabela-favicon.png" type="image/x-icon">
</head>

<body>

    <?php include('inc/nav.php') ?>

    <!-- Apresentação simples de total cadastrado -->
    <div class="container my-5">
        <div class="row">
            <div class="col-sm-6 text-center">
                <h3> Clientes: <?= $resultados[0]['Total'] ?> </h3>
            </div>
            <div class="col-sm-6 text-center">
                <h3>Produtos: <?= $resultados[1]['Total'] ?> </h3>
            </div>
        </div>
    </div>

    <script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>