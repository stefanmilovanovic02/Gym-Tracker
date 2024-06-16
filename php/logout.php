<?php
session_start();

// Provjera da li je korisnik prijavljen (sesija postoji)
if (isset($_SESSION['korisnik_id'])) {
    // Ako postoji sesija, brišemo je
    session_unset();    // Uklanja sve varijable sesije
    session_destroy();  // Uništava sesiju

    // Opcionalno: možemo preusmjeriti korisnika na početnu stranicu ili drugu stranicu
    header("Location: index.php");  // Zamijeni 'index.php' sa stvarnom stranicom na koju želiš preusmjeriti nakon odjave
    exit();
} else {
    // Ako korisnik nije prijavljen, možeš preusmjeriti na početnu stranicu ili gdje god želiš
    header("Location: index.php");
    exit();
}
?>
