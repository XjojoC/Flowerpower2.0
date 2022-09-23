<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "config.php";
    
    // keuze statement
    $sql = "SELECT * FROM product WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // variabeles aan statements
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // proberen statement aanvragen
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
        
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Individuele gegevens opvragen
                $naam = $row["Naam"];
                $beschrijving = $row["Omschrijving"];
                $prijs = $row["Prijs"];
            } else{
                // URL niet goed terug naar pagina
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! er gaat iets fout!.";
        }
    }
     
    // Close statement
    // mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
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
                    <h1 class="mt-5 mb-3">Bekijk gegevens</h1>
                    <div class="form-group">
                        <label>Naam</label>
                        <p><b><?php echo $row["naam"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Beschrijving</label>
                        <p><b><?php echo $row["beschrijving"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Prijs</label>
                        <p><b><?php echo $row["prijs"]; ?></b></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>