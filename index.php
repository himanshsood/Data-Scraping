<?php
require_once 'actions.php';
$jobs = array_reverse($_SESSION['jobs']);
?>
<?php include 'views/header.php'; ?>
<?php include 'views/job_form.php'; ?>
<?php include 'views/job_history.php'; ?>
<?php include 'views/controls.php'; ?>
