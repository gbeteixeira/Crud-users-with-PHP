
<!DOCTYPE html>

<html>

  <head>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width">

  <title>Usuários - Visão Geral</title>

  <!--Styles-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">

  <!--Script-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



</head>
<body>

  <div class="bg-primary text-white text-center p-3">
    <a href="./login" class="btn float-left"><i class="fa fa-user" aria-hidden="true"></i></a>
    Usuarios
  </div>

  <div class="container">

    <div class="col-sm-12">
      <a href="controleusuarios.php" class="btn btn-primary float-right mt-3 p-3"><i class="fa fa-plus" aria-hidden="true"></i></a>
    </div>

    <div class="row">

      <div class="col-sm-12">
      	<!--Tabela de usuarios-->
        <table class="table table-bordered mt-3">
          <tr>
            <th> ID </th>
            <th> Foto </th>
            <th> Nome </th>
            <th> Email </th>
            <th> Celular </th>
            <th> CPF </th>
            <th> Tipo </th>
            <th> Situação </th>
			<td colspan="2" align="center"> DETALHES </td>
          </tr>
	<?php
		try{

	        //importa o arquivo de conexão
	        include "config/conexao.php";

	        //abre a conexão

	        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	        //ARMAZENAR O COMANDO DE INSERÇÃO DE DADOS NA VARIAVEL SQL

	        $sql = "SELECT * FROM usuarios";

	        $consulta = $conn->prepare($sql);

	        $consulta->execute();

	        // Armazena os dados na variavel Row
	        while ($row = $consulta->fetch(PDO::FETCH_ASSOC)) 
	        {
	?>

	          <tr>
	            <td> <?php echo $row['idusuario']?> </td>
              <td> <img class="img-fluid img-thumbnail" src="<?php if(!empty($row['arquivo'])) {echo $row['arquivo'];} else {echo "https://media.istockphoto.com/vectors/profile-placeholder-image-gray-silhouette-no-photo-vector-id1016744034?k=6&m=1016744034&s=170667a&w=0&h=rO1167wSKkLSCFER6c7vjmceJrtyutZW6cF8XSX4bmk="; } ?>"></td>
	            <td> <?php echo $row['nome']?> </td>
	            <td> <?php echo $row['email']?> </td>
	            <td> <?php echo $row['celular']?> </td>
	            <td> <?php echo $row['cpf']?> </td>
	            <td> 
	            	<?php echo ($row['tipo'] == 0) ? "Padrão" : null; ?>
	            	<?php echo ($row['tipo'] == 1) ? "Administrador" : null; ?> 
	            </td>
	            <td align="center"> 
                <i class="fa fa-circle" aria-hidden="true" style="color: 

                  <?php echo ($row['situacao'] == 0) ? "red;" : null; ?>
                  <?php echo ($row['situacao'] == 1) ? "green;" : null; ?> 

                "></i>
	            </td>
	            <td>
                    <a onclick="confirmacao('alterar.php?iduser=<?php echo $row['idusuario']?>')"><i class="fa fa-refresh"></i></a>
                </td>
                <td>
                    <a onclick="confirmacao('index.php?ex=<?php echo $row['idusuario']?>')"><i style="color: red" class="fa fa-trash"></i></a>
                </td>
	          </tr>

	<?php
            }

        }
        catch(PDOException $erro)
        {
            echo "Falha ao exibir os dados: ".$erro->getMessage();
        }

    ?>
          
        </table>

      </div>

    </div>

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

	if(isset($_REQUEST['ex']))
    {
        try{

        	$idusuario = $_REQUEST['ex'];

            //importa o arquivo de conexão
            include "config/conexao.php";

            //abre a conexão
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //ARMAZENAR O COMANDO DE INSERÇÃO DE DADOS NA VARIAVEL SQL
            $sql = "DELETE FROM usuarios WHERE idusuario = :idusuario";

            //prepara a variavel sql de exclusao
            $delete = $conn->prepare($sql);

            //passa os valores passados na variavel ex
            $delete->bindValue(':idusuario', $idusuario);
                
            //executa o comando de exclusao
            $delete->execute();

            //informa o usuario que os dados foram excluidos
            echo "
                <script language=javascript>
	                var notify = $.notify('<strong></strong>', {
	                type: 'success',
	                allow_dismiss: true,
	                showProgressbar: false
	            	});

                	notify.update('type', 'success');
                    notify.update('message', '<strong>Sucesso!</strong> usuário excluido com sucesso');

                    setTimeout(function(){ location.href = './' }, 3000);
                </script>";

        }
        catch(PDOException $erro)
        {
            //echo "Falha ao exibir os dados: ".$erro->getMessage();
            echo "
                <script language=javascript>
	                var notify = $.notify('<strong></strong>', {
	                type: 'success',
	                allow_dismiss: true,
	                showProgressbar: false
	            	});

                	notify.update('type', 'danger');
                    notify.update('message', '<strong>Erro!</strong> Erro ao excluir usuário');

                    setTimeout(function(){ location.href = './' }, 3000);
                </script>";
        }
            
    }

?>