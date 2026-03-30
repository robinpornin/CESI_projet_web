document.addEventListener("DOMContentLoaded", function () {
  if (document.getElementById("menuButton")) {
    initialiserMenu();
    return;
  }

  // Ancien système JS (pages HTML statiques)
  const container = document.getElementById("menu-container");
  if (!container) return;

  const role = parseInt(container.getAttribute("data-role"), 10);
  let menuAffiche = false;

  if (role === 1) {
    renderMenuEtudiant(container);
    menuAffiche = true;
  } else if (role === 2) {
    renderMenuPilote(container);
    menuAffiche = true;
  } else if (role === 3) {
    renderMenuAdmin(container);
    menuAffiche = true;
  } else {
    container.innerHTML = "";
  }

  if (menuAffiche) {
    initialiserMenu();
  }
});



// ─────────────────────────────────────────────
// RENDU DES MENUS
// ─────────────────────────────────────────────

function renderMenuEtudiant(container) {
  container.innerHTML = `
    <button class="avatar-btn" id="menuButton" title="Mon compte" aria-label="Ouvrir le menu">
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
      </svg>
    </button>

    <div class="overlay" id="overlay"></div>

    <div class="dropdown" id="dropdown" role="menu" aria-hidden="true">
      <div class="dropdown-head">
        <div class="dropdown-avatar">🎓</div>
        <div class="dropdown-user">
          <span class="dropdown-nom">Étudiant</span>
          <span class="dropdown-badge">Étudiant</span>
        </div>
      </div>
      <div class="dropdown-body">

        <a class="menu-item" href="/listeCandidatures" onclick="closeMenu()">
          <span class="menu-item-icon">📄</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Mes candidatures</span>
            <span class="menu-item-desc">Voir toutes mes candidatures</span>
          </div>
        </a>

        <a class="menu-item" href="/wishlist" onclick="closeMenu()">
          <span class="menu-item-icon">⭐</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Ma wishlist</span>
            <span class="menu-item-desc">Voir mes offres favorites</span>
          </div>
        </a>

        <div class="menu-sep"></div>

        <a class="menu-item" href="/gestionCompte" onclick="closeMenu()">
          <span class="menu-item-icon">✏️</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Modifier mon compte</span>
          </div>
        </a>

        <div class="menu-sep"></div>

        <a class="menu-item deconnexion" href="/deconnexion">
          <span class="menu-item-icon">🚪</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Se déconnecter</span>
          </div>
        </a>

      </div>
    </div>
  `;
}

function renderMenuPilote(container) {
  container.innerHTML = `
    <button class="avatar-btn" id="menuButton" title="Mon compte" aria-label="Ouvrir le menu">
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
      </svg>
    </button>

    <div class="overlay" id="overlay"></div>

    <div class="dropdown" id="dropdown" role="menu" aria-hidden="true">
      <div class="dropdown-head">
        <div class="dropdown-avatar">🧑‍✈️</div>
        <div class="dropdown-user">
          <span class="dropdown-nom">Pilote</span>
          <span class="dropdown-badge">Pilote</span>
        </div>
      </div>
      <div class="dropdown-body">

        <a class="menu-item" href="/espaceEleve" onclick="closeMenu()">
          <span class="menu-item-icon">🎓</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Espace élève</span>
            <span class="menu-item-desc">Gérer les élèves</span>
          </div>
        </a>

        <a class="menu-item" href="/gestionEntreprise" onclick="closeMenu()">
          <span class="menu-item-icon">🏢</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Gestion entreprises</span>
            <span class="menu-item-desc">Gérer les entreprises</span>
          </div>
        </a>

        <a class="menu-item" href="/gestionOffre" onclick="closeMenu()">
          <span class="menu-item-icon">📋</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Gestion offres</span>
            <span class="menu-item-desc">Gérer les offres de stage</span>
          </div>
        </a>

        <div class="menu-sep"></div>

        <a class="menu-item" href="/gestionCompte" onclick="closeMenu()">
          <span class="menu-item-icon">✏️</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Modifier mon compte</span>
          </div>
        </a>

        <div class="menu-sep"></div>

        <a class="menu-item deconnexion" href="/deconnexion">
          <span class="menu-item-icon">🚪</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Se déconnecter</span>
          </div>
        </a>

      </div>
    </div>
  `;
}

function renderMenuAdmin(container) {
  container.innerHTML = `
    <button class="avatar-btn" id="menuButton" title="Mon compte" aria-label="Ouvrir le menu">
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
      </svg>
    </button>

    <div class="overlay" id="overlay"></div>

    <div class="dropdown" id="dropdown" role="menu" aria-hidden="true">
      <div class="dropdown-head">
        <div class="dropdown-avatar">🛠️</div>
        <div class="dropdown-user">
          <span class="dropdown-nom">Administrateur</span>
          <span class="dropdown-badge">Admin</span>
        </div>
      </div>
      <div class="dropdown-body">

        <a class="menu-item" href="/espaceEleve" onclick="closeMenu()">
          <span class="menu-item-icon">🎓</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Espace élève</span>
            <span class="menu-item-desc">Gérer les élèves</span>
          </div>
        </a>

        <a class="menu-item" href="/gestionEntreprise" onclick="closeMenu()">
          <span class="menu-item-icon">🏢</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Gestion entreprises</span>
            <span class="menu-item-desc">Gérer les entreprises</span>
          </div>
        </a>

        <a class="menu-item" href="/gestionOffre" onclick="closeMenu()">
          <span class="menu-item-icon">📋</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Gestion offres</span>
            <span class="menu-item-desc">Gérer les offres de stage</span>
          </div>
        </a>

        <a class="menu-item" href="/gestionComptePiloteAdmin" onclick="closeMenu()">
          <span class="menu-item-icon">🧑‍✈️</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Gestion pilotes</span>
            <span class="menu-item-desc">Gérer les comptes pilotes</span>
          </div>
        </a>

        <a class="menu-item" href="/gestionCompteEleveAdmin" onclick="closeMenu()">
          <span class="menu-item-icon">🎓</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Gestion étudiants</span>
            <span class="menu-item-desc">Gérer les comptes étudiants</span>
          </div>
        </a>

        <div class="menu-sep"></div>

        <a class="menu-item" href="/gestionCompte" onclick="closeMenu()">
          <span class="menu-item-icon">✏️</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Modifier mon compte</span>
          </div>
        </a>

        <div class="menu-sep"></div>

        <a class="menu-item deconnexion" href="/deconnexion">
          <span class="menu-item-icon">🚪</span>
          <div class="menu-item-info">
            <span class="menu-item-titre">Se déconnecter</span>
          </div>
        </a>

      </div>
    </div>
  `;
}

function renderMenuInvite(container) {
  container.innerHTML = `
    <a href="/connexion" class="icon-btn" title="Se connecter">
      <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
      </svg>
    </a>
  `;
}

// ─────────────────────────────────────────────
// LOGIQUE OUVERTURE / FERMETURE
// ─────────────────────────────────────────────

function initialiserMenu() {
  const button = document.getElementById("menuButton");
  const overlay = document.getElementById("overlay");

  if (!button) return;

  button.addEventListener("click", function (e) {
    e.stopPropagation();
    toggleMenu();
  });

  if (overlay) {
    overlay.addEventListener("click", closeMenu);
  }

  document.addEventListener("click", function (e) {
    const dropdown = document.getElementById("dropdown");
    const btn = document.getElementById("menuButton");
    if (!dropdown || !btn) return;
    if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
      closeMenu();
    }
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") closeMenu();
  });
}

function toggleMenu() {
  const dropdown = document.getElementById("dropdown");
  const overlay  = document.getElementById("overlay");
  if (!dropdown || !overlay) return;

  if (dropdown.classList.contains("visible")) {
    closeMenu();
  } else {
    dropdown.classList.add("visible");
    overlay.classList.add("visible");
    dropdown.setAttribute("aria-hidden", "false");
  }
}

function closeMenu() {
  const dropdown = document.getElementById("dropdown");
  const overlay  = document.getElementById("overlay");

  if (dropdown) {
    dropdown.classList.remove("visible");
    dropdown.setAttribute("aria-hidden", "true");
  }
  if (overlay) {
    overlay.classList.remove("visible");
  }
}