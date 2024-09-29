<?php

use EasyPDO\EasyPDO;

class api_logica
{
    private $endpoint;
    private $params;

    //--------------------------------------
    public function __construct($endpoint, $params = [])
    {
        // definir propriedades do objeto/classe
        $this->endpoint = $endpoint;
        $this->params = $params;
    }

    //--------------------------------------
    public function endpoint_existe()
    {
        return method_exists($this, $this->endpoint);
    }


    //--------------------------------------
    public function error_resposta($mensagem)
    {
        //mensagem de erro
        return [
            'status' => 'ERROR',
            'mensagem' => $mensagem,
            'resultados' => []
        ];
    }



    //--------------------------------------
    //  ENDPOINTS
    //--------------------------------------
    public function status()
    {
        return [
            'status' => 'SUCESSO',
            'mensagem' => 'API rodando OK'
        ];
    }

    public function get_totals()
    {
        // Retorna total de cliente/produtos

        $db = new EasyPDO();
        $resultados = $db->select("
        SELECT 'Clientes', COUNT(*) Total FROM clientes WHERE deleted_at IS NULL UNION ALL
        SELECT 'Produtos', COUNT(*) Total FROM produtos WHERE deleted_at IS NULL
        ");

        return [
            'status' => 'SUCESSO',
            'mensagem' => '',
            'resultados' => $resultados
        ];
    }


    //#############################################################################################################################
    //--------------------------------------
    // CLIENTES
    //--------------------------------------

    public function get_all_clientes()
    {
        // Retorna todos os clientes do banco de dados

        $db = new EasyPDO();
        $resultados = $db->select("SELECT * FROM clientes");

        return [
            'status' => 'SUCESSO',
            'mensagem' => '',
            'resultados' => $resultados
        ];
    }

    //--------------------------------------

    public function get_clientes_ativos()
    {
        // Retorna todos os clientes sem registro de 'deleted'

        $db = new EasyPDO();
        $resultados = $db->select("SELECT * FROM clientes WHERE deleted_at IS NULL");

        return [
            'status' => 'SUCESSO',
            'mensagem' => '',
            'resultados' => $resultados
        ];
    }

    //--------------------------------------

    public function get_clientes_inativos()
    {
        // Retorna todos os clientes com registro de deleted

        $db = new EasyPDO();
        $resultados = $db->select("SELECT * FROM clientes WHERE deleted_at IS NOT NULL");

        return [
            'status' => 'SUCESSO',
            'mensagem' => '',
            'resultados' => $resultados
        ];
    }

    //--------------------------------------

    public function get_cliente()
    {
        // Retorna dados de UM cliente especifico
        $sqlComando = "SELECT * FROM clientes WHERE 1 ";

        // checar se if id existe
        if (key_exists('id', $this->params)) {

            if (filter_var($this->params['id'], FILTER_VALIDATE_INT)) {
                $sqlComando .= "AND id_cliente = " . intval($this->params['id']);
            }
        } else {
            return $this->error_resposta('ID cliente não informado.');
        }

        $db = new EasyPDO();
        $resultados = $db->select($sqlComando);

        return [
            'status' => 'SUCESSO',
            'mensagem' => '',
            'resultados' => $resultados
        ];
    }

    //--------------------------------------

    public function create_novo_cliente()
    {
        // Verifica se dados estão setados
        if (
            !isset($this->params['nome']) ||
            !isset($this->params['email']) ||
            !isset($this->params['telefone'])
        ) {
            return $this->error_resposta('Dados insuficientes.');
        }


        //----------------------

        // Verifica se cliente já existe no BD

        $bd = new EasyPDO();
        $parametros = [
            ':nome' => $this->params['nome'],
            ':email' => $this->params['email']
        ];
        $results = $bd->select("
            SELECT id_cliente FROM clientes
            WHERE 1
            AND  (nome = :nome OR email = :email)
            AND deleted_at IS NULL
        ", $parametros);
        if (count($results) != 0) {
            return $this->error_resposta('Já existe um cliente cadastrado com este e-mail.');
        }

        //----------------------

        // Passando dados
        $parametros = [
            ':nome' => $this->params['nome'],
            ':telefone' => $this->params['telefone'],
            ':email' => $this->params['email']
        ];


        // Guardando dados
        $bd->insert("
            INSERT INTO clientes(id_cliente, nome, email, telefone, created_at, update_at, deleted_at) VALUES(
                0,
                :nome,
                :email,
                :telefone,
                NOW(),
                NOW(),
                NULL
            )
        ", $parametros);

        return [
            'status' => 'SUCESSO',
            'mensagem' => 'Cliente adicionado com sucesso!',
            'resultados' => []
        ];
    }
    //--------------------------------------

    public function editar_cliente()
    {
        // Verifica se dados estão setados

        if (
            !isset($this->params['id']) ||
            !isset($this->params['nome']) ||
            !isset($this->params['email']) ||
            !isset($this->params['telefone'])
        ) {
            return $this->error_resposta('Dados insuficientes.');
        }


        //----------------------

        // Verifica se cliente já existe

        $bd = new EasyPDO();
        $parametros = [
            ':id_cliente' => $this->params['id'],
            ':nome' => $this->params['nome'],
            ':email' => $this->params['email']
        ];
        $results = $bd->select("
            SELECT id_cliente FROM clientes
            WHERE 1
            AND  (nome = :nome OR email = :email)
            AND id_cliente <> :id_cliente
            AND deleted_at IS NULL
        ", $parametros);
        if (count($results) != 0) {
            return $this->error_resposta('Já existe um cliente cadastrado com este nome/e-mail.');
        }

        //----------------------

        // Passando dados
        $parametros = [
            ':id_cliente' => $this->params['id'],
            ':nome' => $this->params['nome'],
            ':telefone' => $this->params['telefone'],
            ':email' => $this->params['email']
        ];


        // Atualizando dados
        $bd->update("
            UPDATE clientes SET
                nome = :nome,
                email = :email,
                telefone = :telefone,
                update_at = NOW()
            WHERE id_cliente = :id_cliente
        ", $parametros);

        return [
            'status' => 'SUCESSO',
            'mensagem' => 'Cliente atualizado com sucesso!',
            'resultados' => []
        ];
    }


    // ------------------------------------------

    public function hard_deletar_cliente()
    { // Remove do site e da base de dados

        // verifica existencia do id

        if (!isset($this->params['id']) || !filter_var($this->params['id'], FILTER_VALIDATE_INT)) {
            return $this->error_resposta('ID cliente não informado ou inválido');
        }

        // deleta o cliente do bd

        $bd = new EasyPDO();
        $parametros = [':id_cliente' => $this->params['id']];

        $bd->delete("DELETE FROM clientes WHERE id_cliente = :id_cliente", $parametros);

        return [
            'status' => 'SUCESSO',
            'mensagem' => 'Cliente deletado com sucesso.',
            'resultados' => []
        ];
    }
    // ------------------------------------------

    public function soft_deletar_cliente()
    { // Remove do site PORÉM mantém na base de dados

        // verifica existencia do id

        if (!isset($this->params['id']) || !filter_var($this->params['id'], FILTER_VALIDATE_INT)) {
            return $this->error_resposta('ID cliente não informado ou inválido');
        }

        // deletar o cliente do bd

        $bd = new EasyPDO();
        $parametros = [':id_cliente' => $this->params['id']];

        $bd->update("
        UPDATE clientes SET deleted_at = NOW() WHERE id_cliente = :id_cliente
        ", $parametros);

        return [
            'status' => 'SUCESSO',
            'mensagem' => 'Cliente deletado com sucesso.',
            'resultados' => []
        ];
    }


    //#############################################################################################################################
    //--------------------------------------
    // PRODUTOS
    //--------------------------------------

    public function get_all_produtos()
    {
        // Retorna todos os produtos do banco de dados

        $db = new EasyPDO();
        $resultados = $db->select("SELECT * FROM produtos");

        return [
            'status' => 'SUCESSO',
            'mensagem' => '',
            'resultados' => $resultados
        ];
    }

    //--------------------------------------

    public function get_produto()
    {
        // Retorna dados de UM produto especifico
        $sqlComando = "SELECT * FROM produtos WHERE 1 ";

        // checa se id existe
        if (key_exists('id', $this->params)) {

            if (filter_var($this->params['id'], FILTER_VALIDATE_INT)) {
                $sqlComando .= "AND id_produto = " . intval($this->params['id']);
            }
        } else {
            return $this->error_resposta('ID produto não foi informado/passado.');
        }

        $db = new EasyPDO();
        $resultados = $db->select($sqlComando);

        return [
            'status' => 'SUCESSO',
            'mensagem' => '',
            'resultados' => $resultados
        ];
    }

    //--------------------------------------

    public function get_produtos_ativos()
    {
        // Retorna produtos SEM campo deleted
        $db = new EasyPDO();
        $resultados = $db->select("SELECT * FROM produtos WHERE deleted_at IS NULL");

        return [
            'status' => "SUCESSO",
            'mensagem' => '',
            'resultados' => $resultados
        ];
    }

    //--------------------------------------

    public function get_produtos_inativos()
    {
        // Retorna produtos COM campo deleted
        $db = new EasyPDO();
        $resultados = $db->select("SELECT * FROM produtos WHERE deleted_at IS NOT NULL");

        return [
            'status' => "SUCESSO",
            'mensagem' => '',
            'resultados' => $resultados
        ];
    }

    //--------------------------------------

    public function get_produtos_sem_estoque()
    {
        // Retorna produtos zerados (sem estoque)
        $db = new EasyPDO();
        $resultados = $db->select("SELECT * FROM produtos WHERE quantidade <= 0 AND deleted_at IS NULL");

        return [
            'status' => "SUCESSO",
            'mensagem' => '',
            'resultados' => $resultados
        ];
    }

    //--------------------------------------

    public function create_novo_produto()
    {
        // Verifica se dados estão setados
        if (
            !isset($this->params['produto']) ||
            !isset($this->params['quantidade'])
        ) {
            return $this->error_resposta('Dados insuficientes.');
        }

        //----------------------
        // Verifica se produto já existe

        $bd = new EasyPDO();
        $parametros = [
            ':produto' => $this->params['produto']
        ];
        $results = $bd->select("
            SELECT id_produto FROM produtos
            WHERE
            produto = :produto
            AND deleted_at IS NULL
        ", $parametros);
        if (count($results) != 0) {
            return $this->error_resposta('Este produto já está registrado.');
        }

        //----------------------

        // Passando dados
        $parametros = [
            ':produto' => $this->params['produto'],
            ':quantidade' => $this->params['quantidade']
        ];


        // Guardando dados
        $bd->insert("
            INSERT INTO produtos(produto, quantidade, created_at, updated_at, deleted_at) VALUES(
                :produto,
                :quantidade,
                NOW(),
                NOW(),
                NULL
            )
        ", $parametros);

        return [
            'status' => 'SUCESSO',
            'mensagem' => 'Produto adicionado com sucesso!',
            'resultados' => []
        ];
    }

    // ------------------------------------------

    public function editar_produto()
    {
        // verifica se dados estão setados
        if (
            !isset($this->params['id']) ||
            !isset($this->params['produto']) ||
            !isset($this->params['quantidade'])
        ) {
            return $this->error_resposta('Dados insuficientes.');
        }


        //----------------------
        // verifica se produto já existe

        $bd = new EasyPDO();
        $parametros = [
            ':id_produto' => $this->params['id'],
            ':produto' => $this->params['produto'],
            ':quantidade' => $this->params['quantidade']
        ];
        $results = $bd->select("
            SELECT id_produto FROM produtos
            WHERE produto = :produto
            AND id_produto <> :id_produto
            AND deleted_at IS NULL
        ", $parametros);

        if (count($results) != 0) {
            return $this->error_resposta('Este produto já está cadastrado.');
        }


        //----------------------
        // Passando dados
        $parametros = [
            ':id_produto' => $this->params['id'],
            ':produto' => $this->params['produto'],
            ':quantidade' => $this->params['quantidade']
        ];

        
        // Atualizando dados
        $bd->update("
            UPDATE produtos SET
                produto = :produto,
                quantidade = :quantidade,
                updated_at = NOW()
            WHERE id_produto = :id_produto
        ", $parametros);

        return [
            'status' => 'SUCESSO',
            'mensagem' => 'Produto atualizado com sucesso!',
            'resultados' => []
        ];
    }

    // ------------------------------------------

    public function hard_deletar_produto()
    { // Remove do site E da base de dados

        // verifica existencia do id
        if (!isset($this->params['id']) || !filter_var($this->params['id'], FILTER_VALIDATE_INT)) {
            return $this->error_resposta('ID produto não informado ou inválido');
        }

        // deleta o produto do bd
        $bd = new EasyPDO();
        $parametros = [':id_produto' => $this->params['id']];

        $bd->delete("DELETE FROM produtos WHERE id_produto = :id_produto", $parametros);

        return [
            'status' => 'SUCESSO',
            'mensagem' => 'Produto deletado com sucesso.',
            'resultados' => []
        ];
    }

    // ------------------------------------------

    public function soft_deletar_produto()
    { // Remove do site PORÉM mantém na base de dados

        // verifica existencia do id
        if (!isset($this->params['id']) || !filter_var($this->params['id'], FILTER_VALIDATE_INT)) {
            return $this->error_resposta('ID produto não informado ou inválido');
        }

        // deleta o cliente do bd
        $bd = new EasyPDO();
        $parametros = [':id_produto' => $this->params['id']];

        $bd->update("
        UPDATE produtos SET deleted_at = NOW() WHERE id_produto = :id_produto
        ", $parametros);

        return [
            'status' => 'SUCESSO',
            'mensagem' => 'Produto deletado com sucesso.',
            'resultados' => []
        ];
    }
}
