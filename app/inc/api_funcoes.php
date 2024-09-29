<?php 

function api_request($endpoint, $metodo = 'GET', $variaveis = []){

    // Inicia sessão curl
    $cliente = curl_init();

    // Define retorno-resultado como string
    curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);

    // Seta var com a url da API
    $url = API_BASE_URL;

    // Se request for GET
    if($metodo == 'GET'){
        $url .= "?endpoint=$endpoint";
        if(!empty($variaveis)){
            $url .= "&" . http_build_query($variaveis);
        }
    }

    // Se request for POST
    if($metodo == 'POST'){
        $variaveis = array_merge(['endpoint' => $endpoint], $variaveis);
        curl_setopt($cliente, CURLOPT_POSTFIELDS, $variaveis);
    }

    curl_setopt($cliente, CURLOPT_URL, $url);

    // Retorno resposta
    $resposta = curl_exec($cliente);
    return json_decode($resposta, true);


}