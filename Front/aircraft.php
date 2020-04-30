<?php
session_start();
include("template.php");
$bdd = new PDO('mysql:host=localhost;dbname=airline;charset=utf8', 'root', 'root');

######################## Set template ########################
$template = new Template('./');
$template->set_filenames(array(
  'body' => 'aircraft.html'
));

######################## Delete Aircraft ########################

if (isset($_GET['action'])){
  if ($_GET['action'] == 'delete'){
    $registration_number = $_GET['registration_number'];
    $result = $bdd->query("DELETE FROM aircraft
                           WHERE registration_number = '{$registration_number}'");
    if ($result == true){
      $_SESSION['is_deleted'] = 1;
    }
    else {
      $_SESSION['is_deleted'] = 0;
    }
    $template->assign_vars(array('message_status' => $_SESSION['is_deleted'] == 1?
                                 'The aircraft has been succesfully deleted':
                                 'The aircraft cannot be deleted'));
    unset($_SESSION['is_deleted']);
  }
}

######################## Add Aircraft ########################

if (isset($_POST['registration_number']) &&
    isset($_POST['type'])
    )
{
  $registration_number = $_POST['registration_number'];
  $type = $_POST['type'];
  $result = $bdd->query("INSERT INTO aircraft
                         VALUES ('{$registration_number}', '{$type}')");

  if ($result == true){
    $_SESSION['is_added'] = 1;
  }
  else {
    $_SESSION['is_added'] = 0;
  }
  $template->assign_vars(array('message_status' => $_SESSION['is_added'] == 1?
                               'The aircraft has been succesfully added':
                               'The aircraft cannot be added'));
  unset($_SESSION['is_added']);

}

######################## Read the table aircraft and show it ########################

$result = $bdd->query('SELECT *
                       FROM aircraft
                       ');
while ($row = $result->fetch())
{
  $template->assign_block_vars('aircraft',array(
    'registration_number' => $row['registration_number'],
    'type' => $row['type']
  ));
}

######################## Parse to html ########################
$template->pparse('body');
?>
