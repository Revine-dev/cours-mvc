# 🏗️ Masterclass PHP MVC : Squelette de Framework Personnalisé

[![PHP Version](https://img.shields.io/badge/php-%5E8.5-blue.svg)](https://php.net)
[![Framework](https://img.shields.io/badge/framework-Slim_4-orange.svg)](https://www.slimframework.com/)
[![ORM](https://img.shields.io/badge/ORM-Doctrine_3-red.svg)](https://www.doctrine-project.org/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## 🌟 À propos du projet
Ce projet est un squelette architectural avancé conçu pour illustrer la construction d'une application web moderne en partant de zéro, en utilisant le patron de conception **Modèle-Vue-Contrôleur (MVC)**.

Il sert de laboratoire complet pour maîtriser les mécaniques internes du développement PHP professionnel, en mettant l'accent sur le découplage, la sécurité et les standards de l'industrie. Ce projet est un exercice pédagogique de création de framework et n'est pas destiné à la production.

### Pourquoi le Framework Slim ?
Dans le cadre du **MOOC "Construire un Framework MVC en PHP"**, Slim 4 est le choix idéal pour plusieurs raisons qui s'alignent sur la progression du cours :
- **Faible Abstraction (Ch 1-4)** : Contrairement aux frameworks "lourds", la nature minimaliste de Slim permet de voir clairement le Front Controller, le Routeur et le pipeline de Middlewares, rendant visibles les mécaniques internes d'un framework.
- **Standards PSR (Ch 4-5)** : Il impose l'utilisation de PSR-7 (Messages HTTP) et PSR-15 (Middlewares), enseignant les standards professionnels dès le début.
- **IoC & Injection de Dépendances (Ch 7, 9)** : Son intégration fluide avec PHP-DI offre un terrain de jeu concret pour maîtriser l'Inversion de Contrôle et le pattern Singleton.
- **Découplage Total (Ch 8, 15)** : Slim ne s'impose pas, vous permettant d'implémenter une couche Domaine pure (Entités) et une couche Persistance (Doctrine) comme enseigné dans les chapitres avancés.
- **Pipeline de Middlewares (Ch 21)** : Il offre une manière élégante d'apprendre à intercepter les requêtes pour la sécurité (CSRF, Authentification) sans polluer la logique métier.

### Pourquoi Doctrine ORM ?
Doctrine ORM 3 est intégré dans ce squelette pour couvrir les chapitres avancés sur la manipulation des données du **MOOC** :
- **Pattern Data Mapper (Ch 8, 11)** : Contrairement à Active Record, Doctrine implémente le pattern Data Mapper, imposant une séparation stricte entre vos objets métier (Entités) et le schéma de base de données.
- **Unit of Work (Ch 11)** : Il enseigne le concept de gestion d'état des objets et le regroupement des opérations en base, crucial pour les performances des applications professionnelles.
- **Fonctionnalités PHP Modernes (Ch 14)** : L'utilisation des Attributs PHP 8 pour le mapping illustre l'utilisation des dernières nouveautés du langage pour les métadonnées.
- **Pattern Repository (Ch 10, 15)** : Doctrine favorise naturellement le pattern Repository, facilitant les leçons sur la "Recherche Générique" et le "Découplage" en offrant une interface propre pour la récupération de données.
- **Relations Complexes (Ch 22)** : Il fournit un moyen robuste de gérer les associations One-to-Many et Many-to-Many, montrant comment manipuler des graphes d'objets complexes sans écrire de SQL manuel.

---

## 🛠️ Stack Technique
- **Moteur** : [Slim 4](https://www.slimframework.com/) - Un micro-framework PSR-7 puissant.
- **Base de données** : [Doctrine ORM 3](https://www.doctrine-project.org/) - Le standard industriel pour le pattern Data Mapper.
- **Conteneur** : [PHP-DI](https://php-di.org/) - Conteneur d'injection de dépendances robuste.
- **Frontend** : [Tailwind CSS](https://tailwindcss.com/) - Framework CSS moderne utilitaire.
- **Qualité de code** : 
    - **PHPUnit** : Pour les tests unitaires et fonctionnels automatisés.
    - **PHPStan** : Pour l'analyse statique avancée (Niveau 8+).
    - **PHPCS** : Pour le respect strict des standards de codage.

---

## 🚀 Fonctionnalités Clés
- **Moteur de Vue Personnalisé** : Un système de rendu sophistiqué qui échappe automatiquement les variables pour prévenir les failles XSS, sauf mention explicite `dangerousRaw()`.
- **Routage Avancé** : Routes nommées et middlewares groupés pour une gestion propre et maintenable des URLs.
- **Configuration via Registre (Pattern Registry)** : Un registre de configuration statique centralisé (`ConfigRegistry`) offrant un accès global aux paramètres de l'application et aux variables d'environnement.
- **Pattern Repository** : Couche d'accès aux données abstraite garantissant une testabilité élevée et une séparation des responsabilités.
- **Pipeline de Middlewares** : Gestion fluide des sessions, de la protection CSRF et de l'authentification.
- **Prêt pour l'Entreprise** : Intégration complète des standards PSR (PSR-4, PSR-7, PSR-11, PSR-15).

---

## 🏛️ Patterns Architecturaux
Ce projet met en œuvre plusieurs patrons de conception avancés pour atteindre une architecture de niveau professionnel :
- **Injection de Dépendances (IoC)** : Propulsé par PHP-DI, garantissant que les composants sont découplés et facilement testables.
- **Singleton & Static Helper** : Accès centralisé aux utilitaires globaux (`Helper`) et à la configuration (`ConfigRegistry`) tout en maîtrisant le cycle de vie des instances.
- **Pattern Repository** : Une séparation stricte entre la couche Domaine (Entités) et la couche Infrastructure (Persistance Doctrine).
- **Principes de Clean Architecture** : Organisation garantissant que la logique métier reste indépendante des frameworks et outils externes.
- **Standards PSR** : Conformité totale avec PSR-7 (Messages HTTP) et PSR-15 (Middleware) pour une interopérabilité maximale.

---

## 📁 Structure du Projet
```text
├── app/                # Configuration & Bootstrap de l'application
│   ├── config.php      # Valeurs de configuration (Pattern Registry)
│   ├── constants.php   # Constantes globales & Initialisation du Registre
│   ├── dependencies.php# Définitions de l'Injection de Dépendances
│   ├── routes.php      # Carte des routes de l'application
│   └── settings.php    # Paramètres d'environnement (Slim-specific)
├── src/
│   ├── Application/
│   │   ├── Actions/    # Contrôleurs (Gestion des requêtes)
│   │   ├── Helpers/    # Logique partagée entre Actions et Vues
│   │   └── Response/   # Réponse personnalisée & Sécurité
│   ├── Entity/         # Modèles de domaine (Entités Doctrine)
│   └── Migrations/     # Gestion de version de la base de données
├── public/             # Point d'entrée (index.php)
└── tests/              # Suites de tests
```

---

## ⚙️ Installation
1. **Cloner le dépôt** :
   ```bash
   git clone <url-du-depot>
   cd cours-MVC
   ```
2. **Installer les dépendances** :
   ```bash
   composer install
   ```
3. **Configurer l'environnement** :
   Copiez `.env.example` vers `.env` et ajustez vos paramètres de base de données.
4. **Lancer les migrations** :
   ```bash
   php bin/doctrine migrations:migrate
   ```
5. **Démarrer le serveur de test** :
   ```bash
   composer start
   ```

---

## 🧪 Qualité & Standards
Maintenez l'intégrité du code à l'aide des scripts Composer intégrés :
- **Lancer les tests** : `composer test`
- **Analyse statique** : `composer analyse`
- **Vérification du style** : `composer cs`
- **Vérification complète** : `composer check` (Lance tout ce qui précède)

---

## ⚙️ Intégration Continue (CI)
Le projet intègre un pipeline CI robuste via **GitHub Actions** (`.github/workflows/tests.yml`) qui s'exécute automatiquement à chaque push et pull request :
- **Environnement** : PHP 8.5 et MySQL 8.0.
- **Base de données** : Exécution automatique des migrations via Doctrine pour garantir la cohérence du schéma.
- **Suite QA** : Exécution des standards de codage (`phpcs`), de l'analyse statique (`phpstan`) et des tests unitaires/fonctionnels (`phpunit`) avec rapport de couverture.

---

## 🚀 Vers la Perfection : Améliorations Architecturales
Pour transformer ce squelette en un framework de production de classe mondiale, vous pourriez envisager les évolutions suivantes :
- **Validation Avancée** : Intégrer une couche de validation robuste (ex: Symfony Validator ou Respect/Validation) pour découpler la vérification des données de la logique métier.
- **Contrôle d'Accès par Rôle (RBAC)** : Étendre le middleware de sécurité actuel vers un système complet basé sur les permissions pour une gestion granulaire des droits utilisateurs.
- **Couche de Mise en Cache** : Implémenter la mise en cache PSR-6 ou PSR-16 (Redis/Memcached) pour optimiser les requêtes Doctrine coûteuses et le rendu des vues.
- **Event Dispatcher** : Intégrer un répartiteur d'événements PSR-14 pour permettre aux différentes parties de l'application de communiquer sans dépendances directes.
- **Journalisation Structurée** : Améliorer l'intégration de Monolog avec des logs contextuels et une surveillance externe (ex: Sentry ou ELK Stack).
- **Versionnage d'API** : Implémenter une stratégie de versionnage d'API RESTful pour supporter plusieurs types de clients (web, mobile) sans friction.
- **Pipeline d'Assets Moderne** : Utiliser Vite ou Webpack pour une compilation et une minification efficace des ressources frontend.
- **Auto-déploiement (CD)** : Étendre le pipeline CI pour inclure des workflows de déploiement automatisés vers des environnements de staging ou de production après la réussite des tests.
