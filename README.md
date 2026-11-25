# Five Stitches

*Crafted with Care, Stitched with Love.*

The online shop for **Five Stitches**, a handmade crochet business in Legazpi City. Built with Laravel + Breeze (Blade + Tailwind). Design source: [Figma file](https://www.figma.com/design/uYMS60Y6cbeJjjU7Sr3PWS).

## Requirements

- PHP 8.3+, Composer
- Node.js 20+ and npm
- SQLite (default for local dev) or MySQL 8

## Getting started

```bash
git clone git@github.com:D3N-ver-01-x45/five-stitches.git
cd five-stitches
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
```

Then run these in two terminals:

```bash
npm run dev
php artisan serve
```

Open http://127.0.0.1:8000/login.

### Demo login (local only)

| Email | Password |
|---|---|
| `admin@fivestitches.test` | `password` |

This account is made by `php artisan db:seed`. Never seed it on the live site.

## Branches

- `main`: stable, presentable code
- `develop`: the team's working branch
- `feature/<name>`: one feature per branch, opened as a pull request into `develop`

Commit messages follow Conventional Commits (`feat:`, `fix:`, `style:`, `chore:` …).

## Before opening a pull request

```bash
php artisan test
./vendor/bin/pint
```

## Brand tokens

Colors and fonts in `tailwind.config.js` match the Figma styles: `cream`, `ink`, `forest`, `sage`, `sage-light`, `coral`, `line`, `muted`; fonts `font-heading` (Zilla Slab), `font-script` (Alegreya Italic), `font-sans` (Inter).

## Never commit

`.env`, `/vendor`, `/node_modules`, `/public/build`, real customer data, or payment details.
