<?php 

  //processo para recuperar os dados e exibir no formulario
  try{
    //importa o arquivo de conexão
    include "config/conexao.php";

    //verifica se a variavel AL que veio do form de edição esta vazia, caso contrario continua a executar o codigo
    if(isset($_REQUEST['iduser']))
    {

      //recupera o valor da variavel al e atribui da variavel de id
      $idusuario = $_REQUEST['iduser'];
                
      //abre a conexão
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //ARMAZENAR O COMANDO DE INSERÇÃO DE DADOS NA VARIAVEL SQL

      $sql = "SELECT * FROM usuarios WHERE idusuario = :idusuario";

      //passar os parametros (valores vindo do form ou variavel para a variavel $sql)

      $result = $conn->prepare($sql);
      $result->bindValue(':idusuario', $idusuario);

      //executar a variavel para inserir os dados no banco de dados mysql

      $result->execute();

      //armazenar os valores da consulta na variavem row
      $row = $result->fetch(PDO::FETCH_ASSOC);

     }


  }
  //exibindo erros ao cadastrar
  catch(PDOException $erro)
  {
    echo "Falha ao retornar dados: ".$erro->getMessage();
  }

  $conn = null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alterando Usuário | <?php echo $row['nome']?></title>

    <!-- Main css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

    <div class="container">
        <div class="row justify-content-center mt-5">
            <form id="formAltUser" method="POST" enctype="multipart/form-data">
              <div class="form-group col-sm-6 align-items-center">
                <img class="img-fluid rounded-circle" src=<?php if(!empty($row['arquivo'])) {echo $row['arquivo'];} else {echo "https://media.istockphoto.com/vectors/profile-placeholder-image-gray-silhouette-no-photo-vector-id1016744034?k=6&m=1016744034&s=170667a&w=0&h=rO1167wSKkLSCFER6c7vjmceJrtyutZW6cF8XSX4bmk="; } ?>>
                <input type="hidden" name="caminho_arquivo" value="<?php if(!empty($row['arquivo'])) {echo $row['arquivo'];}?>">
                <label for="arquivo">Alterar Foto</label>
                <input type="file" class="form-control" id="arquivo" name="arquivo" aria-describedby="arquivo" placeholder="Imagem">
              </div>
              <div class="form-group">
                <label for="iduser">ID User</label>
                <input type="text" class="form-control" id="iduser" name="iduser" aria-describedby="emailHelp" value="<?php echo $row['idusuario']?>" readonly>
              </div>
              <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" aria-describedby="emailHelp" value="<?php echo $row['nome']?>">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']?>">
              </div>
              <div class="form-group">
                <label for="numero">Número</label>
                <input type="text" class="form-control phone-ddd-mask" name="numero" id="numero" value="<?php echo $row['celular']?>">
              </div>
              <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" class="form-control cpf-mask" name="cpf" id="cpf" value="<?php echo $row['cpf']?>">
              </div>

              <div class="row">
                <div class="col">
                    <label for="situacao">Situação</label>
                    <select name="situacao" class="form-control" id="situacao">
                          <!--Verificação de valor da situação-->
                          <?php 
                            switch ($row['situacao']) {
                              case 1:
                                echo '<option value="ativado" selected>Ativado</option>';
                                break;

                              case 0:
                                echo '<option value="desativado" selected>Desativado</option>';
                                break;
                            } 
                          ?>
                        </option>
                        <option value="ativado">Ativado</option>
                        <option value="desativado">Desativado</option>
                    </select>
                </div>
                <div class="col">
                  <label for="exampleInputPassword1">Tipo</label>
                    <select name="tipo" class="form-control" id="tipo">
                          <!--Verificação de valor do tipo de usuário-->
                          <?php 
                            switch ($row['tipo']) {
                              case 1:
                                echo '<option value="administrador" selected>Administrador</option>';
                                break;

                              case 0:
                                echo '<option value="padrao" selected>Padrão</option>';
                                break;
                            } 
                          ?>
                        <option value="administrador">Administrador</option>
                        <option value="padrao">Padrão</option>
                    </select>
                </div>
              </div>
                <input type="submit" name="BtnCadastrar" class="btn btn-success mt-5 float-right" id="Cadastrar" value="Alterar"/>
                <button class="btn btn-primary mt-5 mr-2 float-right"><a href="./" style="text-decoration: none; color: white">Cancelar</a></button>
            </form>
        </div>
    </div>  

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/bootstrap-notify.min.js"></script>
    <script type = "text/javascript" >
      jQuery(document).ready(function() {

        jQuery('#formAltUser').submit(function() {

            var notify = $.notify('<strong></strong>', {
                type: 'success',
                allow_dismiss: true,
                showProgressbar: false
            });

          //var dados = jQuery(this).serialize();
          var dados = new FormData(document.getElementById("formAltUser"));  

          jQuery.ajax({
            type: "POST",
            url: "config/alterardados.php",
            dataType: 'json',
            data: dados,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(response)
            {  

                notify.update('type', 'warning');
                notify.update('message', '<strong>Aguarde...</strong> Cadastrando dados...');

             },
            success: function(response) 
            {
                if (response.codigo == 1) 
                {
                   notify.update('type', 'success');
                   notify.update('message', '<strong>Sucesso!</strong> '+ response.mensagem);
                   setTimeout(function(){ location.href = './' }, 3000);
                }

                if (response.codigo == 2) 
                {
                   notify.update('type', 'danger');
                   notify.update('message', '<strong>Ocorreu um erro!</strong> '+response.mensagem);
                }
            },
            error: function (error) 
            {
                console.log(error.responseText);
                //alert(error.responseText);
                notify.update('type', 'danger');
                notify.update('message', '<strong>Ocorreu um erro!</strong> '+error.responseText);
            }

          });

          return false;
        });
      }); 
    </script>
</body>
</html>