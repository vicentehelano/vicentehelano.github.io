<html>
 <head>
  <title>UFCA - Coordena&ccedil;&atilde;o do Curso de Engenharia Civil - Demanda 2014.2</title>

  <style>
    .error {color: #FF0000;}
  </style>
 </head>
 <body>

<?php
session_start();
header('Content-type: text/html; charset=utf-8');

$siape = '';
$siapeErr = "Campo obrigat&oacute;rio";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["siape"])) {
    $siapeErr = "Campo obrigat&oacute;rio";
  } else {
    $siape = check_siape($_POST['siape']);
    // verifica se o SIAPE informado contém apenas números
    if (!preg_match('/^[1-9][0-9]{6,6}$/',$siape)) {
      $siapeErr = 'Matrícula SIAPE inválida.<br>Lembre-se: sua matrícula SIAPE possui 7 dígitos (e.g., 1990765).';
    } else {
      $hasErrors = 'No';
    }
  }

  if ($hasErrors == 'No') {
    $_SESSION['siape'] = $siape;
    $professores = simplexml_load_file('./data/professores.xml');
    $p = new stdClass();
    foreach ($professores->Teacher as $p) {
      if ($p->Siape == $siape) {
        $_SESSION['nome'] = (string) $p->Name[0];
        header('Location: setor-de-estudo.php');
      }
    }
    $siapeErr = 'Matrícula não encontrada.';
  }
}

function check_siape($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
 ?>

<h2>Universidade Federal do Cariri<br>
Campus de Juazeiro do Norte<br>
Coordenação do Curso de Engenharia Civil</h2>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
Por gentileza, informe seu n&uacute;mero SIAPE: <input type="text" name="siape" value="<?php echo $siape;?>">
<span class="error">* <?php echo $siapeErr;?></span>
<br>
<input value="Avan&ccedil;ar" type="submit">
</form>

 </body>
</html>
