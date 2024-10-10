<?php
    // Handling success or deletion messages
    if (isset($_GET['added'])) {
?>
        <div class="alert alert-success my-3" role="alert">
            Election has been added successfully :)
        </div>
<?php
    } else if (isset($_GET['updated'])) {
?>
        <div class="alert alert-success my-3" role="alert">
            Election has been updated successfully :)
        </div>
<?php
    } else if (isset($_GET['delete_id'])) {
        $delete_id = mysqli_real_escape_string($db, $_GET['delete_id']);

        // Correct DELETE query syntax
        mysqli_query($db, "DELETE FROM elections WHERE id = '$delete_id'") or die(mysqli_error($db));
?>
        <div class="alert alert-danger my-3" role="alert">
            Your election has been deleted successfully.
        </div>
<?php
    }
?>

<div class="row my-3">
    <div class="col-4">
        <?php
        // If we are editing an election, fetch the existing data
        if (isset($_GET['edit_id'])) {
            $edit_id = mysqli_real_escape_string($db, $_GET['edit_id']);
            $fetchElection = mysqli_query($db, "SELECT * FROM elections WHERE id = '$edit_id'") or die(mysqli_error($db));
            $election = mysqli_fetch_assoc($fetchElection);
        ?>
        <h3>Edit Election</h3>
        <form method="POST">
            <input type="hidden" name="election_id" value="<?php echo $election['id']; ?>" />
            <div class="form-group">
                <input type="text" name="election_topic" value="<?php echo $election['election_topic']; ?>" placeholder="Election Topic" class="form-control" required/>
            </div>
            <div class="form-group">
                <input type="number" name="no_of_candidate" value="<?php echo $election['no_of_candidate']; ?>" placeholder="No of Candidates" class="form-control" required/>
            </div>
            <div class="form-group">
                <input type="date" name="starting_date" value="<?php echo $election['starting_date']; ?>" placeholder="Starting Date" class="form-control" required/>
            </div>
            <div class="form-group">
                <input type="date" name="ending_date" value="<?php echo $election['ending_date']; ?>" placeholder="Ending Date" class="form-control" required/>
            </div>
            <input type="submit" value="Update Election" name="updateElectionbtn" class="btn btn-primary"/>
        </form>
        <?php
        } else {
        ?>
        <h3>Add New Election</h3>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="election_topic" placeholder="Election Topic" class="form-control" required/>
            </div>
            <div class="form-group">
                <input type="number" name="no_of_candidate" placeholder="No of Candidates" class="form-control" required/>
            </div>
            <div class="form-group">
                <input type="date" name="starting_date" placeholder="Starting Date" class="form-control" required/>
            </div>
            <div class="form-group">
                <input type="date" name="ending_date" placeholder="Ending Date" class="form-control" required/>
            </div>
            <input type="submit" value="Add Election" name="addElectionbtn" class="btn btn-success"/>
        </form>     
        <?php } ?>
    </div>

    <div class="col-8">
        <h3>Upcoming Elections</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Election Name</th>
                    <th scope="col">No. of Candidates</th>
                    <th scope="col">Starting Date</th>
                    <th scope="col">Ending Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $fetchingdata = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db));
                    $isAnyelectionadded = mysqli_num_rows($fetchingdata);
                    if ($isAnyelectionadded > 0) {
                        $sno = 1;
                        while ($row = mysqli_fetch_assoc($fetchingdata)) {
                            $election_id = $row['id'];
                ?>
                <tr>
                    <td><?php echo $sno++; ?></td>
                    <td><?php echo $row['election_topic']; ?></td>
                    <td><?php echo $row['no_of_candidate']; ?></td>
                    <td><?php echo $row['starting_date']; ?></td>
                    <td><?php echo $row['ending_date']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <a href="index2.php?addelectionpage=1&edit_id=<?php echo $election_id; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger" onclick="Deletedata(<?php echo $election_id; ?>)">Delete</button>
                    </td>
                </tr>
                <?php
                        }
                    } else {
                ?>
                <tr>
                    <td colspan="7">No elections have been added yet.</td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const Deletedata = (e_id) => {
        let confirmDelete = confirm("Are you sure you want to delete this election?");
        if (confirmDelete) {
            location.assign("index2.php?addelectionpage=1&delete_id=" + e_id);
        }
    }
</script>

<?php
// Handling election form submission for adding or updating
if (isset($_POST['addElectionbtn'])) {
    $election_topic = mysqli_real_escape_string($db, $_POST['election_topic']);
    $no_of_candidate = mysqli_real_escape_string($db, $_POST['no_of_candidate']);
    $starting_date = mysqli_real_escape_string($db, $_POST['starting_date']);
    $ending_date = mysqli_real_escape_string($db, $_POST['ending_date']);
    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");

    $currentDate = date_create($inserted_on);
    $startDate = date_create($starting_date);
    $endDate = date_create($ending_date);

    if ($currentDate && $startDate && $endDate) {
        if ($currentDate >= $startDate && $currentDate <= $endDate) {
            $status = "active";
        } else {
            $status = "inactive";
        }
    } else {
        echo "Error in date creation.";
    }

    mysqli_query($db, "INSERT INTO elections(election_topic, no_of_candidate, starting_date, ending_date, status, inserted_by, inserted_on) 
    VALUES('$election_topic', '$no_of_candidate', '$starting_date', '$ending_date', '$status', '$inserted_by', '$inserted_on')") 
    or die(mysqli_error($db));
?>
    <script>
        location.assign("index2.php?addelectionpage=1&added=1");
    </script>
<?php
}

// Handling election update
if (isset($_POST['updateElectionbtn'])) {
    $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
    $election_topic = mysqli_real_escape_string($db, $_POST['election_topic']);
    $no_of_candidate = mysqli_real_escape_string($db, $_POST['no_of_candidate']);
    $starting_date = mysqli_real_escape_string($db, $_POST['starting_date']);
    $ending_date = mysqli_real_escape_string($db, $_POST['ending_date']);
    
    $currentDate = date("Y-m-d");
    $startDate = date_create($starting_date);
    $endDate = date_create($ending_date);

    if ($startDate && $endDate) {
        if ($currentDate >= $starting_date && $currentDate <= $ending_date) {
            $status = "active";
        } else {
            $status = "inactive";
        }
    } else {
        echo "Error in date creation.";
    }

    mysqli_query($db, "UPDATE elections SET election_topic='$election_topic', no_of_candidate='$no_of_candidate', starting_date='$starting_date', ending_date='$ending_date', status='$status' WHERE id='$election_id'") 
    or die(mysqli_error($db));
?>
    <script>
        location.assign("index2.php?addelectionpage=1&updated=1");
    </script>
<?php
}?>
