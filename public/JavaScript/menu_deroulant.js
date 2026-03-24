document.addEventListener("DOMContentLoaded", function () {
  const container = document.getElementById("menu-container");

  if (!container) return;

  // 1 = étudiant, 2 = pilote, 3 = admin (A CHANGER SELON LA BDD)
  const role = 1;

  let menuFile = "";

  if (role === 1) {
    menuFile = "page_menu_deroulant_etudiant.html";
  } else if (role === 2) {
    menuFile = "page_menu_deroulant_pilote.html";
  } else if (role === 3) {
    menuFile = "page_menu_deroulant_admin.html";
  } else {
    console.error("Rôle invalide :", role);
    return;
  }

  fetch(menuFile)
    .then(response => {
      if (!response.ok) {
        throw new Error("Impossible de charger le fichier : " + menuFile);
      }
      return response.text();
    })
    .then(data => {
      container.innerHTML = data;
      initialiserMenu();
    })
    .catch(error => {
      console.error("Erreur lors du chargement du menu :", error);
    });
});

function initialiserMenu() {
  const button = document.getElementById("menuButton");
  const overlay = document.getElementById("overlay");

  if (!button) {
    console.error("Bouton du menu introuvable.");
    return;
  }

  button.addEventListener("click", function (event) {
    event.stopPropagation();
    toggleMenu();
  });

  if (overlay) {
    overlay.addEventListener("click", function () {
      closeMenu();
    });
  }

  document.addEventListener("click", function (event) {
    const dropdown = document.getElementById("dropdown");
    const button = document.getElementById("menuButton");

    if (!dropdown || !button) return;

    if (!dropdown.contains(event.target) && !button.contains(event.target)) {
      closeMenu();
    }
  });

  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      closeMenu();
    }
  });
}

function toggleMenu() {
  const dropdown = document.getElementById("dropdown");
  const overlay = document.getElementById("overlay");

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
  const overlay = document.getElementById("overlay");

  if (dropdown) {
    dropdown.classList.remove("visible");
    dropdown.setAttribute("aria-hidden", "true");
  }

  if (overlay) {
    overlay.classList.remove("visible");
  }
}

function seDeconnecter() {
  closeMenu();

  if (confirm("Confirmer la déconnexion ?")) {
    alert("Vous avez été déconnecté.");
    // window.location.href = "connexion.html";
  }
}

function supprimerCompte() {
  closeMenu();

  if (confirm("Confirmer la suppression définitive de votre compte ?")) {
    alert("Compte supprimé.");
    // window.location.href = "connexion.html";
  }
}