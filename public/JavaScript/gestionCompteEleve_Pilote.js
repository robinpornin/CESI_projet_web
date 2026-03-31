document.addEventListener('DOMContentLoaded', function () {

  // ── Listeners ─────────────────────────────────────────────────────────────
  document.getElementById('creerBtn').addEventListener('click', function () {
    const form  = document.getElementById('creerForm');
    const arrow = document.getElementById('creerArrow');
    const open  = form.style.display !== 'none' && form.style.display !== '';
    form.style.display = open ? 'none' : 'block';
    arrow.textContent  = open ? '▼' : '▲';
  });

  document.getElementById('btnRechercher').addEventListener('click', rechercherEtudiant);
  document.getElementById('searchEtudiant').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') rechercherEtudiant();
  });

  document.getElementById('btnCreerCompte').addEventListener('click', creerCompte);
  document.getElementById('btnEnregistrer').addEventListener('click', enregistrer);
  document.getElementById('btnSupprimer').addEventListener('click', supprimerCompte);

  // ── Recherche ──────────────────────────────────────────────────────────────
  function rechercherEtudiant() {
    const valeur = document.getElementById('searchEtudiant').value.toLowerCase().trim();

    if (!valeur) { alert('Veuillez saisir un nom, prénom ou email.'); return; }

    const etudiant = etudiants.find(function (e) {
      if (!e) return false;
      const nom    = (e.Nom    || '').toLowerCase();
      const prenom = (e.Prenom || '').toLowerCase();
      const email  = (e.Email  || '').toLowerCase();
      return (
        nom.includes(valeur) || prenom.includes(valeur) || email.includes(valeur) ||
        (prenom + ' ' + nom).includes(valeur) || (nom + ' ' + prenom).includes(valeur)
      );
    });

    if (!etudiant) {
      document.getElementById('fichePlaceholder').style.display = 'flex';
      document.getElementById('ficheContent').style.display     = 'none';
      alert('Aucun étudiant trouvé.');
      return;
    }

    afficherEtudiant(etudiant);
  }

  // ── Afficher fiche ─────────────────────────────────────────────────────────
  function afficherEtudiant(etudiant) {
    document.getElementById('fichePlaceholder').style.display = 'none';
    document.getElementById('ficheContent').style.display     = 'block';
    document.getElementById('ficheContent').dataset.id        = etudiant.ID_Utilisateur || '';

    document.getElementById('ficheNomAffiche').textContent   = ((etudiant.Prenom || '') + ' ' + (etudiant.Nom || '')).trim();
    document.getElementById('ficheEmailAffiche').textContent = etudiant.Email || '';
    document.getElementById('fichePrenom').value = etudiant.Prenom || '';
    document.getElementById('ficheNom').value    = etudiant.Nom    || '';
    document.getElementById('ficheEmail').value  = etudiant.Email  || '';

    // Lien vers les candidatures de cet étudiant
    document.getElementById('btnVoirCandidatures').href =
      '/listeCandidaturesPilote?id=' + etudiant.ID_Utilisateur;
  }

  // ── Créer ──────────────────────────────────────────────────────────────────
  async function creerCompte() {
    const prenom = document.getElementById('createPrenom').value.trim();
    const nom    = document.getElementById('createNom').value.trim();
    const email  = document.getElementById('createEmail').value.trim();
    const mdp    = document.getElementById('createPassword').value.trim();

    if (!prenom || !nom || !email || !mdp) { alert('Merci de remplir tous les champs.'); return; }

    const data = await postAction({ action: 'create', prenom, nom, email, mdp });
    if (!data) return;

    etudiants.push(data.etudiant);
    afficherEtudiant(data.etudiant);

    document.getElementById('createPrenom').value   = '';
    document.getElementById('createNom').value      = '';
    document.getElementById('createEmail').value    = '';
    document.getElementById('createPassword').value = '';

    alert(data.message);
  }

  // ── Modifier ───────────────────────────────────────────────────────────────
  async function enregistrer() {
    const id     = document.getElementById('ficheContent').dataset.id;
    const prenom = document.getElementById('fichePrenom').value.trim();
    const nom    = document.getElementById('ficheNom').value.trim();
    const email  = document.getElementById('ficheEmail').value.trim();

    if (!id)                       { alert('Aucun étudiant sélectionné.'); return; }
    if (!prenom || !nom || !email) { alert('Merci de remplir tous les champs.'); return; }

    const data = await postAction({ action: 'update', id, prenom, nom, email });
    if (!data) return;

    const index = etudiants.findIndex(e => e && String(e.ID_Utilisateur) === String(id));
    if (index !== -1) etudiants[index] = data.etudiant; else etudiants.push(data.etudiant);

    afficherEtudiant(data.etudiant);
    alert(data.message);
  }

  // ── Supprimer ──────────────────────────────────────────────────────────────
  async function supprimerCompte() {
    const id = document.getElementById('ficheContent').dataset.id;
    if (!id) { alert('Aucun étudiant sélectionné.'); return; }
    if (!confirm('Voulez-vous vraiment supprimer ce compte étudiant ?')) return;

    const data = await postAction({ action: 'delete', id });
    if (!data) return;

    etudiants.splice(etudiants.findIndex(e => e && String(e.ID_Utilisateur) === String(id)), 1);

    document.getElementById('ficheContent').style.display     = 'none';
    document.getElementById('fichePlaceholder').style.display = 'flex';
    document.getElementById('ficheContent').dataset.id        = '';

    alert(data.message);
  }

  // ── Helper fetch ───────────────────────────────────────────────────────────
  async function postAction(payload) {
    try {
      const response = await fetch('/gestionCompteEtudiantAdmin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });
      const text = await response.text();
      let data;
      try { data = JSON.parse(text); } catch (e) { alert('Réponse serveur invalide.'); return null; }
      if (!data.success) { alert(data.message || 'Erreur serveur.'); return null; }
      return data;
    } catch (err) {
      console.error(err);
      alert('Erreur réseau.');
      return null;
    }
  }

}); // fin DOMContentLoaded