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
  echo '<h1>', $_SESSION['Greetings'], ' ', $_SESSION['nome'], ',</h1>';
?>

<p>Selecione as disciplinas com as quais você possui maior afinidade.<br>
Buscaremos, sempre que possível, convidá-lo para ministrar apenas as disciplinas selecionadas abaixo, respeitando os limites inferior e superior de sua carga-horária de sala de aula.</p>


<?php

$setores = $_SESSION['setores'];
$nome = $_SESSION['nome'];
$siape = $_SESSION['siape'];

// Exibe uma lista das disciplinas no setor especificado
// e retorna o número de disciplinas encontradas.
function exibe_disciplinas($setor, $fname, $tag)
{
  $row = 1;
  $count = 0;
  $handle = fopen ($fname,'r');
  $pattern = str_replace('-', " ", $setor);
  while (($d = fgetcsv($handle, 1000, ",")) !== FALSE) {
    if ($d[4] === $pattern) {
      echo '<input type="checkbox" name="'.$tag.'[]" value="'.$d[0].' - '.$d[1].'" /> ';
      echo $d[0], ' - ', $d[1], "<br />\n";
      ++$count;
    }
    $row++;
  }
  fclose ($handle);
  return $count;
}

$disciplinasErr = "Selecione ao menos uma disciplina obrigatória.";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['obrigatorias'])) { // precisamos ter ao menos uma obrigatória selecionada.
    if (is_array($_POST['obrigatorias'])) {
      $_SESSION['obrigatorias'] = array();
      foreach($_POST['obrigatorias'] as $key => $value) {
        $_SESSION['obrigatorias'][$key] = $value;
      }

      if (isset($_POST['optativas'])) { // armazena as disciplinas optativas
        if (is_array($_POST['optativas'])) {
          $_SESSION['optativas'] = array();
          foreach($_POST['optativas'] as $key => $value) {
            $_SESSION['optativas'][$key] = $value;
          }
        }
      }
      header('Location: disponibilidade.php');
    } else {
      $disciplinasErr = "Nenhuma disciplina obrigatória foi selecionada. Por gentileza, selecione ao menos uma disciplina obrigatória.";
    }
  } else {
    $disciplinasErr = "Nenhuma disciplina obrigatória foi selecionada. Por gentileza, selecione ao menos uma disciplina obrigatória.";
  }
}

?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

<h2>Disciplinas Obrigatórias de 2014.2</h2>
<span class="error">* <?php echo $disciplinasErr;?><br/><br/></span>
<?php
$n = 0;
foreach ($setores as $setor) {
  $n = $n + exibe_disciplinas($setor, './data/disciplinas-obrigatorias-2014-2.csv', 'obrigatorias');
}
if ($n == 0) {
  echo '<p>Não há disciplinas obrigatórias no setor de estudo selecionado.</p>';
}

echo '<h2>Demais Disciplinas Obrigatórias</h2>';
$n = 0;
foreach ($setores as $setor) {
  $n = $n + exibe_disciplinas($setor, './data/disciplinas-obrigatorias-demais.csv', 'obrigatorias');
}
if ($n == 0) {
  echo '<p>Não há outras disciplinas obrigatórias no setor de estudo selecionado.</p>';
}

echo '<h2>Disciplinas Optativas</h2>';
$n = 0;
foreach ($setores as $setor) {
  $n = $n + exibe_disciplinas($setor, './data/disciplinas-optativas.csv', 'optativas');
}
if ($n == 0) {
  echo '<p>Não há disciplinas optativas no setor de estudo selecionado.</p>';
}

?>
  <br />
  <input type="submit" value="Pr&oacute;ximo" />
</form>

</body>
</html>
