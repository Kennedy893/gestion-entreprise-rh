<style>
    .main-content {
        width: 1350px;
        margin: 20px 350px;
    }

    

    h2 {
        font-size: 1.8rem;
        color: var(--text-main);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* --- CARTE PRINCIPALE --- */
    .card {
        background: var(--bg-card);
        border-radius: var(--radius);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 30px;
    }

    /* --- FORMULAIRE --- */
    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-main);
    }

    #date {
        width: 100%;
        max-width: 300px;
        /* Limite la largeur de l'input date */
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    #date:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .date-preview {
        margin-top: 15px;
        padding: 10px 15px;
        background-color: var(--info-bg);
        border: 1px solid var(--info-border);
        border-radius: 8px;
        color: var(--primary-dark);
        font-weight: 500;
        font-size: 1.05rem;
        display: none;
        /* Géré par JS */
    }

    button[type="submit"] {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
        transition: background-color 0.2s, transform 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    button[type="submit"]:hover {
        background-color: var(--primary-dark);
        transform: translateY(-1px);
    }

    /* --- BOÎTE D'INFORMATION --- */
    .info-box {
        background-color: var(--info-bg);
        border: 1px solid var(--info-border);
        padding: 20px;
        border-radius: var(--radius);
        color: var(--text-main);
    }

    .info-box h3 {
        font-size: 1.15rem;
        color: var(--primary-dark);
        margin-top: 0;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-box ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-box ul li {
        margin-bottom: 8px;
        font-size: 0.95rem;
        color: var(--secondary);
        position: relative;
        padding-left: 20px;
    }

    .info-box ul li::before {
        content: "•";
        color: var(--primary);
        font-weight: bold;
        display: inline-block;
        width: 1em;
        margin-left: -1em;
        position: absolute;
        left: 0;
        top: 0;
    }
</style>
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