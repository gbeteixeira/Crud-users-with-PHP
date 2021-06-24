<?php 
  session_start();
  if(isset($_SESSION['auth']) && $_SESSION['auth'] == true)
  {
    header('Location: ../dashboard');
  }
?>
<!DOCTYPE html>

<html>

  <head>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width">

  <title>Login</title>

  <!--Styles-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <link rel="stylesheet" href="style.css">

  <!--Script-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



  <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">

</head>
<body>
  <!--Div login-->
  <div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <form id="formLogin" class="box">
                    <h1>Login</h1>
                    <p class="text-muted"> Insira seu login e sua senha!</p> 
                    <input type="text" name="email" placeholder="Email"> 
                    <input type="password" name="senha" placeholder="Senha"> 
                    <a class="forgot text-muted" onclick="forgot()">Esqueceu sua senha?</a> 
                    <input type="submit" name="" value="Login" href="#">
                </form>
            </div>
        </div>
    </div>
  </div>

 <script src="../vendor/jquery/jquery.min.js"></script>
 <script src="../js/bootstrap-notify.min.js"></script>
 <script type = "text/javascript" >
      jQuery(document).ready(function() {
        //quando o formulario com id formLogin for tiver seus dados enviados ele executa tudo
        // o que está dentro do bloco
        jQuery('#formLogin').submit(function() {

            //variavel de notificação do bootstrap notify
            var notify = $.notify('<strong></strong>', {
                type: 'warning',
                allow_dismiss: true,
                showProgressbar: false
            });

          //pega todos os dados do formulario
          var dados = jQuery(this).serialize();

          jQuery.ajax({
            type: "POST",
            url: "../config/login.php",
            dataType: 'json',
            data: dados,
            beforeSend: function()
            {  
                //depos que enviado é notificado ao usuario que os dados estão sendo validados
                notify.update('type', 'warning');
                notify.update('message', '<strong>Aguarde...</strong> Validando dados...');

             },
            success: function(response) 
            {
                //caso sucesso na envio do dados faz a veruficação do codigo para saber o que
                //retornar ao usuario
                if (response.codigo == 1) 
                {
                   notify.update('type', 'success');
                   notify.update('message', '<strong>Sucesso!</strong> '+ response.mensagem);
                   setTimeout(function(){ location.href = '../dashboard' }, 3000);
                }

                if (response.codigo == 2) 
                {
                   notify.update('type', 'danger');
                   notify.update('message', '<strong>Ocorreu um erro!</strong> '+response.mensagem);
                }

                if (response.codigo == 3) 
                {
                   notify.update('type', 'danger');
                   notify.update('message', '<strong>Ocorreu um erro!</strong> '+response.mensagem);
                }
            },
            error: function (error) 
            {
                //caso algum erro ocorer

                //console.log(error.responseText);
                //alert(error.responseText);
                notify.update('type', 'danger');
                notify.update('message', '<strong>Ocorreu um erro!</strong> '+error.responseText);
            }

          });

          return false;
        });
      }); 


      function forgot(){
        $('#forgot').modal();
      }


      $('document').ready(function(){
 
        $(".btn-forgot").click(function(){

          //pega o email digitado
          var email = $('#email_forgot').val();
          
          //verifica se o email está vazio
          if(email != "" )
          {

            $.ajax({
              type : 'POST',
              url  : '../config/forgot.php',
              data : {email: email},
              dataType: 'json',
              beforeSend: function()
              { 
                $('.img-snipper').show();
                //depois que enviado é notificado ao usuario que os dados estão sendo validados
              },
              success :  function(response)
              {            
                //caso sucesso na alteração manda verificar o email
                if(response.codigo == 202)
                {
                  $('.resp-forgot').html('Verifique seu e-mail');
                  setTimeout(function()
                  {  
                    setTimeout(function(){location.href="./"} , 2000);  
                  }, 2000);
                }

                //caso email nn encontrado
                if(response.codigo == 404)
                {
                  $('.resp-forgot').html('E-mail não cadastrado');
                }

              },
              //erros desconhecidos
              error: function (request, status, error) 
              {
                 //alert(request.responseText);
                 notify.update('type', 'danger');
                 notify.update('message', 'Erro ao alterar a senha!');
              }

            });

          }
          else
          {
            $('.resp-forgot').html('Informe o email');
          }
        });

       
      });

    </script>
</body>
</html>

<!-- MODAL FORGOT -->
<div class="modal" id="forgot" tabindex="-1" role="dialog" aria-labelledby="smallModalHead" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">                    
            <div class="modal-body">
                <h2 class="tex-center">Recuperação de senha</h2><br>
        <form class="" id="forgot-form" >
          <div class="form-group">
            <input type="email" id="email_forgot" name="email_forgot" class="form-control" placeholder="email..." required="required">
          </div>  
          <div class="resp-forgot pull-left">
            <div class="img-snipper" style="display: none;">
              <img src="img/Double Ring-1.5s-200px.svg" width="50px" alt="">
            </div>
          </div>
          <button type="button" class="btn btn-success btn-forgot pull-right">Recuperar</button>
        </form>
      </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>  
<!-- END MODAL FORGOT -->