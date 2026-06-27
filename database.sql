CREATE DATABASE IF NOT EXISTS qcm_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE qcm_platform;

DROP TABLE IF EXISTS reponses_utilisateur;
DROP TABLE IF EXISTS statistiques;
DROP TABLE IF EXISTS tentatives;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS utilisateurs;

CREATE TABLE utilisateurs (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    statut ENUM('actif','bloque') NOT NULL DEFAULT 'actif',
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE questions (
    id_question INT AUTO_INCREMENT PRIMARY KEY,
    texte_question TEXT NOT NULL,
    reponse1 VARCHAR(255) NOT NULL,
    reponse2 VARCHAR(255) NOT NULL,
    reponse3 VARCHAR(255) NOT NULL,
    reponse4 VARCHAR(255) NOT NULL,
    bonne_reponse INT NOT NULL,
    categorie VARCHAR(100),
    CHECK (bonne_reponse BETWEEN 1 AND 4)
) ENGINE=InnoDB;

CREATE TABLE tentatives (
    id_tentative INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    date_tentative DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    score FLOAT NOT NULL DEFAULT 0,
    temps_utilise INT NOT NULL DEFAULT 0,
    statut ENUM('en_cours','validee','annulee') NOT NULL DEFAULT 'en_cours',
    CONSTRAINT fk_tentative_utilisateur
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateurs(id_utilisateur)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE reponses_utilisateur (
    id_reponse INT AUTO_INCREMENT PRIMARY KEY,
    id_tentative INT NOT NULL,
    id_question INT NOT NULL,
    reponse_choisie INT NOT NULL,
    CONSTRAINT fk_reponse_tentative
        FOREIGN KEY (id_tentative)
        REFERENCES tentatives(id_tentative)
        ON DELETE CASCADE,
    CONSTRAINT fk_reponse_question
        FOREIGN KEY (id_question)
        REFERENCES questions(id_question)
        ON DELETE CASCADE,
    CHECK (reponse_choisie BETWEEN 1 AND 4)
) ENGINE=InnoDB;

CREATE TABLE statistiques (
    id_statistique INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL UNIQUE,
    date_statistique DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total_tentatives INT NOT NULL DEFAULT 0,
    score_moyen FLOAT NOT NULL DEFAULT 0,
    temps_total_utilise INT NOT NULL DEFAULT 0,
    temps_moyen INT NOT NULL DEFAULT 0,
    reponses_correctes_moyenne FLOAT NOT NULL DEFAULT 0,
    CONSTRAINT fk_statistique_utilisateur
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateurs(id_utilisateur)
        ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, statut)
VALUES ('Admin', 'QCM', 'admin@qcm.local', '$2y$12$oF.2tewy3Ntqb8BYJor1MuC6NOeBNdGz6mJF8Xw0.xrIdvQagcDY6', 'admin', 'actif');

INSERT INTO questions (texte_question, reponse1, reponse2, reponse3, reponse4, bonne_reponse, categorie) VALUES
('Que signifie HTML ?','HyperText Markup Language','HighText Machine Language','Hyperlink Text Manager Language','Home Tool Markup Language',1,'HTML'),
('Quelle balise permet de créer un lien ?','<p>','<a>','<div>','<img>',2,'HTML'),
('Quelle balise contient le contenu visible d''une page HTML ?','<head>','<title>','<body>','<meta>',3,'HTML'),
('Quelle balise permet d''insérer une image ?','<image>','<img>','<src>','<picture-only>',2,'HTML'),
('Quelle balise permet de créer un titre principal ?','<h1>','<p>','<span>','<section>',1,'HTML'),
('Quel attribut indique l''adresse d''un lien ?','src','alt','href','class',3,'HTML'),
('Quelle balise permet une liste non ordonnée ?','<ol>','<ul>','<li>','<table>',2,'HTML'),
('Quelle balise représente une ligne dans un tableau ?','<td>','<tr>','<th>','<table>',2,'HTML'),
('Quel attribut donne un texte alternatif à une image ?','title','alt','href','name',2,'HTML'),
('Quelle balise contient les métadonnées d''une page ?','<body>','<head>','<footer>','<main>',2,'HTML'),
('Quelle balise sert à créer un formulaire ?','<input>','<form>','<button>','<label>',2,'HTML'),
('Quelle balise permet un champ de saisie ?','<field>','<input>','<write>','<text>',2,'HTML'),
('Quelle balise permet un bouton cliquable ?','<btn>','<button>','<click>','<submit>',2,'HTML'),
('Quelle balise définit une cellule de tableau ?','<tr>','<td>','<table>','<thead>',2,'HTML'),
('Quel type d''input sert à saisir un mot de passe ?','text','secret','password','hidden',3,'HTML'),
('Quel attribut rend un champ obligatoire ?','required','needed','must','obligatory',1,'HTML'),
('Quelle balise sert à une zone de texte longue ?','<textarea>','<input long>','<textzone>','<area>',1,'HTML'),
('Quelle balise permet de regrouper une partie principale ?','<main>','<big>','<page>','<content>',1,'HTML'),
('Quelle balise représente le bas d''une page ?','<bottom>','<footer>','<end>','<section>',2,'HTML'),
('Quelle balise représente une zone de navigation ?','<navigation>','<nav>','<menu-only>','<linkbar>',2,'HTML'),
('Que signifie CSS ?','Computer Style Sheet','Cascading Style Sheets','Creative Style Syntax','Color Sheet System',2,'CSS'),
('Quelle propriété CSS change la couleur du texte ?','background-color','font-size','color','text-style',3,'CSS'),
('Quelle propriété CSS change la couleur de fond ?','color','background-color','font-color','bg-text',2,'CSS'),
('Quelle propriété CSS modifie la taille du texte ?','font-size','text-weight','size','letter-size',1,'CSS'),
('Quelle propriété CSS met un texte en gras ?','font-weight','font-bold','text-size','bold',1,'CSS'),
('Quelle propriété CSS ajoute une marge extérieure ?','padding','margin','border','gap',2,'CSS'),
('Quelle propriété CSS ajoute un espace intérieur ?','margin','padding','outline','display',2,'CSS'),
('Quelle propriété CSS définit une bordure ?','border','outline-text','box','line',1,'CSS'),
('Quelle valeur de display permet Flexbox ?','block','inline','flex','gridbox',3,'CSS'),
('Quelle propriété aligne les éléments horizontalement en flex ?','align-items','justify-content','text-align','place-text',2,'CSS'),
('Quelle propriété aligne les éléments verticalement en flex ?','align-items','justify-content','float','vertical',1,'CSS'),
('Quelle propriété arrondit les coins ?','border-radius','corner','radius-border','round',1,'CSS'),
('Quelle propriété cache un élément ?','display: none','visible: yes','opacity: 2','hide: true',1,'CSS'),
('Quelle propriété rend un site adaptable aux écrans ?','media queries','font-family','border-style','text-shadow',1,'CSS'),
('Quelle unité est relative à la taille de police de l''élément ?','px','em','cm','pt',2,'CSS'),
('Quelle propriété change la police ?','font-family','text-font','police','font-name',1,'CSS'),
('Quelle propriété ajoute une ombre à une boîte ?','box-shadow','text-shadow','shadow-box','border-shadow',1,'CSS'),
('Quelle propriété centre le texte ?','align-items','justify-content','text-align','center-text',3,'CSS'),
('Quelle valeur de position fixe l''élément par rapport à la fenêtre ?','absolute','relative','fixed','static',3,'CSS'),
('Quelle propriété contrôle l''ordre d''empilement ?','z-index','order-index','position-order','layer',1,'CSS'),
('Que signifie PHP ?','Personal Home Page / PHP Hypertext Preprocessor','Page Hyper Processor','Private Hosting Page','Program HTML Parser',1,'PHP'),
('Quel symbole commence une variable en PHP ?','#','$','@','%',2,'PHP'),
('Quelle fonction affiche du texte en PHP ?','echo','printText','show','display',1,'PHP'),
('Quelle fonction permet de démarrer une session ?','start_session()','session_start()','open_session()','session_open()',2,'PHP'),
('Quelle superglobale contient les données envoyées en POST ?','$_GET','$_POST','$_SESSION','$_SERVER',2,'PHP'),
('Quelle fonction redirige vers une autre page ?','redirect()','go()','header()','location()',3,'PHP'),
('Quelle fonction hash un mot de passe ?','password_hash()','md5_secure()','hash_password()','crypt_text()',1,'PHP'),
('Quelle fonction vérifie un mot de passe hashé ?','password_verify()','verify_hash()','check_password()','hash_check()',1,'PHP'),
('Quelle fonction inclut un fichier une seule fois ?','include_once','require_file','load_once','import_once',1,'PHP'),
('Quelle extension permet d''utiliser MySQL avec mysqli ?','mysqli','mysqlold','pdo-only','dbphp',1,'PHP'),
('Quelle fonction mysqli ouvre la connexion ?','mysqli_open','mysqli_connect','mysql_connect_new','db_connect',2,'PHP'),
('Quelle fonction mysqli exécute une requête ?','mysqli_query','mysqli_execute_all','query_mysql','mysql_run',1,'PHP'),
('Quelle fonction protège une chaîne contre l''injection SQL ?','mysqli_real_escape_string','mysqli_clean_sql','escape_html','secure_password',1,'PHP'),
('Quelle fonction récupère une ligne sous forme de tableau associatif ?','mysqli_fetch_assoc','mysqli_fetch_array_only','mysqli_row_assoc','fetch_sql',1,'PHP'),
('Quelle fonction compte les lignes d''un résultat ?','mysqli_num_rows','mysqli_count_rows','count_result','rows_number',1,'PHP'),
('Quelle superglobale stocke les données de session ?','$_COOKIE','$_SESSION','$_POST','$_FILES',2,'PHP'),
('Quel mot-clé permet une condition ?','if','for','while','echo',1,'PHP'),
('Quelle boucle répète tant qu''une condition est vraie ?','for','while','switch','case',2,'PHP'),
('Quelle fonction détruit une session ?','session_destroy()','session_delete()','destroy_login()','logout_session()',1,'PHP'),
('Quelle fonction transforme les caractères HTML pour l''affichage ?','htmlspecialchars()','htmlsecure()','textsafe()','html_clean()',1,'PHP'),
('Que signifie SQL ?','Structured Query Language','Simple Question Language','Server Query Link','Style Query Language',1,'MySQL'),
('Quelle commande SQL permet de lire des données ?','SELECT','INSERT','UPDATE','DELETE',1,'MySQL'),
('Quelle commande SQL ajoute une ligne ?','SELECT','INSERT','CREATE','ALTER',2,'MySQL'),
('Quelle commande SQL modifie des données ?','UPDATE','CHANGE','MODIFY ROW','SETTABLE',1,'MySQL'),
('Quelle commande SQL supprime des lignes ?','REMOVE','DELETE','DROP DATABASE','CLEAR',2,'MySQL'),
('Quelle clause filtre les résultats ?','WHERE','ORDER','LIMIT','GROUP',1,'MySQL'),
('Quelle clause trie les résultats ?','ORDER BY','SORT WITH','GROUP BY','WHERE',1,'MySQL'),
('Quelle clause limite le nombre de résultats ?','LIMIT','MAXROWS','STOP','COUNT',1,'MySQL'),
('Quelle fonction SQL compte les lignes ?','COUNT()','SUM()','AVG()','TOTAL()',1,'MySQL'),
('Quelle fonction SQL calcule une moyenne ?','AVG()','MEAN()','SUM()','COUNT()',1,'MySQL'),
('Quelle contrainte empêche les doublons ?','UNIQUE','PRIMARY','DUPLICATE','FOREIGN',1,'MySQL'),
('Quelle clé identifie une ligne de façon unique ?','clé primaire','clé étrangère','clé simple','clé texte',1,'MySQL'),
('Quelle clé relie deux tables ?','clé étrangère','clé primaire','clé locale','clé unique',1,'MySQL'),
('Quel moteur MySQL gère les clés étrangères ?','InnoDB','MyISAM seulement','CSV','Memory only',1,'MySQL'),
('Quelle commande crée une table ?','CREATE TABLE','MAKE TABLE','NEW TABLE','ADD TABLE',1,'MySQL'),
('Quelle commande supprime une table ?','DROP TABLE','DELETE TABLE','REMOVE ROWS','CLEAR TABLE',1,'MySQL'),
('Quelle clause regroupe les résultats ?','GROUP BY','ORDER BY','WHERE','LIMIT',1,'MySQL'),
('Quelle commande ajoute une colonne ?','ALTER TABLE','UPDATE TABLE','INSERT COLUMN','ADD ROW',1,'MySQL'),
('Quel type stocke une date et une heure ?','DATETIME','VARCHAR','BOOLEAN','FLOAT',1,'MySQL'),
('Quel type stocke un long texte ?','TEXT','INT','DATE','BOOL',1,'MySQL'),
('Quel protocole est utilisé pour charger les pages web ?','HTTP','FTP','SMTP','SSH',1,'Web'),
('Que signifie URL ?','Uniform Resource Locator','Universal Route Link','User Resource Login','Unique Reference Line',1,'Web'),
('Quel code HTTP signifie succès ?','200','404','500','301',1,'Web'),
('Quel code HTTP signifie page introuvable ?','200','404','201','302',2,'Web'),
('Quel code HTTP signifie erreur serveur ?','500','100','204','301',1,'Web'),
('Quel protocole chiffre les échanges web ?','HTTPS','HTTP simple','FTP','SMTP',1,'Web'),
('Quel élément stocke des informations côté serveur en PHP ?','session','localStorage','CSS','HTML',1,'Web'),
('Quel stockage est côté navigateur ?','localStorage','session PHP','base MySQL','serveur Apache',1,'Web'),
('Pourquoi hasher un mot de passe ?','Pour ne pas le stocker en clair','Pour l''afficher','Pour le rendre plus court uniquement','Pour supprimer l''email',1,'Sécurité'),
('Quelle attaque vise les formulaires SQL mal protégés ?','Injection SQL','DDoS uniquement','Spam CSS','Erreur 404',1,'Sécurité'),
('Quelle protection évite l''injection SQL avec mysqli ?','Échapper les données ou utiliser des requêtes préparées','Mettre du CSS','Changer le logo','Ajouter une image',1,'Sécurité'),
('Pourquoi utiliser une session après connexion ?','Pour garder l''utilisateur connecté','Pour créer une table','Pour changer le style','Pour supprimer les questions',1,'Sécurité'),
('Quelle action anti-triche peut être détectée en JavaScript ?','Changement d''onglet','Hash du mot de passe','Création SQL','Connexion serveur',1,'Sécurité'),
('Quel mode limite la sortie de l''utilisateur pendant le QCM ?','Plein écran','Mode lecture','Mode tableau','Mode sombre',1,'Sécurité'),
('Que signifie responsive ?','Adapté aux différentes tailles d''écran','Protégé contre SQL','Connecté à MySQL','Rapide uniquement',1,'Web'),
('Quel langage rend une page interactive côté navigateur ?','JavaScript','SQL','PHP serveur uniquement','MariaDB',1,'Web'),
('Quel fichier contient souvent les styles ?','style.css','index.sql','login.php only','database.exe',1,'Web'),
('Quel fichier est souvent la page d''accueil en PHP ?','index.php','home.css','start.sql','readme.txt',1,'Web'),
('Quel outil permet de gérer MySQL dans le navigateur ?','phpMyAdmin','Photoshop','Word','FileZilla uniquement',1,'MySQL'),
('Quel serveur local est souvent utilisé avec PHP/MySQL ?','XAMPP','Excel','PowerPoint','Notepad only',1,'Web');
