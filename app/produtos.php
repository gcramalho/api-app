<?php
// Dependências
require_once('inc/api_funcoes.php');
require_once('inc/config.php');

// Lógica de apresentação (var com o endpoint)
$retorno = api_request('get_produtos_ativos', 'GET');

// Tratamento de erro
if ($retorno['data']['status'] == 'SUCESSO') {
    $produtos = $retorno['data']['resultados'];
} else {
    $produtos = [];
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos Dados</title>
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
                        <h1>Produtos</h1>
                    </div>

                    <div class="col text-end align-self-center">
                        <a href="produtos_novos.php" class="btn btn-primary btn-sm">Add Produto...</a>
                    </div>
                </div>

                <!-- Lógica para se não houver produtos cadastrados -->
                <?php if (count($produtos) == 0) : ?>
                    <p>Nenhum registro de produtos.</p>

                <?php else : ?>
                    
                    <!-- Apresenta produtos cadastros -->
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th width="50%">Tipo</th>
                                <th width="50%">Quantidade</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($produtos as $produto) : ?>
                                <tr>
                                
                                    <td>
                                        <a href="produtos_editar.php?id=<?= $produto['id_produto'] ?>"> &#x270E; </a>
                                        <?= $produto['produto'] ?>
                                    </td>
                                    
                                    <td> <?= $produto['quantidade'] ?> </td>

                                    <td>
                                        <a href="produtos_deletar.php?id=<?= $produto['id_produto'] ?>">Deletar</a>
                                    </td>
                                </tr>
                                
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <p>Total de Produtos: <strong> <?= count($produtos) ?> </strong></p>

                <?php endif; ?>

            </div>
        </div>
    </section>


    <script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>