<?php
  //verificacao se tem uma sessao inciada
  session_cache_expire(60);
  session_start();

  if(!isset($_SESSION['auth']) || $_SESSION['auth'] != true)
  {
    header('Location: login/');
  }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastrar Users</title>

    <!-- Main css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

    <div class="container">
        <div class="row justify-content-center mt-5">
            <form id="formCadUser" method="POST">
              <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" aria-describedby="emailHelp" placeholder="Nome">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
              </div>
              <div class="form-group">
                <label for="numero">Número</label>
                <input type="text" class="form-control phone-ddd-mask" name="numero" id="numero" placeholder="Ex.: (00) 0000-0000">
              </div>
              <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" class="form-control cpf-mask" name="cpf" id="cpf" placeholder="Ex.: 000.000.000-00">
              </div>
              <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" class="form-control" name="senha" id="senha" placeholder="Senha">
              </div>
              <div class="row">
                <div class="col">
                    <label for="situacao">Situação</label>
                    <select name="situacao" class="form-control" id="situacao">
                        <option value="ativado">Ativado</option>
                        <option value="desativado" selected>Desativado</option>
                    </select>
                </div>
                <div class="col">
                  <label for="exampleInputPassword1">Tipo</label>
                    <select name="tipo" class="form-control" id="tipo">
                        <option value="administrador">Administrador</option>
                        <option value="padrao" selected>Padrão</option>
                    </select>
                </div>
              </div>
                <input type="submit" name="BtnCadastrar" class="btn btn-success mt-5 float-right" id="Cadastrar" value="Cadastrar">
                <input type="reset" name="BtnLimpar" class="btn btn-primary mt-5 mr-2 float-right" value="Limpar">
            </form>
        </div>
    </div>  

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/bootstrap-notify.min.js"></script>
    <script type = "text/javascript" >
      jQuery(document).ready(function() {

        jQuery('#formCadUser').submit(function() {

            var notify = $.notify('<strong></strong>', {
                type: 'success',
                allow_dismiss: true,
                showProgressbar: false
            });

          var dados = jQuery(this).serialize();

          jQuery.ajax({
            type: "POST",
            url: "config/salvardados.php",
            dataType: 'json',
            data: dados,
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