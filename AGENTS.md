# Repository Guidelines

## Project Structure & Module Organization

This is a plain PHP sports/blog application. The web entry point is `public/index.php`; only `public/` should be served by a web server. CSS lives in `public/assets/css/`, and uploaded sport images live in `public/uploads/sport-images/`.

Core application code is split by role: `controllers/` handles requests, `models/` contains domain objects, `repositories/` handles persistence access, `db/` and `config/` contain database setup, and `core/` contains shared framework utilities such as routing and CSRF handling. Views are PHP templates under `views/`, with shared layout files in `views/layouts/`. Tests live in `tests/`.

## Build, Test, and Development Commands

Run commands from the repository root.

```sh
composer install
```

Installs PHP dependencies and generates Composer autoload files.

```sh
php -S 127.0.0.1:8000 -t public
```

Starts the local PHP development server with `public/` as the document root.

```sh
php tests/csrf.php
```

Runs the current CSRF check script.

## Coding Style & Naming Conventions

Use PHP with clear, role-based classes and namespaces matching `composer.json` PSR-4 mappings, such as `Controller\\`, `Model\\`, and `Repository\\`. Keep request logic in controllers, data access in repositories, and display markup in views.

Use 4-space indentation in PHP and CSS. Escape output in views with `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`. Keep CSS page-specific when possible, following existing files such as `sport-dashboard.css` and `login.css`.

## Testing Guidelines

Add tests under `tests/` using descriptive names, for example `csrf.php` or `sport-validation.php`. At minimum, run `php tests/csrf.php` before submitting changes that touch forms, sessions, routing, authentication, or CSRF tokens.

Manually verify affected pages through the local server, including mobile-width layout when changing CSS or views.

## Commit & Pull Request Guidelines

The Git history uses short imperative messages, such as `remove test file`. Keep commit messages concise and action-oriented, for example `update dashboard styles` or `fix sport image validation`.

Pull requests should include a short description, affected areas, test commands run, and screenshots for visible UI changes. Link related issues when available.

## Security & Configuration Tips

Keep `.env` local and out of commits. Do not expose the repository root through a web server; serve only `public/`. After server changes, confirm `.env`, `composer.json`, `controllers/`, `vendor/`, and uploaded PHP files are not directly accessible.
