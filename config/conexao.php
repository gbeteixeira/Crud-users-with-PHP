<?php

	//definir as informaçoes de
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "crudusuarios";

	try
	{

		//criar a conexao mysql e instanciar a classe PDO
		$conn = new PDO ("mysql:host=$servername; dbname=$dbname;", $username, $password);
		//trata erro retornado
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "<br>Conexao realizada com Sucesso!";
	}
	//exibindo o erro caso nao conecte no banco de dados
	catch(PDOException $erro)
	{
		echo "Falha na conexao: ".$erro->getMessage();
	}

?>
