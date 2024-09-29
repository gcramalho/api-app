<?php
// Dependencias
require_once('inc/api_funcoes.php');
require_once('inc/config.php');
require_once('inc/funcoes.php');

// Tratamento de mensagem
$error_mensagem = '';
$successo_mensagem = '';



// Lógica/tratamento informação vinda do GET

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
    if(!isset($_GET['id'])) {
        die('ID não passado');
    }

    $cliente = api_request('get_cliente', 'POST', ['id' => $_GET['id']]) ['data']['resultados'][0];
}



// Lógica/tratamento ao clicar em 'atualizar'

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Chamada api-endpoint 
    $resultados = api_request('editar_cliente', 'POST', [
        'id' => $_POST['id'],
        'nome' => $_POST['nome'],
        'telefone' => $_POST['telefone'],
        'email' => $_POST['email']
    ]);
    

    // Lógica de mensagem da operação 
    if (isset($resultados['data']['status'])) {

        if ($resultados['data']['status'] == 'ERROR') {
            $error_mensagem = $resultados['data']['mensagem'];
        } elseif ($resultados['data']['status'] == 'SUCESSO') {
            $successo_mensagem = $resultados['data']['mensagem'];
        }
    }


    // Recarregando info
    $cliente = api_request('get_cliente', 'POST', ['id' => $_POST['id']]) ['data']['resultados'][0];
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar cliente</title>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="shortcut icon" href="../recursos/favicon/tabela-favicon.png" type="image/x-icon">
</head>

<body>

    <?php include('inc/nav.php') ?>

    <section class="container">
        <div class="row my-5">
            <div class="col-sm-6 offset-sm-3 card bg-light p-4">

                <form action="clientes_editar.php" method="POST">

                    <input type="hidden" name="id" value="<?=$cliente['id_cliente']?>">

                    <div class="mb-3">

                        <label for="nome">
                            Atualizar nome do cliente:
                            (Atual:<strong> <?=$cliente['nome']?></strong>)
                        </label>


                        <input type="text" name="nome" class="form-control" autocomplete="off" required>

                    </div>


                    <div class="mb-3">

                        <label for="telefone">Atualizar telefone:</label>
                        
                        <input type="text" name="telefone" class="form-control" value="<?=$cliente['telefone']?>">

                    </div>

                    <!-- botões -->
                    <div class="mb-3">

                        <label for="email">
                            Atualizar e-mail:
                        </label>

                        <input type="text" name="email" class="form-control" value="<?=$cliente['email']?>">

                    </div>

                    <div class="mb-3 text-center">
                        <input type="submit" class="btn btn-primary btn-sm" value="Atualizar">

                        <a href="clientes.php" class="btn btn-secondary btn-sm">Cancelar</a>
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