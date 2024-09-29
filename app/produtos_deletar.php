<?php 
// Dependencias
require_once('inc/config.php');
require_once('inc/api_funcoes.php');
require_once('inc/funcoes.php');

// Tratamento de entrada
if(!isset($_GET['id'])){
    header('Location: produtos.php');
    exit;
}

// ------------------------------------------
// Lógica p/ deletar produto

if(isset($_GET['acao']) && $_GET['acao'] == 'sim')
{
    // chamada API-Endpoint remover soft
    api_request('soft_deletar_produto', 'GET', ['id' => $_GET['id']]);
    header('Location: produtos.php');
    exit;
}

// ------------------------------------------
// Lógica chamada API-Endpoint p/ apresentar produto deletado

$retorno = api_request('get_produto', 'GET', ['id' => $_GET['id']]);

if($retorno['data']['status'] == 'SUCESSO' && isset($retorno['data']['resultados'][0]))
{
    $produto = $retorno['data']['resultados'][0];

} else {
    $produto = [];
}

// ------------------------------------------
// Tratamento erro else
if(empty($produto)){
    header('Location: produtos.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Produto</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="shortcut icon" href="../recursos/favicon/tabela-favicon.png" type="image/x-icon">
</head>
<body>
    <?php include('inc/nav.php') ?>

    <section class="container">
        <div class="row">
            <div class="col p-5">

            <h5>Deseja eliminar o produto <strong><?= isset($produto['produto']) ? $produto['produto'] : 'N/A' ?></strong>?</h5>


            <div class="text-center mt-3">
                <a href="produtos.php" class="btn btn-secondary">Não</a>
                <a href="?acao=sim&id=<?= $produto['id_produto'] ?>" class="btn btn-primary">Sim</a>

            </div>

            </div>
        </div>
    </section>
    


    <script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>