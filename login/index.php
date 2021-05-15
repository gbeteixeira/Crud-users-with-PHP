<?php 
  session_start();
  if(isset($_SESSION['auth']) && $_SESSION['auth'] == true)
  {
    header('Location: ../');
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
                    <a class="forgot text-muted" href="#">Esqueceu sua senha?</a> 
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
                   setTimeout(function(){ location.href = '../' }, 3000);
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
    </script>
</body>

</html>