
<div class="row my-3">
    <div class="col-12">
    <h3>Elections</h3>
    <table class="table">
  <thead>
    <tr>
      <th scope="col">S.No</th>
      <th scope="col">Election Name</th>
      <th scope="col">* Candidates</th>
      <th scope="col">Starting Date</th>
      <th scope="col">Ending Date</th>
      <th scope="col">Status</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
        <tbody>
            <?php        
                   $fetchingdata = mysqli_query($db, "SELECT * FROM elections")
                   or die(mysqli_errno($db)); 
                   $isAnyelectionadded = mysqli_num_rows($fetchingdata);
                   if($isAnyelectionadded > 0){
                    $sno=1;
                        while($row = mysqli_fetch_assoc($fetchingdata)){
            ?>
                   <tr> 
                    <td> <?php echo $sno++;?></td>
                    <td> <?php echo $row['election_topic'];?></td>  
                    <td> <?php echo $row['no_of_candidate'];?></td>       
                    <td> <?php echo $row['starting_date'];?></td>       
                    <td> <?php echo $row['ending_date'];?></td>        
                    <td> <?php echo $row['status'];?></td>   
                    <!-- <td> <?php echo $row['inserted_by'];?></td>       
                    <td> <?php echo $row['inserted_on'];?></td>        -->

                    <td>
                        
                    <a href="#" class="btn btn-sm btn-success">View Results</a>
                   

                    </td>
                
                </tr>

                <?php
                        }
                   }else{
            
                 ?>
                   <tr> <td colspan="7">NO any elections is addedd yet</td></tr>
                <?php

                   }
                 ?>
           </tbody>
  </table>
    </div>
</div>


