<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Choix Document Employé</title>
</head>
<body>

<h2>Générer un document pour un employé</h2>

<form id="docForm">
  <label for="employeId">ID Employé :</label>
  <input type="number" id="employeId" name="employeId" required min="1" />

  <br /><br />

  <label for="docType">Type de document :</label>
  <select id="docType" name="docType" required>
    <option value="">-- Choisir --</option>
    <option value="contratGen">Contrat</option>
    <option value="attestation">Attestation de travail</option>
  </select>

  <br /><br />

  <button type="submit">Générer</button>
</form>

<script>
const baseUrl = "<?= constant('BASE_URL') ?>";
document.getElementById('docForm').addEventListener('submit', function(event) {
  event.preventDefault();

  const id = document.getElementById('employeId').value.trim();
  const docType = document.getElementById('docType').value;

  if (!id || !docType) {
    alert('Merci de remplir tous les champs.');
    return;
  }

  // Construire l'URL selon le choix
  // Exemple base URL (à modifier selon ta config)
  const url = `${baseUrl}/${docType}/${encodeURIComponent(id)}`;

  // Rediriger vers l’URL
  window.location.href = url;
});
</script>

</body>
</html>
