<?php 

  //função que limpa a string evitando ataques xss
  function limpaString($dados)
  {
    $dados = addslashes($dados);
    $dados = strip_tags($dados);
    $dados = htmlspecialchars($dados);

    return $dados;
  }

  //senha recebida via formulario
  $email = isset($_REQUEST['email']) ? limpaString($_REQUEST['email']) : ''; 

  try
  {

    //importa o arquivo de conexão
    include "conexao.php";

    //importa os arquivos para envio de email com a nova senha
    require_once "email_senha.php";

     //abre a conexão
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Busca hash da senha pelo email
    $sql1 = "SELECT * FROM usuarios WHERE email = :email";
    //prepara a conexao
    $result = $conn->prepare($sql1);
    //atribui o email que foi passado no campo de login na condição de busca
    $result->bindValue(':email', $email);

    //executa a busca
    $result->execute();

    //atribui a variavel dados os valores retornados da busca
    $dados = $result->fetch(PDO::FETCH_ASSOC);


    //Se houver resultados
    if(!empty($dados))
    {
      //gera a senha
      $senha = gerar_senha(6, true, true, false);
          
      //mensagem que será enviada ao usuario
      $msg = "Você solicitou uma alteração de senha, sua nova senha é: " . $senha;

      //envia pelo email a nova senha
      emailContato($email, $email, 'Recuperação de Senha', $msg);

      //criptografa a nova senha
      $pass = password_hash($senha, PASSWORD_DEFAULT);

      //altera no banco de dados a senha
      try
      {
        //ARMAZENAR O COMANDO DE INSERÇÃO DE DADOS NA VARIAVEL SQL
        $sql = "UPDATE usuarios SET senha = :senha WHERE email = :email";
        //prepara a conexao
        $stm = $conn->prepare($sql);
        //atribui o valor da senha
        $stm->bindValue(":senha", $pass);
        //atribui no where o email do usuario
        $stm->bindValue(":email", $email);
        //executa o login
        $stm->execute();

        //retorna ao usuario que a senha foi alterada
        $retorno = array('codigo' => 202);
        echo json_encode($retorno);
        exit();

      }
      //exibindo erros ao alterar a senha
      catch(PDOException $erro)
      {
        $retorno = array('codigo' => 404);
        echo json_encode($retorno);
        exit();
        //echo "Falha ao cadastrar dados: ".$erro->getMessage();
      }

    }
    else
    {
      //retorna ao usuario que o email digitado não está cadastrado
      $retorno = array('codigo' => 404);
      echo json_encode($retorno);
      exit();
    } 
  }
  //exibindo erros ao consultar email
  catch(PDOException $erro)
  {
      $retorno = array('codigo' => 404);
      echo json_encode($retorno);
      exit();
      //echo "Falha ao cadastrar dados: ".$erro->getMessage();
  }

function gerar_senha($tamanho, $maiusculas, $numeros, $simbolos){
	 
   $lmin = 'abcdefghijklmnopqrstuvwxyz';
		
   $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
   $num = '1234567890';
		
   $simb = '!@#$%*-';
		
   $retorno = '';
		
   $caracteres = '';
		
   $caracteres .= $lmin;
		
   if ($maiusculas) $caracteres .= $lmai;
		
   if ($numeros) $caracteres .= $num;
		
   if ($simbolos) $caracteres .= $simb;
		
   $len = strlen($caracteres);
		
   for ($n = 1; $n <= $tamanho; $n++) {
			
	  $rand = mt_rand(1, $len);
			
	  $retorno .= $caracteres[$rand-1];
			
	}
	return $retorno;
}

?>

