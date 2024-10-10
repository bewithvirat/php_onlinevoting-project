<?php 
require_once("inc/header.php");
require_once("inc/navigation.php");

if(isset($_GET['homepage'])){
    require_once("inc/homepage.php"); 
}

 else if(isset($_GET['addelectionpage'])){
    require_once("inc/addelection.php");
}

else if(isset($_GET['addcandidatepage'])){
    require_once("inc/addcandidate.php");
} else if(isset($_GET['viewresult'])){
    require_once("inc/viewresult.php");
}else if(isset($_['edit'])){
    require_once("inc/edit.php");
}
?>

<?php 
require_once("inc/footer.php");


?>

