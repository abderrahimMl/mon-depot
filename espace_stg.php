<?php
require_once "conn.php"; // Connexion à la base de données

// Fonction pour afficher tous les stagiaires
function afficherStagiaires()
{
    global $conn; // Connexion à la base de données

    try {
        // Appel de la procédure stockée pour récupérer tous les stagiaires
        $stmt = $conn->prepare("CALL GetAllStagiaires()");
        $stmt->execute();
        $stagiaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Afficher les stagiaires dans un tableau
        if (!empty($stagiaires)) {
            echo "<table class='table table-striped table-bordered mt-3'>
                    <thead class='table-dark'>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Groupe</th>
                            <th>Email</th>
                            <th>Filière</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($stagiaires as $stagiaire) {
                echo "<tr>
                        <td>" . htmlspecialchars($stagiaire['id']) . "</td>
                        <td>" . htmlspecialchars($stagiaire['nom']) . "</td>
                        <td>" . htmlspecialchars($stagiaire['group_name']) . "</td>
                        <td>" . htmlspecialchars($stagiaire['email']) . "</td>
                        <td>" . htmlspecialchars($stagiaire['filiere']) . "</td>
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

// Fonction pour rechercher des stagiaires par groupe
function rechercherStagiairesParGroupe($group_name)
{
    global $conn;

    try {
        // Appel de la procédure stockée pour récupérer les stagiaires par groupe
        $stmt = $conn->prepare("CALL GetStagiairesByGroup(:group_name)");
        $stmt->bindParam(':group_name', $group_name, PDO::PARAM_STR);
        $stmt->execute();
        $stagiaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Afficher les stagiaires dans un tableau
        if (!empty($stagiaires)) {
            echo "<table class='table table-striped table-bordered mt-3'>
                    <thead class='table-dark'>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Filière</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($stagiaires as $stagiaire) {
                echo "<tr>
                        <td>" . htmlspecialchars($stagiaire['stagiaire_id']) . "</td>
                        <td>" . htmlspecialchars($stagiaire['stagiaire_nom']) . "</td>
                        <td>" . htmlspecialchars($stagiaire['user_email']) . "</td>
                        <td>" . htmlspecialchars($stagiaire['filiere_id']) . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Aucun stagiaire trouvé pour ce groupe.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Erreur lors de l'exécution de la requête : " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Stagiaires - OFPPT</title>
    <link rel="stylesheet" href="../sql/bootstrap-5.0.2-dist/bootstrap.css">
</head>
<body>
    <div class="container-fluid">
        <!-- Barre de navigation -->
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                    <a class="navbar-brand text-light fw-bold fs-3" href="#">OFPPT</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active text-dark fs-4" href="">Accueil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark fs-4" href="add_stagiaire.php">Ajouter stagiaire</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark fs-4" href="#">Contact</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Affichage des stagiaires -->
        <div class="mt-3">
            <h2>Liste des Stagiaires</h2>
            <form method="POST">
                <button type="submit" name="afficher" class="btn btn-primary">Afficher les Stagiaires</button>
            </form>

            <div class="mt-3">
                <?php
                // Appeler la fonction afficherStagiaires() si le bouton est cliqué
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['afficher'])) {
                    afficherStagiaires();
                }
                ?>
            </div>
        </div>

        <!-- Formulaire de recherche de stagiaires par groupe -->
        <div class="row mt-3">
            <div class="col-12">
                <h2 class="text-primary">Rechercher des Stagiaires par Groupe</h2>
                <form method="POST" class="mb-4">
                    <div class="mb-3 input-group">
                        <label for="group_name" class="form-label">Nom du Groupe :</label>
                        <input type="text" id="group_name" name="group_name" placeholder="Nom du groupe" class="form-control w-25" required>
                        <button type="submit" class="btn input-group-text btn-outline-primary">Rechercher</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Résultats de la recherche -->
        <div class="row">
            <div class="col-12">
                <?php
                // Recherche des stagiaires par groupe si le formulaire est soumis
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['group_name'])) {
                    $group_name = strtoupper($_POST['group_name']); // Convertir le nom du groupe en majuscule
                    rechercherStagiairesParGroupe($group_name);
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="../sql/bootstrap-5.0.2-dist/js/bootstrap.js" crossorigin="anonymous"></script>
</body>
</html>
