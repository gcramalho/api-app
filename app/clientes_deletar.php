<?php 
// Dependencias
require_once('inc/config.php');
require_once('inc/api_funcoes.php');
require_once('inc/funcoes.php');


// Tratamento de entrada
if(!isset($_GET['id'])){
    header('Location: clientes.php');
    exit;
}

// ------------------------------------------
// Lógica para deletar cliente

if(isset($_GET['acao']) && $_GET['acao'] == 'sim')
{
    // chamada API-endpoint remover soft 
    api_request('soft_deletar_cliente', 'GET', ['id' => $_GET['id']]);
    header('Location: clientes.php');
    exit;
       
}


// ------------------------------------------

// Lógica para apresentação dos dados

$retorno = api_request('get_cliente', 'GET', ['id' => $_GET['id']]);

if($retorno['data']['status'] == 'SUCESSO' && isset($retorno['data']['resultados'][0]))
{
    $cliente = $retorno['data']['resultados'][0];
} else {
    $cliente = [];
}

// ------------------------------------------

// Tratamento erro (sem cliente)
if(empty($cliente)){
    header('Location: clientes.php');
    exit;
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Cliente</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="shortcut icon" href="../recursos/favicon/tabela-favicon.png" type="image/x-icon">
</head>
<body>

    <?php include('inc/nav.php')?>

    <section class="container">
        <div class="row">
            <div class="col p-5">

            <h5>
                <p>Deseja eliminar o cliente <strong><?= isset($cliente['nome']) ? $cliente['nome'] : 'N/A' ?></strong>?</p>
            </h5>

            <div class="text-center mt-3">
                <a href="clientes.php"class="btn btn-secondary">Não</a>
                <a href="?acao=sim&id=<?= $cliente['id_cliente'] ?>"class="btn btn-primary">Sim</a>
            </div>

            </div>
        </div>
    </section>
    


    <script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>