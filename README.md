# 📊 Contract Monitoring System

A comprehensive **Contract Monitoring System** built with Laravel 12 for managing tenants, assets, contracts, payments, invoices, amendments, and contract workflows. The system features role-based access control, a dynamic dashboard, and a visual workflow tracking interface.

---

## 🚀 Tech Stack

| Layer       | Technology                                    |
|-------------|-----------------------------------------------|
| Backend     | PHP 8.2.4, Laravel 12, Laravel Sanctum         |
| Frontend    | Blade Templates, TailwindCSS 4, Alpine.js     |
| Charts      | ApexCharts                                     |
| Alerts      | SweetAlert2                                    |
| Build Tool  | Vite 7                                         |
| Database    | MySQL / MariaDB                                |

---

## ✨ Features

- **Tenant Management** — Create, view, and manage tenant information
- **Asset Management** — Track and organize company assets
- **Contract Management** — Full contract lifecycle with start/end dates and associated assets
- **Contract Amendments** — Support for contract modifications and renewals
- **Payment Tracking** — Record and monitor payments linked to contracts
- **Invoice Generation** — Create and manage invoices with asset line items
- **Workflow System** — Visual step-by-step contract workflow with evidence uploads
- **Renewal Workflow** — Automated renewal process with choice to create new contract or amendment
- **Dashboard** — Dynamic overview with charts, expiring contracts alerts, and key metrics
- **Role-Based Access** — Multi-role authentication powered by Laravel Sanctum
- **Forgot Password** — Email-based password recovery flow

---

## 📋 Prerequisites

- **PHP** >= 8.2.4
- **Composer** >= 2.x
- **Node.js** >= 18.x & **npm**
- **MySQL** or **MariaDB**

---

## ⚙️ Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd monitoring
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node.js dependencies

```bash
npm install
```

### 4. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database credentials in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=monitoring
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run database migrations

> **⚠️ Important:** This project uses migration files located in the `database/migrations/final/` directory. You must specify this path when running migrations.

```bash
php artisan migrate --path=database/migrations/final
```

<details>
<summary>📄 Migration files included</summary>

| # | Migration File | Description |
|---|----------------|-------------|
| 1 | `0001_01_01_000000_create_users_table.php` | Users, password resets, and sessions tables |
| 2 | `0001_01_01_000001_create_cache_table.php` | Cache and cache locks tables |
| 3 | `0001_01_01_000002_create_jobs_table.php` | Jobs, job batches, and failed jobs tables |
| 4 | `2026_01_07_010000_create_tenants_table.php` | Tenants table |
| 5 | `2026_01_07_010001_create_assets_table.php` | Assets table |
| 6 | `2026_01_07_010002_create_contracts_table.php` | Contracts table |
| 7 | `2026_01_07_010003_create_contract_assets_table.php` | Contract-Asset pivot table |
| 8 | `2026_01_07_010004_create_payments_table.php` | Payments table |
| 9 | `2026_02_03_100000_add_role_to_users_table.php` | Adds role column to users |
| 10 | `2026_02_20_021059_create_contract_workflows_table.php` | Contract workflows table |
| 11 | `2026_02_20_021105_create_workflow_evidence_table.php` | Workflow evidence uploads table |
| 12 | `2026_02_24_023848_create_invoices_table.php` | Invoices table |
| 13 | `2026_02_24_023925_create_invoice_assets_table.php` | Invoice-Asset pivot table |
| 14 | `2026_02_27_040000_create_amendments_table.php` | Contract amendments table |
| 15 | `2026_02_27_040001_create_amendment_assets_table.php` | Amendment-Asset pivot table |
| 16 | `2026_02_27_040002_add_amendment_id_to_payments_table.php` | Links payments to amendments |

</details>

### 6. Run database seeders

> **⚠️ Important:** Seeders **must** be run individually and in the exact order listed below, as each seeder depends on data from the previous one.

```bash
php artisan db:seed --class=TenantSeeder
php artisan db:seed --class=TestAsset
php artisan db:seed --class=TestContractSeeder
php artisan db:seed --class=ContractAsset
php artisan db:seed --class=PaymentSeeder
```

| Order | Seeder | Description |
|-------|--------|-------------|
| 1 | `TenantSeeder` | Seeds tenant records |
| 2 | `TestAsset` | Seeds asset/property records |
| 3 | `TestContractSeeder` | Seeds contracts linked to tenants |
| 4 | `ContractAsset` | Seeds the contract-asset relationships |
| 5 | `PaymentSeeder` | Seeds payment records for contracts |

### 7. Build frontend assets

```bash
npm run build
```

### 8. Start the development server

```bash
php artisan serve
npm run dev
```

Or use the composer shortcut to run everything concurrently:

```bash
composer dev
```

The application will be available at **http://localhost:8000**.

---

## 📁 Project Structure

```
monitoring/
├── app/
│   ├── Http/Controllers/    # Application controllers
│   └── Models/              # Eloquent models
├── database/
│   ├── migrations/final/    # ⭐ Production migration files
│   ├── seeders/             # Database seeders
│   └── factories/           # Model factories
├── resources/
│   └── views/               # Blade templates
│       ├── amendments/      # Amendment views
│       ├── assets/          # Asset views
│       ├── contracts/       # Contract views
│       ├── invoices/        # Invoice views
│       ├── layouts/         # Layout templates
│       ├── login/           # Auth views
│       ├── payments/        # Payment views
│       ├── tenants/         # Tenant views
│       └── dashboard.blade.php
├── routes/                  # Route definitions
├── public/                  # Public assets
└── config/                  # Configuration files
```

---

## 🗄️ Database Schema

```mermaid
erDiagram
    USERS ||--o{ CONTRACTS : manages
    TENANTS ||--o{ CONTRACTS : has
    CONTRACTS ||--o{ CONTRACT_ASSETS : contains
    ASSETS ||--o{ CONTRACT_ASSETS : linked_to
    CONTRACTS ||--o{ PAYMENTS : receives
    CONTRACTS ||--o{ CONTRACT_WORKFLOWS : tracks
    CONTRACT_WORKFLOWS ||--o{ WORKFLOW_EVIDENCE : uploads
    CONTRACTS ||--o{ INVOICES : generates
    INVOICES ||--o{ INVOICE_ASSETS : includes
    CONTRACTS ||--o{ AMENDMENTS : modified_by
    AMENDMENTS ||--o{ AMENDMENT_ASSETS : contains
    AMENDMENTS ||--o{ PAYMENTS : receives
```

---

## 👤 Author

**Evan** — Developer & Maintainer

---

## 📄 License

This project is licensed under the **MIT License**.

```
MIT License

Copyright (c) 2026 Evan

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
