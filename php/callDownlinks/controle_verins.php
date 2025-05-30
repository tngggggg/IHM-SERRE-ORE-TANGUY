<?php
// Lecture des données POST
$input = json_decode(file_get_contents('php://input'), true);

// Vérification des champs requis
if (
    !isset($input['verin']) || !in_array($input['verin'], [1, 2]) ||
    !isset($input['action']) || !in_array($input['action'], ['O', 'F'])
) {
    http_response_code(400);
    echo json_encode(["error" => "Paramètres invalides. Requiert 'verin' (1 ou 2) et 'action' ('O' ou 'F')"]);
    exit;
}

// Paramètres
$verin = (int)$input['verin']; // 1 ou 2
$action = strtoupper($input['action']);      // 'O' ou 'F'

// Mapping redirection
$scriptMap = [
    '1' => ['O' => 'ouvrirVerin1.js', 'F' => 'fermerVerin1.js'],
    '2' => ['O' => 'ouvrirVerin2.js', 'F' => 'fermerVerin2.js'],
];

// Résolution du chemin du script à exécuter
$scriptName = $scriptMap[$verin][$action];
$scriptPath = escapeshellarg("/home/proto-projet/chirpstack-downlink/$scriptName"); // Mettre répertoire node.js

// Exécution du script Node.js
exec("/usr/bin/node $scriptPath 2>&1", $output, $returnCode);

// Réponse JSON
if ($returnCode === 0) {
    echo json_encode(["success" => true, "output" => $output]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Erreur lors de l’exécution du script Node.js", "details" => $output]);
}
?>
