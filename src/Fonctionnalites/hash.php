<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| CONFIGURATION BDD — chargée depuis .env
|--------------------------------------------------------------------------
*/
function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        throw new RuntimeException('.env introuvable : ' . $path);
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

loadEnv(__DIR__ . '/../../.env'); // adapte le chemin selon ta structure

$host   = $_ENV['DB_HOST']  ?? 'localhost';
$dbname = $_ENV['DB_NAME']  ?? '';
$username = $_ENV['DB_USER'] ?? '';
$password = $_ENV['DB_PASS'] ?? '';


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
        ['Durand', 'Alexandre', 'alexandre.durand@cesi.fr', 'AlexandreMDP1234', 1],
        ['Lemoine', 'Camille', 'camille.lemoine@cesi.fr', 'CamilleMDP1234', 1],
        ['Moreau', 'Julien', 'julien.moreau@cesi.fr', 'JulienMDP1234', 1],
        ['Roux', 'Thomas', 'thomas.roux@cesi.fr', 'ThomasMDP1234', 1],
        ['Lefevre', 'Claire', 'claire.lefevre@cesi.fr', 'ClaireMDP1234', 1],
        ['Garnier', 'Lucas', 'lucas.garnier@cesi.fr', 'LucasMDP1234', 1],
        ['Faure', 'Emma', 'emma.faure@cesi.fr', 'EmmaMDP1234', 1],
        ['Mercier', 'Maxime', 'maxime.mercier@cesi.fr', 'MaximeMDP1234', 1],
        ['Andre', 'Léa', 'lea.andre@cesi.fr', 'LéaMDP1234', 1],
        ['Bernard', 'Victor', 'victor.bernard@cesi.fr', 'VictorMDP1234', 1],
        ['Simon', 'Clémence', 'clemence.simon@cesi.fr', 'ClémenceMDP1234', 1],
        ['Guerin', 'Nathan', 'nathan.guerin@cesi.fr', 'NathanMDP1234', 1],
        ['Benoit', 'Chloé', 'chloe.benoit@cesi.fr', 'ChloéMDP1234', 1],
        ['Forato', 'Hugo', 'hugo.forato@cesi.fr', 'HugoMDP1234', 1],
        ['Martinez', 'Inès', 'ines.martinez@cesi.fr', 'InèsMDP1234', 1],
        ['Dupont', 'Mathieu', 'mathieu.dupont@cesi.fr', 'MathieuMDP1234', 1],
        ['Giraud', 'Julie', 'julie.giraud@cesi.fr', 'JulieMDP1234', 1],
        ['Colin', 'Adrien', 'adrien.colin@cesi.fr', 'AdrienMDP1234', 1],
        ['Renaud', 'Manon', 'manon.renaud@cesi.fr', 'ManonMDP1234', 1],

        ['Fabre', 'Claire', 'claire.fabre@cesi.fr', 'ClaireMDP1234', 2],
        ['Olivier', 'Marc', 'marc.olivier@cesi.fr', 'MarcMDP1234', 2],
        ['Lemoine', 'Anne', 'anne.lemoine@cesi.fr', 'AnneMDP1234', 2],
        ['Dupuis', 'Paul', 'paul.dupuis@cesi.fr', 'PaulMDP1234', 2],

        ['Perrin', 'Isabelle', 'isabelle.perrin@cesi.fr', 'IsabelleMDP1234', 3],
        ['Noel', 'Jean', 'jean.noel@cesi.fr', 'JeanMDP1234', 3],
        ['Marchand', 'Sophie', 'sophie.marchand@cesi.fr', 'SophieMDP1234', 3],
        ['Blanc', 'David', 'david.blanc@cesi.fr', 'DavidMDP1234', 3],
        ['Gauthier', 'Elodie', 'elodie.gauthier@cesi.fr', 'ElodieMDP1234', 3],
        ['Muller', 'Alex', 'alex.muller@cesi.fr', 'AlexMDP1234', 3],




        //peuplement épisode 2
        ['Martin', 'Eva', 'eva.martin@cesi.fr', 'EvaMDP1234', 1],
        ['Robert', 'Leo', 'leo.robert@cesi.fr', 'LeoMDP1234', 1],
        ['Fischer', 'Nina', 'nina.fischer@cesi.fr', 'NinaMDP1234', 1],
        ['Lambert', 'Hugo', 'hugo.lambert@cesi.fr', 'HugoMDP1234', 1],
        ['Perrot', 'Enzo', 'enzo.perrot@cesi.fr', 'EnzoMDP1234', 1],
        ['Henry', 'Lina', 'lina.henry@cesi.fr', 'LinaMDP1234', 1],
        ['Rivier', 'Tom', 'tom.rivier@cesi.fr', 'TomMDP1234', 1],
        ['Chevalier', 'Maeva', 'maeva.chevalier@cesi.fr', 'MaevaMDP1234', 1],
        ['Baron', 'Alexis', 'alexis.baron@cesi.fr', 'AlexisMDP1234', 1],
        ['Renard', 'Sarah', 'sarah.renard@cesi.fr', 'SarahMDP1234', 1],
        ['Picard', 'Noah', 'noah.picard@cesi.fr', 'NoahMDP1234', 1],
        ['Gros', 'Elena', 'elena.gros@cesi.fr', 'ElenaMDP1234', 1],
        ['Girard', 'Ethan', 'ethan.girard@cesi.fr', 'EthanMDP1234', 1],
        ['Guichard', 'Mila', 'mila.guichard@cesi.fr', 'MilaMDP1234', 1],
        ['Cohen', 'Robin', 'robin.cohen@cesi.fr', 'RobinMDP1234', 1],
        ['Schmitt', 'Loris', 'loris.schmitt@cesi.fr', 'LorisMDP1234', 1],
        ['Lopez', 'Ana', 'ana.lopez@cesi.fr', 'AnaMDP1234', 1],
        ['Gomes', 'Matteo', 'matteo.gomes@cesi.fr', 'MatteoMDP1234', 1],
        ['Besson', 'Nora', 'nora.besson@cesi.fr', 'NoraMDP1234', 1],
        ['Pires', 'Victor', 'victor.pires@cesi.fr', 'VictorMDP1234', 1],
        ['Benali', 'Kaïs', 'kais.benali@cesi.fr', 'KaisMDP1234', 1],
        ['Toussaint', 'Iris', 'iris.toussaint@cesi.fr', 'IrisMDP1234', 1],
        ['Navarro', 'Diego', 'diego.navarro@cesi.fr', 'DiegoMDP1234', 1],
        ['Masson', 'Lola', 'lola.masson@cesi.fr', 'LolaMDP1234', 1],
        ['Hoarau', 'Tim', 'tim.hoarau@cesi.fr', 'TimMDP1234', 1],
        ['Poulain', 'Alix', 'alix.poulain@cesi.fr', 'AlixMDP1234', 1],
        ['Barreau', 'Rayan', 'rayan.barreau@cesi.fr', 'RayanMDP1234', 1],
        ['Boulanger', 'Maya', 'maya.boulanger@cesi.fr', 'MayaMDP1234', 1],
        ['Rolland', 'Eliott', 'eliott.rolland@cesi.fr', 'EliottMDP1234', 1],
        ['Jacquet', 'Ayla', 'ayla.jacquet@cesi.fr', 'AylaMDP1234', 1],
        ['Delorme', 'Oscar', 'oscar.delorme@cesi.fr', 'OscarMDP1234', 1],
        ['Pottier', 'Liya', 'liya.pottier@cesi.fr', 'LiyaMDP1234', 1],
        ['Bertin', 'Yanis', 'yanis.bertin@cesi.fr', 'YanisMDP1234', 1],
        ['Vernet', 'Selma', 'selma.vernet@cesi.fr', 'SelmaMDP1234', 1],
        ['Lagarde', 'Naël', 'nael.lagarde@cesi.fr', 'NaelMDP1234', 1],
        ['Armand', 'Flora', 'flor.aarmand@cesi.fr', 'FloraMDP1234', 1],
        ['Guillet', 'Kylian', 'kylian.guillet@cesi.fr', 'KylianMDP1234', 1],
        ['Bouvet', 'Tess', 'tess.bouvet@cesi.fr', 'TessMDP1234', 1],
        ['Carrel', 'Ilian', 'ilian.carrel@cesi.fr', 'IlianMDP1234', 1],
        ['Ledoux', 'Oceane', 'oceane.ledoux@cesi.fr', 'OceaneMDP1234', 1],
        ['Ferreira', 'Cassandre', 'cassandre.ferreira@cesi.fr', 'CassandreMDP1234', 1],
        ['Barros', 'Ilyes', 'ilyes.barros@cesi.fr', 'IlyesMDP1234', 1],
        ['Payet', 'Nahia', 'nahia.payet@cesi.fr', 'NahiaMDP1234', 1],
        ['Weber', 'Théo', 'theo.weber@cesi.fr', 'TheoMDP1234', 1],
        ['Perret', 'Zoe', 'zoe.perret@cesi.fr', 'ZoeMDP1234', 1],
        ['Forest', 'Mael', 'mael.forest@cesi.fr', 'MaelMDP1234', 1],
        ['Collet', 'Amina', 'amina.collet@cesi.fr', 'AminaMDP1234', 1],
        ['Pichon', 'Erwan', 'erwan.pichon@cesi.fr', 'ErwanMDP1234', 1],
        ['Dumont', 'Siena', 'siena.dumont@cesi.fr', 'SienaMDP1234', 1],
        ['Lebon', 'Mathis', 'mathis.lebon@cesi.fr', 'MathisMDP1234', 1],
        ['Charpentier', 'Alexis', 'alexis.charpentier@cesi.fr', 'AlexisMDP1234', 1],
        ['Garnier', 'Marine', 'marine.garnier@cesi.fr', 'MarineMDP1234', 1],
        ['Vidal', 'Nathan', 'nathan.vidal@cesi.fr', 'NathanMDP1234', 1],
        ['Chevalier', 'Léa', 'lea.chevalier@cesi.fr', 'LeaMDP1234', 1],
        ['Dubois', 'Hugo', 'hugo.dubois@cesi.fr', 'HugoMDP1234', 1],
        ['Martins', 'Clara', 'clara.martins@cesi.fr', 'ClaraMDP1234', 1],
        ['Roux', 'Lucas', 'lucas.roux@cesi.fr', 'LucasMDP1234', 1],
        ['Blanchard', 'Emma', 'emma.blanchard@cesi.fr', 'EmmaMDP1234', 1],
        ['Fournier', 'Maxime', 'maxime.fournier@cesi.fr', 'MaximeMDP1234', 1],
        ['Morel', 'Chloé', 'chloe.morel@cesi.fr', 'ChloeMDP1234', 1],
        ['Benoit', 'Mathieu', 'mathieu.benoit@cesi.fr', 'MathieuMDP1234', 1],
        ['Perrier', 'Julie', 'julie.perrier@cesi.fr', 'JulieMDP1234', 1],
        ['Barbier', 'Nathan', 'nathan.barbier@cesi.fr', 'NathanMDP1234', 1],
        ['Giraud', 'Manon', 'manon.giraud@cesi.fr', 'ManonMDP1234', 1],
        ['Chevalier', 'Lina', 'lina.chevalier@cesi.fr', 'LinaMDP1234', 1],
        ['Dupuis', 'Enzo', 'enzo.dupuis@cesi.fr', 'EnzoMDP1234', 1],
        ['Fabre', 'Noah', 'noah.fabre@cesi.fr', 'NoahMDP1234', 1],
        ['Leclerc', 'Sarah', 'sarah.leclerc@cesi.fr', 'SarahMDP1234', 1],
        ['Lemoine', 'Ethan', 'ethan.lemoine@cesi.fr', 'EthanMDP1234', 1],
        ['Marchand', 'Anaïs', 'anais.marchand@cesi.fr', 'AnaisMDP1234', 1],
        ['Gauthier', 'Tom', 'tom.gauthier@cesi.fr', 'TomMDP1234', 1],
        ['Renaud', 'Mila', 'mila.renaud@cesi.fr', 'MilaMDP1234', 1],
        ['Lefevre', 'Victor', 'victor.lefevre@cesi.fr', 'VictorMDP1234', 1],
        ['Petit', 'Aline', 'aline.petit@cesi.fr', 'AlineMDP1234', 1],
        ['Bernard', 'Alex', 'alex.bernard@cesi.fr', 'AlexMDP1234', 1],
        ['Navarro', 'Clémence', 'clemence.navarro@cesi.fr', 'ClemenceMDP1234', 1],
        ['Moreau', 'Lola', 'lola.moreau@cesi.fr', 'LolaMDP1234', 1],
        ['Rivet', 'Mathis', 'mathis.rivet@cesi.fr', 'MathisMDP1234', 1],
        ['Colin', 'Sofia', 'sofia.colin@cesi.fr', 'SofiaMDP1234', 1],
        ['Guillaume', 'Leo', 'leo.guillaume@cesi.fr', 'LeoMDP1234', 1],
        ['Lambert', 'Eva', 'eva.lambert@cesi.fr', 'EvaMDP1234', 1],
        ['Henry', 'Jules', 'jules.henry@cesi.fr', 'JulesMDP1234', 1],
        ['Poulain', 'Nina', 'nina.poulain@cesi.fr', 'NinaMDP1234', 1],
        ['Fabre', 'Oscar', 'oscar.fabre@cesi.fr', 'OscarMDP1234', 1],
        ['Gros', 'Maya', 'maya.gros@cesi.fr', 'MayaMDP1234', 1],
        ['Lemoine', 'Tom', 'tom.lemoine@cesi.fr', 'TomMDP1234', 1],
        ['Chevalier', 'Léon', 'leon.chevalier@cesi.fr', 'LeonMDP1234', 1],
        ['Renaud', 'Clara', 'clara.renaud@cesi.fr', 'ClaraMDP1234', 1],
        ['Benoit', 'Ana', 'ana.benoit@cesi.fr', 'AnaMDP1234', 1],
        ['Dubois', 'Victor', 'victor.dubois@cesi.fr', 'VictorMDP1234', 1],
        ['Picard', 'Lina', 'lina.picard@cesi.fr', 'LinaMDP1234', 1],
        ['Giraud', 'Ethan', 'ethan.giraud@cesi.fr', 'EthanMDP1234', 1],
        ['Vernet', 'Chloé', 'chloe.vernet@cesi.fr', 'ChloeMDP1234', 1],
        ['Martin', 'Noah', 'noah.martin@cesi.fr', 'NoahMDP1234', 1],
        ['Robert', 'Elena', 'elena.robert@cesi.fr', 'ElenaMDP1234', 1],
        ['Fischer', 'Alexis', 'alexis.fischer@cesi.fr', 'AlexisMDP1234', 1],
        ['Lambert', 'Clara', 'clara.lambert@cesi.fr', 'ClaraMDP1234', 1],
        ['Perrot', 'Tom', 'tom.perrot@cesi.fr', 'TomMDP1234', 1],
        ['Henry', 'Sofia', 'sofia.henry@cesi.fr', 'SofiaMDP1234', 1],
        ['Rivier', 'Léa', 'lea.rivier@cesi.fr', 'LeaMDP1234', 1],
        ['Chevalier', 'Ethan', 'ethan.chevalier@cesi.fr', 'EthanMDP1234', 1],
        ['Baron', 'Mila', 'mila.baron@cesi.fr', 'MilaMDP1234', 1],
        ['Renard', 'Alexis', 'alexis.renard@cesi.fr', 'AlexisMDP1234', 1],
        ['Durand', 'Léa', 'lea.durand@cesi.fr', 'LeaMDP1234', 1],
        ['Moreau', 'Noah', 'noah.moreau@cesi.fr', 'NoahMDP1234', 1],
        ['Nguyen', 'Linh', 'linh.nguyen@cesi.fr', 'LinhMDP1234', 1],
        ['Petit', 'Emma', 'emma.petit@cesi.fr', 'EmmaMDP1234', 1],
        ['Lefevre', 'Chloé', 'chloe.lefevre@cesi.fr', 'ChloeMDP1234', 1],
        ['Khan', 'Amir', 'amir.khan@cesi.fr', 'AmirMDP1234', 1],
        ['Faure', 'Hugo', 'hugo.faure@cesi.fr', 'HugoMDP1234', 1],
        ['Mercier', 'Sophie', 'sophie.mercier@cesi.fr', 'SophieMDP1234', 1],
        ['Andre', 'Mathis', 'mathis.andre@cesi.fr', 'MathisMDP1234', 1],
        ['Bernard', 'Clara', 'clara.bernard@cesi.fr', 'ClaraMDP1234', 1],
        ['Simon', 'Ethan', 'ethan.simon@cesi.fr', 'EthanMDP1234', 1],
        ['Guerin', 'Manon', 'manon.guerin@cesi.fr', 'ManonMDP1234', 1],
        ['Benoit', 'Lucas', 'lucas.benoit@cesi.fr', 'LucasMDP1234', 1],
        ['Forato', 'Anaïs', 'anais.forato@cesi.fr', 'AnaisMDP1234', 1],
        ['Martinez', 'Léa', 'lea.martinez@cesi.fr', 'LeaMDP1234', 1],
        ['Dupont', 'Tom', 'tom.dupont@cesi.fr', 'TomMDP1234', 1],
        ['Giraud', 'Eva', 'eva.giraud@cesi.fr', 'EvaMDP1234', 1],
        ['Colin', 'Jules', 'jules.colin@cesi.fr', 'JulesMDP1234', 1],
        ['Renaud', 'Lina', 'lina.renaud@cesi.fr', 'LinaMDP1234', 1],
        ['Fabre', 'Leo', 'leo.fabre@cesi.fr', 'LeoMDP1234', 1],
        ['Olivier', 'Clara', 'clara.olivier@cesi.fr', 'ClaraMDP1234', 1],
        ['Lemoine', 'Alex', 'alex.lemoine@cesi.fr', 'AlexMDP1234', 1],
        ['Dupuis', 'Sofia', 'sofia.dupuis@cesi.fr', 'SofiaMDP1234', 1],
        ['Perrin', 'Rayan', 'rayan.perrin@cesi.fr', 'RayanMDP1234', 1],
        ['Noel', 'Nina', 'nina.noel@cesi.fr', 'NinaMDP1234', 1],
        ['Marchand', 'Mila', 'mila.marchand@cesi.fr', 'MilaMDP1234', 1],
        ['Blanc', 'Tom', 'tom.blanc@cesi.fr', 'TomMDP1234', 1],
        ['Muller', 'Alexis', 'alexis.muller@cesi.fr', 'AlexisMDP1234', 1],
        ['Martin', 'Léon', 'leon.martin@cesi.fr', 'LeonMDP1234', 1],
        ['Robert', 'Clara', 'clara.robert@cesi.fr', 'ClaraMDP1234', 1],
        ['Fischer', 'Noah', 'noah.fischer@cesi.fr', 'NoahMDP1234', 1],
        ['Perrot', 'Sofia', 'sofia.perrot@cesi.fr', 'SofiaMDP1234', 1],
        ['Henry', 'Lucas', 'lucas.henry@cesi.fr', 'LucasMDP1234', 1],
        ['Rivier', 'Lina', 'lina.rivier@cesi.fr', 'LinaMDP1234', 1],
        ['Chevalier', 'Mathis', 'mathis.chevalier@cesi.fr', 'MathisMDP1234', 1],
        ['Baron', 'Eva', 'eva.baron@cesi.fr', 'EvaMDP1234', 1],
        ['Renard', 'Tom', 'tom.renard@cesi.fr', 'TomMDP1234', 1],
        ['Picard', 'Manon', 'manon.picard@cesi.fr', 'ManonMDP1234', 1],
        ['Gros', 'Hugo', 'hugo.gros@cesi.fr', 'HugoMDP1234', 1],
        ['Girard', 'Clara', 'clara.girard@cesi.fr', 'ClaraMDP1234', 1],
        ['Guichard', 'Léa', 'lea.guichard@cesi.fr', 'LeaMDP1234', 1],
        ['Cohen', 'Alex', 'alex.cohen@cesi.fr', 'AlexMDP1234', 1],
        ['Schmitt', 'Mila', 'mila.schmitt@cesi.fr', 'MilaMDP1234', 1],
        ['Lopez', 'Lucas', 'lucas.lopez@cesi.fr', 'LucasMDP1234', 1],
        ['Gomes', 'Clara', 'clara.gomes@cesi.fr', 'ClaraMDP1234', 1],
        ['Besson', 'Tom', 'tom.besson@cesi.fr', 'TomMDP1234', 1],
        ['Pires', 'Eva', 'eva.pires@cesi.fr', 'EvaMDP1234', 1],
        ['Benali', 'Amir', 'amir.benali@cesi.fr', 'AmirMDP1234', 1],
        ['Toussaint', 'Lina', 'lina.toussaint@cesi.fr', 'LinaMDP1234', 1],
        ['Navarro', 'Manuel', 'manuel.navarro@cesi.fr', 'ManuelMDP1234', 1],
        ['Masson', 'Sofia', 'sofia.masson@cesi.fr', 'SofiaMDP1234', 1],
        ['Hoarau', 'Noah', 'noah.hoarau@cesi.fr', 'NoahMDP1234', 1],
        ['Poulain', 'Eva', 'eva.poulain@cesi.fr', 'EvaMDP1234', 1],
        ['Barreau', 'Lucas', 'lucas.barreau@cesi.fr', 'LucasMDP1234', 1],
        ['Boulanger', 'Clara', 'clara.boulanger@cesi.fr', 'ClaraMDP1234', 1],
        ['Rolland', 'Mathis', 'mathis.rolland@cesi.fr', 'MathisMDP1234', 1],
        ['Jacquet', 'Léa', 'lea.jacquet@cesi.fr', 'LeaMDP1234', 1],
        ['Delorme', 'Hugo', 'hugo.delorme@cesi.fr', 'HugoMDP1234', 1],
        ['Pottier', 'Eva', 'eva.pottier@cesi.fr', 'EvaMDP1234', 1],
        ['Bertin', 'Tom', 'tom.bertin@cesi.fr', 'TomMDP1234', 1],
        ['Vernet', 'Lina', 'lina.vernet@cesi.fr', 'LinaMDP1234', 1],
        ['Lagarde', 'Mathis', 'mathis.lagarde@cesi.fr', 'MathisMDP1234', 1],
        ['Armand', 'Clara', 'clara.armand@cesi.fr', 'ClaraMDP1234', 1],
        ['Guillet', 'Lucas', 'lucas.guillet@cesi.fr', 'LucasMDP1234', 1],
        ['Bouvet', 'Eva', 'eva.bouvet@cesi.fr', 'EvaMDP1234', 1],
        ['Carrel', 'Tom', 'tom.carrel@cesi.fr', 'TomMDP1234', 1],
        ['Ledoux', 'Lina', 'lina.ledoux@cesi.fr', 'LinaMDP1234', 1],
        ['Ferreira', 'Mathis', 'mathis.ferreira@cesi.fr', 'MathisMDP1234', 1],
        ['Barros', 'Clara', 'clara.barros@cesi.fr', 'ClaraMDP1234', 1],
        ['Payet', 'Hugo', 'hugo.payet@cesi.fr', 'HugoMDP1234', 1],
        ['Weber', 'Eva', 'eva.weber@cesi.fr', 'EvaMDP1234', 1],
        ['Perret', 'Tom', 'tom.perret@cesi.fr', 'TomMDP1234', 1],
        ['Forest', 'Lina', 'lina.forest@cesi.fr', 'LinaMDP1234', 1],
        ['Collet', 'Lucas', 'lucas.collet@cesi.fr', 'LucasMDP1234', 1],
        ['Pichon', 'Clara', 'clara.pichon@cesi.fr', 'ClaraMDP1234', 1],
        ['Dumont', 'Mathis', 'mathis.dumont@cesi.fr', 'MathisMDP1234', 1],
        ['Lebon', 'Léa', 'lea.lebon@cesi.fr', 'LeaMDP1234', 1],


        ['Durand', 'Vincent', 'vincent.durand@cesi.fr', 'VincentMDP1234', 2],
        ['Martin', 'Isabelle', 'isabelle.martin@cesi.fr', 'IsabelleMDP1234', 2],
        ['Bernard', 'Thierry', 'thierry.bernard@cesi.fr', 'ThierryMDP1234', 2],
        ['Petit', 'Sophie', 'sophie.petit@cesi.fr', 'SophieMDP1234', 2],
        ['Robert', 'Laurent', 'laurent.robert@cesi.fr', 'LaurentMDP1234', 2],
        ['Richard', 'Catherine', 'catherine.richard@cesi.fr', 'CatherineMDP1234', 2],
        ['Durieux', 'Frédéric', 'frederic.durieux@cesi.fr', 'FredericMDP1234', 2],
        ['Lemoine', 'Martine', 'martine.lemoine@cesi.fr', 'MartineMDP1234', 2],
        ['Morel', 'Philippe', 'philippe.morel@cesi.fr', 'PhilippeMDP1234', 2],
        ['Blanc', 'Monique', 'monique.blanc@cesi.fr', 'MoniqueMDP1234', 2],
        ['Fabre', 'Daniel', 'daniel.fabre@cesi.fr', 'DanielMDP1234', 2],
        ['Garnier', 'Christine', 'christine.garnier@cesi.fr', 'ChristineMDP1234', 2],
        ['Benoit', 'Patrick', 'patrick.benoit@cesi.fr', 'PatrickMDP1234', 2],
        ['Rochefort', 'Ismaël', 'ismael.rochefort@cesi.fr', 'IsmaelMDP1234', 2],
        ['Clément', 'Sandrine', 'sandrine.clement@cesi.fr', 'SandrineMDP1234', 2],


        ['Vallencourt', 'Adrian', 'adrian.vallencourt@cesi.fr', 'AdrianMDP1234', 3],
        ['Fontavie', 'Beatrice', 'beatrice.fontavie@cesi.fr', 'BeatriceMDP1234', 3],
        ['Rovelli', 'Matteo', 'matteo.rovelli@cesi.fr', 'MatteoMDP1234', 3],
        ['Castelmont', 'Elena', 'elena.castelmont@cesi.fr', 'ElenaMDP1234', 3],
        ['Bellandi', 'Lorenzo', 'lorenzo.bellandi@cesi.fr', 'LorenzoMDP1234', 3],
        ['Devalois', 'Marianne', 'marianne.devalois@cesi.fr', 'MarianneMDP1234', 3],
        ['Luciani', 'Stefano', 'stefano.luciani@cesi.fr', 'StefanoMDP1234', 3],
        ['Montarelli', 'Giulia', 'giulia.montarelli@cesi.fr', 'GiuliaMDP1234', 3],
        ['Ferrandis', 'Julien', 'julien.ferrandis@cesi.fr', 'JulienMDP1234', 3],
        ['Bellucci', 'Chiara', 'chiara.bellucci@cesi.fr', 'ChiaraMDP1234', 3],
        ['Delacroix', 'Nicolas', 'nicolas.delacroix@cesi.fr', 'NicolasMDP1234', 3],
        ['Vassalli', 'Sofia', 'sofia.vassalli@cesi.fr', 'SofiaMDP1234', 3],
        ['Montclair', 'Pascal', 'pascal.montclair@cesi.fr', 'PascalMDP1234', 3],
        ['D’Amico', 'Luca', 'luca.damico@cesi.fr', 'LucaMDP1234', 3],
        ['Bellavance', 'Aline', 'aline.bellavance@cesi.fr', 'AlineMDP1234', 3]



    ];
}

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
