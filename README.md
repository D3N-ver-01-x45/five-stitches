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

| Role | Email | Password | After login |
|---|---|---|---|
| Admin | `admin@fivestitches.test` | `password` | Orders dashboard |
| Seller | `seller@fivestitches.test` | `password` | Orders dashboard |
| Buyer | `buyer@fivestitches.test` | `password` | Back to the shop |

`php artisan migrate:fresh --seed` also adds the 4 real products and 15 sample orders.

On the live site, add only the real products with `php artisan db:seed --class=ProductSeeder`. Never seed on the live site.

New sign-ups are always **buyers**. To make a teammate a seller, run:

```bash
php artisan tinker --execute "App\Models\User::where('email','them@example.com')->first()->forceFill(['role'=>'seller'])->save();"
```

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
