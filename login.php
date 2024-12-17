<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StagiaireHub</title>
    <link rel="stylesheet" href="../sql/bootstrap-5.0.2-dist/bootstrap.css">
</head>
<body>
<div class="container-fluid bg-secondary  d-flex flex-column justify-content-center">
    <!-- Row for Header -->
    <div class="row bg-light py">
        <div class="col-md-2 d-flex justify-content-center align-items-center">
            <img class="img-fluid" src="ofppt.png" alt="OFPPT Logo" style="max-width: 80px;">
        </div>
       
    </div>

    <!-- Row for Login Form -->
    <div class="row justify-content-center my-5">
    <div class="col-md-6 text-center text-md-start d-flex flex-column justify-content-center">
            <h1 class="text-primary">StagiaireHub</h1>
            <p class="text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore quasi vel doloribus voluptatem nesciunt corporis.</p>
        </div>
        <div class="col-lg-6 col-md-8 col-sm-10 bg-white shadow rounded p-5">
            <h1 class="text-center text-success mb-4">Login</h1>
            <form action="" method="post" class="needs-validation" novalidate>
                <!-- Username -->
                <div class="mb-3">
                    <input type="text" name="email" class="form-control" placeholder="email" required>
                    <div class="invalid-feedback">Please enter your username.</div>
                </div>
                <!-- Password -->
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="invalid-feedback">Please enter your password.</div>
                </div>
                <!-- Forget Password -->
                <div class="mb-3 text-end">
                    <a href="#" class="text-decoration-none">Forget Password?</a>
                </div>
                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Connexion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../sql/bootstrap-5.0.2-dist/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include_once 'conn.php';  // Inclure votre fichier de connexion à la base de données
// Connexion à la base de données
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $login = $_POST['email'];
    $password = $_POST['password'];

    // Sécuriser les données pour éviter les injections SQL
    $login = htmlspecialchars($login);
    $password = htmlspecialchars($password);

    // Requête pour récupérer l'utilisateur en fonction du nom d'utilisateur
    $query = "SELECT * FROM users WHERE email = :login";  // Utilisation de l'alias :login pour éviter l'injection SQL
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->execute();

    // Vérifier si l'utilisateur existe
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si le mot de passe est correct avec password_verify()
        if ($password==$user['password']) {
            if($user["role"]=="stagiaire"){
                header("Location: stagiaire.php?id=" . $user["id"]);

                echo "Bonne connexion"; 
            }elseif($user["role"]=="formateur"){
                header("location: formateur.php" );
                echo "Bonne connexion"; 
            }elseif($user["role"]=="admin"){
                header('location: admin.php');
                echo "Bonne connexion";
            }else{
                echo "check votre role please";
            }
            
             // Connexion réussie
        } else {
           
            echo "Mauvaise connexion";
              // Mot de passe incorrect
        }
    } else {
        echo " Aucun donnes dans la table";  // Utilisateur non trouvé
    }
}  
?>

