<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="/public/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/sidebar_rh.php'; ?>
    
    <div class="main-content">
        <?php include __DIR__ . '/../partials/header.php'; ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-clipboard-check"></i> Résultats des entretiens</h1>
            </div>

            <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Évaluation enregistrée avec succès !
            </div>
            <?php endif; ?>

            <?php if (empty($entretiens)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h2>Aucun entretien terminé</h2>
                <p>Les entretiens notés par les managers apparaîtront ici.</p>
            </div>
            <?php else: ?>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Candidat</th>
                            <th>Poste</th>
                            <th>Date entretien</th>
                            <th>Note Manager</th>
                            <th>Qualités</th>
                            <th>Défauts</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entretiens as $e): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($e['candidat_nom'] . ' ' . $e['candidat_prenom']) ?></strong><br>
                                <small><?= htmlspecialchars($e['candidat_email']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($e['poste_nom']) ?></td>
                            <td><?= date('d/m/Y à H:i', strtotime($e['date_entretien'])) ?></td>
                            <td>
                                <div class="note-display">
                                    <span class="note-value"><?= $e['note_manager'] ?>/20</span>
                                </div>
                            </td>
                            <td>
                                <button class="btn-detail" onclick="showDetail('qualites-<?= $e['id'] ?>')">
                                    <i class="fas fa-eye"></i> Voir
                                </button>
                                <div id="qualites-<?= $e['id'] ?>" class="detail-popup" style="display:none;">
                                    <?= nl2br(htmlspecialchars($e['qualites_manager'])) ?>
                                </div>
                            </td>
                            <td>
                                <button class="btn-detail" onclick="showDetail('defauts-<?= $e['id'] ?>')">
                                    <i class="fas fa-eye"></i> Voir
                                </button>
                                <div id="defauts-<?= $e['id'] ?>" class="detail-popup" style="display:none;">
                                    <?= nl2br(htmlspecialchars($e['defauts_manager'])) ?>
                                </div>
                            </td>
                            <td>
                                <?php if (empty($e['note_rh'])): ?>
                                <a href="/rh/entretien/<?= $e['id'] ?>/evaluer" class="btn btn-primary">
                                    <i class="fas fa-star"></i> Évaluer
                                </a>
                                <?php else: ?>
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> Évalué
                                </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .empty-state i {
            font-size: 5rem;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .note-display {
            text-align: center;
        }

        .note-value {
            display: inline-block;
            padding: 8px 16px;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 20px;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .btn-detail {
            padding: 6px 12px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .btn-detail:hover {
            background: #e2e8f0;
        }

        .detail-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            z-index: 1000;
        }

        .detail-popup::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: -1;
        }
    </style>

    <script>
        function showDetail(id) {
            const popup = document.getElementById(id);
            if (popup.style.display === 'none') {
                popup.style.display = 'block';
                popup.addEventListener('click', function() {
                    this.style.display = 'none';
                });
            }
        }
    </script>
</body>
</html>