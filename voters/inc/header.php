

<?php
// Start the session
session_start();

// Include the configuration file to connect to the database
require_once("../admin/inc/config.php");

// Check if the session key is set and equals "VoterKey"
if (!isset($_SESSION['key']) || $_SESSION['key'] != "VoterKey") {
    // If the session key is not set or invalid, redirect to logout
    header("Location: ../admin/inc/logout.php");
    exit(); // Use exit to properly terminate the script
}

// Your protected page code continues below...
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Panel Online voting system</title>
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

