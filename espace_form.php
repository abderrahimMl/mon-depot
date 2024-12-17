
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sql/bootstrap-5.0.2-dist/bootstrap.css">
    <title>Afficher Stagiaires</title>
</head>
<body>
<div class="container mt-5">
    <form method="POST">
        <button type="submit" name="afficher" class="btn btn-primary">Afficher touts les Stagiaires</button>
    </form>
    <div class="mt-3">
    <?php
require_once "conn.php";

function afficherStagiaires()
{
    global $conn; // Utiliser la connexion définie dans conn.php

    try {
        $sql = "SELECT 
                    s.stg_id AS id,
                    s.nom AS nom,
                    c.nom AS group_name,
                    u.email AS email,
                    f.description AS filiere
                FROM stagiaire s
                JOIN class c ON s.group_id = c.group_id
                JOIN users u ON s.id_user = u.id
                JOIN filiere f ON s.filiere_id = f.filiere_id ORDER by stg_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stagiaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($stagiaires)) {
            echo "<table class='table table-striped table-bordered mt-3'>";
            echo "<thead class='table-dark'>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Groupe</th>
                        <th>Email</th>
                        <th>Filière</th>
                    </tr>
                </thead><tbody>";
            foreach ($stagiaires as $stagiaire) {
                echo "<tr>
                        <td>{$stagiaire['id']}</td>
                        <td>{$stagiaire['nom']}</td>
                        <td>{$stagiaire['group_name']}</td>
                        <td>{$stagiaire['email']}</td>
                        <td>{$stagiaire['filiere']}</td>
                    </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Aucun stagiaire trouvé.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Erreur lors de l'exécution de la requête : " . $e->getMessage() . "</p>";
    }
}
?>
<?php
//require_once "function_stagiaires.php"; // Le fichier contenant la fonction

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['afficher'])) {
    // Appeler la fonction lorsque le bouton est cliqué
    afficherStagiaires();
}
?>
    </div>
</div>
</body>
</html>
