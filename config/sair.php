<?php
  
  //verifica se o parametro de logout está sendo passado na url
  if(isset($_GET['logout'])) {
    //destroi a sessão
    session_destroy();
    // redireciona o usuario para a pagina de login
    header('location:../login/');
  }

?>