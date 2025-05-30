<?php
$host = 'localhost';
$db   = 'serre';
$user = 'serre';
$pass = 'serre2025';

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si le paramètre 'export' est passé en POST
    if (isset($_POST['export'])) {
        // Requête SQL pour récupérer les données
        $sql = "SELECT 
                    temperature,
                    luminosite,
                    co2,
                    humidite_sol_1,
                    humidite_sol_2,
                    humidite_air,
                    tension,
                    courant,
                    batterie,
                    timestamp  
                FROM data";

        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // En-têtes HTTP pour forcer le téléchargement du CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="data_serre.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Ouverture du flux de sortie
        $output = fopen('php://output', 'w');

        // Écrire les en-têtes du fichier CSV
        fputcsv($output, ['Temperature', 'Luminosite', 'CO2', 'Humidite sol 1', 'Humidite sol 2', 'Humidite air', 'Tension', 'Courant', 'Batterie', 'Date'], ';');

        // Écrire chaque ligne du résultat
        foreach ($rows as $row) {
            fputcsv($output, [
                $row['temperature'],
                $row['luminosite'],
                $row['co2'],
                $row['humidite_sol_1'],
                $row['humidite_sol_2'],
                $row['humidite_air'],
                $row['tension'],
                $row['courant'],
                $row['batterie'],
                "\t" . $row['timestamp'] // Ajout de tabulation pour forcer Excel à conserver le format date/heure
            ], ';');
        }

        fclose($output);
        exit;
    }

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>
