# CI4 Grocery CRUD Demo

Example project demonstrating [Grocery CRUD for CodeIgniter 4](https://github.com/triasfahrudin/grocery-crud).

## Cara Penggunaan

### 1. Buat project CI4 baru (jika belum punya)

```bash
composer create-project codeigniter4/appstarter project-name
cd project-name
```

### 2. Install Grocery CRUD

```bash
composer require triasfahrudin/grocery-crud
```

### 3. Copy file demo

Copy file berikut dari repo ini ke project CI4 Anda:

| File | Tujuan |
|------|--------|
| `app/Controllers/GroceryCrudDemo.php` | `app/Controllers/GroceryCrudDemo.php` |
| `app/Config/GroceryCrud.php` | `app/Config/GroceryCrud.php` |
| `app/Database/Migrations/001_create_sample_tables.php` | `app/Database/Migrations/001_create_sample_tables.php` |

### 4. Setup database

Edit `.env`:
```env
database.default.hostname = localhost
database.default.database = grocery_crud_demo
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

### 5. Run migration

```bash
php spark migrate -n App
```

### 6. Start server

```bash
php spark serve
```

Buka `http://localhost:8080/grocery-crud-demo`

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
