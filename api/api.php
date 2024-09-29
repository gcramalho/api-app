<?php 
// Dependencias
require_once(dirname(__FILE__) . '/inc/config.php');
require_once(dirname(__FILE__) . '/inc/api_resposta.php');
require_once(dirname(__FILE__) . '/inc/api_logica.php');
require_once(dirname(__FILE__) . '/inc/EasyPDO.php');

// Instancia
$api_resposta = new api_resposta();

// Valida método
if (!$api_resposta->checar_metodo($_SERVER['REQUEST_METHOD']))
{
    $api_resposta->api_request_erro('Request method invalido');
}

// Setar request-method
$api_resposta->set_metodo($_SERVER['REQUEST_METHOD']);
$params = null;

if ($api_resposta->get_metodo() == 'GET') {
    $api_resposta->set_endpoint($_GET['endpoint']);
    $params = $_GET;
} elseif ($api_resposta->get_metodo() == 'POST'){
    $api_resposta->set_endpoint($_POST['endpoint']);
    $params = $_POST;
}

// --------------------------------------------------------
// Prepara logica api

$api_logica = new api_logica($api_resposta->get_endpoint(), $params);

// --------------------------------------------------------
// Checa existencia endpoint

if(!$api_logica->endpoint_existe()){
    $api_resposta->api_request_erro('Endpoint inexistente: ' . $api_resposta->get_endpoint());
}


// Request p/ a api_logica

$resultado = $api_logica->{$api_resposta->get_endpoint()}();


$api_resposta->add_ao_data('data', $resultado);

$api_resposta->envia_resposta();
