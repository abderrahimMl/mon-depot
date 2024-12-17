<?php
include_once "conn.php";


function ajouterStagiaire() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = htmlspecialchars($_POST['nom']);
        $group_id = htmlspecialchars($_POST['group_id']);
        $id_user = htmlspecialchars($_POST['id_user']);
        $filiere_id = htmlspecialchars($_POST['filiere_id']);

        try {
            $stmt = $conn->prepare("
                INSERT INTO stagiaire (nom, group_id, id_user, filiere_id) 
                VALUES (:nom, :group_id, :id_user, :filiere_id)
            ");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':group_id', $group_id);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':filiere_id', $filiere_id);

            $stmt->execute();

            echo "<p class='alert alert-success'>Stagiaire ajouté avec succès !</p>";
        } catch (PDOException $e) {
            echo "<p class='alert alert-danger'>Erreur : " . $e->getMessage() . "</p>";
        }
    }
}

ajouterStagiaire();

try {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    
        // Préparer et exécuter la requête pour récupérer les informations de l'utilisateur
        $stmtUser = $conn->prepare("SELECT id, nom FROM users WHERE id = :id");
        $stmtUser->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtUser->execute();
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    
        // Vérifier si l'utilisateur existe
        if ($user) {
            $userNom = $user['nom'];
            $userid = $user['id'];
        } else {
            $userNom = ''; // Si l'utilisateur n'existe pas, laisser vide
            $userid = '';  // Si l'utilisateur n'existe pas, laisser vide
        }
    } else {
        $userNom = ''; // Si aucun ID n'est passé, laisser vide
        $userid = '';  // Si aucun ID n'est passé, laisser vide
    }


    // Récupérer les groupes
    $stmtGroup = $conn->query("SELECT group_id , nom FROM class");
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
    <title>Ajouter Stagiaire</title>
    <link rel="stylesheet" href="../sql/bootstrap-5.0.2-dist/bootstrap.css">
</head>
<body>
    <div id="formContainer">
        <button class="btn btn-outline-danger" onclick="showForm(event, <?= htmlspecialchars($user['id'] ?? '') ?>)">Afficher le formulaire</button>
        <button class="btn btn-outline-dark"><a href="add_stagiaire.php">retour</a></button>
        <button class="btn btn-outline-primary"><a href="espace_stg.php">Voir stagiaire</a></button>
    </div>
</body>
<script>
    // Fonction pour afficher le formulaire dynamique
    function showForm(event, userId) {
        event.preventDefault();

        // Générer le formulaire dynamique
        const formHtml = `
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-4">Ajouter un Stagiaire</h2>
                    <form method="POST" >
                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" value="<?= htmlspecialchars($userNom ?? '') ?>" class="form-control" id="nom" name="nom" placeholder="Entrez le nom du stagiaire" required>
                        </div>

                       

                        <!-- Groupe -->
                        <div class="mb-3">
                            <label for="group_id" class="form-label">Nom Groupe</label>
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
                                <input type="number" name="id_user" value="<?= htmlspecialchars($userid ?? '') ?>" class="form-control" id="nom" name="nom" placeholder="Entrez le nom du stagiaire" required>

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

                        <button type="submit" name="ajouter" class="btn btn-primary">Ajouter le Stagiaire</button>
                    </form>
                </div>
            </div>
        `;

        document.getElementById('formContainer').innerHTML = formHtml;
    }
</script>
</html>