<!DOCTYPE html>

<html>

  <head>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width">

  <title>Administrador - Visão Geral</title>

  <!--Styles-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <link rel="stylesheet" href="../css/font-awesome/css/font-awesome.min.css">

  <!--Script-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

  <div class="bg-primary text-white text-center p-3">
  	<a onclick="confirmacao('?logout')" class="btn float-left"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
    Home
  </div>

  <div class="container">
	  <?php

		echo "<h1 class='text-center mb-3 mt-3'>Olá ".$_SESSION['sess_name_user']." você é um administrador!</h1>";

	  ?>

    <p>Nome: <?=$_SESSION['sess_name_user']?></p>
    <p>Email: <?=$_SESSION['sess_email_user']?></p>
    <p><img src="../<?=$_SESSION['sess_foto_user']?>" width="15%" alt=""></p>
    <p>Sessão: <?=session_id()?></p>
    <p>Alterar senha: <a href="alterarsenha.php">Alterar</a></p>

  </div>

 <!-- JS -->
 <script src="vendor/jquery/jquery.min.js"></script>
 <script src="js/bootstrap-notify.min.js"></script>

 <script>
	function confirmacao(link)
	{
		var link = link;

		var r=confirm("Você tem certeza?");

		if (r==true)
		{
		  window.location.href= link;
		}
	}
</script>

</body>

</html>

<?php
  require_once '../config/sair.php';
?>