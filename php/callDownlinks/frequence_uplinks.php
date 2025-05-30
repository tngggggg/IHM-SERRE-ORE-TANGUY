<?php
header('Content-Type: application/json');

// Récupère et décode le JSON envoyé par le client (fetch)
$input = json_decode(file_get_contents('php://input'), true);

// Vérifie que la valeur est bien présente et numérique
if (!isset($input['valeurFrequence']) || !is_numeric($input['valeurFrequence'])) {
    http_response_code(400);
    echo json_encode(["error" => "Paramètre 'valeurFrequence' manquant ou invalide"]);
    exit;
}

// Sécurisation de la valeur pour l'exécuter en ligne de commande
$valeur = escapeshellarg($input['valeurFrequence']);
$node = "/usr/bin/node";
$dir = "/home/proto-projet/chirpstack-downlink";

// Liste des scripts Node.js à exécuter
$scripts = [
    "frequence_humterre.js",
    "frequence_humairco2temp.js",
    "frequence_tensioncourant.js"
];

// Exécution de chaque script avec la valeur
foreach ($scripts as $script) {
    $path = escapeshellarg("$dir/$script");
    exec("$node $path $valeur");
}

// Réponse JSON de confirmation
echo json_encode([
    "success" => true,
    "frequenceEnvoyee" => $input['valeurFrequence'],
    "scriptsAppeles" => $scripts
]);
?>
