<?php 
// session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = mysqli_connect("localhost", "root", "", "onlinevotingsystem") or die("connection fail");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (isset($_GET['added'])) {
    echo '<div class="alert alert-success">Candidate added successfully!</div>';
} elseif (isset($_GET['updated'])) {
    echo '<div class="alert alert-success">Candidate updated successfully!</div>';
} elseif (isset($_GET['failed'])) {
    echo '<div class="alert alert-danger">Operation failed. Please try again.</div>';
} elseif (isset($_GET['deleted'])) {
    echo '<div class="alert alert-danger">Candidate deleted successfully!</div>';
}

// Delete candidate functionality
if (isset($_POST['deleteCandidateId'])) {
    $delete_candidate_id = mysqli_real_escape_string($db, $_POST['deleteCandidateId']);
    $delete_candidate = mysqli_query($db, "DELETE FROM candidate_details WHERE id = '$delete_candidate_id'");
    if ($delete_candidate) {
        echo '<script>location.assign("index2.php?addcandidatepage=1&deleted=1");</script>';
    } else {
        echo '<script>location.assign("index2.php?addcandidatepage=1&failed=1");</script>';
    }
}

if (isset($_POST['addCandidatebtn']) || isset($_POST['editCandidatebtn'])) {
    $election_id = mysqli_real_escape_string($db, $_POST['election_id']);
    $candidate_name = mysqli_real_escape_string($db, $_POST['candidate_name']);
    $candidate_details = mysqli_real_escape_string($db, $_POST['candidate_details']);
    $uploaded_by = $_SESSION['username'];
    $uploaded_on = date("Y-m-d");
    $targeted_folder = "../asset/images/candidate/";
    if (!is_dir($targeted_folder)) {
        mkdir($targeted_folder, 0755, true);
    }

    // Check candidate limit
    $currentCandidateCount = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) AS count FROM candidate_details WHERE election_id = '$election_id'"))['count'];
    $maxCandidates = mysqli_fetch_assoc(mysqli_query($db, "SELECT no_of_candidate FROM elections WHERE id = '$election_id'"))['no_of_candidate'];

    if (isset($_POST['addCandidatebtn'])) {
        if ($currentCandidateCount >= $maxCandidates) {
            echo "<script>alert('Cannot add candidate: limit reached for this election.');</script>";
        } else {
            $candidate_photo = '';
            if (is_uploaded_file($_FILES['candidate_photo']['tmp_name'])) {
                $candidate_photo_name = rand(1111111111, 9999999999) . "_" . basename($_FILES['candidate_photo']['name']);
                $candidate_photo = $targeted_folder . $candidate_photo_name;
                $candidate_photo_type = strtolower(pathinfo($candidate_photo, PATHINFO_EXTENSION));
                $allowed_types = ["png", "jpeg", "jpg"];
                $image_size = $_FILES['candidate_photo']['size'];
                if ($image_size <= 2000000 && in_array($candidate_photo_type, $allowed_types)) {
                    if (!move_uploaded_file($_FILES['candidate_photo']['tmp_name'], $candidate_photo)) {
                        $candidate_photo = '';
                    }
                } else {
                    echo "<script>location.assign('index2.php?addcandidatepage=1&largefile=1');</script>";
                }
            }

            $stmt = $db->prepare("INSERT INTO candidate_details (election_id, candidate_name, candidate_details, candidate_photo, inserted_by, inserted_on) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $election_id, $candidate_name, $candidate_details, $candidate_photo, $uploaded_by, $uploaded_on);
        }
    } elseif (isset($_POST['editCandidatebtn'])) {
        $edit_candidate_id = mysqli_real_escape_string($db, $_GET['edit_candidate_id']);
        if ($candidate_photo == '') {
            $candidate_photo = $_POST['existing_photo'];
        }
        $stmt = $db->prepare("UPDATE candidate_details SET election_id=?, candidate_name=?, candidate_details=?, candidate_photo=?, inserted_by=?, inserted_on=? WHERE id=?");
        $stmt->bind_param("isssssi", $election_id, $candidate_name, $candidate_details, $candidate_photo, $uploaded_by, $uploaded_on, $edit_candidate_id);
    }

    if (isset($stmt) && $stmt->execute()) {
        echo "<script>location.assign('index2.php?addcandidatepage=1&" . (isset($_POST['addCandidatebtn']) ? "added=1" : "updated=1") . "');</script>";
    } else {
        echo "<script>location.assign('index2.php?addcandidatepage=1&failed=1');</script>";
    }
}

$candidateData = [];
if (isset($_GET['edit_candidate_id'])) {
    $edit_candidate_id = mysqli_real_escape_string($db, $_GET['edit_candidate_id']);
    $candidateQuery = mysqli_query($db, "SELECT * FROM candidate_details WHERE id = '$edit_candidate_id'");
    $candidateData = mysqli_fetch_assoc($candidateQuery);
    if ($candidateData === null) {
        echo '<div class="alert alert-danger">Candidate not found.</div>';
        $candidateData = [];
    }
}
?>

<div class="row my-3">
    <div class="col-4">
        <h3><?php echo isset($_GET['edit_candidate_id']) ? 'Edit Candidate' : 'Add New Candidate'  ; ?></h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <select class="form-control" name="election_id" required>
                    <option value="">Select Election</option>
                    <?php 
                    $fetchingElections = mysqli_query($db, "SELECT * FROM elections");
                    while ($row = mysqli_fetch_assoc($fetchingElections)) {
                        $selected = (isset($candidateData['election_id']) && $candidateData['election_id'] == $row['id']) ? 'selected' : '';
                        echo "<option value='".$row['id']."' $selected>".$row['election_topic']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="candidate_name" placeholder="Candidate Name" value="<?php echo htmlspecialchars($candidateData['candidate_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="form-control" required/>
            </div>
            <div class="form-group">
                <?php if (isset($candidateData['candidate_photo']) && !empty($candidateData['candidate_photo'])) { ?>
                    <img src="<?php echo htmlspecialchars($candidateData['candidate_photo'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;" />
                    <input type="hidden" name="existing_photo" value="<?php echo htmlspecialchars($candidateData['candidate_photo'], ENT_QUOTES, 'UTF-8'); ?>"/>
                <?php } ?>
                <input type="file" name="candidate_photo" class="form-control" <?php echo isset($_GET['edit_candidate_id']) ? '' : 'required'; ?> />
            </div>
            <div class="form-group">
                <input type="text" name="candidate_details" placeholder="Candidate Details" value="<?php echo htmlspecialchars($candidateData['candidate_details'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="form-control" required/>
            </div>
            <input type="submit" name="<?php echo isset($_GET['edit_candidate_id']) ? 'editCandidatebtn' : 'addCandidatebtn'; ?>" value="<?php echo isset($_GET['edit_candidate_id']) ? 'Update Candidate' : 'Add Candidate'; ?>" class="btn btn-success"/>
        </form>
    </div>
    <style>
    .table th, .table td {
        vertical-align: middle; /* Center align content vertically */
        padding: 12px; /* Add padding for better spacing */
       
    }
    .table th {
        background-color: #f8f9fa; /* Light background for headers */
        font-weight: bold; /* Make headers bold */
       
    }
    .table img {
        border-radius: 50%; /* Ensure candidate photos are circular */
        width: 80px; /* Set a fixed width */
        height: 80px; /* Set a fixed height */
        object-fit: cover; /* Maintain aspect ratio */
    }
</style>

    <div class="col-8">
        <h3>Candidate Details</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Election</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $fetchingCandidates = mysqli_query($db, "SELECT * FROM candidate_details");
            $sno = 1;
            while ($row = mysqli_fetch_assoc($fetchingCandidates)) {
                $electionQuery = mysqli_query($db, "SELECT election_topic FROM elections WHERE id = '".$row['election_id']."'");
                $election = mysqli_fetch_assoc($electionQuery);
                $election_topic = $election ? $election['election_topic'] : 'Unknown Election';
                ?>
                <tr>
                    <td><?php echo $sno++; ?></td>
                    <td><img src="<?php echo htmlspecialchars($row['candidate_photo'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 80px; height: 80px; border-radius: 50%;" /></td>
                    <td><?php echo htmlspecialchars($row['candidate_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row['candidate_details'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($election_topic, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="index2.php?addcandidatepage=1&edit_candidate_id=<?php echo $row['id']; ?>" class="btn btn-info">Edit</a>
                 <form method="POST" style="display:inline; gap:23px;">
                            <input type="hidden" name="deleteCandidateId" value="<?php echo $row['id']; ?>" />
                            <input type="submit" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this candidate?');" />
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
