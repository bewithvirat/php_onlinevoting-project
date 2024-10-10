<?php

session_start();
require_once("config.php");

// Check if the session key is set and equals "AdminKey"
if ($_SESSION['key'] != "AdminKey") {
    // Redirect to the homepage or login page if the session key is not valid
    echo "<script>location.assign('logout.php');</script>";
die; // Stop further execution of the script
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Online voting system</title>
    <link rel="stylesheet" href="../asset/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../asset/css/style.css"/>

</head>
<body >
<div class="container-fluid">
    <div class="row bg-black text--black" >
        <div class="col-1 ">
            <img src="../asset/images/th.jfif" width="80px"/>        
        </div>
        <div class="col-11 my-auto">
        <h3>online voting system  ~<small> Welcome <?php echo $_SESSION['username'];?></small></h3>
        </div>
    </div>

