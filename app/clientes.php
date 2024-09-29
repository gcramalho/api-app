<?php
// Dependencias
require_once('inc/api_funcoes.php');
require_once('inc/config.php');
require_once('inc/funcoes.php');

// Lógica de apresentação
$retorno = api_request('get_clientes_ativos', 'GET');

// Tratamento de erro
if ($retorno['data']['status'] == 'SUCESSO') {
    $clientes = $retorno['data']['resultados'];
} else {
    $clientes = [];
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes Dados</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="shortcut icon" href="../recursos/favicon/tabela-favicon.png" type="image/x-icon">
</head>

<body>

    <?php include('inc/nav.php') ?>

    <section class="container">
        <div class="row">
            <div class="col">

                <div class="row">
                    <div class="col">
                        <h1>Clientes</h1>
                    </div>

                    <div class="col text-end align-self-center">
                        <a href="clientes_novos.php" class="btn btn-primary btn-sm">Add Cliente...</a>
                    </div>
                </div>

                <!-- tratamento de erro caso var-clientes esteja vazia -->
                <?php if (count($clientes) == 0) : ?>
                    <p class="text-center">Sem registro de clientes.</p>

                <?php else : ?>

                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Tel.:</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($clientes as $cliente) : ?>

                                <tr>
                                    <td>
                                        <a href="clientes_editar.php?id=<?=$cliente['id_cliente']?>"> &#x270E; </a> 
                                        <?= $cliente['nome'] ?>
                                    </td>

                                    <td> <?= $cliente['email'] ?> </td>
                                    <td> <?= $cliente['telefone'] ?> </td>
                                    
                                    <td>
                                        <a href="clientes_deletar.php?id=<?= $cliente['id_cliente'] ?>"> Deletar </a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>
                    </table>

                    <p class="text-end">Total de Clientes: <strong> <?= count($clientes)?> </strong> </p>

                <?php endif; ?>

            </div>
        </div>
    </section>

    <script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>