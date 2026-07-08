# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

A backoffice for managing digital pub/restaurant menu boards. It's a CRUD admin for **stock levels and prices, per product, per store** (the same product can have different price/stock at different store locations). It is consumed via an authenticated API (Laravel Sanctum) and will ship as a Docker container.

Docker setup (multi-stage Dockerfile, Nginx, MySQL) is done. Sanctum is fully wired end-to-end (login + a token-protected route), covered by passing feature tests (see Auth below). No custom domain models yet — Store/Product/per-store pricing is the next major piece. Update this file's "Project" and architecture sections as real structure lands — don't let it drift into describing a state that no longer matches the code.

This is a learning project: prefer smaller, explainable steps over large generated diffs, walk through *why* for non-obvious framework/Docker/auth decisions rather than just writing the file, and let the user type/run anything that's a learning objective (shell commands, Dockerfiles, config, auth wiring, tests) instead of doing it silently.

## Commands

- Install: `composer install && npm install` (host — only needed if you want IDE autocompletion; actual running/testing happens in Docker, see below)
- Bring the full stack up: `docker compose up -d --build`
- Run artisan commands inside the container: `docker compose exec app php artisan <command>` (e.g. `migrate`, `make:controller`, `make:model`)
- Run tests inside the container: `docker compose exec app php artisan test` (or `--filter=SomeTest` for one class)
- Regenerate the autoloader after adding a new class, if something says "Target class does not exist": `docker compose exec app composer dump-autoload` (see gotcha below on *why* this is sometimes needed)
- Lint/format (Pint): `docker compose exec app vendor/bin/pint`
- App reachable at `http://localhost:8080` (mapped from the `nginx` service); MySQL reachable at `localhost:3306` for external DB clients

## Stack notes

- PHP **8.4** (bumped up from the originally-scaffolded 8.3 — `composer.lock`'s resolved Symfony/Laravel packages required `>=8.4.1`, and host PHP is 8.5.8, so the container was raised to match reality rather than fighting the lock file), Laravel 13, MySQL only — no SQLite anywhere (including tests), by deliberate choice, so local/test/prod all run against the same DB engine
- PHPUnit (via `artisan test`), no Pest — chosen over Pest specifically for transferable PHP skill, not just Laravel-specific
- `composer.json`'s `config.optimize-autoloader` is `false` (was `true` from the default scaffold) — intentional: with it `true`, newly-generated classes (via any `make:*` command) aren't autoloadable until an explicit `composer dump-autoload`, which is an easy-to-forget footgun during active dev. `prod`'s Dockerfile still explicitly passes `--optimize-autoloader` on its `composer install`, so production keeps the performance win regardless of this default.
- `phpunit.xml` has `cacheResult="false"` — PHPUnit's result-cache file couldn't be written under `/var/www/html` even after the UID-matching fix below (something about this machine's `/mnt/storage` mount specifically); disabling the cache sidesteps a non-essential feature rather than chasing a filesystem quirk unrelated to the app itself.
- Host machine does not have `pdo_sqlite` or a working DB driver set up — don't try to run `artisan migrate`/`test` directly on the host, it'll fail; always go through `docker compose exec app ...`
- Tailwind 4 + Vite are present from the default Laravel scaffold but this project is API-first — don't assume a Blade/SPA frontend is in scope unless asked

## Docker architecture

- **Two containers for the app, not one**: `nginx` (accepts HTTP, serves static files, proxies `.php` requests) and `app` (PHP-FPM, executes PHP). FPM only speaks FastCGI, not HTTP, so it can't stand alone as a web-facing service — hence the split. `nginx` reaches `app` over the internal Compose network via `fastcgi_pass app:9000;` (service name resolves via Compose's built-in DNS).
- **`Dockerfile` is multi-stage**: `base` (system packages, PHP extensions — `pdo_mysql`, `zip`, `gd` — Composer binary, dependency manifest copy) → `dev` and `prod` branch from it independently. `dev` additionally renumbers the image's `www-data` user to `UID/GID 1000` (passed as build args from `docker-compose.yml`, currently hardcoded to `1000`/`1000` to match this host) so files created via the bind mount aren't owned by a mismatched UID; `prod` deliberately does **not** do this — it has no bind mount, so there's nothing to match, and matching a real dev machine's UID would be pointless/sloppy in an image meant to run elsewhere. Stage is selected via `docker-compose.yml`'s `build.target` (currently `dev`; a `docker-compose.prod.yml` targeting `prod` is still to be written).
- **`db` uses a named volume** (`db_data:/var/lib/mysql`) so data survives `docker compose down`; `app`/`nginx` use **bind mounts** (`.:/var/www/html`) instead, so local file edits are reflected immediately without rebuilding (a dev-only convenience — prod should run exactly what's baked into the image).
- **The bind mount shadows the *entire* project directory, including `vendor/`.** Whatever Composer does during the image build (e.g. `composer dump-autoload`) is invisible at runtime in `dev` — the container is actually serving the host's `vendor/`, not the one built into the image. This is *why* `docker compose exec app composer dump-autoload` (run against the live, bind-mounted container) is sometimes needed after generating a new class — the image-build-time autoload generation doesn't count.
- Known gap: `depends_on` only waits for the `db` container to *start*, not for MySQL to actually be ready to accept connections — first-boot `migrate` can occasionally race this. Not yet solved with a healthcheck/wait step.
- **Permissions gotcha (dev only)**: `storage/` and `bootstrap/cache/` need `chmod -R 777` on the *host* after first bringing the stack up (`www-data`'s in-image `chown` gets overridden by the bind mount's host-owned permissions). Same root cause as the UID-matching fix above, patched pragmatically for these two directories specifically rather than relying solely on UID matching.

## Auth (Sanctum)

- Fully wired: `HasApiTokens` on `User`, `bootstrap/app.php` registers `api: routes/api.php`, `routes/api.php` has `POST /login` (`AuthController::login` — validates credentials via `Auth::attempt`, issues a token via `createToken()->plainTextToken`, throws `ValidationException` on bad credentials) and a `GET /user` behind `auth:sanctum` middleware returning `$request->user()`.
- Covered by `tests/Feature/AuthenticationTest.php` (login issues a token; unauthenticated request to the protected route gets 401; authenticated request succeeds). All passing.
- Not yet built: logout/token revocation, registration (deliberately out of scope so far — this is an admin backoffice, users are presumably provisioned some other way, not self-registered), and any authorization/policies beyond "has a valid token."
