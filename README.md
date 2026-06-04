# CI4 Grocery CRUD Demo

Example project demonstrating [Grocery CRUD for CodeIgniter 4](https://github.com/triasfahrudin/grocery-crud).

## Setup

```bash
# Clone / copy project
cd ci4-grocery-crud

# Install dependencies
composer install

# Copy env
cp .env.example .env
# Edit .env with your database credentials

# Run migration
php spark migrate -n App

# Start server
php spark serve

# Open browser
# http://localhost:8080/grocery-crud-demo
```

## Demo Pages

| URL | Deskripsi |
|-----|-----------|
| `/grocery-crud-demo` | Menu utama |
| `/grocery-crud-demo/products` | Full CRUD dengan relations, upload, callbacks |
| `/grocery-crud-demo/categories` | Simple CRUD dengan enum & custom actions |
| `/grocery-crud-demo/tags` | Minimal CRUD dengan color picker |

## Struktur Database

```
categories  ──→  products  ←──  product_tags  ──→  tags
     (1:N)           (products : tags = N:N via junction)
```
