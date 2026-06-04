# CI4 Grocery CRUD Demo

Example project demonstrating [Grocery CRUD for CodeIgniter 4](https://github.com/triasfahrudin/grocery-crud).

## Requirements

- PHP 8.2+
- MySQL/MariaDB
- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

## Quick Start

### 1. Clone & Install

```bash
git clone https://github.com/triasfahrudin/ci4-grocery-crud.git
cd ci4-grocery-crud
composer install
```

### 2. Setup Database

Copy `env` to `.env` dan sesuaikan konfigurasi database:

```env
# .env
database.default.hostname = localhost
database.default.database = ci4_grocery_crud
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

### 3. Run Migration

```bash
php spark migrate -n App
```

Migration akan membuat tables:
- `categories` — kategori produk (dengan ENUM status)
- `products` — data produk dengan relasi ke categories
- `tags` — tag/label produk (dengan color picker)
- `product_tags` — junction table untuk N-to-N relasi

### 4. Start Server

```bash
php spark serve
```

Buka **http://localhost:8080/grocery-crud-demo**

## Demo Pages

| URL | Deskripsi |
|-----|-----------|
| `/grocery-crud-demo` | Menu utama dengan daftar fitur |
| `/grocery-crud-demo/products` | Full CRUD: relations, upload, callbacks, NtoN tags |
| `/grocery-crud-demo/categories` | Simple CRUD: enum field, custom actions |
| `/grocery-crud-demo/tags` | Minimal CRUD: color picker, field type override |

## Fitur yang Didemonstrasikan

| Fitur | Products | Categories | Tags |
|-------|----------|------------|------|
| Basic CRUD | ✓ | ✓ | ✓ |
| Belongs_to (category) | ✓ | - | - |
| N-to-N (tags) | ✓ | - | - |
| Callbacks (timestamps) | ✓ | - | - |
| Column callbacks | ✓ | - | ✓ |
| Validation | ✓ | ✓ | - |
| File upload | ✓ | - | - |
| Custom actions | - | ✓ | ✓ |
| Export CSV/Excel | ✓ | ✓ | ✓ |
| Search | ✓ | ✓ | ✓ |
| Field type override | - | - | ✓ |
| Multi-language | ✓ | ✓ | ✓ |

## Struktur Database

```
categories  ──1:N──→  products  ←──N:N──→  tags
                           ↑
                     product_tags (junction)
```

## File Penting

| File | Fungsi |
|------|--------|
| `app/Controllers/GroceryCrudDemo.php` | Demo controller (index, products, categories, tags) |
| `app/Config/GroceryCrud.php` | Konfigurasi library (theme, upload, pagination) |
| `app/Database/Migrations/2026-06-04-221905_CreateSampleTables.php` | Migration + seed data |

## Repository Management

For bug reports and feature requests, use the [main repository](https://github.com/triasfahrudin/grocery-crud) issues.

This demo repository is part of the Grocery CRUD ecosystem for CodeIgniter 4.
