# 🏗️ PHP MVC Masterclass: Custom Framework Skeleton

[![PHP Version](https://img.shields.io/badge/php-%5E8.5-blue.svg)](https://php.net)
[![Framework](https://img.shields.io/badge/framework-Slim_4-orange.svg)](https://www.slimframework.com/)
[![ORM](https://img.shields.io/badge/ORM-Doctrine_3-red.svg)](https://www.doctrine-project.org/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## 🌟 About the Project
This project is an advanced architectural skeleton designed to demonstrate the construction of a modern web application from the ground up using the **Model-View-Controller (MVC)** design pattern. 

It serves as a comprehensive laboratory for mastering the internal mechanics of professional PHP development, focusing on decoupling, security, and industry standards. Note that this is an educational exercise in framework-building, not a production-ready framework.

### Why Slim Framework?
In the context of the **MOOC "Building an MVC Framework in PHP"**, Slim 4 is the ideal choice for several reasons that align with the course's progression:
- **Low Abstraction (Ch 1-4)**: Unlike "heavy" frameworks, Slim's minimalist nature allows you to clearly see the Front Controller, the Router, and the Middleware pipeline, making the internal mechanics of a framework visible.
- **PSR Standards (Ch 4-5)**: It enforces the use of PSR-7 (HTTP Messages) and PSR-15 (Middleware), teaching professional standards from the start.
- **IoC & Dependency Injection (Ch 7, 9)**: Its seamless integration with PHP-DI provides a concrete playground for mastering Inversion of Control and the Singleton pattern.
- **Total Decoupling (Ch 8, 15)**: Slim stays out of your way, allowing you to implement a pure Domain layer (Entities) and a Persistence layer (Doctrine) as taught in the advanced chapters.
- **Middleware Pipeline (Ch 21)**: It offers an elegant way to learn how to intercept requests for security (CSRF, Authentication) without polluting the business logic.

### Why Doctrine ORM?
Doctrine ORM 3 is integrated into this skeleton to cover the advanced data-handling chapters of the **MOOC**:
- **Data Mapper Pattern (Ch 8, 11)**: Unlike Active Record, Doctrine implements the Data Mapper pattern, enforcing a strict separation between your business objects (Entities) and the database schema.
- **Unit of Work (Ch 11)**: It teaches the concept of managing object states and batching database operations, which is crucial for performance in professional applications.
- **Modern PHP Features (Ch 14)**: The use of PHP 8 Attributes for mapping demonstrates how to use the latest language features for metadata and configuration.
- **Repository Pattern (Ch 10, 15)**: Doctrine naturally encourages the Repository pattern, facilitating the "Generic Search" and "Decoupling" lessons by providing a clean interface for data retrieval.
- **Complex Relationships (Ch 22)**: It provides a robust way to handle One-to-Many and Many-to-Many associations, showing how to manage complex object graphs without writing manual SQL.

---

## 🛠️ Technical Stack
- **Engine**: [Slim 4](https://www.slimframework.com/) - A powerful PSR-7 micro-framework.
- **Database**: [Doctrine ORM 3](https://www.doctrine-project.org/) - Industry-standard Data Mapper pattern.
- **Container**: [PHP-DI](https://php-di.org/) - Robust Dependency Injection container.
- **Frontend**: [Tailwind CSS](https://tailwindcss.com/) - Modern utility-first styling.
- **Quality Assurance**: 
    - **PHPUnit**: For automated unit and functional testing.
    - **PHPStan**: For advanced static analysis (Level 8+).
    - **PHPCS**: For maintaining strict coding standards.

---

## 🚀 Key Features
- **Custom View Engine**: A sophisticated rendering system that automatically escapes variables to prevent XSS, unless explicitly marked as `dangerousRaw()`.
- **Advanced Routing**: Named routes and grouped middleware for clean and maintainable URL management.
- **Pattern Registry Configuration**: A centralized, static configuration registry (`ConfigRegistry`) providing global access to application settings and environment variables.
- **Repository Pattern**: Abstracted data access layer ensuring high testability and separation of concerns.
- **Middleware Pipeline**: Seamless handling of sessions, CSRF protection, and authentication logic.
- **Enterprise Ready**: Full integration of PSR standards (PSR-4, PSR-7, PSR-11, PSR-15).

---

## 🏛️ Architectural Patterns
This project implements several advanced design patterns to achieve a professional-grade architecture:
- **Dependency Injection (IoC)**: Powered by PHP-DI, ensuring components are decoupled and easily testable.
- **Singleton & Static Helper**: Centralized access to global utilities (`Helper`) and configuration (`ConfigRegistry`) while maintaining control over instance lifecycle.
- **Repository Pattern**: A strict separation between the Domain layer (Entities) and the Infrastructure layer (Doctrine Persistence).
- **Clean Architecture Principles**: Organized to ensure that business logic remains independent of external frameworks and tools.
- **PSR Standards**: Full compliance with PSR-7 (HTTP Messages) and PSR-15 (Middleware) for maximum ecosystem compatibility.

---

## 📁 Project Structure
```text
├── app/                # Application Configuration & Bootstrap
│   ├── config.php      # Main configuration values (Pattern Registry)
│   ├── constants.php   # Global constants & Registry registration
│   ├── dependencies.php# Dependency Injection definitions
│   ├── routes.php      # Application Route map
│   └── settings.php    # Environment settings (Slim-specific)
├── src/
│   ├── Application/
│   │   ├── Actions/    # Controllers (Handling Requests)
│   │   ├── Helpers/    # Logic shared between Actions and Views
│   │   └── Response/   # Custom Response & Security wrapping
│   ├── Entity/         # Domain Models (Doctrine Entities)
│   └── Migrations/     # Database version control
├── public/             # Entry point (index.php)
└── tests/              # Test suites
```

---

## ⚙️ Installation
1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd cours-MVC
   ```
2. **Install dependencies**:
   ```bash
   composer install
   ```
3. **Configure Environment**:
   Copy `.env.example` to `.env` and adjust your database settings.
4. **Run Database Migrations**:
   ```bash
   php bin/doctrine migrations:migrate
   ```
5. **Start the Dev Server**:
   ```bash
   composer start
   ```

---

## 🧪 Quality & Standards
Maintain the codebase integrity using the built-in Composer scripts:
- **Run Tests**: `composer test`
- **Static Analysis**: `composer analyse`
- **Code Style Check**: `composer cs`
- **Full Check**: `composer check` (Runs all the above)

---

## ⚙️ Continuous Integration (CI)
The project includes a robust CI pipeline via **GitHub Actions** (`.github/workflows/tests.yml`) that automatically executes on every push and pull request:
- **Environment**: PHP 8.5 and MySQL 8.0.
- **Database**: Automated migrations via Doctrine to ensure schema consistency.
- **QA Suite**: Runs Coding Standards (`phpcs`), Static Analysis (`phpstan`), and Unit/Functional Tests (`phpunit`) with coverage reporting.

---

## 🚀 Path to Perfection: Architectural Enhancements
To evolve this skeleton into a world-class production framework, consider implementing the following:
- **Advanced Validation**: Integrate a robust validation layer (e.g., Symfony Validator or Respect/Validation) to decouple input checking from business logic.
- **Role-Based Access Control (RBAC)**: Expand the current security middleware into a full permission-based system for granular user rights management.
- **Caching Layer**: Implement PSR-6 or PSR-16 caching (Redis/Memcached) to optimize expensive Doctrine queries and view rendering.
- **Event Dispatcher**: Integrate a PSR-14 Event Dispatcher to allow different parts of the application to communicate without direct dependencies.
- **Structured Logging**: Enhance the Monolog integration with contextual logging and external monitoring (e.g., Sentry or ELK Stack).
- **API Versioning**: Implement a strategy for RESTful API versioning to support multi-platform clients seamlessly.
- **Modern Asset Pipeline**: Use Vite or Webpack for efficient frontend asset compilation and minification.
- **Auto-deployment (CD)**: Extend the CI pipeline to include automated deployment workflows to staging or production environments upon successful test completion.
