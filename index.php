<?php
    
    include('./config/db_connect.php');

    // db query grab all data
    $sqlQuery = 'SELECT * FROM crops ORDER BY created_at';  // construct query
    $result = mysqli_query($connection, $sqlQuery); // make query
    $crops = mysqli_fetch_all($result, MYSQLI_ASSOC); // fetch result

    // best practice is to now free the result from memory & close connection
    mysqli_free_result($result);
    mysqli_close($connection);

    // print_r($crops);

    explode(',', $crops[0]['varieties'])
?>


<html lang="en">
    <?php include('templates/header.php')?>

    <h4 class="center grey-text">Grow some greens!</h4>

    <div class="container">
        <div class="row">
            <?php foreach($crops as $crop): ?>

                <div class="col s6 m4 l4">
                    <div class="card z-depth-0">
                        <img src="./images/<?php echo $crop['crop']; ?>.png" class="crop-img" alt="crop-img">
                        <div class="card-content center">
                            <h6><?php echo htmlspecialchars(ucfirst($crop['crop'])); ?></h6>
                            <div><?php echo htmlspecialchars(ucfirst($crop['family'])); ?></div>
                            <ul>
                                <?php foreach(explode(',', $crop['varieties']) as $variety): ?>
                                    <li><?php echo htmlspecialchars($variety) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="card-action right-align">
                            <a href="crop_details.php?id=<?php echo $crop['id'] ?>" class="brand-text">more info</a>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>

    <?php include('templates/footer.php')?>
</html>