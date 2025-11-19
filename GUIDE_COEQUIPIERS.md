# 👥 Guide pour les Coéquipiers - Covoit2025

## 📋 Configuration Locale pour Développement

Ce guide explique comment configurer le projet **Covoit2025** sur votre ordinateur local pour développer.

---

## 🎯 Prérequis à Installer

Avant de commencer, installez :
- ✅ **Git** : https://git-scm.com/downloads
- ✅ **PHP 8.2+** : https://www.php.net/downloads
- ✅ **Composer** : https://getcomposer.org/download/
- ✅ **Node.js** : https://nodejs.org/ (version LTS)
- ✅ **MySQL** : https://dev.mysql.com/downloads/mysql/ (ou XAMPP/WAMP)

---

## 📦 Étape 1 : Cloner le Projet

```bash
# 1. Cloner le dépôt GitHub
git clone https://github.com/codebyjay621/Covoit2025.git

# 2. Entrer dans le dossier
cd Covoit2025

# 3. Créer une nouvelle branche pour vos modifications
git checkout -b votre-nom/feature-nom
```

**Exemple** : `git checkout -b alice/fix-recherche`

---

## 🗄️ Étape 2 : Créer la Base de Données Locale

### A. Ouvrir MySQL (via terminal ou MySQL Workbench)

**Via Terminal** :
```bash
mysql -u root -p
```

**Via MySQL Workbench** :
- Ouvrir MySQL Workbench
- Se connecter à votre serveur local

### B. Créer la Base de Données

```sql
CREATE DATABASE Covoiturage;
USE Covoiturage;
```

### C. Importer les Tables

**Option 1 : Via MySQL Workbench** (RECOMMANDÉ)
1. Server → Data Import
2. Import from Self-Contained File
3. Sélectionner `Covoiturage.sql`
4. Default Schema : `Covoiturage`
5. Start Import

**Option 2 : Via Terminal**
```bash
mysql -u root -p Covoiturage < Covoiturage.sql
```

---

## ⚙️ Étape 3 : Configurer le Fichier .env

### A. Copier le Fichier Exemple

```bash
# Créer votre .env local
cp envs.txt .env
```

### B. Modifier les Valeurs pour Votre Environnement Local

Ouvrez `.env` et modifiez ces lignes :

```env
APP_NAME=Covoit2025
APP_ENV=local
APP_KEY=base64:HuQFuZdDtHbPWYTwBF+9zFInx0wGoZ7LBBSyOdd25S8=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de données LOCALE (PAS Railway !)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Covoiturage
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe_mysql

# Garder les autres paramètres...
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Email - Garder les mêmes valeurs
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=covoit.2025@gmail.com
MAIL_PASSWORD=ytpvmddfirbxpdva
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=covoit.2025@gmail.com
MAIL_FROM_NAME="Covoiturage 2025"
```

**⚠️ IMPORTANT** : Changez `DB_PASSWORD` avec VOTRE mot de passe MySQL local !

---

## 📦 Étape 4 : Installer les Dépendances

```bash
# 1. Installer les dépendances PHP
composer install

# 2. Installer les dépendances Node
npm install
```

---

## 🏗️ Étape 5 : Compiler les Assets

```bash
# Compiler les assets CSS/JS avec Vite
npm run build

# OU pour le développement avec hot reload
npm run dev
```

**Note** : Si vous utilisez `npm run dev`, laissez ce terminal ouvert !

---

## 🚀 Étape 6 : Démarrer le Serveur Local

**Ouvrez un NOUVEAU terminal** et lancez :

```bash
php artisan serve
```

Votre site sera accessible sur : **http://localhost:8000**

---

## 👨‍💻 Workflow de Développement

### Modifier du Code

1. **Faites vos modifications** dans le code
2. **Testez localement** : `http://localhost:8000`
3. **Commit vos changements** :
   ```bash
   git add .
   git commit -m "Description de vos modifications"
   ```
4. **Pushez sur votre branche** :
   ```bash
   git push origin votre-nom/feature-nom
   ```

### Envoyer vos Modifications à Jay

1. Sur GitHub, créez une **Pull Request** (PR)
2. **Jay** reviendra le code
3. **Jay** mergera dans `main`
4. **Jay** déploiera sur Railway

---

## 🔄 Récupérer les Dernières Modifications

Avant de commencer à travailler chaque jour :

```bash
# 1. Aller sur la branche main
git checkout main

# 2. Récupérer les derniers changements
git pull origin main

# 3. Retourner sur votre branche
git checkout votre-nom/feature-nom

# 4. Mettre à jour votre branche avec main
git merge main
```

---

## 🗄️ Mettre à Jour la Base de Données Locale

Si Jay ajoute de nouvelles tables ou colonnes :

### Option 1 : Réimporter le SQL
```sql
-- Dans MySQL
DROP DATABASE Covoiturage;
CREATE DATABASE Covoiturage;
USE Covoiturage;

-- Puis réimporter Covoiturage.sql
```

### Option 2 : Exécuter les Migrations
```bash
php artisan migrate:fresh
```

---

## 🐛 Résolution de Problèmes

### Erreur : "Access denied for user 'root'"
→ Vérifiez votre mot de passe MySQL dans `.env`

### Erreur : "Database 'Covoiturage' doesn't exist"
→ Créez la base de données : `CREATE DATABASE Covoiturage;`

### Erreur : "Class not found"
→ Exécutez : `composer dump-autoload`

### Assets CSS/JS ne chargent pas
→ Exécutez : `npm run build`

### Erreur 500 sur localhost
→ Vérifiez les logs : `storage/logs/laravel.log`

---

## 📧 Configuration Email (Optionnel)

Pour tester l'envoi d'emails localement :

```bash
# 1. Démarrer le queue worker
php artisan queue:work

# 2. Dans un autre terminal, démarrer le serveur
php artisan serve
```

Les emails utiliseront le compte Gmail du projet (déjà configuré).

---

## 🚫 Ce Que Vous NE Devez PAS Faire

❌ **NE PAS** modifier le fichier `.env` de Railway  
❌ **NE PAS** pusher sur Railway directement  
❌ **NE PAS** modifier la base de données Railway  
❌ **NE PAS** pusher directement sur `main` (utilisez des branches)  
❌ **NE PAS** commiter le fichier `.env` (il est dans .gitignore)  

---

## ✅ Checklist Quotidienne

Chaque fois que vous travaillez :

- [ ] `git pull origin main` pour récupérer les derniers changements
- [ ] `composer install` (si composer.lock a changé)
- [ ] `npm install` (si package-lock.json a changé)
- [ ] `npm run build` ou `npm run dev` pour les assets
- [ ] `php artisan serve` pour démarrer le serveur
- [ ] Tester vos modifications sur `http://localhost:8000`
- [ ] Commit et push sur VOTRE branche
- [ ] Créer une Pull Request pour Jay

---

## 🤝 Collaboration

### Qui Fait Quoi ?

**Vous (Coéquipiers)** :
- ✅ Développement local
- ✅ Tests locaux
- ✅ Commit sur vos branches
- ✅ Pull Requests

**Jay** :
- ✅ Review du code
- ✅ Merge dans `main`
- ✅ Push sur Railway
- ✅ Gestion de la production

---

## 📞 Besoin d'Aide ?

Si vous avez des problèmes :
1. Vérifiez les logs : `storage/logs/laravel.log`
2. Demandez à Jay
3. Vérifiez ce guide

---

**Bon développement ! 🚀**

---

**Créé par Jay pour l'équipe Covoit2025**  
**Novembre 2025**

