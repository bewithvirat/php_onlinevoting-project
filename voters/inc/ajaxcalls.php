<?php 
require_once("../../admin/inc/config.php");

// Set the time zone to Indian Standard Time (IST)
date_default_timezone_set('Asia/Kolkata');

if (isset($_POST['e_id']) && isset($_POST['c_id']) && isset($_POST['v_id'])) {
    // Get the current date and time in IST
    $vote_date = date("Y-m-d");
    $vote_time = date("H:i:s");

    // Insert the vote into the database
    $query = "INSERT INTO votings (election_id, voters_id, candidate_id, vote_date, vote_time) 
              VALUES ('" . mysqli_real_escape_string($db, $_POST['e_id']) . "', 
                      '" . mysqli_real_escape_string($db, $_POST['v_id']) . "', 
                      '" . mysqli_real_escape_string($db, $_POST['c_id']) . "', 
                      '$vote_date', '$vote_time')";

    $result = mysqli_query($db, $query);

    // Check if the query was successful
    if ($result) {
        echo "Vote recorded successfully.";
    } else {
        echo "Failed to record the vote: " . mysqli_error($db);  // Display error if the query fails
    }
} else {
    echo "Invalid input.";
}
?>
