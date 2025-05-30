<?php
session_start(); 

// Infos BDD
$host = 'localhost';
$db   = 'serre';
$user = 'serre';
$pass = 'serre2025';

try {
    // Connexion à la BDD avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        $sql = "SELECT * FROM admin_web WHERE login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['password'] === $password) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $login;
                echo json_encode(["success" => true, "message" => "Connexion réussie"]);
            } else {
                echo json_encode(["success" => false, "message" => "Informations incorrectes"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Utilisateur non trouvé"]);
        }
    }

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données"]);
}
?>
