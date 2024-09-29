<?php 

class api_resposta
{
    private $data;
    private $metodos_disponiveis = ['GET', 'POST'];
    
    //========================================

    public function __construct()
    {
        $this->data = [];
    }

    //========================================

    public function checar_metodo($metodo)
    {
        // Checa se metodo é valido
        return in_array($metodo, $this->metodos_disponiveis);
    }

    // ========================================

    public function set_metodo($metodo)
    {
        $this->data['metodo'] = $metodo;
    }

    
    // =======================================

    public function get_metodo()
    {
        return $this->data['metodo'];
    }

    // =======================================

    public function set_endpoint($endpoint)
    {
        $this->data['endpoint'] = $endpoint;
    }

    // =======================================

    public function get_endpoint()
    {
        return $this->data['endpoint'];
    }

    // =======================================

    public function add_ao_data($chave, $valor)
    {
        // Add nova chave ao data
        $this->data[$chave] = $valor;
    }

    // =======================================

    // =======================================

    public function api_request_erro($mensagem = " ")
    {
        $data_erro = [
            'status' => "ERROR",
            'error_mensagem' => $mensagem
        ];

        $this->data['data'] = $data_erro;
        $this->envia_resposta();
    }

    // =======================================

    public function envia_resposta()
    {
        header("Content-Type: application/json");
        echo json_encode($this->data);
        die();
    }

    // =================================

    public function envia_api_status()
    {
        $this->data['status'] = "SUCESSO";
        $this->data['mensagem'] = "API rodando ok!";
        $this->envia_resposta();
    }

}


?>