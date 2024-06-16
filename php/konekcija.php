
<?php
    // Podaci za konekciju1
        $servername = "localhost"; 
        $username = "root"; 
        $password = "123"; 
        $database = "teretana";

    // Konekcija sa bazom
        $conn = new mysqli($servername, $username, $password, $database);
    
     // Provera konekcije
        if ($conn->connect_error) {
        die("Greška prilikom povezivanja sa bazom podataka: " . $conn->connect_error);
    }
?>

