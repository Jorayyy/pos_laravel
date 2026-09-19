# VetPOS

Veterinary clinic management and point-of-sale system built with Laravel 13. Handles pet owner records, pet profiles, product inventory, vet services, POS transactions, and business reporting in a single dashboard.

## Tech Stack

- **Backend:** Laravel 13, PHP 8.3
- **Frontend:** Blade, Livewire 4, Alpine.js, Tailwind CSS v4
- **Database:** SQLite (switchable to PostgreSQL/MySQL)
- **PDF:** DomPDF
- **Font:** Plus Jakarta Sans

## Features

- **POS** — Livewire-powered cart with product search, quantity adjust, service line items, cash/change calculation, and one-click checkout
- **Product Management** — SKU, barcode, categories, brands, stock levels, reorder alerts, expiration tracking, photo uploads
- **Service Management** — Vet services with categories and pricing
- **Pet & Owner Records** — Owner profiles linked to pets with breed, vaccination dates, and medical notes
- **Inventory Tracking** — Stock movements logged on every sale, adjustment, and restock with full audit trail
- **Sales History** — Filterable transaction list with receipt view
- **Reports** — Daily, weekly, monthly sales; sales by product, service, cashier; low stock; expiring products; inventory summary; veterinary services report
- **RBAC** — Role-based access control (admin, manager, cashier, vet staff) with per-route middleware
- **User Management** — Create, edit, deactivate staff accounts
- **Audit Logs** — Track all CRUD actions with user, IP, and timestamp
- **Settings** — Clinic name, address, phone, tax rate, receipt footer

## Setup

```bash
# Clone
git clone https://github.com/Jorayyy/pos_laravel.git
cd pos_laravel/vet-pos

# Install
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Storage
php artisan storage:link

# Build assets
npm run build

# Run
php artisan serve
```

## Login

| Email | Password | Role |
|-------|----------|------|
| admin@vetpos.com | password | Admin |
| maria@vetpos.com | password | Manager |
| juan@vetpos.com | password | Cashier |

## License

MIT
