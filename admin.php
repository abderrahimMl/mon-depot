
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sql/bootstrap-5.0.2-dist/bootstrap.css">

    <title>Document</title>
</head>
<body>

  <div class="container-fluid">
    
  <div class="container-fluid p-fixed">
        <div class="row">
            <div class="col-12">
                <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary">
                    <a class="navbar-brand text-light fw-bold fs-3" href="#">OFPPT</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active text-dark fs-4" href="espace_stg.php">Espace stagiaire</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark fs-4" href="espace_form.php">Espace Formateur</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark fs-4" href="#">Contact</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    
<div class="row mt-5"></div>
<div class="row mt-5"> 
   <h1 style="border-bottom:solid 3px gray" class="fw-bold  ">Tableau de bord </h1>
  <div class="col-12 col-lg-3 ">
  <div class="card bg-success mb-3" style="max-width: 18rem;">
  <img src="./img/téléchargement.jpeg"  style="width:70px"  class="card-img-top " alt="Image">
  <div class="card-body">
    <h5 class="card-title text-white">Nombre de stagiaires inscrits</h5>
    <p class="card-text text-white">
      <?php
      require_once "conn.php";

      try {
          // Requête pour compter le nombre de stagiaires
          $sql = "SELECT COUNT(*) AS total_stagiaires FROM users WHERE role = 'stagiaire'";
          $stmt = $conn->prepare($sql);
          $stmt->execute();
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($result) {
              echo htmlspecialchars($result['total_stagiaires']);
          } else {
              echo "0";
          }
      } catch (PDOException $e) {
          echo "Erreur : " . htmlspecialchars($e->getMessage());
      }
      ?>
    </p>
    <a href="#" class="btn text-white btn-outline-danger">En savoir plus</a>
  </div>
</div>
</div>
<div class="col-12 col-lg-3 ">
  <div class="card bg-success mb-3" style="max-width: 18rem;">
  <img src="./img/img2.png"  style="width:70px"  class="card-img-top " alt="Image">
  <div class="card-body">
    <h5 class="card-title text-white">Nombre des formateurs inscrits</h5>
    <p class="card-text text-white">
      <?php
      require_once "conn.php";

      try {
          // Requête pour compter le nombre de stagiaires
          $sql = "SELECT COUNT(*) AS total_stagiaires FROM users WHERE role = 'formateur'";
          $stmt = $conn->prepare($sql);
          $stmt->execute();
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($result) {
              echo htmlspecialchars($result['total_stagiaires']);
          } else {
              echo "0";
          }
      } catch (PDOException $e) {
          echo "Erreur : " . htmlspecialchars($e->getMessage());
      }
      ?>
    </p>
    <a href="#" class="btn text-white btn-outline-dark">En savoir plus</a>
  </div>
</div>
</div>
<div class="col-12 col-lg-3 ">
  <div class="card bg-success mb-3" style="max-width: 18rem;">
  <img src="./img/img3.png"  style="width:70px"  class="card-img-top " alt="Image">
  <div class="card-body">
    <h5 class="card-title text-white"> Nombre des admins inscrits</h5>
    <p class="card-text text-white">
      <?php
      require_once "conn.php";

      try {
          // Requête pour compter le nombre de stagiaires
          $sql = "SELECT COUNT(*) AS total_stagiaires FROM users WHERE role = 'admin'";
          $stmt = $conn->prepare($sql);
          $stmt->execute();
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($result) {
              echo htmlspecialchars($result['total_stagiaires']);
          } else {
              echo "0";
          }
      } catch (PDOException $e) {
          echo "Erreur : " . htmlspecialchars($e->getMessage());
      }
      ?>
    </p>
    <a href="#" class="btn text-white btn-outline-primary">En savoir plus</a>
  </div>
</div>
</div>
</div>

<div class="row">
  <div class="col-12  table-responsive">
  <?php
require_once "conn.php";

try {
    // Requête SQL corrigée avec alias pour éviter les ambiguïtés
    $sql = "
        SELECT s.stg_id, s.nom AS stagiaire_nom, s.group_id, u.email, s.filiere_id
        FROM stagiaire s
        JOIN users u ON s.id_user = u.id ORDER BY s.stg_id DESC limit 5
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($users)) {
        echo "<table class='table table-striped table-sm-3 table-bordered table-hover' border='1'>";
        echo "<tr class='table-dark'>
                <th>ID</th>
                <th>Nom & Prénom</th>
                <th>Groupe</th>
                <th>Email</th>
                <th>Filière</th>
                <th>Actions</th>
              </tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['stg_id']) . "</td>";        
            echo "<td>" . htmlspecialchars($user['stagiaire_nom']) . "</td>";
            echo "<td>" . htmlspecialchars($user['group_id']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($user['filiere_id']) . "</td>";
            echo "<td>";
            echo "<a href='delete.php?id=" . htmlspecialchars($user['stg_id']) . "' class='btn btn-danger btn-sm'>Supprimer</a> ";
            echo "<a href='updateBaseDonnee.php?id=" . htmlspecialchars($user['stg_id']) . "' class='btn btn-warning btn-sm'>Modifier</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "La base de données est vide.";
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

  </div>
</div>
</div>
<script src="../sql/bootstrap-5.0.2-dist/js/bootstrap.js" crossorigin="anonymous"></script>
</body>
</html>
