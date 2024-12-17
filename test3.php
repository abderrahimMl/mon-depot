<?php
require_once "conn.php"; // Connexion à la base de données

// Récupérer les données des tables pour les clés étrangères
try {
    // Récupérer les groupes
    $stmtGroup = $conn->query("SELECT group_id, nom FROM class");
    $groupes = $stmtGroup->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les utilisateurs
    $stmtUser = $conn->query("SELECT id, email FROM users");
    $users = $stmtUser->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les filières
    $stmtFiliere = $conn->query("SELECT filiere_id, description FROM filiere");
    $filieres = $stmtFiliere->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Stagiaire</title>
    <link rel="stylesheet" href="../sql/bootstrap-5.0.2-dist/bootstrap.css">
</head>
<body>
    <div class="container-fluid mt-5">
    <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                    <a class="navbar-brand text-ligth fw-bold fs-3" href="#">OFPPT</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active text-dark  fs-4" href="">Acceuill</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark  fs-4" href="add_stagiaire.php">Ajouter stagiaire</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark  fs-4" href="#">Contact</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
            <h2 class="mb-4">Ajouter un Stagiaire</h2>
        <form method="POST" action="ajouter_stagiaire.php">
            <!-- Nom -->
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez le nom du stagiaire" required>
            </div>

            <!-- Année -->
            <div class="mb-3">
                <label for="anne" class="form-label">Année</label>
                <input type="text" disabled class="form-control" id="anne" name="anne" placeholder="Entrez l'année (ex : 2024)" required>
            </div>

            <!-- Groupe -->
            <div class="mb-3">
                <label for="group_id" class="form-label">ID Groupe</label>
                <select class="form-select" id="group_id" name="group_id" required>
                    <option value="" selected disabled>Choisissez un groupe</option>
                    <?php foreach ($groupes as $groupe): ?>
                        <option value="<?= htmlspecialchars($groupe['group_id']) ?>"><?= htmlspecialchars($groupe['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Utilisateur -->
            <div class="mb-3">
                <label for="id_user" class="form-label">ID Utilisateur</label>
                <select class="form-select" id="id_user" name="id_user" required>
                    <option value="" selected disabled>Choisissez un utilisateur</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= htmlspecialchars($user['id']) ?>"><?= htmlspecialchars($user['email']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filière -->
            <div class="mb-3">
                <label for="filiere_id" class="form-label">ID Filière</label>
                <select class="form-select" id="filiere_id" name="filiere_id" required>
                    <option value="" selected disabled>Choisissez une filière</option>
                    <?php foreach ($filieres as $filiere): ?>
                        <option value="<?= htmlspecialchars($filiere['filiere_id']) ?>"><?= htmlspecialchars($filiere['description']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-primary">Ajouter le Stagiaire</button>
        </form>
    </div>
            </div>
        </div>
        
    </div>
<script src="../sql/bootstrap-5.0.2-dist/js/bootstrap.js" crossorigin="anonymous"></script>

</body>
</html>
