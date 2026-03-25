-- ---------------------------------------------------------
-- Table : Utilisateurs
-- ---------------------------------------------------------
INSERT INTO Utilisateurs (Nom, Prenom, Email, Mdp, Role) VALUES
('Durand', 'Alexandre', 'alexandre.durand@cesi.fr', 'AlexandreMDP', 1),
('Lemoine', 'Camille', 'camille.lemoine@cesi.fr', 'CamilleMDP', 1),
('Moreau', 'Julien', 'julien.moreau@cesi.fr', 'JulienMDP', 1),
('Petit', 'Sophie', 'sophie.petit@cesi.fr', 'SophieMDP', 1),
('Roux', 'Thomas', 'thomas.roux@cesi.fr', 'ThomasMDP', 1),
('Lefevre', 'Claire', 'claire.lefevre@cesi.fr', 'ClaireMDP', 1),
('Garnier', 'Lucas', 'lucas.garnier@cesi.fr', 'LucasMDP', 1),
('Faure', 'Emma', 'emma.faure@cesi.fr', 'EmmaMDP', 1),
('Mercier', 'Maxime', 'maxime.mercier@cesi.fr', 'MaximeMDP', 1),
('Andre', 'Léa', 'lea.andre@cesi.fr', 'LéaMDP', 1),
('Bernard', 'Victor', 'victor.bernard@cesi.fr', 'VictorMDP', 1),
('Simon', 'Clémence', 'clemence.simon@cesi.fr', 'ClémenceMDP', 1),
('Guerin', 'Nathan', 'nathan.guerin@cesi.fr', 'NathanMDP', 1),
('Benoit', 'Chloé', 'chloe.benoit@cesi.fr', 'ChloéMDP', 1),
('Carpentier', 'Ethan', 'ethan.carpentier@cesi.fr', 'EthanMDP', 1),
('Martinez', 'Inès', 'ines.martinez@cesi.fr', 'InèsMDP', 1),
('Dupont', 'Mathieu', 'mathieu.dupont@cesi.fr', 'MathieuMDP', 1),
('Giraud', 'Julie', 'julie.giraud@cesi.fr', 'JulieMDP', 1),
('Colin', 'Adrien', 'adrien.colin@cesi.fr', 'AdrienMDP', 1),
('Renaud', 'Manon', 'manon.renaud@cesi.fr', 'ManonMDP', 1),
-- Pilotes/Admins
('Fabre', 'Claire', 'claire.fabre@cesi.fr', 'ClaireMDP', 2),
('Olivier', 'Marc', 'marc.olivier@cesi.fr', 'MarcMDP', 2),
('Lemoine', 'Anne', 'anne.lemoine@cesi.fr', 'AnneMDP', 2),
('Dupuis', 'Paul', 'paul.dupuis@cesi.fr', 'PaulMDP', 2),
('Perrin', 'Isabelle', 'isabelle.perrin@cesi.fr', 'IsabelleMDP', 3),
('Noel', 'Jean', 'jean.noel@cesi.fr', 'JeanMDP', 3),
('Marchand', 'Sophie', 'sophie.marchand@cesi.fr', 'SophieMDP', 3),
('Blanc', 'David', 'david.blanc@cesi.fr', 'DavidMDP', 3),
('Gauthier', 'Elodie', 'elodie.gauthier@cesi.fr', 'ElodieMDP', 3),
('Muller', 'Alex', 'alex.muller@cesi.fr', 'AlexMDP', 3);

-- ---------------------------------------------------------
-- Table : Competences
-- ---------------------------------------------------------
INSERT INTO Competences (Nom_competence) VALUES
('Python'),('C'),('C++'),('Java'),('SQL'),('MySQL'),('PostgreSQL'),('HTML'),('CSS'),('JavaScript'),
('PHP'),('Linux'),('Windows Server'),('Réseau TCP/IP'),('CCNA'),('Cybersécurité'),('Cloud AWS'),('Docker'),
('Kubernetes'),('Git'),('GitHub'),('GitLab'),('Excel'),('PowerPoint'),('Word'),('MATLAB'),('Simulink'),
('RDM'),('Mécanique des fluides'),('Thermodynamique'),('Électronique'),('Arduino'),('Raspberry Pi'),('Revit'),
('Fusion360'),('SolidWorks'),('3D'),('Intelligence Artificielle'),('Machine Learning'),('Deep Learning'),
('Data Science'),('Big Data'),('Tableau'),('Power BI'),('Analyse de données'),('Statistiques'),('Modélisation 3D'),
('Gestion de projet'),('Scrum'),('Agile'),('Tests unitaires'),('Automatisation'),('Robotics'),('IoT'),
('Architecture logicielle'),('UI/UX Design'),('Sécurité web'),('Cryptographie'),('Virtualisation'),
('DevOps'),('Microservices'),('PHP Symfony'),('PHP Laravel');

-- ---------------------------------------------------------
-- Table : Entreprises
-- ---------------------------------------------------------
INSERT INTO Entreprises (Email_entreprise, Nom_entreprise, Secteur, Type_, Nb_stagiaires, Description_entreprise, Telephone, ID_Utilisateur) VALUES
('contact@techinnov.fr','TechInnov','Paris (75001)','Informatique',5,'Entreprise spécialisée dans le développement logiciel et les solutions cloud pour entreprises.', '0102030405', 21),
('recrutement@batipro.fr','BatiPro','Lyon (69001)','BTP',3,'Entreprise de construction et rénovation de bâtiments publics et privés.', '0605040302', 22),
('contact@genesisai.fr','Genesis AI','Nice (06000)','IA',4,'Startup innovante dans le domaine de l’intelligence artificielle appliquée à l’industrie.', '0708091011', 23),
('contact@designplus.fr','DesignPlus','Marseille (13001)','Design',2,'Studio spécialisé en design industriel et graphique pour produits tech.', '0611121314', 24),
('contact@mechatronix.fr','Mechatronix','Toulouse (31000)','Mécatronique',3,'Entreprise spécialisée en robotique et automatisation industrielle.', '0612345678', 25),
('contact@ecoenergy.fr','EcoEnergy','Bordeaux (33000)','Énergies renouvelables',6,'Entreprise proposant des solutions d’énergie verte et durable pour les entreprises.', '0622334455', 26),
('contact@meditech.fr','MediTech','Lille (59000)','Santé',2,'Entreprise développant des technologies médicales innovantes pour hôpitaux et laboratoires.', '0677889900', 27),
('contact@translog.fr','TransLog','Nantes (44000)','Transport',3,'Entreprise spécialisée dans la logistique et le transport de marchandises.', '0612345678', 28),
('contact@agrotech.fr','AgroTech','Strasbourg (67000)','Agroalimentaire',4,'Entreprise innovante dans le secteur agroalimentaire et l’agriculture de précision.', '0644556677', 29),
('contact@fintechplus.fr','FinTechPlus','Monaco (98000)','Finance',2,'Start-up proposant des solutions financières numériques et sécurisées.', '0655667788', 30);

-- ---------------------------------------------------------
-- Table : Wishlists
-- ---------------------------------------------------------
INSERT INTO Wishlists (ID_Utilisateur) VALUES
(1),(2),(3),(4),(5),(6),(7),(8),(9),(10);

-- ---------------------------------------------------------
-- Table : Offres
-- ---------------------------------------------------------
INSERT INTO Offres (Titre, Remuneration, Date_, Description, Duree, Ville_CP, ID_Entreprise, ID_Utilisateur) VALUES
('Stage Développeur Python', 1200.00, '2026-03-01', 'Stage pratique pour participer au développement de projets Python pour le cloud et les applications web. Travail sur API, bases de données et automatisation.', 12, 'Paris (75001)', 1, 21),
('Stage Ingénieur BTP', 800.00, '2026-02-15', 'Stage au sein de l’équipe de construction et rénovation. Participation à la supervision des chantiers et modélisation de projets.', 8, 'Lyon (69001)', 2, 22),
('Stage IA', 1100.00, '2026-01-20', 'Stage dans le développement d’algorithmes de machine learning et deep learning pour des applications industrielles.', 10, 'Nice (06000)', 3, 23),
('Stage Design Industriel', 900.00, '2026-03-10', 'Stage au sein du studio pour participer à la conception de produits tech et au prototypage 3D.', 6, 'Marseille (13001)', 4, 24),
('Stage Robotique', 1000.00, '2026-02-05', 'Stage pratique sur des projets de robotique et d’automatisation industrielle.', 12, 'Toulouse (31000)', 5, 25);

-- ---------------------------------------------------------
-- Table : Requerir
-- ---------------------------------------------------------
INSERT INTO Requerir (ID_Offre, ID_Competence) VALUES
-- Stage Développeur Python
(1,1),(1,5),(1,8),(1,11),
-- Stage Ingénieur BTP
(2,28),(2,29),(2,27),
-- Stage IA
(3,36),(3,37),(3,38),(3,39),
-- Stage Design Industriel
(4,33),(4,34),(4,35),
-- Stage Robotique
(5,44),(5,45),(5,46);

-- ---------------------------------------------------------
-- Table : Evaluations
-- ---------------------------------------------------------
INSERT INTO Evaluations (Note, ID_Utilisateur, ID_Entreprise) VALUES
-- Entreprise 1
(4.5, 1, 1),
(3.8, 2, 1),
(4.2, 3, 1),
-- Entreprise 2
(5.0, 4, 2),
(4.0, 5, 2),
(3.5, 6, 2),
-- Entreprise 3
(4.8, 7, 3),
(4.1, 8, 3),
(3.9, 9, 3);




-- La table 'Candidatures' n'est pas encore peuplée.
