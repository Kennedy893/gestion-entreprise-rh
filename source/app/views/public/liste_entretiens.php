<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title ?? "Entretiens & Candidats") ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/public/assets/css/public.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .public-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .public-header h1 {
            margin: 0 0 10px 0;
            font-size: 2rem;
        }
        .public-header p {
            margin: 0;
            opacity: 0.9;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 15px;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.3);
        }
        .flip-container { 
            max-width: 1200px; 
            margin: 30px auto; 
            padding: 0 20px; 
        }
        .center-action { 
            text-align: center; 
            margin-bottom: 18px; 
        }
        .flip-button {
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            color: #fff; 
            border: 0; 
            padding: 12px 24px;
            border-radius: 999px; 
            cursor: pointer;
            font-size: 1rem; 
            font-weight: 600;
            box-shadow: 0 6px 18px rgba(59,130,246,0.25);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .flip-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(59,130,246,0.35);
        }
        .flip-card { 
            perspective: 1200px; 
        }
        .flip-card-inner { 
            position: relative; 
            width: 100%; 
            min-height: 360px; 
            transform-style: preserve-3d; 
            transition: transform 0.7s; 
        }
        .flip-card-front, .flip-card-back {
            backface-visibility: hidden; 
            position: absolute; 
            top: 0; 
            left: 0; 
            width: 100%; 
            padding: 20px; 
            border-radius: 12px; 
            background: #fff; 
            box-shadow: 0 6px 22px rgba(15,23,42,0.06);
        }
        .flip-card-back { 
            transform: rotateY(180deg); 
        }
        .flipped .flip-card-inner { 
            transform: rotateY(180deg); 
        }
        .entretiens-grid, .candidats-grid {
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); 
            gap: 20px;
        }
        .card { 
            border-radius: 12px; 
            padding: 20px; 
            background: #fff; 
            border: 1px solid #e6eef8; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        .card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .card h3 { 
            margin: 0 0 12px 0; 
            font-size: 1.2rem; 
            color: #1e293b; 
        }
        .meta { 
            color: #64748b; 
            font-size: 0.95rem; 
            margin-bottom: 12px; 
            line-height: 1.6; 
        }
        .pill { 
            padding: 6px 12px; 
            border-radius: 999px; 
            cursor: pointer; 
            border: 1px solid #dbeafe; 
            background: #f8fafc; 
            font-weight: 600; 
            text-decoration: none; 
            color: inherit; 
            display: inline-block; 
            transition: all 0.3s;
        }
        .pill:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
        }
        .pill.active { 
            background: linear-gradient(90deg, #3b82f6, #2563eb); 
            color: #fff; 
            border-color: transparent; 
        }
        .empty-state { 
            text-align: center; 
            padding: 60px 20px; 
            color: #64748b; 
            grid-column: 1/-1;
        }
        .status-badge { 
            padding: 8px 14px; 
            border-radius: 8px; 
            font-weight: 700; 
            font-size: 0.9rem; 
            display: inline-flex; 
            align-items: center; 
            gap: 8px; 
            margin-top: 12px; 
        }
        .status-recu, .status-reussi { 
            background: #dcfce7; 
            color: #166534; 
        }
        .status-rejete { 
            background: #fee2e2; 
            color: #991b1b; 
        }
        .status-attente, .status-formation { 
            background: #fff7ed; 
            color: #92400e; 
        }
        .filter-toolbar { 
            display: flex; 
            gap: 10px; 
            align-items: center; 
            justify-content: flex-end; 
            margin-bottom: 12px; 
        }
        .btn-formation {
            margin-top: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
        }
        .btn-formation:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-confirmation {
            margin-top: 8px;
            background: #10b981;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
        }
        .btn-confirmation:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        @media (max-width: 768px) {
            .flip-button, .filter-toolbar { 
                width: 100%; 
                justify-content: center; 
            }
            .filter-toolbar { flex-wrap: wrap; }
            .entretiens-grid, .candidats-grid { grid-template-columns: 1fr; }
            .public-header { padding: 20px; }
            .public-header h1 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="public-header">
        <h1><i class="fas fa-calendar-alt"></i> Dates d'entretien planifiées</h1>
        <p>Consultez le statut de votre entretien et des démarches à suivre</p>
        <a href="/" class="btn-back"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
    </div>

    <div class="flip-container" id="flipContainerWrapper">
        <div class="center-action">
            <button class="flip-button" id="flipButton">
                <i class="fas fa-exchange-alt"></i> Voir les candidats (Reçus / Non reçus / En attente)
            </button>
        </div>

        <div class="flip-card" id="flipCardRoot">
            <div class="flip-card-inner">
                <!-- FRONT : ENTRETIENS -->
                <div class="flip-card-front">
                    <h2><i class="fas fa-calendar-check"></i> Entretiens planifiés</h2>

                    <?php if (!empty($_GET['success']) && $_GET['success'] === 'questionnaire_submitted'): ?>
                        <div style="background: #dcfce7; border-left: 4px solid #22c55e; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #166534;">
                            <i class="fas fa-check-circle"></i> <strong>Questionnaire envoyé avec succès !</strong><br>
                            <small>Votre évaluation a été transmise au service RH. Ils examineront votre dossier.</small>
                        </div>
                    <?php elseif (!empty($_GET['success']) && $_GET['success'] === 'formation_submitted'): ?>
                        <div style="background: #dcfce7; border-left: 4px solid #22c55e; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #166534;">
                            <i class="fas fa-check-circle"></i> <strong>Formulaire de formation enregistré !</strong><br>
                            <small>Le formulaire a été sauvegardé. Vous pouvez maintenant accéder à la confirmation ci-dessous.</small>
                        </div>
                    <?php elseif (!empty($_GET['error'])): ?>
                        <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #991b1b;">
                            <i class="fas fa-exclamation-circle"></i> <strong>Erreur !</strong><br>
                            <small><?= htmlspecialchars($_GET['error']) ?></small>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($entretiens) || !is_array($entretiens)): ?>
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h3>Aucun entretien planifié</h3>
                            <p>Les dates d'entretien seront affichées ici dès qu'elles seront définies.</p>
                        </div>
                    <?php else: ?>
                        <div class="entretiens-grid">
                            <?php foreach ($entretiens as $entretien): 
                                $decision = $entretien['decision_finale'] ?? '';
                                $statutFormationOriginal = $entretien['statut_formation'] ?? '';
                                $idMiseFormation = $entretien['id_mise_formation'] ?? null;
                                $idCandidature = $entretien['id_candidature'] ?? null;
                                
                                // Récupérer le statut le plus récent en base pour cette candidature
                                $currentStatut = $statutFormationOriginal;
                                try {
                                    $dbCheck = \Flight::db();
                                    $stmtCheck = $dbCheck->prepare("SELECT id, statut FROM Candidat_Mise_Formation WHERE id_candidature = ? ORDER BY id DESC LIMIT 1");
                                    $stmtCheck->execute([$idCandidature]);
                                    $mf = $stmtCheck->fetch(\PDO::FETCH_ASSOC);
                                    if ($mf) {
                                        $currentStatut = $mf['statut'];
                                        $idMiseFormation = $mf['id'];
                                    }
                                } catch (\Throwable $e) {
                                    error_log('liste_entretiens.php: DB check failed for candidature ' . $idCandidature . ' - ' . $e->getMessage());
                                }
                                
                                // Déterminer si le formulaire a été soumis (statut BD prioritaire)
                                $formulaireSoumis = false;
                                if (in_array($currentStatut, ['en_formation', 'questionnaire_soumis', 'terminee'])) {
                                    $formulaireSoumis = true;
                                } elseif (!empty($_SESSION['formation_data']) && 
                                    (($_SESSION['formation_data']['id_candidature'] ?? '') == $idCandidature)) {
                                    $formulaireSoumis = true;
                                }
                                
                                if ($decision === 'accepte') {
                                    $statutLabel = 'Entretien fini et réussi';
                                    $statutClass = 'status-reussi';
                                    $statusIcon = 'fa-check-circle';
                                } elseif (in_array($currentStatut, ['proposee', 'acceptee', 'en_formation', 'questionnaire_soumis', 'terminee'])) {
                                    $statutLabel = 'Entretien fini, formation requise';
                                    $statutClass = 'status-formation';
                                    $statusIcon = 'fa-graduation-cap';
                                } elseif ($decision === 'rejete') {
                                    $statutLabel = 'Rejeté';
                                    $statutClass = 'status-rejete';
                                    $statusIcon = 'fa-times-circle';
                                } else {
                                    $statutLabel = 'En attente d\'évaluation';
                                    $statutClass = 'status-attente';
                                    $statusIcon = 'fa-clock';
                                }
                            ?>
                                <div class="card">
                                    <h3><?= htmlspecialchars(trim(($entretien['candidat_nom'] ?? '') . ' ' . ($entretien['candidat_prenom'] ?? ''))) ?></h3>
                                    
                                    <div class="meta">
                                        <strong>Poste :</strong> <?= htmlspecialchars($entretien['annonce_titre'] ?? $entretien['poste_nom'] ?? '—') ?>
                                        &nbsp;•&nbsp;
                                        <strong>Date :</strong> <?= !empty($entretien['date_entretien']) ? date('d/m/Y H:i', strtotime($entretien['date_entretien'])) : '—' ?>
                                    </div>
                                    
                                    <?php if (!empty($entretien['lieu'])): ?>
                                        <div class="meta"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($entretien['lieu']) ?></div>
                                    <?php endif; ?>

                                    <div class="status-badge <?= $statutClass ?>">
                                        <i class="fas <?= $statusIcon ?>"></i>
                                        <?= $statutLabel ?>
                                    </div>

                                    <?php if ($currentStatut === 'acceptee' && !empty($idMiseFormation)): ?>
                                        <!-- Formation acceptée : afficher lien vers le formulaire -->
                                        <a href="/candidat/formation/<?= $idCandidature ?>" class="btn-formation">
                                            <i class="fas fa-graduation-cap"></i>
                                            Commencer la formation
                                        </a>
                                        
                                        <!-- ✅ NOUVEAU : Si formulaire soumis, afficher bouton Confirmation -->
                                        <?php if ($formulaireSoumis): ?>
                                            <a href="/candidat/confirmer-formation" class="btn-confirmation">
                                                <i class="fas fa-file-check"></i>
                                                Voir la confirmation
                                            </a>
                                        <?php endif; ?>
                                        
                                    <?php elseif ($currentStatut === 'en_formation'): ?>
                                        <!-- Formation en cours : afficher le questionnaire si formulaire rempli -->
                                        
                                        <!-- ✅ NOUVEAU : Bouton vers confirmation si formulaire en session -->
                                        <?php if ($formulaireSoumis): ?>
                                            <a href="/candidat/confirmer-formation" class="btn-confirmation">
                                                <i class="fas fa-file-check"></i>
                                                Voir la confirmation
                                            </a>
                                            
                                            <div style="margin-top:8px;">
                                                <a href="/candidat/questionnaire-start" class="btn-formation" style="background: #10b981;">
                                                    <i class="fas fa-list-check"></i>
                                                    Remplir le questionnaire
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <!-- Formulaire pas encore rempli : lien pour le remplir -->
                                            <a href="/candidat/formation/<?= $idCandidature ?>" class="btn-formation">
                                                <i class="fas fa-graduation-cap"></i>
                                                Continuer la formation
                                            </a>
                                        <?php endif; ?>
                                        
                                    <?php elseif ($currentStatut === 'questionnaire_soumis'): ?>
                                        <!-- Questionnaire soumis : afficher message de confirmation -->
                                        <div style="margin-top:12px; padding: 12px; background: #dcfce7; border-left: 4px solid #22c55e; border-radius: 6px;">
                                            <p style="color: #166534; margin: 0;"><i class="fas fa-check-circle"></i> <strong>Questionnaire soumis !</strong></p>
                                            <small style="color: #166534;">Votre évaluation a été transmise au service RH.</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- BACK : CANDIDATS (inchangé) -->
                <div class="flip-card-back">
                    <h2><i class="fas fa-users"></i> Candidats</h2>

                    <div class="filter-toolbar">
                        <div style="flex:1"></div>
                        <div>
                            <span class="pill active" id="filter_all">Tous</span>
                            <span class="pill" id="filter_recus">Reçus</span>
                            <span class="pill" id="filter_non_recus">Non reçus</span>
                            <span class="pill" id="filter_attente">En attente</span>
                        </div>
                    </div>

                    <?php
                    $candidats_list = [];
                    if (!empty($candidats_recus) && is_array($candidats_recus)) {
                        $candidats_list = $candidats_recus;
                    } elseif (!empty($candidats) && is_array($candidats)) {
                        $candidats_list = $candidats;
                    } elseif (!empty($all_candidats) && is_array($all_candidats)) {
                        $candidats_list = $all_candidats;
                    }
                    ?>

                    <div id="candidatsWrapper">
                    <?php if (empty($candidats_list)): ?>
                        <div class="empty-state" id="candidatsLoading">
                            <i class="fas fa-spinner fa-spin" style="font-size:32px;color:#3b82f6"></i>
                            <h3>Chargement des candidats...</h3>
                        </div>
                    <?php else: ?>
                        <div class="candidats-grid" id="candidatsGrid">
                            <?php foreach ($candidats_list as $cand):
                                if (isset($cand['status_computed'])) {
                                    $status = $cand['status_computed'];
                                } else {
                                    $decision = strtolower(trim((string)($cand['entretien_decision'] ?? $cand['decision_finale'] ?? $cand['statut'] ?? '')));
                                    if (in_array($decision, ['accepte','accepté','accepted','accepter','reçu','recu']) || !empty($cand['date_decision'])) {
                                        $status = 'recu';
                                    } elseif (in_array($decision, ['rejete','rejeté','rejet','rejected','refuse','refusé'])) {
                                        $status = 'rejete';
                                    } else {
                                        $status = 'attente';
                                    }
                                }

                                $candId = $cand['id'] ?? $cand['candidature_id'] ?? '';
                            ?>
                                <div class="card candidat-card" data-status="<?= $status ?>">
                                    <h3><?= htmlspecialchars(trim(($cand['nom'] ?? '') . ' ' . ($cand['prenom'] ?? ''))) ?></h3>
                                    <div class="meta">
                                        <strong>Email :</strong> <?= htmlspecialchars($cand['email'] ?? '—') ?> &nbsp;•&nbsp;
                                        <strong>Tél :</strong> <?= htmlspecialchars($cand['telephone'] ?? '—') ?>
                                    </div>
                                    <div class="meta"><strong>Poste :</strong> <?= htmlspecialchars($cand['annonce_titre'] ?? $cand['poste'] ?? '—') ?></div>
                                    <?php if (!empty($cand['date_decision'])): ?>
                                        <div class="meta"><strong>Date validation :</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($cand['date_decision']))) ?></div>
                                    <?php endif; ?>

                                    <div style="margin-top:12px;display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                                        <?php if ($status === 'recu'): ?>
                                            <span class="status-badge status-recu"><i class="fas fa-check-circle"></i> Reçu</span>
                                        <?php elseif ($status === 'rejete'): ?>
                                            <span class="status-badge status-rejete"><i class="fas fa-times-circle"></i> Rejeté</span>
                                        <?php else: ?>
                                            <span class="status-badge status-attente"><i class="fas fa-clock"></i> En attente</span>
                                        <?php endif; ?>

                                        <div style="margin-left:auto;">
                                            <a href="/rh/candidature/<?= urlencode($candId) ?>" class="pill"><i class="fas fa-eye"></i> Voir</a>
                                            <a href="/rh/contrats?candidature_id=<?= urlencode($candId) ?>" class="pill" style="margin-left:6px;"><i class="fas fa-file-contract"></i> Contrat</a>
                                            <a href="/rh/candidature/<?= urlencode($candId) ?>/pdf" class="pill" style="margin-left:6px;"><i class="fas fa-file-pdf"></i> PDF</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function() {
        const wrapper = document.getElementById('flipContainerWrapper');
        const flipButton = document.getElementById('flipButton');
        let candidatsLoadedFromAjax = false;

        flipButton.addEventListener('click', function() {
            wrapper.classList.toggle('flipped');
            flipButton.innerHTML = wrapper.classList.contains('flipped')
                ? '<i class="fas fa-calendar-check"></i> Revenir aux entretiens'
                : '<i class="fas fa-exchange-alt"></i> Voir les candidats (Reçus / Non reçus / En attente)';

            if (wrapper.classList.contains('flipped') && !candidatsLoadedFromAjax && !document.getElementById('candidatsGrid')) {
                fetchCandidatsRecus();
            }

            setTimeout(() => wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' }), 200);
        });

        const filters = ['filter_all','filter_recus','filter_non_recus','filter_attente'];
        const filterEls = filters.map(id => document.getElementById(id));

        function setActive(el) {
            filterEls.forEach(i => i && i.classList.remove('active'));
            if (el) el.classList.add('active');
        }

        function filter(mode) {
            const grid = document.getElementById('candidatsGrid');
            if (!grid) return;
            
            grid.querySelectorAll('.candidat-card').forEach(card => {
                const status = card.getAttribute('data-status');
                card.style.display = mode === 'all' ? '' :
                                   mode === 'recu' ? (status === 'recu' ? '' : 'none') :
                                   mode === 'non_recus' ? (status !== 'recu' ? '' : 'none') :
                                   mode === 'attente' ? (status === 'attente' ? '' : 'none') : '';
            });
        }

        filterEls.forEach((el, i) => {
            if (el) el.addEventListener('click', () => {
                setActive(el);
                filter(['all','recu','non_recus','attente'][i]);
            });
        });

        async function fetchCandidatsRecus() {
            const container = document.getElementById('candidatsWrapper');
            if (!container) return;
            
            try {
                const resp = await fetch('/validation/candidats_recus', { credentials: 'same-origin' });
                if (!resp.ok) throw new Error('Network response not ok');
                const list = await resp.json();
                renderCandidats(list);
                candidatsLoadedFromAjax = true;
            } catch (err) {
                container.innerHTML = '<div class="empty-state"><h3>Impossible de charger les candidats</h3><p>Veuillez réessayer plus tard.</p></div>';
            }
        }

        function renderCandidats(list) {
            const container = document.getElementById('candidatsWrapper');
            if (!Array.isArray(list) || list.length === 0) {
                container.innerHTML = '<div class="empty-state"><h3>Aucun candidat disponible</h3><p>Les candidats apparaîtront ici.</p></div>';
                return;
            }

            const grid = document.createElement('div');
            grid.className = 'candidats-grid';
            grid.id = 'candidatsGrid';

            list.forEach(c => {
                const decision = (c.entretien_decision ?? c.decision_finale ?? c.statut ?? '').toString().trim().toLowerCase();
                const isRecu = ['accepte','accepté','accepted','accepter','reçu','recu'].includes(decision) || !!c.date_decision;
                const isRejete = ['rejete','rejeté','rejet','rejected','refuse','refusé'].includes(decision);
                const status = isRecu ? 'recu' : (isRejete ? 'rejete' : 'attente');
                const id = encodeURIComponent(c.id ?? '');

                const card = document.createElement('div');
                card.className = 'card candidat-card';
                card.setAttribute('data-status', status);
                card.innerHTML = `
                    <h3>${escapeHtml((c.nom ?? '') + ' ' + (c.prenom ?? ''))}</h3>
                    <div class="meta"><strong>Email:</strong> ${escapeHtml(c.email ?? '—')} &nbsp;•&nbsp; <strong>Tél:</strong> ${escapeHtml(c.telephone ?? '—')}</div>
                    <div class="meta"><strong>Poste:</strong> ${escapeHtml(c.annonce_titre ?? c.poste ?? '—')}</div>
                    ${c.date_decision ? `<div class="meta"><strong>Date validation:</strong> ${escapeHtml(c.date_decision.split(' ')[0])}</div>` : ''}
                    <div style="margin-top:12px;display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                        ${isRecu ? '<span class="status-badge status-recu"><i class="fas fa-check-circle"></i> Reçu</span>' :
                          isRejete ? '<span class="status-badge status-rejete"><i class="fas fa-times-circle"></i> Rejeté</span>' :
                                     '<span class="status-badge status-attente"><i class="fas fa-clock"></i> En attente</span>'}
                        <div style="margin-left:auto;">
                            <a href="/rh/candidature/${id}" class="pill"><i class="fas fa-eye"></i> Voir</a>
                            <a href="/rh/contrats?candidature_id=${id}" class="pill" style="margin-left:6px;"><i class="fas fa-file-contract"></i> Contrat</a>
                            <a href="/rh/candidature/${id}/pdf" class="pill" style="margin-left:6px;"><i class="fas fa-file-pdf"></i> PDF</a>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });

            container.innerHTML = '';
            container.appendChild(grid);
            setActive(filterEls[0]);
            filter('all');
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>"'`=\/]/g, s => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;',
                "'": '&#39;', '`': '&#x60;', '=': '&#x3D;', '/': '&#x2F;'
            })[s]);
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('candidatsGrid')) {
                setActive(filterEls[0]);
                filter('all');
            }
        });
    })();
    </script>
</body>
</html>