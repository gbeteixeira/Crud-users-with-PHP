<?php

    //limpa dados atk xss
    function limpaString($dados)
    {

        $dados = addslashes($dados);
        $dados = strip_tags($dados);
        $dados = htmlspecialchars($dados);

        return $dados;
    }

    //var dump
    //var_dump($_REQUEST);

    $idusuario  = isset($_REQUEST['iduser']) ? limpaString($_REQUEST['iduser']) : '';
    $nome  = isset($_REQUEST['nome']) ? limpaString($_REQUEST['nome']) : '';
    $email = isset($_REQUEST['email']) ? limpaString($_REQUEST['email']) : '';
    $celular = isset($_REQUEST['numero']) ? limpaString($_REQUEST['numero']) : '';
    $cpf = isset($_REQUEST['cpf']) ? limpaString($_REQUEST['cpf']) : '';
    $tipo = isset($_REQUEST['tipo']) ? limpaString($_REQUEST['tipo']) : '';
    $situacao = isset($_REQUEST['situacao']) ? limpaString($_REQUEST['situacao']) : '';
    $datacadastro = date("Y-m-d");

    if (empty($nome)) 
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Preencha o campo Nome!');
        echo json_encode($retorno);
        exit();
    }
    if (empty($email)) 
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Preencha o campo Email!');
        echo json_encode($retorno);
        exit();
    }
    if (empty($celular)) 
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Preencha o campo Número de celular!');
        echo json_encode($retorno);
        exit();
    }
    if (empty($cpf)) 
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Preencha o campo de CPF!');
        echo json_encode($retorno);
        exit();
    }
    if (empty($tipo)) 
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Selecione o tipo de usuário!');
        echo json_encode($retorno);
        exit();
    }
    if (empty($situacao)) 
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Selecione a situação do usuário!');
        echo json_encode($retorno);
        exit();
    }


    switch ($tipo) {
        case 'administrador':
            $tipo = 1;
            break;

        case 'padrao':
            $tipo = 0;
            break;
            
        default:
            $retorno = array('codigo' => 2, 'mensagem' => 'Selecione um tipo de usuário!');
            echo json_encode($retorno);
            break;
    }

    switch ($situacao) {
        case 'ativado':
            $situacao = 1;
            break;

        case 'desativado':
            $situacao = 0;
            break;
            
        default:
            $retorno = array('codigo' => 2, 'mensagem' => 'Selecione corretamente a situação do usuário!');
            echo json_encode($retorno);
            break;
    }

    try{
        //importa o arquivo de conexão
        include "conexao.php";

        //abre a conexão

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //ARMAZENAR O COMANDO DE INSERÇÃO DE DADOS NA VARIAVEL SQL

        $sql = "UPDATE usuarios SET nome = :nome, email = :email, celular = :celular, cpf = :cpf, tipo = :tipo, situacao = :situacao WHERE idusuario = :idusuario";

        //passar os parametros (valores vindo do form ou variavel para a variavel $sql)

        $result = $conn->prepare($sql);
        $result->bindValue(':nome', $nome);
        $result->bindValue(':email', $email);
        $result->bindValue(':celular', $celular);
        $result->bindValue(':cpf', $cpf);
        $result->bindValue(':tipo', $tipo);
        $result->bindValue(':situacao', $situacao);
        $result->bindValue(':idusuario', $idusuario);


        //executar a variavel para inserir os dados no banco de dados mysql

        $result->execute();

        //msg para o usuario saber que os dados foram inseridos com sucesso
        $retorno = array('codigo' => 1, 'mensagem' => 'Dados alterados com Sucesso!');
        echo json_encode($retorno);
        exit();


    }
    //exibindo erros ao cadastrar
    catch(PDOException $erro)
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Erro ao alterar os dados!');
        echo json_encode($retorno);
        exit();
        //echo "Falha ao cadastrar dados: ".$erro->getMessage();
    }

    $conn = null;
?>