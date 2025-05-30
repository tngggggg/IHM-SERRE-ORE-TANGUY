document.addEventListener("DOMContentLoaded", function () {
    // Fonction pour rafraîchir les données capteurs
    function rafraichirDonneesCapteurs() {
        fetch("../php/get_db_data.php")
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error("Erreur BDD :", data.error);
                } else {
                    document.getElementById("temperature").textContent = data.temperature;
                    document.getElementById("luminosite").textContent = data.luminosite;
                    document.getElementById("co2").textContent = data.co2;
                    document.getElementById("humidite_air").textContent = data.humidite_air;
                    document.getElementById("humidite_sol_1").textContent = data.humidite_sol_1;
                    document.getElementById("humidite_sol_2").textContent = data.humidite_sol_2;
                    document.getElementById("tension").textContent = data.tension;
                    document.getElementById("courant").textContent = data.courant;
                    document.getElementById("batterie").textContent = data.batterie;
                }
            })
            .catch(error => {
                console.error("Erreur lors de la récupération des données capteurs :", error);
            });
    }

    // Rafraîchir une première fois à l'ouverture
    rafraichirDonneesCapteurs();
    // Puis toutes les 3 secondes
    setInterval(rafraichirDonneesCapteurs, 3000);

    // GESTION AFFICHAGE SEUILS
    const configurerButton = document.getElementById('configurer-seuils');
    const seuilsConfig = document.getElementById('seuils-config');

    configurerButton.addEventListener('click', function () {
        const isVisible = seuilsConfig.style.display === 'block';
        seuilsConfig.style.display = isVisible ? 'none' : 'block';
    });

    // GESTION AFFICHAGE EQUIPEMENTS
    const piloterButton = document.getElementById('piloter-equipements');
    const equipementsControls = document.getElementById('equipements-controls');

    piloterButton.addEventListener('click', function () {
        const isVisible = equipementsControls.style.display === 'block';
        equipementsControls.style.display = isVisible ? 'none' : 'block';
    });

    // GESTION VALIDATION SEUILS
    const validerButton = document.getElementById('valider-seuils');
    validerButton.addEventListener('click', function () {
        const seuilVerin = {
            seuilVerinPpmOuverture: document.getElementById('seuil_verin_ppm_ouverture').value,
            seuilVerinPpmFermeture: document.getElementById('seuil_verin_ppm_fermeture').value,
            seuilVerinHumOuverture: document.getElementById('seuil_verin_hum_ouverture').value,
            seuilVerinHumFermeture: document.getElementById('seuil_verin_hum_fermeture').value,
            seuilVerinTempOuverture: document.getElementById('seuil_verin_temp_ouverture').value,
            seuilVerinTempFermeture: document.getElementById('seuil_verin_temp_fermeture').value
        };

        const seuilElectrovanne = {
            seuilElectrovanneBac1Ouverture: document.getElementById('seuil_electrovanne_ouverture_1').value,
            seuilElectrovanneBac1Fermeture: document.getElementById('seuil_electrovanne_fermeture_1').value,
            seuilElectrovanneBac2Ouverture: document.getElementById('seuil_electrovanne_ouverture_2').value,
            seuilElectrovanneBac2Fermeture: document.getElementById('seuil_electrovanne_fermeture_2').value
        };

        fetch('../php/callDownlinks/seuils_hum_temp_co2.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(seuilVerin)
        })
        .then(response => response.json())
        .then(data => console.log('Réponse de seuils_hum_temp_co2.php (vérins):', data))
        .catch(error => console.error('Erreur seuils_hum_temp_co2.php:', error));

        fetch('../php/callDownlinks/seuils_humidite_bacs.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                seuilElectrovanneBac1Ouverture: seuilElectrovanne.seuilElectrovanneBac1Ouverture,
                seuilElectrovanneBac1Fermeture: seuilElectrovanne.seuilElectrovanneBac1Fermeture
            })
        })
        .then(response => response.json())
        .then(data => console.log('Réponse de seuils_humidite_bacs.php:', data))
        .catch(error => console.error('Erreur seuils_humidite_bacs.php:', error));

        fetch('../php/callDownlinks/seuils_humidite_bacs.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                seuilElectrovanneBac2Ouverture: seuilElectrovanne.seuilElectrovanneBac2Ouverture,
                seuilElectrovanneBac2Fermeture: seuilElectrovanne.seuilElectrovanneBac2Fermeture
            })
        })
        .then(response => response.json())
        .then(data => console.log('Réponse de seuils_humidite_bacs.php:', data))
        .catch(error => console.error('Erreur seuils_humidite_bacs.php:', error));
    });

    // GESTION FREQUENCE D'ENREGISTREMENT
    const configFrequenceButton = document.getElementById('configurer-frequence');
    const frequenceConfig = document.getElementById('frequence-config');
    let validerFrequenceBtn = null;

    configFrequenceButton.addEventListener('click', function () {
        const isVisible = frequenceConfig.style.display === 'block';
        frequenceConfig.style.display = isVisible ? 'none' : 'block';

        if (!validerFrequenceBtn) {
            validerFrequenceBtn = document.createElement('button');
            validerFrequenceBtn.className = 'button';
            validerFrequenceBtn.innerText = 'Valider la fréquence';
            frequenceConfig.appendChild(validerFrequenceBtn);

            validerFrequenceBtn.addEventListener('click', function () {
                const valeurFrequence = document.getElementById('frequence').value;
                fetch('../php/callDownlinks/frequence_uplinks.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ valeurFrequence: valeurFrequence })
                })
                .then(response => response.json())
                .then(data => console.log('Réponse de frequences.php :', data))
                .catch(error => console.error('Erreur frequences.php:', error));
            });
        }
    });

    // PILOTAGE ELECTROVANNES
    const ouvrirElectrovanne1 = document.getElementById('ouvrir-electrovanne-1');
    const fermerElectrovanne1 = document.getElementById('fermer-electrovanne-1');
    const ouvrirElectrovanne2 = document.getElementById('ouvrir-electrovanne-2');
    const fermerElectrovanne2 = document.getElementById('fermer-electrovanne-2');

    ouvrirElectrovanne1.addEventListener('click', function () {
        fetch('../php/callDownlinks/controle_electrovannes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'O', electrovanne: 1})
        })
        .then(response => response.json())
        .then(data => console.log('Ouverture électrovanne 1:', data))
        .catch(error => console.error('Erreur ouverture électrovanne 1:', error));
    });

    fermerElectrovanne1.addEventListener('click', function () {
        fetch('../php/callDownlinks/controle_electrovannes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'F', electrovanne: 1})
        })
        .then(response => response.json())
        .then(data => console.log('Fermeture électrovanne 1:', data))
        .catch(error => console.error('Erreur fermeture électrovanne 1:', error));
    });

    ouvrirElectrovanne2.addEventListener('click', function () {
        fetch('../php/callDownlinks/controle_electrovannes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'O', electrovanne: 2})
        })
        .then(response => response.json())
        .then(data => console.log('Ouverture électrovanne 2:', data))
        .catch(error => console.error('Erreur ouverture électrovanne 2:', error));
    });

    fermerElectrovanne2.addEventListener('click', function () {
        fetch('../php/callDownlinks/controle_electrovannes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'F', electrovanne: 2})
        })
        .then(response => response.json())
        .then(data => console.log('Fermeture électrovanne 2:', data))
        .catch(error => console.error('Erreur fermeture électrovanne 2:', error));
    });

    // PILOTAGE VERINS
    const ouvrirVerin1 = document.getElementById('ouvrir-verin-1');
    const fermerVerin1 = document.getElementById('fermer-verin-1');
    const ouvrirVerin2 = document.getElementById('ouvrir-verin-2');
    const fermerVerin2 = document.getElementById('fermer-verin-2');

    ouvrirVerin1.addEventListener('click', function () {
        fetch('../php/callDownlinks/controle_verins.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'O', verin: 1 })
        })
        .then(response => response.json())
        .then(data => console.log('Ouverture vérin 1:', data))
        .catch(error => console.error('Erreur ouverture vérin 1:', error));
    });

    fermerVerin1.addEventListener('click', function () {
        fetch('../php/callDownlinks/controle_verins.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'F', verin: 1 })
        })
        .then(response => response.json())
        .then(data => console.log('Fermeture vérin 1:', data))
        .catch(error => console.error('Erreur fermeture vérin 1:', error));
    });

    ouvrirVerin2.addEventListener('click', function () {
        fetch('../php/callDownlinks/controle_verins.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'O', verin: 2 })
        })
        .then(response => response.json())
        .then(data => console.log('Ouverture vérin 2:', data))
        .catch(error => console.error('Erreur ouverture vérin 2:', error));
    });

    fermerVerin2.addEventListener('click', function () {
        fetch('../php/callDownlinks/controle_verins.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'F', verin: 2 })
        })
        .then(response => response.json())
        .then(data => console.log('Fermeture vérin 2:', data))
        .catch(error => console.error('Erreur fermeture vérin 2:', error));
    });

    // GESTION REDIRECTION VERS LA PAGE DE CONNEXION
    const authButton = document.getElementById('authButton');
    if (authButton) {
        authButton.addEventListener('click', function () {
            window.location.href = 'http://192.168.137.15/html/login_page.html';
        });
    }

    // EXPORT CSV
    const downloadButton = document.getElementById("telecharger-donnees");
    if (downloadButton) {
        downloadButton.addEventListener("click", function(event) {
            event.preventDefault();

            fetch("../php/export.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `export=true`
            })
            .then(response => response.blob())
            .then(blob => {
                const link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = "data_serre.csv";
                link.click();
            })
            .catch(error => console.error("Erreur lors du téléchargement:", error));
        });
    }

    // Restriction usage boutons
    const isLoggedIn = document.body.dataset.loggedin === 'true';

    if (!isLoggedIn) {
        document.querySelectorAll('.admin-only').forEach(button => {
            button.classList.add('disabled');
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopImmediatePropagation();
                alert("Vous devez être connecté en tant qu'admin pour piloter les équipements de la serre.");
            }, true);
        });
    }
});
