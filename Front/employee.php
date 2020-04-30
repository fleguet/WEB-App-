<?php
session_start();
include("template.php");
$bdd = new PDO('mysql:host=localhost;dbname=airline;charset=utf8', 'root', 'root');

######################## Set template ########################
$template = new Template('./');
$template->set_filenames(array(
  'body' => 'employee.html'
));

######################## Delete Employee ########################
if (isset($_GET['action'])){
  if ($_GET['action'] == 'delete'){
    $ssn = $_GET['ssn'];
    $result = $bdd->query("DELETE FROM employee
                           WHERE ssn = '{$ssn}'");
    if ($result == true){
      $_SESSION['is_deleted'] = 1;
    }
    else {
      $_SESSION['is_deleted'] = 0;
    }
    $template->assign_vars(array('message_status' => $_SESSION['is_deleted'] == 1?
                                 'The employee has been succesfully deleted':
                                 'The employee cannot be deleted'));
    unset($_SESSION['is_deleted']);
  }
}

######################## Add Employee ########################
if (isset($_POST['ssn']) &&
    isset($_POST['first_name']) &&
    isset($_POST['last_name']) &&
    isset($_POST['address']) &&
    isset($_POST['salary'])
    )
{
  $ssn = $_POST['ssn'];
  $last_name = $_POST['last_name'];
  $first_name = $_POST['first_name'];
  $address = $_POST['address'];
  $salary = $_POST['salary'];
  $result = $bdd->query("INSERT INTO employee
                         VALUES ('{$ssn}', '{$first_name}', '{$last_name}', '{$address}', '{$salary}')");

  if ($result == true){
    $_SESSION['is_added'] = 1;
  }
  else {
    $_SESSION['is_added'] = 0;
  }
  $template->assign_vars(array('message_status' => $_SESSION['is_added'] == 1?
                               'The employee has been succesfully added':
                               'The employee cannot be added'));
  unset($_SESSION['is_added']);

}

######################## Read the table employee and show it ########################
$result = $bdd->query('SELECT *
                       FROM employee
                       ORDER BY salary desc');
while ($row = $result->fetch())
{
  $template->assign_block_vars('employee',array(
    'ssn' => $row['ssn'],
    'first_name' => $row['first_name'],
    'last_name'  => $row['last_name'],
    'address' => $row['address'],
    'salary' => $row['salary']
  ));
}

######################## Parse to html ########################
$template->pparse('body');
?>
