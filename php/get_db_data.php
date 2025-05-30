<?php
$host = 'localhost';
$db   = 'serre';
$user = 'serre';
$pass = 'serre2025';

try {
    // Connexion à la BDD avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer la dernière entrée
    $sql = "SELECT * FROM data ORDER BY id_data DESC LIMIT 1";
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode([
            "temperature"      => $row["temperature"],
            "luminosite"       => $row["luminosite"],
            "co2"              => $row["co2"],
            "humidite_air"     => $row["humidite_air"],
            "humidite_sol_1"   => $row["humidite_sol_1"],
            "humidite_sol_2"   => $row["humidite_sol_2"],
            "tension"          => $row["tension"],
            "courant"          => $row["courant"],
            "batterie"         => $row["batterie"]
        ]);
    } else {
        echo json_encode(["error" => "Aucune donnée trouvée"]);
    }

} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
}
?>
