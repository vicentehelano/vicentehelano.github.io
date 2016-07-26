
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>UFCA - Coordena&ccedil;&atilde;o do Curso de Engenharia Civil - Demanda 2014.2</title>
 </head>
 <body>

<?php
session_start();
header('Content-type: text/html; charset=utf-8');
$nome = $_SESSION['nome'];
$siape = $_SESSION['siape'];

$s = 'PROFA.';
$_SESSION['Greetings'] = 'Prezado';
$pos = strpos( strtoupper($nome), $s);
if ($pos !== false) {
  $_SESSION['Greetings'] = 'Prezada';
}

echo '<h1>', $_SESSION['Greetings'], ' ', $_SESSION['nome'], ',</h1>';

$days = array('segunda', 'terca', 'quarta', 'quinta', 'sexta');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  foreach ($days as $day) {
    $_SESSION[$day] = array();
    if (isset($_POST[$day])) {
      if (is_array($_POST[$day])) {
        foreach($_POST[$day] as $key => $value){
          $_SESSION[$day][$key] = $value;
        }
      }
    }
  }

  // Grava dados do professor
  $repl =  array('Profa.', 'Prof.', ' ');
  $prefix = str_replace($repl, '', './data/'.$siape.'_'.$nome);
  if (!$handle = fopen($prefix.'-info.xml', 'w')) {
    echo "<br/>Não foi possível salvar seus dados.<br/>Por gentileza, procure o administrador desta página.<br/>";
    exit;
  }

  fwrite($handle, "<Teacher>\n");
  fwrite($handle, "  <Name>".$nome."</Name>\n");
  fwrite($handle, "  <Siape>".$siape."</Siape>\n");

  $setores = $_SESSION['setores'];
  fwrite($handle, "  <Study_Fields>\n");
  foreach ($setores as $setor) {
    fwrite($handle, "    <Study_Field>\n");
    fwrite($handle, "      <Name>".str_replace('-', ' ', $setor)."</Name>\n");
    fwrite($handle, "    </Study_Field>\n");
  }
  fwrite($handle, "  </Study_Fields>\n");

  fwrite($handle, "  <Subjects_List>\n");
  if (isset($_SESSION['obrigatorias'])) {
    $disciplinas = $_SESSION['obrigatorias'];
    foreach ($disciplinas as $disciplina) {
      fwrite($handle, "    <Subject>\n");
      fwrite($handle, "      <Name>".$disciplina."</Name>\n");
      fwrite($handle, "    </Subject>\n");
    }
  }

  if (isset($_SESSION['optativas'])) {
    $disciplinas = $_SESSION['optativas'];
    foreach ($disciplinas as $disciplina) {
      fwrite($handle, "    <Subject>\n");
      fwrite($handle, "      <Name>".$disciplina."</Name>\n");
      fwrite($handle, "    </Subject>\n");
    }
  }
  fwrite($handle, "  </Subjects_List>\n");
  fwrite($handle, "</Teacher>\n\n");
  fclose($handle);

  // Grava restrições de horário do professor
  if (!$handle = fopen($prefix.'-constraints.xml', 'w')) {
    echo "<br/>Não foi possível salvar suas restrições de horário.<br/>Por gentileza, procure o administrador desta página.<br/>";
    exit;
  }

  fwrite($handle, "<ConstraintTeacherNotAvailableTimes>\n");
  fwrite($handle, "  <Weight_Percentage>100</Weight_Percentage>\n");
  fwrite($handle, "  <Teacher>".$nome."</Teacher>\n");
  $count = 0;
  foreach ($days as $day) {
    $count = $count + count($_SESSION[$day]);
  }
  fwrite($handle, "  <Number_of_Not_Available_Times>".$count."</Number_of_Not_Available_Times>\n");
  foreach ($days as $day) {
    foreach ($_SESSION[$day] as $hour) {
      fwrite($handle, "  <Not_Available_Time>\n");
      fwrite($handle, "    <Day>".strtoupper(substr($day, 0, 3))."</Day>\n");
      $h = sprintf("%02d:00", $hour);
      fwrite($handle, "    <Hour>".$h."</Hour>\n");
      fwrite($handle, "  </Not_Available_Time>\n");
    }
  }
  fwrite($handle, "</ConstraintTeacherNotAvailableTimes>");
  fclose($fhandle);

  header('Location: thanks.php');
}
?>

<p>Selecione os horários nos quais você estará INDISPONÍVEL para ministrar disciplinas em 2014.2.<br/>
Aproveito para lembrar que as disciplinas obrigatórias devem sempre ser ofertadas pela manhã ou pela tarde, pois o curso é diurno.</p>
<p>Obs.: a coluna "Horário" indica a hora de início da aula. Ao marcar "12:00" na coluna "Segunda", por exemplo, quer dizer que você não estará disponível de 12h às 13h das segundas-feiras.</p>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<table width="50%" style="text-align: center;" border="1">
<thead>
<tr>
  <th>Horário</th>
  <th>Segunda</th>
  <th>Terça</th>
  <th>Quarta</th>
  <th>Quinta</th>
  <th>Sexta</th>
</tr>
</thead>
<tbody>
<?php
for ($i = 8; $i < 22; ++$i) {
echo '<tr>';
printf('  <td>%02d:00</td>', $i);
echo '  <td><input type="checkbox" name="segunda[]" value="'.$i.'" /></td>';
echo '  <td><input type="checkbox" name="terca[]" value="'.$i.'" /></td>';
echo '  <td><input type="checkbox" name="quarta[]" value="'.$i.'" /></td>';
echo '  <td><input type="checkbox" name="quinta[]" value="'.$i.'" /></td>';
echo '  <td><input type="checkbox" name="sexta[]" value="'.$i.'" /></td>';
echo '</tr>';
}
?>
</tbody>
</table><br/>
<input type="submit" value="Enviar" />
</form>
</body>
</html>
