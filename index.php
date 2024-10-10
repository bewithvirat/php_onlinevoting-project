<?php 
require_once("admin/inc/config.php");

if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

$fetchingelections = mysqli_query($db, "SELECT * FROM elections") or die("Error fetching elections: " . mysqli_error($db));

while ($data = mysqli_fetch_assoc($fetchingelections)) {
    $starting_date = $data['starting_date'];
    $ending_date = $data['ending_date'];
    $curr_date = date("Y-m-d");
    $election_id = $data['id'];
    $status = $data['status'];

    $currentDate = new DateTime($curr_date);
    $startDate = new DateTime($starting_date);
    $endDate = new DateTime($ending_date);

    if ($currentDate > $endDate) {
        if ($status != 'Expired') {
            $stmt = $db->prepare("UPDATE elections SET status = 'Expired' WHERE id = ?");
            $stmt->bind_param("i", $election_id);
            $stmt->execute();
            $stmt->close();
        }
    } else if ($currentDate >= $startDate && $currentDate <= $endDate && $status == "Inactive") {
        $stmt = $db->prepare("UPDATE elections SET status = 'Active' WHERE id = ?");
        $stmt->bind_param("i", $election_id);
        $stmt->execute();
        $stmt->close();
    }

    $updatedStatusResult = mysqli_query($db, "SELECT status FROM elections WHERE id = '$election_id'");
    if ($updatedStatusResult) {
        $updatedStatusData = mysqli_fetch_assoc($updatedStatusResult);
        echo $updatedStatusData['status'] . "<br><br>";
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Online-voting</title>
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/css/login.css">
    <link rel="stylesheet" href="asset/css/style.css">
</head>
<body>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="asset/images/logo.jfif" class="brand_logo" alt="Logo">
                    </div>
                </div>
                <?php
                if(isset($_GET['sign-up'])) { ?>
                    <div class="d-flex justify-content-center form_container">
                        <form method="POST" action="index.php?sign-up=1">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="su_username" class="form-control input_user" placeholder="username *" required/>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="text" name="su_contact_no" class="form-control input_pass" placeholder="Contact *" required/>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="su_password" class="form-control input_pass" placeholder="Password *" required/>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="su_retype_password" class="form-control input_pass" placeholder="ReType Password *" required/>
                            </div>
                            <div class="d-flex justify-content-center mt-3 login_container">
                                <button type="submit" name="sign_up_btn" class="btn login_btn">Sign Up</button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-center links text-white">
                            Already created account <a href="index.php" class="ml-2">Sign In</a>
                        </div><br>
                    </div>
                <?php
                } else {
                ?>
                    <div class="d-flex justify-content-center form_container">
                        <form method="POST" action="index.php">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="contact_no" class="form-control input_user" placeholder="Contact No" required/>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control input_pass" placeholder="password" required/>
                            </div>
                          
                            <div class="d-flex justify-content-center mt-3 login_container">
                                <button type="submit" name="loginbtn" class="btn login_btn">Login</button>
                            </div>
                        </form> 
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-center links text-white">
                            Don't have an account? <a href="?sign-up=1" class="ml-2">Sign Up</a>
                        </div><br>
                        <div class="d-flex justify-content-center links">
                            <a href="#">Forgot your password?</a>
                        </div>
                    </div>
                <?php
                }
                ?>

				<?php   
				
				if(isset($_GET['registered'])){
					?>
					<span class="bg-white text-sucess text-center my-3">your account has been created</span>
					<?php
				}else if(isset($_GET['invalid'])){
					?>
					<span class="bg-white text-danger text-center my-3">password wrong try again!</span>

					<?php
				}
				else if(isset($_GET['not_registered'])){
					?>
					<span class="bg-white text-warning text-center my-3">sorry dost you are not registered</span>
					<?php
				}
				else if(isset($_GET['invalid_acess'])){
					?>
					<span class="bg-white text-danger text-center my-3">invalid username or password</span>
					<?php
				}

				?>
            </div>
        </div>
    </div>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/jquery.min.js"></script>
</body>
</html>

<?php
require_once("admin/inc/config.php");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['sign_up_btn'])){
    $su_username = mysqli_real_escape_string($db, $_POST['su_username']);
    $su_contact_no = mysqli_real_escape_string($db, $_POST['su_contact_no']);
    $su_password = mysqli_real_escape_string($db, sha1($_POST['su_password']));
    $su_retype_password = mysqli_real_escape_string($db, sha1($_POST['su_retype_password']));
    $user_role="voter";
    if($su_password == $su_retype_password) {
        mysqli_query($db,"INSERT INTO users(username, contact_no, password, user_role) VALUES('$su_username', '$su_contact_no', '$su_password', '$user_role')") or die(mysqli_error($db));
        ?>
        <script>
        location.assign("index.php?sign-up=1&registered=1");
        </script>
        <?php
    } else {
        ?>
        <script>
            location.assign("index.php?sign-up=1&invalid=1");
        </script>
        <?php
    }
} elseif(isset($_POST['loginbtn'])){
    session_start(); // Start session here at the top of the script
    $contact_no = mysqli_real_escape_string($db, $_POST['contact_no']);
    $password = mysqli_real_escape_string($db, sha1($_POST['password']));
    
    $fetchingdata = mysqli_query($db, "SELECT * FROM users WHERE contact_no='$contact_no'") or die(mysqli_error($db));

    if(mysqli_num_rows($fetchingdata) > 0)
    {
        $data = mysqli_fetch_assoc($fetchingdata);
        if($password == $data['password']) {
            $_SESSION['user_role'] = $data['user_role'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['user_id'] = $data['id'];
            if($data['user_role'] == "Admin"){
                $_SESSION['key']="AdminKey";
                ?>
                <script>location.assign("admin/index2.php?homepage=1");</script>
                <?php
            } else {
                $_SESSION['key']="VoterKey";
                ?>
                <script>location.assign("voters/index1.php");</script>    
                <?php
            }
        } else {
            ?>
            <script>location.assign("index.php?invalid_acess=1");</script>
            <?php
        }
    } else {
        ?>
        <script>location.assign("index.php?sign-up=1&not_registered=1");</script>
        <?php
    }
}
?>
