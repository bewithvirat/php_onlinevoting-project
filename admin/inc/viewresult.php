<?php 
$election_id=$_GET['viewresult'];
?>

<div class="row my-3" >
    <div class="col-12">
        <h2>Election Result</h2>
        <?php 
        $fetchingActiveElections = mysqli_query($db, "SELECT * FROM elections WHERE id='".$election_id."'") or die(mysqli_error($db));

        if(mysqli_num_rows($fetchingActiveElections) > 0)
        {
            while($data =mysqli_fetch_assoc($fetchingActiveElections)){
                $electoin_id = $data['id'];
                $election_topic = $data['election_topic'];
                ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-white" style="background-color:rgb(8, 176, 214);">
                                <h5>Election Topic : --> <?php echo strtoupper($election_topic); ?></h5>
                            <tr>
                                <th>Photo</th>
                                <th>Candidate Details</th>
                                <th>Number of Votes</th>
                            </tr>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php  
                    $fetchingCandidates = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id ='".$electoin_id."'") or die(mysqli_error($db));

                    while ($candidatedata = mysqli_fetch_array($fetchingCandidates)) {
                        $candidate_id = $candidatedata['id'];
                        $candidate_photo = $candidatedata['candidate_photo'];

                        // Fetch the number of votes for this candidate
                        $fetchingVotes = mysqli_query($db, "SELECT * FROM votings WHERE candidate_id= '".$candidate_id."'") or die(mysqli_error($db));
                        $totalVotes = mysqli_num_rows($fetchingVotes);  // Correctly count votes

                    ?>
                    <tr>
                        <td><img src="<?php echo $candidate_photo; ?>" style="width: 80px; height: 80px; border: 2px solid green; border-radius: 50%; object-fit: cover;" /></td>
                        <td>
                            <b><?php echo $candidatedata['candidate_name']; ?></b><br>
                            <?php echo $candidatedata['candidate_details']; ?>
                        </td>
                        <td><?php echo $totalVotes; ?></td> <!-- Displaying the correct number of votes -->
                    </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            <?php
            }
        } else {
            echo "No active elections";
        }
        ?>
        <hr>
        <h3>Voting Details</h3>
        <?php
        $fetchingVoteDetails = mysqli_query($db, "SELECT * FROM votings WHERE election_id='".$election_id."'") or die(mysqli_error($db));
        $numberOfVotes = mysqli_num_rows($fetchingVoteDetails);

        if($numberOfVotes > 0){
            $sno = 1;
        ?>
        <table class="table">
            <tr>
                <td>Sno.</td>
                <td>Voter Name</td>
                <td>Contact No</td>
                <td>Voted to</td>
                <td>Date</td>
                <td>Time</td>
            </tr>

        <?php
            while($data = mysqli_fetch_assoc($fetchingVoteDetails)){
                $voters_id = $data['voters_id'];
                $candidate_id = $data['candidate_id'];

                // Fetch voter details
                $fetchVoterName = mysqli_query($db, "SELECT * FROM users WHERE id = '".$voters_id."'") or die(mysqli_error($db));
                $isDataAvail = mysqli_num_rows($fetchVoterName);
                $userData = mysqli_fetch_assoc($fetchVoterName);
                if($isDataAvail > 0){
                    $username = $userData['username'];
                    $contact_no = $userData['contact_no'];
                } else {
                    $username = "No Data";
                    $contact_no = "No Data";
                }

                // Fetch candidate name
                $fetchCandidateName = mysqli_query($db, "SELECT * FROM candidate_details WHERE id = '".$candidate_id."'") or die(mysqli_error($db));
                $isDataAvail = mysqli_num_rows($fetchCandidateName);
                $candidateData = mysqli_fetch_assoc($fetchCandidateName);
                if($isDataAvail > 0){
                    $candidate_name = $candidateData['candidate_name'];
                } else {
                    $candidate_name = "No Data";
                }
        ?>
                <tr>
                    <td><?php echo $sno++; ?></td>
                    <td><?php echo $username; ?></td>
                    <td><?php echo $contact_no; ?></td>
                    <td><?php echo $candidate_name; ?></td>
                    <td><?php echo $data['vote_date']; ?></td>
                    <td><?php echo $data['vote_time']; ?></td>
                </tr>
        <?php
            }
            echo "</table>";
        } else {
            echo "No voting details available.";
        }
        ?>
    </div>
</div>
