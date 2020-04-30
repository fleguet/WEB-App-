<?php
session_start();
include("template.php");
$bdd = new PDO('mysql:host=localhost;dbname=airline;charset=utf8', 'user', 'user');

######################## Set template ########################
$template = new Template('./');
$template->set_filenames(array(
  'body' => 'user_client_registration.html'
));

######################## Add Client ########################
if (isset($_POST['first_name']) &&
    isset($_POST['last_name']) &&
    isset($_POST['address'])
    )
{
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $address = $_POST['address'];
  $result = $bdd->query("INSERT INTO client (first_name, last_name, address)
                         VALUES ('{$first_name}', '{$last_name}', '{$address}')");

  if ($result == true){
    $_SESSION['is_added'] = 1;
  }
  else {
    $_SESSION['is_added'] = 0;
  }
  $template->assign_vars(array('message_status' => $_SESSION['is_added'] == 1?
                               'Your information are well registered':
                               'impossible to register, check your information'));
  unset($_SESSION['is_added']);

}

$template->pparse('body');
?>