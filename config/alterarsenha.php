<?php 

  session_start();

  if(!isset($_SESSION['auth']) || $_SESSION['auth'] != true)
  {
    header('Location: ../login/');
  }

?>
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

    //email
    $email = $_SESSION['sess_email_user'];
    //senha
    $senhaAntiga  = isset($_REQUEST['senha']) ? limpaString($_REQUEST['senha']) : '';
    $novaSenha    = isset($_REQUEST['novasenha']) ? limpaString($_REQUEST['novasenha']) : '';
    $cNovaSenha   = isset($_REQUEST['confirmesenha']) ? limpaString($_REQUEST['confirmesenha']) : '';

    if (empty($senhaAntiga)) 
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Preencha sua senha antiga!');
        echo json_encode($retorno);
        exit();
    }
    if (empty($novaSenha)) 
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Preencha sua nova senha!');
        echo json_encode($retorno);
        exit();
    }
    if (empty($cNovaSenha)) 
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Confirme sua senha!');
        echo json_encode($retorno);
        exit();
    }

    if ($novaSenha != $cNovaSenha)
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'As senhas não se coincidem!');
        echo json_encode($retorno);
        exit();
    }

    //nova senha criptografada
    $newSenha = password_hash($novaSenha, PASSWORD_DEFAULT);


    try{
        //importa o arquivo de conexão
        include "conexao.php";

        //abre a conexão
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //REALIZA CONSULTA A PARTIR DO EMAIL 
            $consulta = $conn->prepare("SELECT *  FROM usuarios WHERE email = :email");

            $consulta->bindValue(":email", $email);
            $consulta->execute();

            $row = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($novaSenha == $cNovaSenha and password_verify($senhaAntiga, $row['senha']))
        {

            //ARMAZENAR O COMANDO DE INSERÇÃO DE DADOS NA VARIAVEL SQL
            $sql = "UPDATE usuarios SET senha = :senha WHERE email = :email";

            
            //passar os parametros (valores vindo do form ou variavel para a variavel $sql)
            $result = $conn->prepare($sql);
            $result->bindValue(':senha', $newSenha);
            $result->bindValue(':email', $email);

            //executar a variavel para inserir os dados no banco de dados mysql
            $result->execute();

            //msg para o usuario saber que os dados foram inseridos com sucesso
            $retorno = array('codigo' => 1, 'mensagem' => 'Senha alterada com Sucesso!');
            echo json_encode($retorno);
            exit();

        }
        else
        {
            //msg para o usuario saber que os dados foram inseridos com sucesso
            $retorno = array('codigo' => 2, 'mensagem' => 'Sua senha antiga está incorreta');
            echo json_encode($retorno);
            exit();
        }

       

    }
    //exibindo erros ao cadastrar
    catch(PDOException $erro)
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Erro ao alterar sua senha!');
        echo json_encode($retorno);
        exit();
        //echo "Falha ao cadastrar dados: ".$erro->getMessage();
    }

    $conn = null;
?>