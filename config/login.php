<?php

    session_start();
    if(isset($_SESSION['auth']) && $_SESSION['auth'] == true)
    {
        header('Location: ../');
    }

    //limpa dados atk xss
    function limpaString($dados)
    {

        $dados = addslashes($dados);
        $dados = strip_tags($dados);
        $dados = htmlspecialchars($dados);

        return $dados;
    }

    //dados recebidos pelo formulario, são verificados e limpos, contra qualquer tipo de string especial do html
    $email = isset($_REQUEST['email']) ? limpaString($_REQUEST['email']) : '';
    $senha  = isset($_REQUEST['senha']) ? limpaString($_REQUEST['senha']) : '';
    
    //verifica se a variavel email está vazia
    if (empty($email)) 
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Preencha o campo de Email!');
        echo json_encode($retorno);
        exit();
    }
    //verifica se a variavel senha está vazia
    if (empty($senha)) 
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Preencha o campo Senha!');
        echo json_encode($retorno);
        exit();
    }

    try{

        //importa o arquivo de conexão
        include "conexao.php";

        //abre a conexão
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Busca hash da senha pelo email
        $sql1 = "SELECT senha FROM usuarios WHERE email = :email";
        //prepara a conexao
        $result = $conn->prepare($sql1);
        //atribui o email que foi passado no campo de login na condição de busca
        $result->bindValue(':email', $email);

        //executa a busca
        $result->execute();

        //atribui a variavel dados os valores retornados da busca
        $dados = $result->fetch(PDO::FETCH_ASSOC);

        //pega senha criptografada do bd
        $hash = $dados['senha'];

        //Se a hash for for diferente de NULL ele continua o login
        if($hash != NULL)
        {
            //Verifica se a senha é = ao hash (senha criptografada)
            if(!password_verify($senha, $hash)) 
            {
                $retorno = array('codigo' => 3, 'mensagem' => 'Senha Inválida!');
                echo json_encode($retorno);
                exit(); 
            }

            //caso as senha sejam iguais executa o login
            try
            {
                //ARMAZENAR O COMANDO DE INSERÇÃO DE DADOS NA VARIAVEL SQL
                $sql = "SELECT * FROM usuarios WHERE email = ? AND senha = ? ";
                //prepara a conexao
                $stm = $conn->prepare($sql);
                //atribui o email que foi passado no campo de login na condição de busca
                $stm->bindValue(1, $email);
                //atribui o hash que foi retornado na verificação de senhas 
                $stm->bindValue(2, $hash);
                //executa o login
                $stm->execute();
                //atribui os dados retornados na variavel dadosUser
                $dadosUser = $stm->fetch(PDO::FETCH_ASSOC);

                //retorna que os dados estão sendo validados
                $retorno = array('codigo' => 1, 'mensagem' => 'Validando dados...');

                if($dadosUser['situacao'] == 1)
                {
                    //sessao de login passa a ser true
                    $_SESSION['auth'] = true;
                    //sessao de com o id do usuario
                    $_SESSION['sess_id_user'] = $dadosUser['idusuario'];
                    //sessao de com o nome do usuario
                    $_SESSION['sess_name_user'] = $dadosUser['nome'];
                    //sessao de com o nome do usuario
                    $_SESSION['sess_email_user'] = $dadosUser['email'];
                    //sessao de com o nivel de acesso do usuario
                    $_SESSION['sess_tipo_user'] = $dadosUser['tipo'];
                    //sessao com valor da foto do user
                    $_SESSION['sess_foto_user'] = $dadosUser['arquivo'];

                }
                else
                {
                    $retorno = array('codigo' => 2, 'mensagem' => 'O usuario está desativado!');
                    echo json_encode($retorno);
                    exit();
                }

                //retorna o que está acontecendo em Json
                echo json_encode($retorno);
                exit();
            }
            catch(PDOException $erro)
            {
                $retorno = array('codigo' => 2, 'mensagem' => 'Erro ao efetuar o Login!');
                echo json_encode($retorno);
                exit();
                //echo "Falha ao cadastrar dados: ".$erro->getMessage();
            }

        }
        //se as senhas não forem identicas diz que houve algum erro
        else
        {
          $retorno = array('codigo' => 2, 'mensagem' => 'E-mail ou senha incorretos!');
          echo json_encode($retorno);
          exit();
        }
    }
    //exibindo erros ao logar
    catch(PDOException $erro)
    {
        $retorno = array('codigo' => 2, 'mensagem' => 'Erro ao efetuar o login!');
        echo json_encode($retorno);
        exit();
        //echo "Falha ao cadastrar dados: ".$erro->getMessage();
    }

    $conn = null;
?>