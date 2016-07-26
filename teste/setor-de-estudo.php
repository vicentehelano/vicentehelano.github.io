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

echo '<h1>', $_SESSION['Greetings'], ' ', $_SESSION['nome'], ',</h1>';

$setoresErr = "Selecione ao menos um setor de estudo.";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['setores'])) {
    if (is_array($_POST['setores'])) {
      $_SESSION['setores'] = array();
      foreach($_POST['setores'] as $key => $value){
        $_SESSION['setores'][$key] = $value;
      }
      header('Location: disciplinas.php');
    } else {
      $setoresErr = "Nenhum setor de estudo foi selecionado. Por gentileza, selecione ao menos um setor de estudo.";
    }
  } else {
    $setoresErr = "Nenhum setor de estudo foi selecionado. Por gentileza, selecione ao menos um setor de estudo.";
  }
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <p>Precisamos conhecer o seu Setor de Estudo para que possamos listar as disciplinas que você poderá lecionar em 2014.2.<br>
    Lembro que estamos reenquadrando os professores em suas áreas de especialidade, o que nos ajudará a identificar as áreas mais carentes de nosso quadro docente atual.<br>
    Portanto, indique seu novo Setor de Estudo <b>por afinidade</b>.</p>
    <input type="checkbox" name="setores[]" value="COMPUTACAO" />Computação<br>
    <input type="checkbox" name="setores[]" value="ESTATISTICA" />Estatística<br>
    <input type="checkbox" name="setores[]" value="MATEMATICA" />Matemática<br>
    <input type="checkbox" name="setores[]" value="FISICA" />Física<br>
    <input type="checkbox" name="setores[]" value="QUIMICA" />Química<br>
    <input type="checkbox" name="setores[]" value="BASICO-DA-AREA" />Conteúdos Básicos da Área (e.g., Desenho, Topografia)<br>
    <input type="checkbox" name="setores[]" value="BASICO-DE-OUTRAS-AREAS" />Conteúdos Básicos de Outras Áreas (e.g., Economia, Administração)<br>
    <input type="checkbox" name="setores[]" value="CONSTRUCAO-CIVIL" />Construção Civil<br>
    <input type="checkbox" name="setores[]" value="ESTRUTURAS" />Estruturas<br>
    <input type="checkbox" name="setores[]" value="GEOTECNIA" />Geotecnia<br>
    <input type="checkbox" name="setores[]" value="RECURSOS-HIDRICOS-E-SANEAMENTO" />Recursos Hídricos e Saneamento<br>
    <input type="checkbox" name="setores[]" value="TRANSPORTES" />Transportes<br><br>
    <span class="error">* <?php echo $setoresErr;?><br/><br/></span>
    <input type="submit" value="Pr&oacute;ximo" />
</form>
</body>
</html>
