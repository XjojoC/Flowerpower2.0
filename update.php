<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$naam = $omschrijving = $prijs = "";
$naam_err = $omschrijving_err = $prijs_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // // Naam
    // $input_naam = trim($_POST["Naam"]);
    // if(empty($input_Naam)){
    //     $naam_err = "Vul een naam in.";
    // } elseif(!filter_var($input_naam, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
    //     $naam_err = "Een goede naam.";
    // } else{
    //     $naam = $input_naam;
    // }
   
    // omschrijving checken
    $input_omschrijving = trim($_POST["omschrijving"]);
    if(empty($input_omschrijving)){
        $omschrijving_err = "Voeg hier uw beschrijving toe.";     
    } else{
        $omschrijving = $input_omschrijving;
    }
    
    // Omschrijving product
    $input_omschrijving = trim($_POST["omschrijving"]);
    if(empty($input_omschrijving)){
        $omschrijving_err = "Graag een omschrijving invullen";     
    // } elseif(!ctype_text($input_omschrijving)){
    //     $omschrijving_err = "Graag een goede omschrijving geven";
    } else{
        $omschrijving = $input_omschrijving;
    }
    
    // Check input errors before inserting in database
    if(empty($naam_err) && empty($omschrijving_err) && empty($prijs_err)){
        //update statement
        $sql = "UPDATE employees SET naam=?, omschrijving=?, prijs=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // variabele naar statements
            mysqli_stmt_bind_param($stmt, "sssi", $param_naam, $param_omschrijving, $param_omschrijving, $param_id);
            
            // Set parameters
            $param_naam = $naam;
            $param_omschrijving = $omschrijving;
            $param_prijs = $prijs;
            $param_id = $id;
            
            // execute statement
            if(mysqli_stmt_execute($stmt)){
                // bewerken gelukt. terug naar hoofdpagina
                header("location: index.php");
                exit();
            } else{
                echo "Oops! er gaat iets fout.";
            }
        }
         
        // Close statement
         mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Kijken of ID bestaat
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Select statement
        $sql = "SELECT * FROM employees WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // variabele naar statement
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // execute statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    
                    $naam = $row["naam"];
                    $address = $row["omschrijving"];
                    $salary = $row["prijs"];
                } else{
                   
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // sluit statement
         mysqli_stmt_close($stmt);
        
        // sluit connectie
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">gegevens</h2>
                    <p>Hier graag gegevens bewerken.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Naam</label>
                            <input type="text" naam="naam" class="form-control <?php echo (!empty($naam_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $naam; ?>">
                            <span class="invalid-feedback"><?php echo $naam_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Omschrijving</label>
                            <textarea name="omschrijving" class="form-control <?php echo (!empty($omschrijving_err)) ? 'is-invalid' : ''; ?>"><?php echo $omschrijving; ?></textarea>
                            <span class="invalid-feedback"><?php echo $omschrijving_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Prijs</label>
                            <input type="text" name="Prijs" class="form-control <?php echo (!empty($prijs_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $prijs; ?>">
                            <span class="invalid-feedback"><?php echo $prijs_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>