<?php
//dependencias
require_once('inc/api_funcoes.php');
require_once('inc/config.php');
require_once('inc/funcoes.php');

// tratamento de mensagem
$error_mensagem = '';
$successo_mensagem = '';

//lógica
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $resultados = api_request('create_novo_produto', 'POST', [
        'produto' => $_POST['produto'],
        'quantidade' => $_POST['quantidade']
    ]);


    // apresenta resultado da operação na API 

    if (isset($resultados['data']['status'])) {

        if ($resultados['data']['status'] == 'ERROR') {
            $error_mensagem = $resultados['data']['mensagem'];
        } elseif ($resultados['data']['status'] == 'SUCESSO') {
            $successo_mensagem = $resultados['data']['mensagem'];
        }
    }
}


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar novo produto</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="shortcut icon" href="../recursos/favicon/tabela-favicon.png" type="image/x-icon">
</head>

<body>

    <?php include('inc/nav.php') ?>

    <section class="container">
        <div class="row my-5">
            <div class="col-sm-6 offset-sm-3 card bg-light p-4">

                <form action="produtos_novos.php" method="POST">

                    <div class="mb-3">

                        <label for="nome">Novo Produto:</label>
                        <input type="text" name="produto" class="form-control" required autocomplete="off">

                    </div>


                    <div class="mb-3">

                        <label for="telefone">Quantidade:</label>
                        <input type="number" name="quantidade" class="form-control" required>

                    </div>

                    <!-- Botões -->
                    <div class="mb-3 text-center">
                        <input type="submit" class="btn btn-primary btn-sm" value="Salvar">
                        <a href="produtos.php" class="btn btn-secondary btn-sm">Cancelar</a>
                    </div>

                    
                    <!-- Bloco apresentação de mensagem -->
                    <?php if($error_mensagem) : ?>
                        <div class="alert alert-danger p-2 text-center">
                            <?= $error_mensagem ?>
                        </div>

                    <?php elseif($successo_mensagem) : ?>
                        <div class="alert alert-success p-2 text-center">
                            <?= $successo_mensagem ?>
                        </div>

                    <?php endif;?>


                </form>

            </div>
        </div>


    </section>


    <script src="assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>