<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'projet_ofppt';  // Remplacez par le nom de votre base de données
$username = 'root';  // Nom d'utilisateur de la base de données
$password = '';  // Mot de passe de la base de données

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Sécuriser les données pour éviter les injections SQL
    $login = htmlspecialchars($login);
    $password = htmlspecialchars($password);

    // Requête pour récupérer l'utilisateur en fonction du nom d'utilisateur
    $query = "SELECT * FROM personne WHERE login = :login";  // Utilisation de l'alias :login pour éviter l'injection SQL
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->execute();

    // Vérifier si l'utilisateur existe
    if ($stmt->rowCount() >0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si le mot de passe est correct avec password_verify()
        if ($password==$user['password']) {
            echo "Bonne connexion";  // Connexion réussie
        } else {
            echo "Mauvaise connexion";  // Mot de passe incorrect
        }
    } else {
        echo " Aucun donnes dans la table";  // Utilisateur non trouvé
    }
}
?>

<!-- Formulaire HTML pour entrer le login et le mot de passe -->
<form method="POST">
    <label for="login">Nom d'utilisateur :</label>
    <input type="text" name="login" required>
    <br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required>
    <br>
    <button type="submit">Se connecter</button>
</form>
