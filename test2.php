<?php
// Connexion à la base de données
require_once 'conn.php';

// Exécution de la commande SHOW CREATE TABLE pour obtenir la structure de la table stagiaire
try {
    $query = "SHOW CREATE TABLE course;
SHOW CREATE TABLE notes;
"; // Commande pour récupérer la définition de la table
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Récupérer le résultat
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si la table a bien été récupérée
    if ($result) {
        // Afficher la structure de la table
        echo "<pre>";
        echo htmlspecialchars($result['Create Table']); // Affiche la requête de création de la table
        echo "</pre>";
    } else {
        echo "Impossible de récupérer la structure de la table.";
    }

} catch (PDOException $e) {
    die("Erreur de requête : " . $e->getMessage());
}
?>
