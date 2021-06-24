<?php

function emailContato($email, $nome, $assunto = 'Recuperação de Senha - Crud Usuarios', $msg){
    require 'phpmailer/PHPMailerAutoload.php';


    $mail = new PHPMailer();

    // Define que a mensagem será SMTP
    $mail->IsSMTP();

    // Host do servidor SMTP externo, como o SendGrid.
    $mail->Host = "smtp.gmail.com";

    // Autenticação | True
    $mail->SMTPAuth = true;

    // Tipo de encriptação que será usado na conexão SMTP
    $mail->SMTPSecure = 'ssl';

    // Porta do servidor SMTP
    //$mail->Port = 587; //umbler
    $mail->Port = 465;//gmail

    // Usuário do servidor SMTP
    $mail->Username = '';

    // Senha da caixa postal utilizada
    $mail->Password = '';

    $mail->From = $email;
    $mail->FromName = "Recuperação de Senha";
    $mail->AddAddress($email, 'Recuperação de Senha');
    //$mail->AddCC($email);

    // Define que o e-mail será enviado como HTML | True
    $mail->IsHTML(true);

    // Charset da mensagem (opcional)
    $mail->CharSet = 'utf-8';

    // Assunto da mensagem
    $mail->Subject = utf8_decode($assunto);

    // Conteúdo no corpo da mensagem
    $mail->Body = utf8_decode($msg);

    // Conteúdo no corpo da mensagem(texto plano)
    $mail->AltBody = utf8_decode($msg);

    //Envio da Mensagem
    $enviado = $mail->Send();

    $mail->ClearAllRecipients();

    //if ($enviado) {
      //echo ("<script>window.alert('Enviado com sucesso!');</script>");
      //echo ("<script>window.location.href='./';</script>");
      //return 1;  
      
    //} else {
      //print "<script>alert('Tente novamente mais tarde!');</script>";
      
     // echo "Motivo do erro: " . $mail->ErrorInfo;
    //}

}

?>