<?php
session_start();
include("template.php");
$bdd = new PDO('mysql:host=localhost;dbname=airline;charset=utf8', 'root', 'root');

$template = new Template('./');
$template->set_filenames(array(
  'body' => 'home.html'
)); ##

/*
session_start();
$_SESSION["login"]=$_POST["login"];
$_SESSION["pass"]=$_POST["pass"];
  
try {$bdd = new PDO('mysql:host=localhost;dbname=airline', 'root', '');}
catch (Exception $e) {die("L'accès à la base de donnée est impossible.");}
  
if(($_SESSION["login"] == "") or($_SESSION['pass'] == "")) {
    echo "veuillez saisir un login et un mot de passe";
}
else {
    $st = $bdd->query("SELECT COUNT(*) FROM administrateur WHERE login='".$_SESSION["login"]."' AND password='".$_SESSION["pass"]."'")->fetch();
    if ($st['COUNT(*)'] == 1)
        header("Location: ModifSite.php");
}
*/

#Use the following log in information  
$identifiant1 = 'admin';
$password1 = 'admin';

$identifiant2 = 'user';
$password2 = 'user';

if (isset($_POST['identifiant']) &&
    isset($_POST['password'])){

  if ($_POST['identifiant'] == $identifiant1 && $_POST['password'] == $password1){
    $_SESSION['logged_in'] = 1;
    header('Location: employee.php');
  }
  else {
    $_SESSION['logged_in'] = 0;
    
  }

  $template->assign_vars(array('message_status' => $_SESSION['logged_in'] == 1? 'You are now logged in':' identifiant or password is wrong'));
  unset($_SESSION['logged_in']);
}

$template->pparse('body');

?>
