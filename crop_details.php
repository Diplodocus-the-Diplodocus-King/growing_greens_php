<?php

include('config/db_connect.php');

if (isset($_POST['delete'])){

    $id_to_delete = mysqli_real_escape_string($connection, $_POST['id_to_delete']);

    $sqlDelete = "DELETE FROM crops WHERE id = $id_to_delete";

    if(mysqli_query($connection, $sqlDelete)){
        header('Location: index.php');
    } else {
        echo 'query error: ' . mysqli_error($connection);
    }
}

// check GET request id parameter
if (isset($_GET['id'])){

    $id = mysqli_real_escape_string($connection, $_GET['id']);

    // query
    $sqlQuery = "SELECT * FROM crops WHERE id = $id";
    $result = mysqli_query($connection, $sqlQuery);
    // for returning a single result
    $crop = mysqli_fetch_assoc($result);

    // clean up
    mysqli_free_result($result);
    mysqli_close($connection);

}

?>

<html lang="en">
    <?php include('templates/header.php')?>

    <div class="container center">
        <?php if($crop): ?>
            <h4><?php echo htmlspecialchars($crop['crop']); ?></h4>
            <h5><?php echo htmlspecialchars($crop['family']); ?></h5>
            <h5>Varieties</h5>
            <p><?php echo htmlspecialchars($crop['varieties']); ?></p>
            <p><?php echo 'Sow: ' . htmlspecialchars($crop['sow']); ?></p>
            <p><?php echo 'Harvest: ' . htmlspecialchars($crop['harvest']); ?></p>
            <div class="container">
                <p>Created by: <?php echo htmlspecialchars($crop['email']); ?></p>
                <p><?php echo htmlspecialchars($crop['created_at']); ?></p>
            </div>
            <!--  DELETE FORM -->
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <input type="hidden" name="id_to_delete" value="<?php echo $crop['id']; ?>">
                <input type="submit" name="delete" value="Delete" class="btn brand z-depth-0">
            </form>
        <?php else : ?>
            <h5>No such crop exists!</h5>
        <?php endif; ?>
    </div>

    <?php include('templates/footer.php')?>
</html>