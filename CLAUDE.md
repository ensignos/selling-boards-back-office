# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

A backoffice for managing digital pub/restaurant menu boards. It's a CRUD admin for **stock levels and prices, per product, per store** (the same product can have different price/stock at different store locations). It is consumed via an authenticated API (Laravel Sanctum) and will ship as a Docker container.

Docker setup (multi-stage Dockerfile, Nginx, MySQL) is done. Sanctum is installed but not yet fully wired (see Auth below) — no custom domain models yet. Update this file's "Project" and architecture sections as real structure lands — don't let it drift into describing a state that no longer matches the code.

This is a learning project: prefer smaller, explainable steps over large generated diffs, walk through *why* for non-obvious framework/Docker/auth decisions rather than just writing the file, and let the user type/run anything that's a learning objective (shell commands, Dockerfiles, config, auth wiring) instead of doing it silently.

## Commands

- Install: `composer install && npm install` (host — only needed if you want IDE autocompletion; actual running/testing happens in Docker, see below)
- Bring the full stack up: `docker compose up -d --build`
- Run artisan commands inside the container: `docker compose exec app php artisan <command>` (e.g. `migrate`)
- Run tests inside the container: `docker compose exec app php artisan test`
- Lint/format (Pint): `docker compose exec app vendor/bin/pint`
- App reachable at `http://localhost:8080` (mapped from the `nginx` service); MySQL reachable at `localhost:3306` for external DB clients

## Stack notes

- PHP 8.3, Laravel 13, MySQL only — no SQLite anywhere (including tests), by deliberate choice, so local/test/prod all run against the same DB engine
- PHPUnit (via `artisan test`), no Pest — chosen over Pest specifically for transferable PHP skill, not just Laravel-specific
- Host machine does not have `pdo_sqlite` or a working DB driver set up — don't try to run `artisan migrate`/`test` directly on the host, it'll fail; always go through `docker compose exec app ...`
- Tailwind 4 + Vite are present from the default Laravel scaffold but this project is API-first — don't assume a Blade/SPA frontend is in scope unless asked

## Docker architecture

- **Two containers for the app, not one**: `nginx` (accepts HTTP, serves static files, proxies `.php` requests) and `app` (PHP-FPM, executes PHP). FPM only speaks FastCGI, not HTTP, so it can't stand alone as a web-facing service — hence the split. `nginx` reaches `app` over the internal Compose network via `fastcgi_pass app:9000;` (service name resolves via Compose's built-in DNS).
- **`Dockerfile` is multi-stage**: a `base` stage (system packages, PHP extensions — `pdo_mysql`, `zip`, `gd` — Composer binary, dependency manifest copy) that `dev` and `prod` both build from. The only real difference between `dev`/`prod` is `composer install` with vs. without `--no-dev`. Stage is selected via `docker-compose.yml`'s `build.target` (currently `dev`; a `docker-compose.prod.yml` targeting `prod` — leaner, no dev tools, no bind-mounted code — is still to be written).
- **`db` uses a named volume** (`db_data:/var/lib/mysql`) so data survives `docker compose down`; `app`/`nginx` use **bind mounts** (`.:/var/www/html`) instead, so local file edits are reflected immediately without rebuilding (a dev-only convenience — prod should run exactly what's baked into the image).
- Known gap: `depends_on` only waits for the `db` container to *start*, not for MySQL to actually be ready to accept connections — first-boot `migrate` can occasionally race this. Not yet solved with a healthcheck/wait step.

## Auth (Sanctum)

- Package installed, migration published, but not yet wired: `HasApiTokens` trait not yet added to `User`, `routes/api.php` doesn't exist yet, `bootstrap/app.php` doesn't register an `api:` routing entry yet. This is the next thing to build, test-first.
