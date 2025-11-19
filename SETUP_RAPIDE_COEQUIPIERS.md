# ⚡ Setup Rapide pour Coéquipiers

## 🎯 Installation en 5 Minutes

### 1️⃣ Cloner le Projet
```bash
git clone https://github.com/codebyjay621/Covoit2025.git
cd Covoit2025
```

### 2️⃣ Créer la Base de Données
```sql
mysql -u root -p
CREATE DATABASE Covoiturage;
USE Covoiturage;
exit;

# Importer les tables
mysql -u root -p Covoiturage < Covoiturage.sql
```

### 3️⃣ Configurer .env
```bash
cp envs.txt .env
```

**Modifiez dans .env** :
```env
DB_HOST=127.0.0.1
DB_DATABASE=Covoiturage
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 4️⃣ Installer et Builder
```bash
composer install
npm install
npm run build
```

### 5️⃣ Démarrer
```bash
php artisan serve
```

✅ **Site disponible sur** : http://localhost:8000

---

## 🔄 Workflow Quotidien

### Avant de Coder
```bash
git checkout main
git pull origin main
git checkout -b votre-nom/votre-feature
```

### Après avoir Codé
```bash
git add .
git commit -m "Description des changements"
git push origin votre-nom/votre-feature
```

Puis créez une **Pull Request** sur GitHub pour Jay.

---

## 🚫 Règles Importantes

- ❌ Ne JAMAIS pusher directement sur `main`
- ❌ Ne JAMAIS commiter le fichier `.env`
- ❌ Ne JAMAIS modifier la BDD Railway
- ✅ Toujours créer une branche pour vos modifications
- ✅ Toujours tester localement avant de pusher

---

## 💡 Commandes Utiles

```bash
# Mettre à jour depuis main
git pull origin main

# Voir les changements
git status

# Annuler des changements
git restore nom-du-fichier

# Nettoyer le cache Laravel
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

**Questions ? Demandez à Jay !**

