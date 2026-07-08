# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

A backoffice for managing digital pub/restaurant menu boards. It's a CRUD admin for **stock levels and prices, per product, per store** (the same product can have different price/stock at different store locations). It is consumed via an authenticated API (Laravel Sanctum) and will ship as a Docker container.

As of now this is a fresh Laravel 13 skeleton — only the default `User` model/migration exist. No API routing, Sanctum, custom domain models, or Docker setup have been added yet; all of that is being built incrementally. Update this file's "Project" and "Architecture" sections as real structure lands — don't let it drift into describing a state that no longer matches the code.

This is a learning project: prefer smaller, explainable steps over large generated diffs, and call out *why* for non-obvious framework/Docker/auth decisions.

## Commands

- Install: `composer install && npm install`
- Local dev (serves app + queue listener + log tail + vite, concurrently): `composer run dev`
- Run all tests: `composer test` (wraps `php artisan test`)
- Run a single test: `php artisan test --filter=testMethodName` or target a file: `php artisan test tests/Feature/ExampleTest.php`
- Lint/format (Pint): `vendor/bin/pint`
- Migrate: `php artisan migrate` (SQLite by default, see `.env`)

## Stack notes

- PHP 8.3, Laravel 13, SQLite for local dev (`DB_CONNECTION=sqlite`)
- PHPUnit (via `artisan test`), no Pest
- Tailwind 4 + Vite are present from the default Laravel scaffold but this project is API-first — don't assume a Blade/SPA frontend is in scope unless asked
- `bootstrap/app.php` currently only registers `web` and `console` routing (no `api:` entry yet) — adding Sanctum will need an `api.php` route file wired in here
