<?php
session_start();
include("template.php");
$bdd = new PDO('mysql:host=localhost;dbname=airline;charset=utf8', 'root', 'root');

######################## Set template ########################
$template = new Template('./');
$template->set_filenames(array(
  'body' => 'client.html'
));

######################## Delete Client ########################
if (isset($_GET['action'])){
  if ($_GET['action'] == 'delete'){
    $id = $_GET['id'];
    $result = $bdd->query("DELETE FROM client
                           WHERE id = '{$id}'");
    if ($result == true){
      $_SESSION['is_deleted'] = 1;
    }
    else {
      $_SESSION['is_deleted'] = 0;
    }
    $template->assign_vars(array('message_status' => $_SESSION['is_deleted'] == 1?
                                 'The client has been succesfully deleted':
                                 'The client cannot be deleted'));
    unset($_SESSION['is_deleted']);
  }
}

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
                               'The client has been succesfully added':
                               'The client cannot be added'));
  unset($_SESSION['is_added']);

}

######################## Read the table client and show it ########################
$result = $bdd->query('SELECT *
                       FROM client
                       ORDER BY id');
while ($row = $result->fetch())
{
  $template->assign_block_vars('client',array(
    'id' => $row['id'],
    'first_name' => $row['first_name'],
    'last_name'  => $row['last_name'],
    'address' => $row['address']
  ));
}

######################## Parse to html ########################
$template->pparse('body');
?>
