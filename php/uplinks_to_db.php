<?php
// Connexion à la base de données MariaDB
$host = 'localhost';
$db   = 'serre';
$user = 'serre';
$pass = 'serre2025';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connexion échouée: ' . $e->getMessage();
    exit;
}

$json = file_get_contents("php://input");
$data = json_decode($json, true);

if (isset($data['object'])) {
    $timestamp = date('Y-m-d H:i:s');
    $humidite_sol_1 = $data['object']['humidite_bac_1'] ?? null;
    $humidite_sol_2 = $data['object']['humidite_bac_2'] ?? null;
    $temperature    = $data['object']['temperature'] ?? null;
    $humidite_air   = $data['object']['humidite_air'] ?? null;
    $luminosite     = $data['object']['luminosite'] ?? null;
    $co2            = $data['object']['co2'] ?? null;
    $courant        = $data['object']['courant'] ?? null;
    $tension        = $data['object']['tension'] ?? null;
    $batterie       = $data['object']['batterie'] ?? null;

    $sql = "INSERT INTO data (
        humidite_sol_1, humidite_sol_2, temperature, humidite_air,
        luminosite, co2, courant, tension, batterie, timestamp
    ) VALUES (
        :humidite_sol_1, :humidite_sol_2, :temperature, :humidite_air,
        :luminosite, :co2, :courant, :tension, :batterie, :timestamp
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':humidite_sol_1', $humidite_sol_1);
    $stmt->bindParam(':humidite_sol_2', $humidite_sol_2);
    $stmt->bindParam(':temperature',    $temperature);
    $stmt->bindParam(':humidite_air',   $humidite_air);
    $stmt->bindParam(':luminosite',     $luminosite);
    $stmt->bindParam(':co2',            $co2);
    $stmt->bindParam(':courant',        $courant);
    $stmt->bindParam(':tension',        $tension);
    $stmt->bindParam(':batterie',       $batterie);
    $stmt->bindParam(':timestamp',      $timestamp);

    $stmt->execute();

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Données invalides']);
    exit;
}
?>
