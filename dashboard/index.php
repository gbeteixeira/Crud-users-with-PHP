<?php
  //verificacao se tem uma sessao inciada
  session_cache_expire(60);
  session_start();

  if(!isset($_SESSION['auth']) || $_SESSION['auth'] != true)
  {
    header('Location: ../login/');
  }


  switch ($_SESSION['sess_tipo_user']) 
  {
  	case 1:
  		include 'view/indexadmin.php';
  		break;
  	
  	default:
  		include 'view/indexuser.php';
  		break;
  }
?>