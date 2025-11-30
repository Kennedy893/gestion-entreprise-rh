<link rel="stylesheet" href="<?= constant('BASE_URL') ?>public/assets/css/tempGeneral.css">

<div class="main-content">
    <div class="container">
        <h2><i class="fa-solid fa-calendar-check" style="color: var(--primary);"></i> Feuille de Temps - Sélection de la date</h2>

        <div class="card">
            <form method="get" action="<?php echo constant('BASE_URL'); ?>time/timecards">
                <div class="form-group">
                    <label for="date">Sélectionnez une date :</label>
                    <input type="date" id="date" name="date" required>
                    <div id="date-preview" class="date-preview"></div>
                </div>
                
                <button type="submit">
                    <i class="fa-solid fa-chart-line"></i> Voir les détails de la feuille de temps
                </button>
            </form>
        </div>

        <div class="info-box">
            <h3><i class="fa-solid fa-circle-info"></i> Information</h3>
            <ul>
                <li>Sélectionnez une date pour consulter la feuille de temps correspondante.</li>
                <li>Vous pourrez visualiser les **pointages** de tous les employés pour cette date.</li>
                <li>La feuille de temps inclut les **heures travaillées**, absences et retards.</li>
            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('date');
        const datePreview = document.getElementById('date-preview');
        
        // Définir la date d'aujourd'hui par défaut
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;

        function updateDatePreview(dateString) {
            if (dateString) {
                // Création d'une date en utilisant le fuseau horaire local (ajustement pour éviter les décalages d'un jour)
                const dateParts = dateString.split('-');
                const date = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);

                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                };
                const formattedDate = date.toLocaleDateString('fr-FR', options);
                datePreview.innerHTML = `<i class="fa-regular fa-calendar-alt"></i> Date sélectionnée : <strong>${formattedDate}</strong>`;
                datePreview.style.display = 'block';
            } else {
                datePreview.style.display = 'none';
            }
        }

        // Mettre à jour l'aperçu quand la date change
        dateInput.addEventListener('change', function() {
            updateDatePreview(this.value);
        });

        // Mettre à jour l'aperçu au chargement
        updateDatePreview(today);
    });
</script>