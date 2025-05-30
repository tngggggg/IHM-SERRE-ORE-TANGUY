<?php
header('Content-Type: application/json');

// Récupération des données JSON envoyées par le client (fetch)
$input = json_decode(file_get_contents('php://input'), true);

// Vérifie que les données reçues sont valides
if (!$input || !is_array($input)) {
    http_response_code(400);
    echo json_encode(["error" => "Requête invalide"]);
    exit;
}

// Correspondance entre les clés JSON attendues et les scripts Node.js associés
$scriptMap = [
    "seuilElectrovanneBac1Ouverture" => "seuilVanne1Ouverture.js",
    "seuilElectrovanneBac1Fermeture" => "seuilVanne1Fermeture.js",
    "seuilElectrovanneBac2Ouverture" => "seuilVanne2Ouverture.js",
    "seuilElectrovanneBac2Fermeture" => "seuilVanne2Fermeture.js"
];

// Chemin vers l’exécutable Node.js et le répertoire des scripts
$node = "/usr/bin/node";
$dir = "/home/proto-projet/chirpstack-downlink";

// Pour enregistrer les clés qui ont bien été traitées
$success = [];

// Parcours chaque clé attendue
foreach ($scriptMap as $key => $script) {
    if (!empty($input[$key])) {
        // Récupère la valeur et sécurise-la pour l'utiliser en ligne de commande
        $arg = escapeshellarg($input[$key]);

        // Génère le chemin complet du script Node.js
        $path = escapeshellarg("$dir/$script");

        // Exécute le script Node.js avec la valeur du seuil en argument
        exec("$node $path $arg");

        // Ajoute cette clé à la liste des traitements réussis
        $success[] = $key;
    }
}

// S’il n’y a eu aucun seuil reconnu ou transmis, renvoie une erreur
if (empty($success)) {
    http_response_code(400);
    echo json_encode(["error" => "Aucun seuil transmis"]);
} else {
    // Sinon on confirme les clés qui ont été traitées
    echo json_encode(["success" => true, "modifiés" => $success]);
}
?>
