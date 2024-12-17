<?php
require_once "conn.php"; // Inclure la connexion à la base de données

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête SQL pour récupérer les informations actuelles
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Récupérer les données de l'utilisateur
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe
    if ($user) {
        $login = $user['login'];
        $password = $user['password'];
        $nom = $user['nom'];
        $genre = $user['genre'];
        $nationalite = $user['nationalite'];
        $email = $user['email'];
        $role = $user['role'];
        $adress = $user['adress'];
        $phone = $user['phone'];
        $date_naissance = $user['date_naissance'];
    } else {
        echo "Utilisateur non trouvé.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'utilisateur</title>
</head>
<body>

    <h2>Modifier l'utilisateur</h2>

    <?php if (isset($user)): ?>
        <form  method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

            <label for="login">Login:</label>
            <input type="text" id="login" name="login" value="<?= htmlspecialchars($login) ?>" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?= htmlspecialchars($password) ?>" required><br><br>

            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>" required><br><br>

            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($genre) ?>" required><br><br>

            <label for="nationalite">Nationalité:</label>
            <input type="text" id="nationalite" name="nationalite" value="<?= htmlspecialchars($nationalite) ?>" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

            <label for="role">Role:</label>
            <input type="text" id="role" name="role" value="<?= htmlspecialchars($role) ?>" required><br><br>

            <label for="adress">Adresse:</label>
            <input type="text" id="adress" name="adress" value="<?= htmlspecialchars($adress) ?>" required><br><br>

            <label for="phone">Téléphone:</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>" required><br><br>

            <label for="date_naissance">Date de Naissance:</label>
            <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($date_naissance) ?>" required><br><br>

            <button type="submit">Modifier</button>
        </form>
    <?php endif; ?>

</body>
</html>
<?php
require_once "conn.php"; // Inclure la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $id = $_POST['id'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $nom = $_POST['nom'];
    $genre = $_POST['genre'];
    $nationalite = $_POST['nationalite'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $adress = $_POST['adress'];
    $phone = $_POST['phone'];
    $date_naissance = $_POST['date_naissance'];

    try {
        // Préparer la requête SQL pour mettre à jour l'utilisateur
        $sql = "UPDATE users
                SET
                    login = :login,
                    password = :password,
                    nom = :nom,
                    genre = :genre,
                    nationalite = :nationalite,
                    email = :email,
                    role = :role,
                    adress = :adress,
                    phone = :phone,
                    date_naissance = :date_naissance
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        // Lier les paramètres
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':nationalite', $nationalite);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':adress', $adress);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':id', $id);

        // Exécuter la mise à jour
        $stmt->execute();
        
        // Rediriger après succès
        header("Location: add_stagiaire.php?message=success");
        exit;
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

}
?>
