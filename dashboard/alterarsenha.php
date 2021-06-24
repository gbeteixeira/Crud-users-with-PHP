<?php 

  session_start();

  if(!isset($_SESSION['auth']) || $_SESSION['auth'] != true)
  {
    header('Location: ../login/');
  }

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Alterando Usuário | <?=$_SESSION['sess_name_user']?></title>

    <!-- Main css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

    <div class="container">
        <div class="row justify-content-center mt-5">
            <form id="formAltSenha" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <label for="senha">Informe sua senha antiga</label>
                <input type="password" class="form-control" name="senha" id="senha" placeholder="Digite sua senha atual">
              </div>

              <div class="form-group">
                <label for="novasenha">Informe sua nova senha</label>
                <input type="password" class="form-control" name="novasenha" id="novasenha" placeholder="Digite sua nova senha">
              </div>

              <div class="form-group">
                <label for="confirmesenha">Confirme sua senha</label>
                <input type="password" class="form-control" name="confirmesenha" id="confirmesenha" placeholder="Confirme sua nova senha">
              </div>

              <input type="submit" name="BtnAlterar" class="btn btn-success mt-5 float-right" id="Alterar" value="Alterar">
              <input type="reset" name="BtnLimpar" class="btn btn-primary mt-5 mr-2 float-right" value="Limpar">
            </form>
        </div>
    </div>  

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../js/bootstrap-notify.min.js"></script>
    <script type = "text/javascript" >
      jQuery(document).ready(function() {

        jQuery('#formAltSenha').submit(function() {

            var notify = $.notify('<strong></strong>', {
                type: 'success',
                allow_dismiss: true,
                showProgressbar: false
            });

          //var dados = jQuery(this).serialize();
          var dados = new FormData(document.getElementById("formAltSenha"));  

          jQuery.ajax({
            type: "POST",
            url: "../config/alterarsenha.php",
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