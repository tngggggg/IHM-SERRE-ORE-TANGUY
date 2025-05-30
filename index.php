<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home_page_style.css">
    <title>ORE : Serre connectée</title>
    <script type="text/javascript" src="../js/home_page_script.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body data-loggedin="<?php echo isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'true' : 'false'; ?>">
    <img src="../img/logo_ore.png" alt="Logo ORE" id="logo">

    <h1>ORE : Serre connectée</h1>

    <div class="section">
        <h2>Données environnementales :</h2>
        <div class="data">- Température : <span id="temperature">-</span> °C</div>
        <div class="data">- Luminosité : <span id="luminosite">-</span> lux</div>
        <div class="data">- Humidité de l'air : <span id="humidite_air">-</span> %</div>
        <div class="data">- Humidité du bac 1 : <span id="humidite_sol_1">-</span> %</div>
        <div class="data">- Humidité du bac 2 : <span id="humidite_sol_2">-</span> %</div>
        <div class="data">- Concentration en CO2 : <span id="co2">-</span> ppm</div>
    </div>

    <div class="section">
        <h2>Données énergétiques :</h2>
        <div class="data">- Tension de la production solaire : <span id="tension">-</span> V</div>
        <div class="data">- Courant de la production solaire : <span id="courant">-</span> A</div>
        <div class="data">- Niveau de batterie : <span id="batterie">-</span> %</div>
    </div>

    <div class="section">
        <form action="/php/export.php" method="POST">
            <button class="button" type="submit" name="export" id="telecharger-donnees">
                Télécharger les données de la serre
            </button>
        </form>
    </div>

    <div class="section">
        <button class="button admin-only" id="configurer-seuils">Configurer les seuils</button>

        <div id="seuils-config" style="display: none;">
            <!-- Seuils pour vérins -->
            <div class="paired-inputs">
                <div class="data">
                    <label for="seuil_verin_ppm_ouverture">Seuil ouverture vérins (ppm) :</label>
                    <input type="number" id="seuil_verin_ppm_ouverture" class="frequence" placeholder="Saisir valeur">
                </div>
                <div class="data">
                    <label for="seuil_verin_ppm_fermeture">Seuil fermeture vérins (ppm) :</label>
                    <input type="number" id="seuil_verin_ppm_fermeture" class="frequence" placeholder="Saisir valeur">
                </div>
            </div>

            <div class="paired-inputs">
                <div class="data">
                    <label for="seuil_verin_hum_ouverture">Seuil ouverture vérins (%rH) :</label>
                    <input type="number" id="seuil_verin_hum_ouverture" class="frequence" placeholder="Saisir valeur">
                </div>
                <div class="data">
                    <label for="seuil_verin_hum_fermeture">Seuil fermeture vérins (%rH) :</label>
                    <input type="number" id="seuil_verin_hum_fermeture" class="frequence" placeholder="Saisir valeur">
                </div>
            </div>

            <div class="paired-inputs">
                <div class="data">
                    <label for="seuil_verin_temp_ouverture">Seuil ouverture vérins (°C) :</label>
                    <input type="number" id="seuil_verin_temp_ouverture" class="frequence" placeholder="Saisir valeur">
                </div>
                <div class="data">
                    <label for="seuil_verin_temp_fermeture">Seuil fermeture vérins (°C) :</label>
                    <input type="number" id="seuil_verin_temp_fermeture" class="frequence" placeholder="Saisir valeur">
                </div>
            </div>

            <!-- Seuils pour électrovannes -->
            <div class="paired-inputs">
                <div class="data">
                    <label for="seuil_electrovanne_ouverture_1">Seuil ouverture électrovanne 1 (%rH) :</label>
                    <input type="number" id="seuil_electrovanne_ouverture_1" class="frequence" placeholder="Saisir valeur">
                </div>
                <div class="data">
                    <label for="seuil_electrovanne_fermeture_1">Seuil fermeture électrovanne 1 (%rH) :</label>
                    <input type="number" id="seuil_electrovanne_fermeture_1" class="frequence" placeholder="Saisir valeur">
                </div>
            </div>

            <div class="paired-inputs">
                <div class="data">
                    <label for="seuil_electrovanne_ouverture_2">Seuil ouverture électrovanne 2 (%rH) :</label>
                    <input type="number" id="seuil_electrovanne_ouverture_2" class="frequence" placeholder="Saisir valeur">
                </div>
                <div class="data">
                    <label for="seuil_electrovanne_fermeture_2">Seuil fermeture électrovanne 2 (%rH) :</label>
                    <input type="number" id="seuil_electrovanne_fermeture_2" class="frequence" placeholder="Saisir valeur">
                </div>
            </div>

            <button class="button admin-only" id="valider-seuils">Valider les seuils</button>
        </div>
    </div>

    <div class="section">
        <button class="button admin-only" id="piloter-equipements">Piloter les équipements</button>

        <div id="equipements-controls" style="display: none;">
            <button class="button_2 admin-only" id="ouvrir-electrovanne-1">Ouvrir électrovanne 1</button>
            <button class="button_2 admin-only" id="fermer-electrovanne-1">Fermer électrovanne 1</button>
            <button class="button_2 admin-only" id="ouvrir-electrovanne-2">Ouvrir électrovanne 2</button>
            <button class="button_2 admin-only" id="fermer-electrovanne-2">Fermer électrovanne 2</button>            
            <button class="button_2 admin-only" id="ouvrir-verin-1">Ouvrir vérin 1</button>
            <button class="button_2 admin-only" id="fermer-verin-1">Fermer vérin 1</button>
            <button class="button_2 admin-only" id="ouvrir-verin-2">Ouvrir vérin 2</button>
            <button class="button_2 admin-only" id="fermer-verin-2">Fermer vérin 2</button>           
        </div>
    </div>

    <div class="input-section">
        <button class="button admin-only" id="configurer-frequence">
            Fréquence d'enregistrement des échantillons (en secondes)
        </button>
        <div id="frequence-config" style="display: none; margin-top: 10px;">
            <input type="number" id="frequence" class="frequence" placeholder="Saisir valeur">
        </div>
    </div>

    <div class="connexion" id="connexion">
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
            <form action="/php/logout.php" method="POST">
                <button type="submit" class="submit" id="authButton">Déconnexion</button>
            </form>
        <?php } else { ?>
            <button class="submit" id="authButton">Connexion</button>
        <?php } ?>
    </div>
</body>
</html>
