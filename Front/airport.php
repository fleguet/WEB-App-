<?php
session_start();
include("template.php");
$bdd = new PDO('mysql:host=localhost;dbname=airline;charset=utf8', 'root', 'root');

######################## Set template ########################
$template = new Template('./');
$template->set_filenames(array(
  'body' => 'airport.html'
));

######################## Delete Airport ########################
if (isset($_GET['action'])){
  if ($_GET['action'] == 'delete'){
    $code = $_GET['code'];
    $result = $bdd->query("DELETE FROM airport
                           WHERE code = '{$code}'");
    if ($result == true){
      $_SESSION['is_deleted'] = 1;
    }
    else {
      $_SESSION['is_deleted'] = 0;
    }
    $template->assign_vars(array('message_status' => $_SESSION['is_deleted'] == 1?
                                 'The airport has been succesfully deleted':
                                 'The airport cannot be deleted'));
    unset($_SESSION['is_deleted']);
  }
}

######################## Add Airport ########################
if (isset($_POST['code']) &&
    isset($_POST['name'])
    )
{
  $code = $_POST['code'];
  $name = $_POST['name'];
  $result = $bdd->query("INSERT INTO airport
                         VALUES ('{$code}', '{$name}')");

  if ($result == true){
    $_SESSION['is_added'] = 1;
  }
  else {
    $_SESSION['is_added'] = 0;
  }
  $template->assign_vars(array('message_status' => $_SESSION['is_added'] == 1?
                               'The airport has been succesfully added':
                               'The airport cannot be added'));
  unset($_SESSION['is_added']);

}

######################## Read the table airport and show it ########################
$result = $bdd->query('SELECT *
                       FROM airport
                       ');
while ($row = $result->fetch())
{
  $template->assign_block_vars('airport',array(
    'code' => $row['code'],
    'name' => $row['name']
  ));
}

######################## Parse to html ########################
$template->pparse('body');
?>
