<?php
require_once "conn.php";

$filiere_id = 'FIL001'; // Exemple : ID de la filière
$group_id = 'GRP002';   // Exemple : ID du groupe

try {
    $stmt = $conn->prepare("CALL GetStagiairesByFiliereAndGroup(:filiere_id, :group_id)");
    $stmt->bindParam(':filiere_id', $filiere_id, PDO::PARAM_STR);
    $stmt->bindParam(':group_id', $group_id, PDO::PARAM_STR);
    $stmt->execute();

    $stagiaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($stagiaires)) {
        echo "<table class='table table-striped table-bordered'>";
        echo "<thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Groupe</th>
                    <th>Filière</th>
                </tr>
              </thead><tbody>";
        foreach ($stagiaires as $stagiaire) {
            echo "<tr>
                    <td>{$stagiaire['ID']}</td>
                    <td>{$stagiaire['Nom']}</td>
                    <td>{$stagiaire['Email']}</td>
                    <td>{$stagiaire['Groupe']}</td>
                    <td>{$stagiaire['Filiere']}</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Aucun stagiaire trouvé pour ces critères.</p>";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sql/bootstrap-5.0.2-dist/bootstrap.css">

    <title>Document</title>
</head>
<body class="bg-light">
    <div class="container-fluid">
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
                                <a class="nav-link active text-dark fs-4" href="">Acceuill</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark fs-4" href="note.php">Entrer note</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark fs-4" href="#">Contact</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    
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
                        <option value="CMP">CMP</option>
                    </select>
                </div>
                <!-- Sélection du groupe -->
                <div class="col-md-6">
                    <label for="group_id" class="form-label">Choisissez le groupe</label>
                    <select name="group_id" id="group_id" class="form-select" required>
                        <option value="DEV104">DEV104</option>
                        <option value="DEV103">DEV103</option>
                        <option value="GES01">GES01</option>
                    </select>
                </div>
                <!-- Bouton de recherche -->
                <div class="col-12">
                    <button type="submit" class="btn btn-success w-100">Rechercher</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Section pour afficher les résultats -->
    <div class="mt-4">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $filiere_id = $_POST['filiere_id'];
            $group_id = $_POST['group_id'];

            // Inclure la connexion et appeler la procédure
            require_once "conn.php";

            try {
                $stmt = $conn->prepare("CALL GetStagiairesByFiliereAndGroup(:filiere_idn, :group_idn)");
                $stmt->bindParam(':filiere_idn', $filiere_id, PDO::PARAM_STR);
                $stmt->bindParam(':group_idn', $group_id, PDO::PARAM_STR);
                $stmt->execute();
                $stagiaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Affichage des résultats
                if (!empty($stagiaires)) {
                    echo "<table class='table table-striped table-bordered'>";
                    echo "<thead class='table-dark'>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Groupe</th>
                                <th>Filière</th>
                            </tr>
                          </thead><tbody>";
                    foreach ($stagiaires as $stagiaire) {
                        echo "<tr>
                                <td>{$stagiaire['ID']}</td>
                                <td>{$stagiaire['Nom']}</td>
                                <td>{$stagiaire['Email']}</td>
                                <td>{$stagiaire['Groupe']}</td>
                                <td>{$stagiaire['Filiere']}</td>
                              </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<div class='alert alert-warning'>Aucun stagiaire trouvé pour ces critères.</div>";
                }
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Erreur : " . $e->getMessage() . "</div>";
            }
        }
        ?>
    </div>
</div>
    </div>
</div>

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
          $sql = "SELECT COUNT(*) AS total_stagiaires FROM stagiaire ";
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
    </div>
<script src="../sql/bootstrap-5.0.2-dist/bootstrap.bundle.min.js"></script>

</body>
</html>