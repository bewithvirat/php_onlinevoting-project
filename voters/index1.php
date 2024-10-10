<?php
require_once("inc/header.php");
require_once("inc/navigation.php");
?>

<div class="row my-3">
    <div class="col-12">
        <h2>Voters Panel</h2>
        <?php 
        // Fetch active elections
        $fetchingActiveElections = mysqli_query($db, "SELECT * FROM elections WHERE status='Active'") or die(mysqli_error($db));

        if (mysqli_num_rows($fetchingActiveElections) > 0) {
            while ($data = mysqli_fetch_assoc($fetchingActiveElections)) {
                $election_id = $data['id'];
                $election_topic = $data['election_topic'];
                ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th colspan="4" class="text-white" style="background-color:rgb(8, 176, 214);">
                                    <h5>Election Topic: <?php echo strtoupper($election_topic); ?></h5>
                                </th>
                            </tr>
                            <tr>
                                <th>Photo</th>
                                <th>Candidate Details</th>
                                <th>Number of Votes</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>     
                            
                         <?php 
                    // Fetch candidates for this election
                    $fetchingCandidates = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id = '".$election_id."'") or die(mysqli_error($db));

                    // Debugging: Output the election ID and number of candidates
                    echo "Election ID: " . $election_id . "<br>";
                    echo "Number of candidates: " . mysqli_num_rows($fetchingCandidates) . "<br>";

                    if (mysqli_num_rows($fetchingCandidates) > 0) {
                        while ($candidatedata = mysqli_fetch_array($fetchingCandidates)) {
                            $candidate_id = $candidatedata['id'];
                            $candidate_photo = $candidatedata['candidate_photo'];
                         
                            // Fetch the total number of votes for each candidate
                            $fetchingVotes = mysqli_query($db, "SELECT * FROM votings WHERE candidate_id= '".$candidate_id."'") or die(mysqli_error($db));
                            $totalVotes = mysqli_num_rows($fetchingVotes);

                            // Output user session to check voter ID
                            echo $_SESSION['user_id'];

                            // Check if the voter has already voted in this election
                            ?>

<tr>
    <td><img src="<?php echo $candidate_photo; ?>" class="img-fluid" style="width: 80px; height: 80px; border: 2px solid green; border-radius: 50%; object-fit: cover;" /></td>
    <td>
        <b><?php echo $candidatedata['candidate_name']; ?></b><br>
        <?php echo $candidatedata['candidate_details']; ?>
    </td>
    <td><?php echo $totalVotes; ?></td>
    <td>
        <?php
        // Check if the voter has already cast a vote for this election
        $checkIfVoteCasted = mysqli_query($db, "SELECT * FROM votings WHERE voters_id = '".$_SESSION['user_id']."' AND election_id = '".$election_id."'") or die(mysqli_error($db));
        $isVotedCasted = mysqli_num_rows($checkIfVoteCasted);

        if($isVotedCasted > 0) {
            $voteCastdata = mysqli_fetch_assoc($checkIfVoteCasted);
            $voteCastedtoCandidate = $voteCastdata['candidate_id'];

            if ($voteCastedtoCandidate == $candidate_id) {
                // If voter has voted for this candidate, display a confirmation icon or image
                ?>
                <img src="../asset/images/th.jpg" class="img-fluid" style="width: 80px; height: 80px; border: 2px solid green; border-radius: 50%; object-fit: cover;" />
                <?php
            }
        } else {
            // If voter has not yet voted, display the vote button
            ?>
            <button class="btn btn-md btn-success" 
                onclick="CastVote(<?php echo $election_id; ?>, 
                <?php echo $candidate_id; ?>, 
                <?php echo $_SESSION['user_id']; ?>)">
                Vote
            </button>
        <?php 
        }  
        ?>         
    </td>
</tr>
        <?php
                        }
                    } else {
                        // If no candidates are available for this election
                        echo "<tr><td colspan='4'>No candidates available for this election.</td></tr>";
                    }
              ?>                                            
                     </tbody>
                    </table>
                </div>
           <?php
                        }
                    } else {
                        // If no active elections are found
                        echo "No active elections";
                    }
            
        ?>
    </div>
</div>

<script> 
const CastVote = (election_id, candidate_id, voters_id) => {
    $.ajax({
        type: "POST",
        url: "inc/ajaxcalls.php",
        data: {
            e_id: election_id,
            c_id: candidate_id,
            v_id: voters_id
        },
        success: function(response) {
            if (response.trim() === "Vote recorded successfully.") {
                location.assign("index1.php?voteCaasted=1");
            } else {
                location.assign("index1.php?voteNotCaasted=1");
            }
        },
        error: function(xhr, status, error) {
            // Display error details in the console
            console.log("Error: " + error);
        }
    });
}
</script>

<?php
require_once("inc/footer.php");
?>
