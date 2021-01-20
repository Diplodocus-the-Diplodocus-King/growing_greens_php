<?php

    include('./config/db_connect.php');

    $email = $crop = $family = $varieties = $sow = $harvest = '';
    $errors = array('email' => '', 'crop' => '', 'family' => '', 'varieties' => '', 'sow' => '', 'harvest' => '', 'general' => '');

    if(isset($_POST['submit'])){
        // here we are checking if the form has been submitted or not so we can tell if this is a GET or POST request for the page.
        // echo htmlspecialchars($_POST['crop']);
        // echo htmlspecialchars($_POST['family']);
        // echo htmlspecialchars($_POST['varieties']);
        // echo htmlspecialchars($_POST['sow']);
        // echo htmlspecialchars($_POST['harvest']);
        // note htmlspecialchars converts any script into html entities which renders it useless and makes the submission safe. Protects against XSS (cross site scripting)
        
         // FORM VALIDATION
        $inputFields = ['email', 'crop', 'family', 'varieties', 'sow', 'harvest'];
        $months = ["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "decemeber"];

        foreach($inputFields as $input){
            
            switch($input) {
                case "email":
                    if (empty($_POST["$input"])){
                        $errors['email'] = "$input is required <br />";
                    } else {
                        $email = $_POST["$input"];
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                            $errors['email'] = 'email must be a valid email address';
                        }
                    }
                    break;
                case "crop":
                    if (empty($_POST["$input"])){
                        $errors['crop'] = "$input is required <br />";
                    } else {
                        $crop = $_POST["$input"];
                        if (!preg_match('/^[a-zA-z\'\s]+$/', $crop)){
                            $errors['crop'] = 'Crop must only contain letters or spaces';
                        }
                    }
                    break;
                case "family":
                    if (empty($_POST["$input"])){
                        $errors['family'] = "$input is required <br />";
                    } else {
                        $family = $_POST["$input"];
                        if (!preg_match('/^[a-zA-z]+$/', $family)){
                            $errors['family'] = 'Family must only contain letters';
                        }
                    }
                    break;
                case "varieties":
                    if (empty($_POST["$input"])){
                        $errors['varieties'] = "$input is required <br />";
                    } else {
                        $varieties = $_POST["$input"];
                        if (!preg_match('/^([a-zA-z\'\s]+)(,\s*[a-zA-Z\'\s]*)*$/', $varieties)){
                            $errors['varieties'] = 'Varieties must be a comma seperated list';
                        }
                    }
                    break;
                case "sow":
                    if (empty($_POST["$input"])){
                        $errors['sow'] = "$input is required <br />";
                    } else {
                        $sow = strtolower($_POST["$input"]);
                        if (!in_array($sow, $months)){
                            $errors['sow'] = 'Sowing month must be a valid month of the year';
                        }
                    }
                    break;
                case "harvest":
                    if (empty($_POST["$input"])){
                        $errors['harvest'] = "$input is required <br />";
                    } else {
                        $harvest = strtolower($_POST["$input"]);
                        if (!in_array($harvest, $months)){
                            $errors['harvest'] = 'Harvest month must be a valid month of the year';
                        }
                    }
                    break;
                default:
                    $errors['general'] = 'input not recognised';
            }
        } 

        // array_filter works like the JS filter it takes in a callback function. However if we don't provide a function the statement will evaluate to true if there is 
        // values inside the array, otherwise if the array is empty it will return false.
        if(!array_filter($errors)){
            $email = mysqli_real_escape_string($connection, $_POST["email"]);
            $crop = mysqli_real_escape_string($connection, $_POST["crop"]);
            $family = mysqli_real_escape_string($connection, $_POST["family"]);
            $varieties = mysqli_real_escape_string($connection, $_POST["varieties"]);
            $sow = mysqli_real_escape_string($connection, $_POST["sow"]);
            $harvest = mysqli_real_escape_string($connection, $_POST["harvest"]);

            // create sql
            $sqlAdd = "INSERT INTO crops(email, crop, family, varieties, sow, harvest) 
                VALUES('$email', '$crop', '$family', '$varieties', '$sow', '$harvest')";

            // save to db and check
            if(mysqli_query($connection, $sqlAdd)){
                // redirect
                header('Location: index.php');
            } else {
                echo 'query error: ' . mysqli_error($connection);
            }   
        }
    }
?>

<html lang="en">
    <?php include('templates/header.php')?>

    <section class="container grey-text">
        <h4 class="center">Add a Crop</h4>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="white">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email) ?>">
            <div class="red-text"><?php echo $errors['email']; ?></div>
            <label for="crop">Crop:</label>
            <input type="text" name="crop" value="<?php echo htmlspecialchars($crop) ?>">
            <div class="red-text"><?php echo $errors['crop']; ?></div>
            <label for="family">Family:</label>
            <input type="text" name="family" value="<?php echo htmlspecialchars($family) ?>">
            <div class="red-text"><?php echo $errors['family']; ?></div>
            <label for="varieties">Varieties (comma seperated):</label>
            <input type="text" name="varieties" value="<?php echo htmlspecialchars($varieties) ?>">
            <div class="red-text"><?php echo $errors['varieties']; ?></div>
            <label for="sow">Sow Month:</label>
            <input type="text" name="sow" value="<?php echo htmlspecialchars($sow) ?>">
            <div class="red-text"><?php echo $errors['sow']; ?></div>
            <label for="harvest">Harvest Month:</label>
            <input type="text" name="harvest" value="<?php echo htmlspecialchars($harvest) ?>">
            <div class="red-text"><?php echo $errors['harvest']; ?></div>
            <div class="center">
                <div class="red-text"><?php echo $errors['general']; ?></div>
                <input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php')?>
</html>