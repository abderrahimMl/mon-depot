<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stagiaire Notes</title>
    <link rel="stylesheet" href="../sql/bootstrap-5.0.2-dist/bootstrap.css">
</head>
<body>
<?php
require_once "conn.php";

// Get the stagiaire ID from the URL parameter
$stagiaire_id = $_GET['id'];

// Connect to the database
try {
    // Fetch stagiaire information
    $stmt = $conn->prepare("
        SELECT s.nom AS stagiaire_nom, f.description AS filiere_description, c.nom AS class_nom
        FROM stagiaire s
        LEFT JOIN filiere f ON s.filiere_id = f.filiere_id
        LEFT JOIN class c ON s.group_id = c.group_id
        WHERE s.id_user = :stagiaire_id
    ");
    $stmt->bindParam(':stagiaire_id', $stagiaire_id);
    $stmt->execute();
    $stagiaire_info = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch notes information
    $stmt = $conn->prepare("
        SELECT n.*, c.course_name AS module_name
        FROM notes n
        JOIN course c ON n.course_name = c.course_name
        join stagiaire s on s.stg_id=n.id_stagiaire
        join users u on u.id=s.id_user
        WHERE u.id=:stagiaire_id");
    $stmt->bindParam(':stagiaire_id', $stagiaire_id);
    $stmt->execute();
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Close the database connection
$conn = null;
?>
<div class="container-fluid">

<div class="row bg-primary text-center">
    <div class="col-3">
        <img class="img-rounded border rounded-4 border-rounded" style="width:80px" src="ofppt.png" alt="">
    </div>
    <div class="col-3">
        <p class="text-center text-white fw-bold">Nom : <?php echo htmlspecialchars($stagiaire_info['stagiaire_nom']); ?></p>
    </div>
    <div class="col-3">
        <p class="text-center text-white fw-bold">Filière : <?php echo htmlspecialchars($stagiaire_info['filiere_description']); ?></p>
    </div>
    <div class="col-3">
        <p class="text-center text-white fw-bold">Classe : <?php echo htmlspecialchars($stagiaire_info['class_nom']); ?></p>
    </div>
</div>

<div class="container mt-4">
    <?php if (count($notes) > 0): ?>
        <table class="table table-striped table-sm-3 table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID Stagiaire</th>
                    <th>Nom Stagiaire</th>
                    <th>Module</th>
                    <th>Note 1</th>
                    <th>Note 2</th>
                    <th>Note 3</th>
                    <th>Note 4</th>
                    <th>EFM</th>
                    <th>Coef</th>
                    <th>Moyenne</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($note['id_stagiaire']); ?></td>
                        <td><?php echo htmlspecialchars($note['nom_stagiaire']); ?></td>
                        <td><?php echo htmlspecialchars($note['module_name']); ?></td>
                        <td><?php echo htmlspecialchars($note['controle1']); ?></td>
                        <td><?php echo htmlspecialchars($note['controle2']); ?></td>
                        <td><?php echo htmlspecialchars($note['controle3']); ?></td>
                        <td><?php echo htmlspecialchars($note['controle4']); ?></td>
                        <td><?php echo htmlspecialchars($note['EFM']); ?></td>
                        <td><?php echo htmlspecialchars($note['coef']); ?></td>
                        <td><?php echo htmlspecialchars($note['moyenne']); ?></td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">Aucune note trouvée pour ce stagiaire.</p>
    <?php endif; ?>

</div>
</div>
</body>
</html>