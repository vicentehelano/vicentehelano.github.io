<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>UFCA - Coordena&ccedil;&atilde;o do Curso de Engenharia Civil - Demanda 2014.2</title>
  
  <style>.error {color: #FF0000;}</style>
 </head>
 <body>

<?php
session_start();
header('Content-type: text/html; charset=utf-8');
$nome = $_SESSION['nome'];

$s = 'PROFA.';
$_SESSION['Greetings'] = 'Prezado';
$pos = strpos( strtoupper($nome), $s);
if ($pos !== false) {
  $_SESSION['Greetings'] = 'Prezada';
}

echo '<h1> Muito Obrigado, ', $_SESSION['nome'], '!</h1>';
?>
</body>
</html>
