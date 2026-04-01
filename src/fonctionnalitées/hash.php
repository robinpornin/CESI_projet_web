<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| CONFIGURATION BDD
|--------------------------------------------------------------------------
| Remplace ces valeurs par les tiennes
*/
$host = 'localhost';
$dbname = 'CESI_projet_web';
$username = 'phpmyadmin';
$password = 'A2#DevWeb!';

/*
|--------------------------------------------------------------------------
| CONNEXION PDO
|--------------------------------------------------------------------------
*/
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

/*
|--------------------------------------------------------------------------
| OUTILS MOT DE PASSE
|--------------------------------------------------------------------------
*/
function hashPassword(string $plainPassword): string
{
    $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

    if ($hash === false) {
        throw new RuntimeException('Erreur lors du hachage du mot de passe.');
    }

    return $hash;
}

function verifyPassword(string $plainPassword, string $hashedPassword): bool
{
    return password_verify($plainPassword, $hashedPassword);
}

function needsRehashPassword(string $hashedPassword): bool
{
    return password_needs_rehash($hashedPassword, PASSWORD_DEFAULT);
}

/*
|--------------------------------------------------------------------------
| INSERTION UTILISATEUR SÉCURISÉE
|--------------------------------------------------------------------------
| Cette fonction :
| - valide les données
| - vérifie l'email
| - empêche les doublons
| - hache le mot de passe
| - insère avec requête préparée
*/
function insertUser(PDO $pdo, string $nom, string $prenom, string $email, string $motDePasseClair, int $role = 1): bool
{
    $nom = trim($nom);
    $prenom = trim($prenom);
    $email = trim($email);
    $motDePasseClair = trim($motDePasseClair);

    if ($nom === '' || $prenom === '' || $email === '' || $motDePasseClair === '') {
        throw new InvalidArgumentException('Tous les champs sont obligatoires.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Adresse email invalide.');
    }

    if (mb_strlen($motDePasseClair) < 8) {
        throw new InvalidArgumentException('Le mot de passe doit contenir au moins 8 caractères.');
    }

    $sqlCheck = "SELECT ID_Utilisateur FROM Utilisateurs WHERE Email = :email";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([
        'email' => $email,
    ]);

    $userExists = $stmtCheck->fetch();

    if ($userExists) {
        throw new RuntimeException('Un utilisateur existe déjà avec cet email.');
    }

    $motDePasseHache = hashPassword($motDePasseClair);

    $sqlInsert = "INSERT INTO Utilisateurs (Nom, Prenom, Email, Mdp, Role)
                  VALUES (:nom, :prenom, :email, :mdp, :role)";

    $stmtInsert = $pdo->prepare($sqlInsert);

    return $stmtInsert->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'mdp' => $motDePasseHache,
        'role' => $role,
    ]);
}

/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION UTILISATEUR
|--------------------------------------------------------------------------
| Retourne l'utilisateur si email + mot de passe sont corrects
*/
function authenticateUser(PDO $pdo, string $email, string $motDePasseClair): array|false
{
    $email = trim($email);
    $motDePasseClair = trim($motDePasseClair);

    $sql = "SELECT ID_Utilisateur, Nom, Prenom, Email, Mdp, Role
            FROM Utilisateurs
            WHERE Email = :email";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'email' => $email,
    ]);

    $utilisateur = $stmt->fetch();

    if (!$utilisateur) {
        return false;
    }

    if (!verifyPassword($motDePasseClair, $utilisateur['Mdp'])) {
        return false;
    }

    if (needsRehashPassword($utilisateur['Mdp'])) {
        $nouveauHash = hashPassword($motDePasseClair);

        $sqlUpdate = "UPDATE Utilisateurs
                      SET Mdp = :mdp
                      WHERE ID_Utilisateur = :id";

        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            'mdp' => $nouveauHash,
            'id' => $utilisateur['ID_Utilisateur'],
        ]);

        $utilisateur['Mdp'] = $nouveauHash;
    }

    return $utilisateur;
}

//|--------------------------------------------------------------------------| MODE SEED|--------------------------------------------------------------------------
//| - Web : ?action=seed
//| - CLI : php hash.php seed*/
$isCli = php_sapi_name() === 'cli';

$shouldSeed = false;

if ($isCli && isset($argv[1]) && $argv[1] === 'seed') {
    $shouldSeed = true;
}

if (isset($_GET['action']) && $_GET['action'] === 'seed') {
    $shouldSeed = true;
}

if ($shouldSeed) {
    $utilisateurs = [
        ['Durand', 'Alexandre', 'alexandre.durand@cesi.fr', 'AlexandreMDP', 1],
        ['Lemoine', 'Camille', 'camille.lemoine@cesi.fr', 'CamilleMDP', 1],
        ['Moreau', 'Julien', 'julien.moreau@cesi.fr', 'JulienMDP', 1],
        ['Petit', 'Sophie', 'sophie.petit@cesi.fr', 'SophieMDP', 1],
        ['Roux', 'Thomas', 'thomas.roux@cesi.fr', 'ThomasMDP', 1],
        ['Lefevre', 'Claire', 'claire.lefevre@cesi.fr', 'ClaireMDP', 1],
        ['Garnier', 'Lucas', 'lucas.garnier@cesi.fr', 'LucasMDP', 1],
        ['Faure', 'Emma', 'emma.faure@cesi.fr', 'EmmaMDP1', 1],
        ['Mercier', 'Maxime', 'maxime.mercier@cesi.fr', 'MaximeMDP', 1],
        ['Andre', 'Léa', 'lea.andre@cesi.fr', 'LéaMDP12', 1],
        ['Bernard', 'Victor', 'victor.bernard@cesi.fr', 'VictorMDP', 1],
        ['Simon', 'Clémence', 'clemence.simon@cesi.fr', 'ClémenceMDP', 1],
        ['Guerin', 'Nathan', 'nathan.guerin@cesi.fr', 'NathanMDP', 1],
        ['Benoit', 'Chloé', 'chloe.benoit@cesi.fr', 'ChloéMDP', 1],
        ['Forato', 'Hugo', 'hugo.forato@cesi.fr', 'HugoMDP1', 1],
        ['Martinez', 'Inès', 'ines.martinez@cesi.fr', 'InèsMDP1', 1],
        ['Dupont', 'Mathieu', 'mathieu.dupont@cesi.fr', 'MathieuMDP', 1],
        ['Giraud', 'Julie', 'julie.giraud@cesi.fr', 'JulieMDP', 1],
        ['Colin', 'Adrien', 'adrien.colin@cesi.fr', 'AdrienMDP', 1],
        ['Renaud', 'Manon', 'manon.renaud@cesi.fr', 'ManonMDP', 1],
        ['Fabre', 'Claire', 'claire.fabre@cesi.fr', 'ClaireMDP', 2],
        ['Olivier', 'Marc', 'marc.olivier@cesi.fr', 'MarcMDP1', 2],
        ['Lemoine', 'Anne', 'anne.lemoine@cesi.fr', 'AnneMDP1', 2],
        ['Dupuis', 'Paul', 'paul.dupuis@cesi.fr', 'PaulMDP1', 2],
        ['Perrin', 'Isabelle', 'isabelle.perrin@cesi.fr', 'IsabelleMDP', 3],
        ['Noel', 'Jean', 'jean.noel@cesi.fr', 'JeanMDP1', 3],
        ['Marchand', 'Sophie', 'sophie.marchand@cesi.fr', 'SophieMDP', 3],
        ['Blanc', 'David', 'david.blanc@cesi.fr', 'DavidMDP', 3],
        ['Gauthier', 'Elodie', 'elodie.gauthier@cesi.fr', 'ElodieMDP', 3],
        ['Muller', 'Alex', 'alex.muller@cesi.fr', 'AlexMDP1', 3],
    ];

    if (!$isCli) {
        echo '<pre>';
    }

    foreach ($utilisateurs as [$nom, $prenom, $email, $motDePasse, $role]) {
        try {
            insertUser($pdo, $nom, $prenom, $email, $motDePasse, $role);
            echo "OK : $email ajouté\n";
        } catch (Throwable $e) {
            echo "ERREUR : $email -> " . $e->getMessage() . "\n";
        }
    }

    echo "Seed terminé.\n";

    if (!$isCli) {
        echo '</pre>';
    }

    exit;
}

/*
|--------------------------------------------------------------------------
| MODE CONNEXION SIMPLE
|--------------------------------------------------------------------------
| Met ?action=login dans l'URL pour tester une connexion
*/
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    $message = '';
    $erreur = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        $utilisateur = authenticateUser($pdo, $email, $motDePasse);

        if ($utilisateur === false) {
            $erreur = 'Email ou mot de passe incorrect.';
        } else {
            $_SESSION['utilisateur'] = [
                'id' => $utilisateur['ID_Utilisateur'],
                'nom' => $utilisateur['Nom'],
                'prenom' => $utilisateur['Prenom'],
                'email' => $utilisateur['Email'],
                'role' => $utilisateur['Role'],
            ];

            $message = 'Connexion réussie pour ' . $utilisateur['Prenom'] . ' ' . $utilisateur['Nom'];
        }
    }

    echo '<h1>Connexion</h1>';

    if ($message !== '') {
        echo '<p style="color: green;">' . htmlspecialchars($message) . '</p>';
    }

    if ($erreur !== '') {
        echo '<p style="color: red;">' . htmlspecialchars($erreur) . '</p>';
    }

    echo '
    <form method="post">
        <input type="email" name="email" placeholder="Email"><br><br>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe"><br><br>
        <button type="submit">Se connecter</button>
    </form>
    ';
    exit;
}

/*
|--------------------------------------------------------------------------
| PAGE PAR DÉFAUT
|--------------------------------------------------------------------------
*/
echo '
<h1>users_tools.php</h1>
<ul>
    <li><a href="?action=seed">Lancer le seed</a></li>
    <li><a href="?action=register">Tester l\'inscription</a></li>
    <li><a href="?action=login">Tester la connexion</a></li>
</ul>
';
