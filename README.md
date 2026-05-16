# SymHome — Système E-commerce de Vente de Mobilier et Décoration

Application web e-commerce développée avec **Symfony 7**, permettant la vente en ligne de meubles organisés par catégories, avec un espace client complet et un espace administrateur avec tableau de bord.

---

## Table des matières

- [Présentation](#présentation)
- [Technologies utilisées](#technologies-utilisées)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Base de données et migrations](#base-de-données-et-migrations)
- [Modélisation des données](#modélisation-des-données)
- [Fonctionnalités](#fonctionnalités)
- [Structure du projet](#structure-du-projet)
- [Comptes de test](#comptes-de-test)
- [Auteurs](#auteurs)

---

## Présentation

**SymHome** est une boutique en ligne de mobilier et décoration intérieure, proposant 4 grandes catégories de produits :

| Catégorie | Produits |
|---|---|
| Séjour | Canapés, tables basses, meubles TV |
| Chambre | Lits, armoires, tables de chevet |
| Bureau | Bureaux, chaises ergonomiques, étagères |
| Cuisine | Tables de repas, chaises, éléments de cuisine |

---

## Technologies utilisées

| Couche | Technologie |
|---|---|
| Framework backend | PHP 8.2 + Symfony 7 |
| ORM | Doctrine ORM (entités, migrations, fixtures) |
| Moteur de templates | Twig + Bootstrap 5 |
| Base de données | MySQL 8.4 / MariaDB 10.4 |
| Authentification | Symfony Security + AppAuthenticator |
| Formulaires | Symfony Form + Bootstrap 5 layout |
| Données de test | DoctrineFixturesBundle |
| Graphiques dashboard | Chart.js |
| Polices | Cormorant Garamond + Jost (Google Fonts) |
| Panier | Sessions PHP (sans base de données) |

---

## Prérequis

- PHP >= 8.2
- Composer
- Symfony CLI
- MySQL 8.4 ou MariaDB 10.4
- XAMPP ou serveur équivalent

---

## Installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/saidirayen/SymHome.git
cd SymHome

# 2. Installer les dépendances PHP
composer install
```

---

## Configuration

Créer un fichier `.env.local` à la racine du projet (ne jamais le committer) :

```env
# Pour MySQL 8.4
DATABASE_URL="mysql://root:@127.0.0.1:3306/symhome?serverVersion=8.4.3&charset=utf8mb4"

# Pour MariaDB 10.4
# DATABASE_URL="mysql://root:@127.0.0.1:3306/symhome?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
```

---

## Base de données et migrations

```bash
# Créer la base de données
php bin/console doctrine:database:create

# Créer le schéma directement
php bin/console doctrine:schema:create

# Marquer toutes les migrations comme exécutées
php bin/console doctrine:migrations:sync-metadata-storage
php bin/console doctrine:migrations:version --add --all

# Charger les données de test
php bin/console doctrine:fixtures:load
```

### Historique des migrations

| Version | Date | Description |
|---|---|---|
| `Version20260506203836` | 06/05/2026 | Création initiale des tables : `categorie`, `commande`, `ligne_commande`, `meuble`, `user`, `messenger_messages` |
| `Version20260506204338` | 06/05/2026 | Correction des clés étrangères (`user_id`, `commande_id`, `meuble_id`, `categorie_id`), suppression du champ `adresse` de `user` |
| `Version20260513141032` | 13/05/2026 | Ajout du champ `roles` (JSON) à la table `user` pour la gestion des rôles Symfony Security |

---

## Modélisation des données

### Schéma relationnel

```
User (1) ──────────────────── (N) Commande
                                        │
                                        │ (1)
                                        ▼
                               (N) LigneCommande (N) ──── (1) Meuble (N) ──── (1) Categorie
```

### Entité `User`

| Champ | Type | Contrainte |
|---|---|---|
| `id` | int | PK, auto-increment |
| `email` | varchar(255) | NOT NULL, UNIQUE |
| `password` | varchar(255) | NOT NULL (hashé bcrypt) |
| `nom` | varchar(255) | NOT NULL |
| `prenom` | varchar(255) | NOT NULL |
| `telephone` | varchar(255) | nullable |
| `roles` | JSON | NOT NULL (`["ROLE_USER"]` ou `["ROLE_ADMIN"]`) |

### Entité `Categorie`

| Champ | Type | Contrainte |
|---|---|---|
| `id` | int | PK, auto-increment |
| `libelle` | varchar(255) | NOT NULL |
| `slug` | varchar(255) | NOT NULL |
| `description` | varchar(255) | nullable |

### Entité `Meuble`

| Champ | Type | Contrainte |
|---|---|---|
| `id` | int | PK, auto-increment |
| `nom` | varchar(255) | NOT NULL |
| `slug` | varchar(255) | NOT NULL |
| `description` | varchar(255) | nullable |
| `prix` | double | NOT NULL |
| `stock` | int | NOT NULL |
| `image` | varchar(255) | nullable |
| `categorie_id` | int | FK → `categorie.id` |

### Entité `Commande`

| Champ | Type | Contrainte |
|---|---|---|
| `id` | int | PK, auto-increment |
| `reference` | varchar(255) | NOT NULL (ex: `CMD-A1B2C3`) |
| `statut` | varchar(255) | NOT NULL (`en_attente`, `en_cours`, `completee`, `annulee`) |
| `total` | double | NOT NULL |
| `date_creation` | datetime | NOT NULL |
| `user_id` | int | FK → `user.id` |

### Entité `LigneCommande`

| Champ | Type | Contrainte |
|---|---|---|
| `id` | int | PK, auto-increment |
| `quantite` | int | NOT NULL |
| `prix_unitaire` | double | NOT NULL (snapshot du prix au moment de la commande) |
| `commande_id` | int | FK → `commande.id` |
| `meuble_id` | int | FK → `meuble.id` |

### Relations Doctrine

| Relation | Type | Description |
|---|---|---|
| `Commande` → `User` | ManyToOne | Une commande appartient à un utilisateur |
| `Commande` → `LigneCommande` | OneToMany | Une commande contient plusieurs lignes |
| `LigneCommande` → `Commande` | ManyToOne | Chaque ligne appartient à une commande |
| `LigneCommande` → `Meuble` | ManyToOne | Chaque ligne correspond à un meuble |
| `Meuble` → `Categorie` | ManyToOne | Un meuble appartient à une catégorie |

---

## Fonctionnalités

### Espace client

- Consultation de la vitrine sans connexion (accueil, liste, détail produit)
- Recherche par nom de meuble et filtrage par catégorie
- Inscription avec validation du formulaire (nom, prénom, email, téléphone, mot de passe)
- Connexion / déconnexion
- Gestion du panier via sessions (ajout, modification des quantités, suppression, calcul du total)
- Passage de commande avec création en base de données (`Commande` + `LigneCommande`)
- Diminution automatique du stock après validation de commande
- Historique des commandes avec détail de chaque ligne
- Redirection vers la connexion si une action nécessite d'être authentifié

### Espace administrateur (ROLE_ADMIN)

Accessible via `/admin/*`, protégé par `security.yaml`.

- **Dashboard** : chiffre d'affaires total, nombre de commandes, clients et meubles, graphique revenus mensuels (Chart.js), graphique des statuts des commandes
- **Gestion des meubles** : liste, ajout, modification, suppression avec upload d'image locale
- **Gestion des catégories** : liste, ajout, modification, suppression
- **Suivi des commandes** : liste avec statut coloré, changement de statut, détail, suppression (supprime également les lignes associées)
- **Gestion des utilisateurs** : liste, modification, suppression des comptes clients

---

## Structure du projet

```
symhome/
├── assets/styles/app.css                # Styles globaux (palette beige/brun, polices)
├── config/packages/
│   ├── security.yaml                    # Authentification, rôles, firewall
│   └── twig.yaml                        # Bootstrap 5 form themes
├── migrations/
│   ├── Version20260506203836.php        # Création initiale des tables
│   ├── Version20260506204338.php        # Correction FK + suppression adresse
│   └── Version20260513141032.php        # Ajout champ roles (JSON)
├── public/uploads/meubles/              # 20 images JPG des meubles
├── src/
│   ├── Controller/
│   │   ├── HomeController.php
│   │   ├── MeubleController.php
│   │   ├── PanierController.php
│   │   ├── CommandeController.php
│   │   ├── SecurityController.php
│   │   ├── RegistrationController.php
│   │   ├── AdminDashboardController.php
│   │   ├── AdminMeubleController.php
│   │   ├── AdminCategorieController.php
│   │   ├── AdminCommandeController.php
│   │   └── AdminUserController.php
│   ├── Entity/
│   │   ├── User.php
│   │   ├── Categorie.php
│   │   ├── Meuble.php
│   │   ├── Commande.php
│   │   └── LigneCommande.php
│   ├── Form/
│   │   ├── RegistrationFormType.php
│   │   ├── AdminMeubleType.php
│   │   ├── AdminCategorieType.php
│   │   ├── CommandeType.php
│   │   ├── PaiementType.php
│   │   └── UserType.php
│   ├── Repository/
│   │   ├── UserRepository.php
│   │   ├── CategorieRepository.php
│   │   ├── MeubleRepository.php        # Méthode search() par nom et catégorie
│   │   ├── CommandeRepository.php
│   │   └── LigneCommandeRepository.php
│   ├── Security/AppAuthenticator.php   # Auth par email/mot de passe
│   └── DataFixtures/AppFixtures.php    # 4 catégories, 20 meubles, 1 client, 1 admin
└── templates/
    ├── base.html.twig                   # Layout principal (navbar, flash messages)
    ├── admin/base_admin.html.twig       # Layout admin avec sidebar
    ├── home/ meuble/ panier/ commande/  # Templates espace client
    ├── security/ registration/          # Connexion et inscription
    └── admin_meuble/ admin_categorie/   # Templates espace admin
        admin_commande/ admin_user/
        dashboard/
```

---

## Lancer le projet

```bash
symfony serve
```

Accéder à : [http://localhost:8000](http://localhost:8000)

---

## Comptes de test

Après avoir chargé les fixtures :

| Rôle | Email | Mot de passe |
|---|---|---|
| Client | rayensaidi@gmail.com | rayen1234 |
| Administrateur | radhouen@gmail.com | admin |

---

## Auteurs

- **Mohamed Rayen Saidi**
- **Mohamed Radhouene Ksouri**
- Classe : L2-DSI1 — ISET Rades
- Année universitaire : 2025–2026
- Enseignante : Mme Chaabani Marwa

---
