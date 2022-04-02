<?php
require "include/index.php";
$mistnost = getMistnost();
?>


<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<!-- Bootstrap-->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<title>Karta místnosti č. <?php echo $mistnost->cislo; ?></title>
</head>

<body class="container">

<h1>Místnost č. <?php echo $mistnost->cislo; ?></h1>

<dl class='dl-horizontal'>

<?php

echo vypisZakladniUdajeMistnost($mistnost);
echo "<dt>Lidé</dt>";
echo vypisLidiMistnost($mistnost->lide);
echo "<dt>Klíče</dt>";
echo vypisLidiMistnost($mistnost->klice);

?>

</dl>

<a href='seznam_mistnosti.php'>
<span class='glyphicon glyphicon-arrow-left' aria-hidden='true'></span>
Zpět na seznam místností
</a>

</body>
</html>
