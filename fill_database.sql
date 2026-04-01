-- BIEN PENSER A PEUPLER LES UTILISATEURS VIA LE hash.php AVANT D'EXECUTER LES COMMANDES SQL CI-DESSOUS !!!!!!!!

-- Commande Ubuntu pour mettre les Utilisateurs : 
-- php src/Fonctionnalites/hash.php seed



INSERT INTO Competences (Nom_competence) VALUES
('Python'),
('C'),
('C++'),
('Java'),
('C#'),
('JavaScript'),
('TypeScript'),
('HTML'),
('CSS'),
('PHP'),
('SQL'),
('MySQL'),
('PostgreSQL'),
('MongoDB'),
('Linux'),
('Windows Server'),
('Réseau TCP/IP'),
('CCNA'),
('Cybersécurité'),
('Pentesting'),
('Firewall'),
('VPN'),
('Cloud AWS'),
('Cloud Azure'),
('Cloud GCP'),
('Docker'),
('Kubernetes'),
('Virtualisation'),
('Git'),
('GitHub'),
('GitLab'),
('CI/CD'),
('Agile'),
('Scrum'),
('Gestion de projet'),
('Tests unitaires'),
('Automatisation'),
('Data Science'),
('Machine Learning'),
('Deep Learning'),
('IA'),
('Big Data'),
('Hadoop'),
('Spark'),
('Tableau'),
('Power BI'),
('Analyse de données'),
('Statistiques'),
('R'),
('MATLAB'),
('Simulink'),
('Excel'),
('PowerPoint'),
('Word'),
('RDM'),
('Mécanique des fluides'),
('Thermodynamique'),
('Résistance des matériaux'),
('Électronique'),
('Arduino'),
('Raspberry Pi'),
('Robotique'),
('IoT'),
('Automatisation industrielle'),
('PLC'),
('Fusion360'),
('SolidWorks'),
('CATIA'),
('Revit'),
('SketchUp'),
('Autodesk Inventor'),
('3D Modelling'),
('UI/UX Design'),
('Photoshop'),
('Illustrator'),
('InDesign'),
('After Effects'),
('Premiere Pro'),
('Blender'),
('Unity'),
('Unreal Engine'),
('Optique'),
('Photonique'),
('Hydraulique'),
('Énergies renouvelables'),
('Mécatronique'),
('Matériaux composites'),
('Impression 3D'),
('Blockchain'),
('Smart Contracts'),
('SAP'),
('ERP'),
('Business Intelligence'),
('SQL Server'),
('NoSQL'),
('Data Warehousing'),
('Analyse prédictive'),
('Vision par ordinateur'),
('Traitement du signal'),
('Traitement d’image'),
('Reconnaissance vocale'),
('NLP'),
('Nanotechnologie'),
('Bio-ingénierie'),
('Chimie appliquée'),
('Gestion de production'),
('Logistique industrielle'),
('Sécurité industrielle'),
('Simulation numérique'),
('Analyse de contraintes'),
('Fluidique'),
('Aéronautique'),
('Automobile'),
('Équipements médicaux');



INSERT INTO Entreprises (Email_entreprise, Nom_entreprise, Secteur, Type_, Nb_stagiaires, Description_entreprise, Telephone, ID_Utilisateur) VALUES
('contact@techvalley.com', 'Tech Valley', 'Nice (06000)', 'Informatique', 5, 'Start-up innovante dans les solutions cloud.', '0145789632', 20),
('hr@globalfinance.com', 'Global Finance', 'Paris (75000)', 'Finance', 8, 'Entreprise internationale spécialisée en services financiers.', '0169854321', 21),
('info@greenenergy.eu', 'Green Energy', 'Lyon (69000)', 'Énergies renouvelables', 4, 'Solutions durables et énergies renouvelables.', '0154789632', 22),
('contact@meditech.org', 'MediTech', 'Marseille (13000)', 'Santé', 3, 'Innovation dans la technologie médicale et santé numérique.', '0178549632', 23),
('recrutement@edumind.fr', 'EduMind', 'Toulouse (31000)', 'Éducation', 6, 'Plateforme éducative numérique pour étudiants et enseignants.', '0189632547', 24),
('contact@designsphere.com', 'DesignSphere', 'Bordeaux (33000)', 'Design', 2, 'Studio de design et UX/UI pour applications et sites web.', '0197456321', 25),
('info@foodtech.fr', 'FoodTech', 'Lille (59000)', 'Agroalimentaire', 5, 'Innovation dans la foodtech et distribution alimentaire.', '0147859632', 26),
('contact@cybersecure.com', 'CyberSecure', 'Strasbourg (67000)', 'Cybersécurité', 4, 'Solutions de cybersécurité et audit informatique.', '0169857423', 27),
('hr@logisticsplus.com', 'Logistics Plus', 'Nice (06000)', 'Transport', 7, 'Solutions logistiques et transport à l’international.', '0154789632', 28),
('contact@ai-vision.com', 'AI Vision', 'Paris (75000)', 'Intelligence artificielle', 3, 'Développement d’IA et vision par ordinateur.', '0178459632', 29),
('contact@biotools.com', 'BioTools', 'Lyon (69000)', 'Biotechnologie', 4, 'Matériel et logiciels pour laboratoires de biotechnologie.', '0145896321', 210),
('info@solartech.eu', 'SolarTech', 'Marseille (13000)', 'Énergies renouvelables', 5, 'Innovation en panneaux solaires et stockage énergétique.', '0164789523', 211),
('contact@finanalytics.com', 'FinAnalytics', 'Toulouse (31000)', 'Finance', 3, 'Analyse de données financières et conseils en investissement.', '0158964237', 212),
('hr@healthnova.com', 'HealthNova', 'Bordeaux (33000)', 'Santé', 6, 'Solutions numériques pour le secteur de la santé.', '0174859632', 213),
('contact@edutechplus.fr', 'EduTech Plus', 'Lille (59000)', 'Éducation', 4, 'Plateforme d’apprentissage en ligne et ressources pédagogiques.', '0187459632', 214),
('info@creativemind.com', 'CreativeMind', 'Strasbourg (67000)', 'Design', 2, 'Agence de design et communication visuelle.', '0198745632', 215),
('contact@gastroinnov.com', 'GastroInnov', 'Nice (06000)', 'Agroalimentaire', 5, 'Innovation et technologie dans la restauration et alimentation.', '0147859632', 216),
('hr@cybershield.com', 'CyberShield', 'Paris (75000)', 'Cybersécurité', 3, 'Sécurité des systèmes informatiques et audits.', '0169857432', 217),
('info@transglobal.com', 'TransGlobal', 'Lyon (69000)', 'Transport', 7, 'Services logistiques et transport international.', '0154789652', 218),
('contact@visionai.com', 'VisionAI', 'Marseille (13000)', 'Intelligence artificielle', 3, 'Développement d’IA pour la vision industrielle.', '0178459623', 219),
('contact@nanotechlab.com', 'NanoTech Lab', 'Toulouse (31000)', 'Technologie', 4, 'Recherche et développement en nanotechnologie.', '0145896523', 220),
('info@ecoenergy.com', 'EcoEnergy', 'Bordeaux (33000)', 'Énergies renouvelables', 5, 'Solutions écologiques et durables.', '0164789524', 221),
('hr@fininnov.com', 'FinInnov', 'Lille (59000)', 'Finance', 3, 'Innovation et fintech pour entreprises.', '0158964238', 222),
('contact@medinnov.com', 'MedInnov', 'Strasbourg (67000)', 'Santé', 4, 'Technologies médicales et innovations pour hôpitaux.', '0174859633', 223),
('info@learnwise.fr', 'LearnWise', 'Nice (06000)', 'Éducation', 4, 'Plateforme numérique pour la formation continue.', '0187459633', 224),
('contact@designhub.com', 'DesignHub', 'Paris (75000)', 'Design', 3, 'Agence de design créatif et UX.', '0198745633', 225),
('hr@foodinnov.com', 'FoodInnov', 'Lyon (69000)', 'Agroalimentaire', 5, 'Solutions innovantes pour l’industrie alimentaire.', '0147859633', 226),
('info@cyberprotection.com', 'CyberProtection', 'Marseille (13000)', 'Cybersécurité', 4, 'Protection avancée des systèmes et données.', '0169857433', 227),
('contact@logimaster.com', 'LogiMaster', 'Toulouse (31000)', 'Transport', 6, 'Logistique et transport pour entreprises.', '0154789653', 228),
('hr@ai-solutions.com', 'AI Solutions', 'Bordeaux (33000)', 'Intelligence artificielle', 3, 'Développement d’IA pour applications industrielles.', '0178459624', 229),
('info@greenlabs.com', 'Green Labs', 'Lille (59000)', 'Énergies renouvelables', 4, 'Laboratoire de recherche en solutions écologiques.', '0145896524', 230),
('contact@finedge.com', 'FinEdge', 'Strasbourg (67000)', 'Finance', 3, 'Solutions financières et conseils stratégiques.', '0164789525', 231),
('hr@meditechplus.com', 'MediTech Plus', 'Nice (06000)', 'Santé', 5, 'Technologies médicales avancées.', '0174859634', 232),
('info@edusmart.fr', 'EduSmart', 'Paris (75000)', 'Éducation', 4, 'Plateforme digitale d’apprentissage et cours en ligne.', '0187459634', 233),
('contact@creativelab.com', 'CreativeLab', 'Lyon (69000)', 'Design', 3, 'Agence créative spécialisée en UX/UI et branding.', '0198745634', 234),
('hr@foodtechplus.com', 'FoodTech Plus', 'Marseille (13000)', 'Agroalimentaire', 5, 'Solutions technologiques pour la foodtech.', '0147859634', 235),
('info@cyberdefense.com', 'CyberDefense', 'Toulouse (31000)', 'Cybersécurité', 4, 'Sécurité avancée pour systèmes et réseaux.', '0169857434', 236),
('contact@logisticspro.com', 'LogisticsPro', 'Bordeaux (33000)', 'Transport', 6, 'Services de transport et logistique.', '0154789654', 237),
('hr@visionai-lab.com', 'VisionAI Lab', 'Lille (59000)', 'Intelligence artificielle', 3, 'IA et vision industrielle.', '0178459625', 238),
('info@solargenius.com', 'SolarGenius', 'Strasbourg (67000)', 'Énergies renouvelables', 4, 'Solutions innovantes pour l’énergie solaire.', '0145896525', 239),
('contact@financenice.fr', 'Finance Nice', 'Nice (06000)', 'Finance', 5, 'Solutions innovantes pour l’analyse financière et la gestion d’entreprise.', '0112345678', 211),
('contact@medisoft.fr', 'MediSoft', 'Paris (75000)', 'Santé / Logiciel médical', 4, 'Développement de logiciels avancés pour dispositifs médicaux.', '0198765432', 212),
('contact@educonnect.fr', 'EduConnect', 'Lyon (69000)', 'Éducation numérique', 6, 'Plateforme de modules interactifs et contenus pédagogiques.', '0123456789', 213),
('contact@designlab.fr', 'DesignLab', 'Marseille (13000)', 'Design / Graphisme', 3, 'Agence spécialisée en UX/UI et identité visuelle pour applications et sites web.', '0176543210', 214),
('contact@foodinnov.fr', 'FoodInnov', 'Toulouse (31000)', 'Agroalimentaire / FoodTech', 5, 'Recherche et développement de produits alimentaires innovants.', '0135792468', 215),
('contact@cyberdefense.fr', 'CyberDefense Inc', 'Bordeaux (33000)', 'Cybersécurité', 4, 'Audit, sécurisation et protection des systèmes d’information pour entreprises.', '0182468135', 216),
('contact@logistix.fr', 'Logistix', 'Lille (59000)', 'Transport & Logistique', 5, 'Optimisation et suivi des chaînes logistiques nationales et internationales.', '0141357926', 217),
('contact@aiindus.fr', 'AIIndustries', 'Strasbourg (67000)', 'Intelligence Artificielle / Industrie', 6, 'Développement d’algorithmes IA pour applications industrielles.', '0169753182', 218),
('contact@greenind.fr', 'GreenIndustries', 'Nice (06000)', 'Énergies renouvelables / Industrie', 4, 'Mise en place de solutions durables et énergies vertes pour l’industrie.', '0158642739', 219),
('contact@financemonitor.fr', 'FinanceMonitor', 'Paris (75000)', 'Finance', 5, 'Audit et reporting pour entreprises, suivi financier et conseil.', '0183726154', 220);


INSERT INTO Wishlists (ID_Utilisateur) VALUES
(1),(2),(3),(4),(5),(6),(7),(8),(9),(10),
(11),(12),(13),(14),(15),(16),(17),(18),(19),
(30),(31),(32),(33),(34),(35),(36),(37),(38),(39),
(40),(41),(42),(43),(44),(45),(46),(47),(48),(49),
(50),(51),(52),(53),(54),(55),(56),(57),(58),(59),
(60),(61),(62),(63),(64),(65),(66),(67),(68),(69),
(70),(71),(72),(73),(74),(75),(76),(77),(78),(79),
(80),(81),(82),(83),(84),(85),(86),(87),(88),(89),
(90),(91),(92),(93),(94),(95),(96),(97),(98),(99),
(100),(101),(102),(103),(104),(105),(106),(107),(108),(109),
(110),(111),(112),(113),(114),(115),(116),(117),(118),(119),
(120),(121),(122),(123),(124),(125),(126),(127),(128),(129),
(130),(131),(132),(133),(134),(135),(136),(137),(138),(139),
(140),(141),(142),(143),(144),(145),(146),(147),(148),(149),
(150),(151),(152),(153),(154),(155),(156),(157),(158),(159),
(160),(161),(162),(163),(164),(165),(166),(167),(168),(169),
(170),(171),(172),(173),(174),(175),(176),(177),(178),(179),
(180),(181),(182),(183),(184),(185),(186),(187),(188),(189),
(190),(191),(192),(193),(194),(195),(196),(197),(198),(199),
(200),(201),(202),(203),(204),(205),(206),(207),(208),(209);


INSERT INTO Offres (Titre, Remuneration, Date_, Description, Duree, Ville_CP, ID_Entreprise, ID_Utilisateur) VALUES
('Stage Développeur Web', 1000.00, '2026-03-01', 'Participation au développement de sites web et applications internes.', 10, 'Nice (06000)', 1, 20),
('Stage Analyste Financier', 1150.00, '2026-03-05', 'Analyse des performances financières et reporting.', 14, 'Paris (75000)', 2, 21),
('Stage Ingénieur Énergies Renouvelables', 1050.00, '2026-03-10', 'Conception de solutions solaires et éoliennes.', 8, 'Lyon (69000)', 3, 22),
('Stage Développement Logiciel Médical', 1100.00, '2026-03-12', 'Participation au développement de logiciels pour dispositifs médicaux.', 10, 'Marseille (13000)', 4, 23),
('Stage Assistant Pédagogique Numérique', 900.00, '2026-03-15', 'Création de contenu et support pour plateforme éducative.', 6, 'Toulouse (31000)', 5, 24),
('Stage Designer UX/UI', 950.00, '2026-03-18', 'Conception et amélioration d’interfaces pour applications et sites.', 8, 'Bordeaux (33000)', 6, 25),
('Stage Innovation Agroalimentaire', 1000.00, '2026-03-20', 'Participation à des projets foodtech innovants.', 12, 'Lille (59000)', 7, 26),
('Stage Sécurité Informatique', 1050.00, '2026-03-22', 'Audit et sécurisation des systèmes d’information.', 10, 'Strasbourg (67000)', 8, 27),
('Stage Gestion Logistique', 850.00, '2026-03-25', 'Optimisation des flux et transport de marchandises.', 10, 'Nice (06000)', 9, 28),
('Stage Développeur IA', 1200.00, '2026-03-28', 'Développement d’algorithmes de vision par ordinateur et IA.', 12, 'Paris (75000)', 10, 29),
('Stage Bio-informatique', 1000.00, '2026-03-30', 'Analyse de données biologiques et développement de pipelines bio-informatiques.', 10, 'Lyon (69000)', 11, 210),
('Stage Concepteur Panneaux Solaires', 950.00, '2026-04-01', 'Optimisation et conception de systèmes photovoltaïques.', 8, 'Marseille (13000)', 12, 211),
('Stage Analyste Fintech', 1100.00, '2026-04-05', 'Développement d’outils d’analyse pour le secteur financier.', 12, 'Toulouse (31000)', 13, 212),
('Stage Développement Médical', 1000.00, '2026-04-08', 'Création de logiciels pour équipements médicaux et suivi patients.', 10, 'Bordeaux (33000)', 14, 213),
('Stage Support Éducation', 900.00, '2026-04-10', 'Support pour la plateforme d’apprentissage et tutoriels interactifs.', 6, 'Lille (59000)', 15, 214),
('Stage Designer Graphique', 950.00, '2026-04-12', 'Création visuelle pour sites web, apps et branding.', 8, 'Strasbourg (67000)', 16, 215),
('Stage FoodTech Développement', 1000.00, '2026-04-15', 'Prototypage et tests de produits alimentaires innovants.', 14, 'Nice (06000)', 17, 216),
('Stage Sécurité Réseau', 1050.00, '2026-04-18', 'Implémentation de firewall et solutions de cybersécurité.', 20, 'Paris (75000)', 18, 217),
('Stage Gestion Transport', 850.00, '2026-04-20', 'Planification des trajets et suivi logistique.', 10, 'Lyon (69000)', 19, 218),
('Stage Développeur Vision IA', 1200.00, '2026-04-22', 'Développement de systèmes de vision industrielle et reconnaissance d’images.', 12, 'Marseille (13000)', 20, 219),
('Stage Nanotechnologie', 1000.00, '2026-04-25', 'Recherche et développement en nanotechnologies.', 16, 'Toulouse (31000)', 21, 220),
('Stage Énergies Vertes', 950.00, '2026-04-28', 'Implémentation et suivi de projets d’énergies renouvelables.', 18, 'Bordeaux (33000)', 22, 221),
('Stage Analyste Financier Junior', 1100.00, '2026-05-01', 'Analyse et reporting pour projets financiers.', 14, 'Lille (59000)', 23, 222),
('Stage Recherche Médicale', 1000.00, '2026-05-03', 'Participation à la R&D pour technologies médicales.', 12, 'Strasbourg (67000)', 24, 223),
('Stage Plateforme Éducation', 900.00, '2026-05-05', 'Développement de modules et contenu pédagogique numérique.', 16, 'Nice (06000)', 25, 211),
('Stage Designer Interface', 950.00, '2026-05-08', 'Création de maquettes et amélioration UX/UI pour applications.', 8, 'Paris (75000)', 26, 221),
('Stage FoodTech Innovation', 1000.00, '2026-05-10', 'Innovation pour l’industrie alimentaire et distribution.', 12, 'Lyon (69000)', 27, 21),
('Stage Sécurité Informatique Avancée', 1050.00, '2026-05-12', 'Protection des systèmes et analyse de vulnérabilités.', 10, 'Marseille (13000)', 28, 22),
('Stage Transport International', 850.00, '2026-05-15', 'Optimisation et suivi des chaînes logistiques globales.', 10, 'Toulouse (31000)', 29, 23),
('Stage IA Industrielle', 1200.00, '2026-05-18', 'Développement d’algorithmes de machine learning pour l’industrie.', 14, 'Bordeaux (33000)', 30, 230),
('Stage Énergies Solaires', 1000.00, '2026-05-20', 'Participation à des projets photovoltaïques.', 20, 'Lille (59000)', 31, 231),
('Stage Fintech Développement', 950.00, '2026-05-22', 'Création d’outils et applications financières.', 18, 'Strasbourg (67000)', 32, 232),
('Stage Logiciel Médical', 1100.00, '2026-05-25', 'Développement et tests de logiciels pour le secteur médical.', 12, 'Nice (06000)', 33, 233),
('Stage Apprentissage Numérique', 900.00, '2026-05-28', 'Support et développement de plateforme d’e-learning.', 16, 'Paris (75000)', 34, 234),
('Stage UX Design', 950.00, '2026-06-01', 'Amélioration et conception d’interfaces utilisateur.', 18, 'Lyon (69000)', 35, 235),
('Stage FoodTech Projets', 1000.00, '2026-06-03', 'Tests et innovation produits alimentaires.', 12, 'Marseille (13000)', 36, 236),
('Stage Cyberdéfense', 1050.00, '2026-06-05', 'Audit et sécurisation des systèmes d’entreprise.', 20, 'Toulouse (31000)', 37, 237),
('Stage Logistique Industrielle', 850.00, '2026-06-08', 'Optimisation des flux et suivi transport.', 14, 'Bordeaux (33000)', 38, 238),
('Stage Vision Industrielle IA', 1200.00, '2026-06-10', 'Développement de systèmes de vision pour l’industrie.', 12, 'Lille (59000)', 39, 239),
('Stage Énergies Renouvelables', 1000.00, '2026-06-12', 'Projets innovants en éolien et solaire.', 10, 'Strasbourg (67000)', 40, 210),
('Stage Gestion Financière', 950.00, '2026-06-15', 'Analyse et reporting pour entreprises.', 8, 'Nice (06000)', 41, 211),
('Stage Logiciel Médical Avancé', 1100.00, '2026-06-18', 'Développement de logiciels avancés pour santé.', 12, 'Paris (75000)', 42, 212),
('Stage Plateforme Éducation Digitale', 900.00, '2026-06-20', 'Création de modules et contenu interactif.', 16, 'Lyon (69000)', 43, 213),
('Stage Design Graphique', 950.00, '2026-06-22', 'Conception visuelle et branding pour applications.', 12, 'Marseille (13000)', 44, 214),
('Stage FoodTech R&D', 1000.00, '2026-06-25', 'Recherche et développement dans l’industrie alimentaire.', 16, 'Toulouse (31000)', 45, 215),
('Stage Sécurité Systèmes', 1050.00, '2026-06-28', 'Audit et protection des systèmes informatiques.', 14, 'Bordeaux (33000)', 46, 216),
('Stage Transport et Logistique', 850.00, '2026-06-30', 'Optimisation et suivi des chaînes logistiques.', 14, 'Lille (59000)', 47, 217),
('Stage Intelligence Artificielle', 1200.00, '2026-07-01', 'Développement d’IA pour applications industrielles.', 18, 'Strasbourg (67000)', 48, 218),
('Stage Énergies Vertes Industrielles', 1000.00, '2026-07-03', 'Mise en place de solutions durables pour l’industrie.', 20, 'Nice (06000)', 49, 219),
('Stage Analyse Financière', 950.00, '2026-07-05', 'Audit et reporting pour entreprises.', 16, 'Paris (75000)', 50, 220);



INSERT INTO Requerir (ID_Offre, ID_Competence) VALUES
-- Stage Développeur Web
(1, 1),(1, 6),(1, 11),(1, 33),(1, 37),
-- Stage Analyste Financier
(2, 59),(2, 60),(2, 61),(2, 62),
-- Stage Ingénieur Énergies Renouvelables
(3, 35),(3, 36),(3, 32),
-- Stage Développement Logiciel Médical
(4, 1),(4, 36),(4, 67),(4, 68),
-- Stage Assistant Pédagogique Numérique
(5, 60),(5, 61),(5, 62),
-- Stage Designer UX/UI
(6, 39),(6, 40),(6, 41),(6, 42),
-- Stage Innovation Agroalimentaire
(7, 32),(7, 61),(7, 62),
-- Stage Sécurité Informatique
(8, 20),(8, 21),(8, 22),(8, 23),
-- Stage Gestion Logistique
(9, 76),(9, 77),
-- Stage Développeur IA
(10, 1),(10, 36),(10, 37),(10, 38),(10, 39),
-- Stage Bio-informatique
(11, 36),(11, 67),(11, 68),
-- Stage Concepteur Panneaux Solaires
(12, 32),(12, 33),(12, 34),
-- Stage Analyste Fintech
(13, 59),(13, 60),(13, 61),(13, 62),
-- Stage Développement Médical
(14, 1),(14, 36),(14, 67),(14, 68),
-- Stage Support Éducation
(15, 60),(15, 61),(15, 62),
-- Stage Designer Graphique
(16, 39),(16, 40),(16, 41),(16, 42),
-- Stage FoodTech Développement
(17, 32),(17, 61),(17, 62),
-- Stage Sécurité Réseau
(18, 20),(18, 21),(18, 22),(18, 23),
-- Stage Gestion Transport
(19, 76),(19, 77),
-- Stage Développeur Vision IA
(20, 1),(20, 36),(20, 37),(20, 38),(20, 39),
-- Stage Nanotechnologie
(21, 69),(21, 70),(21, 36),
-- Stage Énergies Vertes
(22, 32),(22, 33),(22, 34),
-- Stage Analyste Financier Junior
(23, 59),(23, 60),(23, 61),(23, 62),
-- Stage Recherche Médicale
(24, 36),(24, 67),(24, 68),
-- Stage Plateforme Éducation
(25, 60),(25, 61),(25, 62),
-- Stage Designer Interface
(26, 39),(26, 40),(26, 41),(26, 42),
-- Stage FoodTech Innovation
(27, 32),(27, 61),(27, 62),
-- Stage Sécurité Informatique Avancée
(28, 20),(28, 21),(28, 22),(28, 23),
-- Stage Transport International
(29, 76),(29, 77),
-- Stage IA Industrielle
(30, 1),(30, 36),(30, 37),(30, 38),(30, 39),
-- Stage Énergies Solaires
(31, 32),(31, 33),(31, 34),
-- Stage Fintech Développement
(32, 59),(32, 60),(32, 61),(32, 62),
-- Stage Logiciel Médical
(33, 1),(33, 36),(33, 67),(33, 68),
-- Stage Apprentissage Numérique
(34, 60),(34, 61),(34, 62),
-- Stage UX Design
(35, 39),(35, 40),(35, 41),(35, 42),
-- Stage FoodTech Projets
(36, 32),(36, 61),(36, 62),
-- Stage Cyberdéfense
(37, 20),(37, 21),(37, 22),(37, 23),
-- Stage Logistique Industrielle
(38, 76),(38, 77),
-- Stage Vision Industrielle IA
(39, 1),(39, 36),(39, 37),(39, 38),(39, 39),
-- Stage Énergies Renouvelables
(40, 32),(40, 33),(40, 34),
-- Stage Gestion Financière
(41, 59),(41, 60),(41, 61),(41, 62),
-- Stage Logiciel Médical Avancé
(42, 1),(42, 36),(42, 67),(42, 68),
-- Stage Plateforme Éducation Digitale
(43, 60),(43, 61),(43, 62),
-- Stage Design Graphique
(44, 39),(44, 40),(44, 41),(44, 42),
-- Stage FoodTech R&D
(45, 32),(45, 61),(45, 62),
-- Stage Sécurité Systèmes
(46, 20),(46, 21),(46, 22),(46, 23),
-- Stage Transport et Logistique
(47, 76),(47, 77),
-- Stage Intelligence Artificielle
(48, 1),(48, 36),(48, 37),(48, 38),(48, 39),
-- Stage Énergies Vertes Industrielles
(49, 32),(49, 33),(49, 34),
-- Stage Analyse Financière
(50, 59),(50, 60),(50, 61),(50, 62);





INSERT INTO Evaluations (Note, ID_Utilisateur, ID_Entreprise) VALUES
-- Entreprise 1
(4.2, 1, 1),(3.8, 2, 1),(5.0, 3, 1),(4.5, 4, 1),(3.9, 5, 1),
-- Entreprise 2
(4.0, 6, 2),(4.1, 7, 2),(3.7, 8, 2),(4.8, 9, 2),(3.5, 10, 2),
-- Entreprise 3
(3.9, 11, 3),(4.3, 12, 3),(4.5, 13, 3),(3.8, 14, 3),(4.1, 15, 3),
-- Entreprise 4
(4.7, 16, 4),(4.0, 17, 4),(3.9, 18, 4),(4.2, 19, 4),(4.5, 30, 4),
-- Entreprise 5
(3.8, 31, 5),(4.1, 32, 5),(4.0, 33, 5),(4.3, 34, 5),(3.7, 35, 5),
-- Entreprise 6
(4.4, 36, 6),(3.9, 37, 6),(4.2, 38, 6),(4.0, 39, 6),(4.5, 40, 6),
-- Entreprise 7
(3.7, 41, 7),(4.1, 42, 7),(4.3, 43, 7),(3.8, 44, 7),(4.0, 45, 7),
-- Entreprise 8
(4.5, 46, 8),(4.0, 47, 8),(3.9, 48, 8),(4.2, 49, 8),(4.1, 50, 8),
-- Entreprise 9
(3.8, 1, 9),(4.0, 2, 9),(4.3, 3, 9),(3.9, 4, 9),(4.2, 5, 9),
-- Entreprise 10
(4.1, 6, 10),(3.7, 7, 10),(4.4, 8, 10),(4.0, 9, 10),(3.8, 10, 10),
-- Entreprise 11
(4.2, 11, 11),(3.9, 12, 11),(4.0, 13, 11),(4.3, 14, 11),(3.7, 15, 11),
-- Entreprise 12
(4.5, 16, 12),(4.0, 17, 12),(3.8, 18, 12),(4.2, 19, 12),(4.1, 30, 12),
-- Entreprise 13
(3.9, 31, 13),(4.3, 32, 13),(4.0, 33, 13),(4.2, 34, 13),(3.8, 35, 13),
-- Entreprise 14
(4.0, 36, 14),(4.1, 37, 14),(3.7, 38, 14),(4.3, 39, 14),(4.5, 40, 14),
-- Entreprise 15
(3.8, 41, 15),(4.2, 42, 15),(4.0, 43, 15),(3.9, 44, 15),(4.1, 45, 15),
-- Entreprise 16
(4.3, 46, 16),(4.0, 47, 16),(3.8, 48, 16),(4.1, 49, 16),(4.2, 50, 16),
-- Entreprise 17
(3.7, 1, 17),(4.0, 2, 17),(4.3, 3, 17),(3.9, 4, 17),(4.2, 5, 17),
-- Entreprise 18
(4.0, 6, 18),(3.8, 7, 18),(4.1, 8, 18),(4.2, 9, 18),(3.9, 10, 18),
-- Entreprise 19
(4.3, 11, 19),(4.0, 12, 19),(3.8, 13, 19),(4.1, 14, 19),(4.2, 15, 19),
-- Entreprise 20
(3.9, 16, 20),(4.0, 17, 20),(4.1, 18, 20),(3.7, 19, 20),(4.2, 30, 20),
-- Entreprise 21
(4.0, 31, 21),(3.8, 32, 21),(4.1, 33, 21),(4.2, 34, 21),(3.9, 35, 21),
-- Entreprise 22
(4.3, 36, 22),(4.0, 37, 22),(3.7, 38, 22),(4.1, 39, 22),(4.2, 40, 22),
-- Entreprise 23
(3.8, 41, 23),(4.2, 42, 23),(4.0, 43, 23),(3.9, 44, 23),(4.1, 45, 23),
-- Entreprise 24
(4.3, 46, 24),(4.0, 47, 24),(3.8, 48, 24),(4.1, 49, 24),(4.2, 50, 24),
-- Entreprise 25
(3.9, 1, 25),(4.0, 2, 25),(4.1, 3, 25),(3.7, 4, 25),(4.2, 5, 25),
-- Entreprise 26
(4.0, 6, 26),(3.8, 7, 26),(4.1, 8, 26),(4.2, 9, 26),(3.9, 10, 26),
-- Entreprise 27
(4.3, 11, 27),(4.0, 12, 27),(3.8, 13, 27),(4.1, 14, 27),(4.2, 15, 27),
-- Entreprise 28
(3.9, 16, 28),(4.0, 17, 28),(4.1, 18, 28),(3.7, 19, 28),(4.2, 30, 28),
-- Entreprise 29
(4.0, 31, 29),(3.8, 32, 29),(4.1, 33, 29),(4.2, 34, 29),(3.9, 35, 29),
-- Entreprise 30
(4.3, 36, 30),(4.0, 37, 30),(3.7, 38, 30),(4.1, 39, 30),(4.2, 40, 30),
-- Entreprise 31
(3.8, 41, 31),(4.2, 42, 31),(4.0, 43, 31),(3.9, 44, 31),(4.1, 45, 31),
-- Entreprise 32
(4.3, 46, 32),(4.0, 47, 32),(3.8, 48, 32),(4.1, 49, 32),(4.2, 50, 32),
-- Entreprise 33
(3.9, 1, 33),(4.0, 2, 33),(4.1, 3, 33),(3.7, 4, 33),(4.2, 5, 33),
-- Entreprise 34
(4.0, 6, 34),(3.8, 7, 34),(4.1, 8, 34),(4.2, 9, 34),(3.9, 10, 34),
-- Entreprise 35
(4.3, 11, 35),(4.0, 12, 35),(3.8, 13, 35),(4.1, 14, 35),(4.2, 15, 35),
-- Entreprise 36
(3.9, 16, 36),(4.0, 17, 36),(4.1, 18, 36),(3.7, 19, 36),(4.2, 30, 36),
-- Entreprise 37
(4.0, 31, 37),(3.8, 32, 37),(4.1, 33, 37),(4.2, 34, 37),(3.9, 35, 37),
-- Entreprise 38
(4.3, 36, 38),(4.0, 37, 38),(3.7, 38, 38),(4.1, 39, 38),(4.2, 40, 38),
-- Entreprise 39
(3.8, 41, 39),(4.2, 42, 39),(4.0, 43, 39),(3.9, 44, 39),(4.1, 45, 39),
-- Entreprise 40
(4.3, 46, 40),(4.0, 47, 40),(3.8, 48, 40),(4.1, 49, 40),(4.2, 50, 40),
-- Entreprise 41
(3.9, 1, 41),(4.0, 2, 41),(4.1, 3, 41),(3.7, 4, 41),(4.2, 5, 41),
-- Entreprise 42
(4.0, 6, 42),(3.8, 7, 42),(4.1, 8, 42),(4.2, 9, 42),(3.9, 10, 42),
-- Entreprise 43
(4.3, 11, 43),(4.0, 12, 43),(3.8, 13, 43),(4.1, 14, 43),(4.2, 15, 43),
-- Entreprise 44
(3.9, 16, 44),(4.0, 17, 44),(4.1, 18, 44),(3.7, 19, 44),(4.2, 30, 44),
-- Entreprise 45
(4.0, 31, 45),(3.8, 32, 45),(4.1, 33, 45),(4.2, 34, 45),(3.9, 35, 45),
-- Entreprise 46
(4.3, 36, 46),(4.0, 37, 46),(3.7, 38, 46),(4.1, 39, 46),(4.2, 40, 46),
-- Entreprise 47
(3.8, 41, 47),(4.2, 42, 47),(4.0, 43, 47),(3.9, 44, 47),(4.1, 45, 47),
-- Entreprise 48
(4.3, 46, 48),(4.0, 47, 48),(3.8, 48, 48),(4.1, 49, 48),(4.2, 50, 48),
-- Entreprise 49
(3.9, 1, 49),(4.0, 2, 49),(4.1, 3, 49),(3.7, 4, 49),(4.2, 5, 49),
-- Entreprise 50
(4.0, 6, 50),(3.8, 7, 50),(4.1, 8, 50),(4.2, 9, 50),(3.9, 10, 50);
