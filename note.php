<?php
require_once "conn.php"; // Connexion à la base de données
// Récupération des modules depuis la table course
try {
    $queryModules = "SELECT course_name FROM course";
    $stmtModules = $conn->query($queryModules);
    $modules = $stmtModules->fetchAll(PDO::FETCH_ASSOC); // Liste des modules
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des modules : " . $e->getMessage();
    exit;
}

// Traitement de l'ajout ou de la modification des notes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification de l'action
    if (isset($_POST['action'])) {
        // Récupération des données envoyées depuis le formulaire
        $id = $_POST['id']; // ID du stagiaire
        $nom = $_POST['nom']; // Nom du stagiaire
        $controle1 = (int) $_POST['controle1']; // Conversion en nombre
        $controle2 = (int) $_POST['controle2']; // Conversion en nombre
        $controle3 = (int) $_POST['controle3']; // Conversion en nombre
        $controle4 = (int) $_POST['controle4']; // Conversion en nombre
        $efm = (int) $_POST['efm']; // Conversion en nombre
        $coef = (int) $_POST['coef']; // Conversion en nombre


    
        // Création d'un tableau des contrôles, en filtrant les valeurs nulles ou non définies
$total_controles = array_filter([$controle1, $controle2, $controle3, $controle4]);
// Nombre réel de contrôles
// Création d'un tableau des contrôles en filtrant les valeurs nulles
$total_controles = array_filter([$controle1, $controle2, $controle3, $controle4]);

// Création d'un tableau des contrôles en filtrant les valeurs nulles
$total_controles = array_filter([$controle1, $controle2, $controle3, $controle4]);

// Vérification des notes des contrôles
foreach ($total_controles as $note) {
    if ($note > 20) {
        die("Erreur : Une note de contrôle dépasse 20.");
    }}
}

// Harmoniser la note de l'EFM sur 20 si elle est sur 40
$efm_sur_20 = ($efm * 20) / 40;

// Calcul de la somme des contrôles
$somme_controles = array_sum($total_controles);

// Nombre réel de contrôles
$nombre_controles = count($total_controles);

// Calcul de la moyenne pondérée
$moyenne = $nombre_controles > 0 
    ? ($somme_controles + ($efm_sur_20 * $coef)) / ($nombre_controles + $coef)
    : 0;

// Affichage des résultats
echo "Nombre de contrôles : " . $nombre_controles . "<br>";
echo "La moyenne finale est : " . number_format($moyenne, 2);

// Récupération du module sélectionné
        $course_name = $_POST['module'];

        // Vérification si le module existe dans la table course
        $stmtCheckModule = $conn->prepare("SELECT COUNT(*) FROM course WHERE course_name = :course_name");
        $stmtCheckModule->bindParam(':course_name', $course_name, PDO::PARAM_STR);
        $stmtCheckModule->execute();

        if ($stmtCheckModule->fetchColumn() > 0) {
            // Le module existe, on peut procéder à l'insertion
            try {
                $stmt = $conn->prepare("
                    INSERT INTO notes (
                        id_stagiaire, nom_stagiaire, controle1, controle2, controle3, controle4, efm, coef, moyenne, course_name
                    ) VALUES (
                        :id_stagiaire, :nom, :controle1, :controle2, :controle3, :controle4, :efm, :coef, :moyenne, :course_name
                    )
                ");
                $stmt->bindParam(':id_stagiaire', $id, PDO::PARAM_INT);
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':controle1', $controle1, PDO::PARAM_INT);
                $stmt->bindParam(':controle2', $controle2, PDO::PARAM_INT);
                $stmt->bindParam(':controle3', $controle3, PDO::PARAM_INT);
                $stmt->bindParam(':controle4', $controle4, PDO::PARAM_INT);
                $stmt->bindParam(':efm', $efm, PDO::PARAM_INT);
                $stmt->bindParam(':coef', $coef, PDO::PARAM_INT);
                $stmt->bindParam(':moyenne', $moyenne, PDO::PARAM_INT);
                $stmt->bindParam(':course_name', $course_name, PDO::PARAM_STR); // Utilisation de PARAM_STR
                $stmt->execute();
                echo "<div class='alert alert-success'>Les notes ont été ajoutées avec succès.</div>";
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Erreur lors de l'ajout : " . $e->getMessage() . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Erreur : Le module sélectionné n'existe pas dans la table `course`.</div>";
        }
    }


// Préparation de la récupération des stagiaires en fonction de la filière et du groupe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filiere_id']) && isset($_POST['group_id'])) {
    $filiere_id = $_POST['filiere_id'];
    $group_id = $_POST['group_id'];

    try {
        $stmt = $conn->prepare("CALL ajouter_note(:filiere_idn, :group_idn)");
        $stmt->bindParam(':filiere_idn', $filiere_id, PDO::PARAM_STR);
        $stmt->bindParam(':group_idn', $group_id, PDO::PARAM_STR);
        $stmt->execute();

        $stagiaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes des Stagiaires</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Recherche des Stagiaires</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" class="row g-3">
                            <!-- Sélection de la filière -->
                            <div class="col-md-6">
                                <label for="filiere_id" class="form-label">Choisissez la filière</label>
                                <select name="filiere_id" id="filiere_id" class="form-select" required>
                                    <option value="AA">AA</option>
                                    <option value="DEV">DEV</option>
                                    <option value="RI">RI</option>
                                    <option value="CM">CM</option>
                                    <option value="IM">IM</option>
                                    <option value="IAD">IAD</option>
                                    <option value="GE">GE</option>
                                    <option value="ASR">ASR</option>
                                    <option value="RI">RI</option>
                                    <option value="SD">SD</option>
                                </select>
                            </div>
                            <!-- Sélection du groupe -->
                            <div class="col-md-6">
                                <label for="group_id" class="form-label">Choisissez le groupe</label>
                                <select name="group_id" id="group_id" class="form-select" required>
                                    <option value="DEV101">DEV101</option>
                                    <option value="DEV102">DEV102</option>
                                    <option value="DEV103">DEV103</option>
                                    <option value="DEV104">DEV104</option>
                                    <option value="GES101">GES101</option>
                                    <option value="GES103">GES103</option>
                                    <option value="GES104">GES104</option>
                                    <option value="GES105">GES105</option>
                                    <option value="RESS101">RESS101</option>
                                    <option value="RES102">RES102</option>
                                    <option value="RES103">RES103</option>
                                    <option value="AA101">AA101</option>
                                    <option value="AA102">AA102</option>
                                </select>
                            </div>
                            <!-- Bouton de recherche -->
                            <div class="col-12">
                                <button type="submit" class="btn btn-success w-100">Rechercher</button>
                            </div>
                        </form>
                    </div>
                </div>

                <h2 class="text-center">Notes des Stagiaires</h2>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Contrôle 1</th>
                            <th>Contrôle 2</th>
                            <th>Contrôle 3</th>
                            <th>Contrôle 4</th>
                            <th>EFM</th>
                            <th>Coef</th>
                            <th>module</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($stagiaires)): ?>
                            <?php foreach ($stagiaires as $stagiaire): ?>
                                <tr>
                                    <td><?= htmlspecialchars($stagiaire['ID']) ?></td>
                                    <td><?= htmlspecialchars($stagiaire['Nom']) ?></td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="id" value="<?= $stagiaire['ID'] ?>">
                                        <input type="hidden" name="nom" value="<?= $stagiaire['Nom'] ?>"> <!-- Nom du stagiaire -->
                                        <td><input  type="number" class="form-control" name="controle1" placeholder="Note"></td>
                                        <td><input  type="number" class="form-control" name="controle2" placeholder="Note"></td>
                                        <td><input  type="number" class="form-control" name="controle3" placeholder="Note"></td>
                                        <td><input value='<?php echo " "?>'type="number" class="form-control" name="controle4" placeholder="Note"></td>
                                        <td><input  type="number" class="form-control" name="efm" placeholder="Note"></td>
                                        <td><input  type="number" class="form-control" name="coef" placeholder="Coef"></td>
                                        <td>
                                            
                                          <select    name="module" id="module" class="form-select" required>
                                            <?php foreach ($modules as $module): ?>
                                                <option value="<?= htmlspecialchars($module['course_name']) ?>">
                                                    <?= htmlspecialchars($module['course_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                          </select>
                                        </td>

                                        <td>
                                            <button name="action" value="ajouter" type="submit" class="btn btn-primary btn-sm">Ajouter</button>
                                            <button name="action" value="modifier" type="submit" class="btn btn-warning btn-sm">Modifier</button>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Aucun stagiaire trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
