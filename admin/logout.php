<?php

session_start();

session_destroy();
session_unset();

echo "<script>location.assign('../index.php');</script>";
?>