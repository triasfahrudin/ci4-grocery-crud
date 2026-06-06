# CI4 Grocery CRUD & Image CRUD Demo

Example project demonstrating [Grocery CRUD for CodeIgniter 4](https://github.com/triasfahrudin/grocery-crud)
and **Image CRUD** — an instant photo gallery CRUD for CI4.

---

## Daftar Isi

- [Grocery CRUD](#grocery-crud)
  - [Requirements](#requirements)
  - [Quick Start](#quick-start)
  - [Demo Pages](#grocery-crud-demo-pages)
  - [Fitur](#grocery-crud-fitur)
  - [Struktur Database (Grocery CRUD)](#struktur-database-grocery-crud)
  - [File Penting (Grocery CRUD)](#file-penting-grocery-crud)
- [Image CRUD](#image-crud)
  - [Apa itu Image CRUD?](#apa-itu-image-crud)
  - [Setup](#image-crud-setup)
  - [Panduan Singkat](#panduan-singkat)
  - [Demo Pages](#image-crud-demo-pages)
  - [Fitur](#image-crud-fitur)
  - [Method Reference](#method-reference)
  - [Cara Kerja Relation](#cara-kerja-relation)
  - [Struktur Database (Image CRUD)](#struktur-database-image-crud)
  - [File Structure](#file-structure-image-crud)
  - [State & AJAX Flow](#state--ajax-flow)

---

# Grocery CRUD

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
- `example_1` — simple image gallery
- `example_2` — gallery with ordering
- `example_3` — gallery with relation + ordering
- `example_4` — gallery with title + ordering

### 4. Start Server

```bash
php spark serve
```

Buka **http://localhost:8080**

---

## Grocery CRUD Demo Pages

| URL | Deskripsi |
|-----|-----------|
| `/grocery-crud-demo` | Menu utama dengan daftar fitur |
| `/grocery-crud-demo/products` | Full CRUD: relations, upload, callbacks, NtoN tags |
| `/grocery-crud-demo/categories` | Simple CRUD: enum field, custom actions |
| `/grocery-crud-demo/tags` | Minimal CRUD: color picker, field type override |

## Grocery CRUD Fitur

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

## Struktur Database (Grocery CRUD)

```
categories  ──1:N──→  products  ←──N:N──→  tags
                            ↑
                      product_tags (junction)
```

## File Penting (Grocery CRUD)

| File | Fungsi |
|------|--------|
| `app/Controllers/GroceryCrudDemo.php` | Demo controller (index, products, categories, tags) |
| `app/Config/GroceryCrud.php` | Konfigurasi library (theme, upload, pagination) |
| `app/Database/Migrations/2026-06-04-221905_CreateSampleTables.php` | Migration + seed data |

---

# Image CRUD

## Apa itu Image CRUD?

Image CRUD adalah library untuk CodeIgniter 4 yang membuat **photo gallery CRUD instan**
hanya dengan beberapa baris kode. Porting dari library [scoumbourdis/image-crud](https://github.com/scoumbourdis/image-crud) untuk CI3.

Cukup set tabel database, folder upload, dan field URL — library secara otomatis menangani:

- Upload file (drag & drop atau klik, via FineUploader)
- Resize otomatis (max 1024×768, configurable)
- Generate thumbnail otomatis (90×60)
- Tampilan galeri dengan lightbox (ColorBox)
- Delete image
- Drag-and-drop ordering (jQuery UI Sortable)
- Inline title editing
- Filter by relation (foreign key)
- Multi-language (English, Indonesian)

## Image CRUD Setup

Image CRUD menggunakan migration terpisah. Jika sudah menjalankan `php spark migrate -n App`
maka 4 tabel contoh sudah dibuat:

| Table | Kolom | Demo |
|-------|-------|------|
| `example_1` | id, url | Simple gallery |
| `example_2` | id, url, priority | Gallery + ordering |
| `example_3` | id, url, category_id, priority | Gallery + relation + ordering |
| `example_4` | id, title, url, priority | Gallery + title + ordering |

## Panduan Singkat

Di controller kamu:

```php
use App\Libraries\ImageCrud;

public function gallery()
{
    $crud = new ImageCrud();
    $crud->setTable('photos')
         ->setUrlField('url')
         ->setImagePath(FCPATH . 'uploads')
         ->setTitleField('title')
         ->setOrderingField('priority');

    $output = $crud->render();
    if (is_string($output)) {
        return $output;
    }

    return $this->buildPage($output);
}
```

`render()` mengembalikan object dengan properti `output`, `cssFiles`, dan `jsFiles`.
Kamu perlu membungkusnya dalam layout HTML (contoh: method `buildPage()` di demo controller).

Untuk AJAX/upload state, `render()` langsung `echo` output dan `exit` — bagian ini
sudah ditangani otomatis oleh library.

> **Catatan:** library membutuhkan jQuery 1.8+, jQuery UI 1.9+ (untuk sortable),
> serta file JS FineUploader dan ColorBox. Sertakan dependensi ini di layout kamu.

### Konfigurasi

Buat `app/Config/ImageCrud.php` (sudah tersedia di project ini):

```php
<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class ImageCrud extends BaseConfig
{
    public int $maxWidth = 1024;
    public int $maxHeight = 768;
    public string $defaultLanguage = 'english';
}
```

## Image CRUD Demo Pages

| URL | Table | Fitur | Deskripsi |
|-----|-------|-------|-----------|
| `/image-crud-demo` | — | — | Menu utama Image CRUD |
| `/image-crud-demo/simple` | `example_1` | Upload, delete, lightbox | Galeri dasar tanpa fitur tambahan |
| `/image-crud-demo/ordering` | `example_2` | + Drag ordering | Galeri dengan prioritas, bisa diurutkan via drag & drop |
| `/image-crud-demo/relation` | `example_3` | + Relation filter | Galeri yang bisa difilter berdasarkan kategori (foreign key) |
| `/image-crud-demo/title` | `example_4` | + Editable title | Galeri dengan judul yang bisa diedit langsung (inline edit) |

### Relation — URL Parameters

Kunjungi `/image-crud-demo/relation` untuk melihat semua gambar.
Untuk filter berdasarkan kategori, tambahkan angka di URL:

| URL | Tampilan |
|-----|----------|
| `/image-crud-demo/relation` | Semua gambar (tanpa filter) |
| `/image-crud-demo/relation/1` | Hanya gambar dengan `category_id = 1` |
| `/image-crud-demo/relation/2` | Hanya gambar dengan `category_id = 2` |

## Image CRUD Fitur

| Fitur | simple | ordering | relation | title |
|-------|--------|----------|----------|-------|
| Upload file | ✓ | ✓ | ✓ | ✓ |
| Delete image | ✓ | ✓ | ✓ | ✓ |
| Lightbox (ColorBox) | ✓ | ✓ | ✓ | ✓ |
| Drag ordering | - | ✓ | ✓ | ✓ |
| Filter by relation | - | - | ✓ | - |
| Editable title | - | - | - | ✓ |
| Thumbnail otomatis | ✓ | ✓ | ✓ | ✓ |
| Resize otomatis | ✓ | ✓ | ✓ | ✓ |
| Multi-language | ✓ | ✓ | ✓ | ✓ |

## Method Reference

### Config Methods (Fluent API — semua mengembalikan `self`)

| Method | Parameter | Default | Deskripsi |
|--------|-----------|---------|-----------|
| `setTable(string $tableName)` | Nama tabel database | - | **Wajib.** Tabel untuk menyimpan data gambar |
| `setUrlField(string $urlField)` | Nama kolom filename | `'url'` | Kolom yang menyimpan nama file gambar |
| `setImagePath(string $imagePath)` | Path absolut/relatif | - | **Wajib.** Folder untuk menyimpan file upload |
| `setOrderingField(string $fieldName)` | Nama kolom urutan | - | Kolom integer untuk drag ordering |
| `setRelationField(string $fieldName)` | Nama kolom foreign key | - | Kolom integer untuk filter kategori/relasi |
| `setTitleField(string $titleField)` | Nama kolom judul | - | Kolom untuk inline title editing |
| `setSubject(string $subject)` | Label singular | `'Record'` | Subjek untuk pesan sukses/error |
| `setPrimaryKeyField(string $fieldName)` | Nama kolom primary key | `'id'` | Primary key tabel |
| `setMaxWidth(int $value)` | Pixel | `1024` | Lebar maksimum gambar (resize otomatis) |
| `setMaxHeight(int $value)` | Pixel | `768` | Tinggi maksimum gambar (resize otomatis) |
| `setThumbnailPrefix(string $prefix)` | Prefix string | `'thumb__'` | Prefix untuk file thumbnail |
| `setLanguage(string $language)` | `'english'` / `'indonesian'` | dari Config | Bahasa antarmuka |
| `setCss(string $cssFile)` | Path ke CSS | - | Register CSS asset |
| `setJs(string $jsFile)` | Path ke JS | - | Register JS asset |
| `where($key, $value, bool $escape)` | Key, value, escape | - | Tambah WHERE clause |
| `unsetDelete()` | — | - | Sembunyikan tombol delete |
| `unsetUpload()` | — | - | Sembunyikan uploader |

### Render Method

| Method | Return | Deskripsi |
|--------|--------|-----------|
| `render()` | `object\|string\|null` | Main entry point. Untuk state `list` mengembalikan object layout. Untuk AJAX state langsung output dan exit |

**Return object `render()` untuk state list:**

```php
{
    output: string,    // HTML galeri
    cssFiles: array,   // Daftar URL CSS
    jsFiles: array,    // Daftar URL JS
}
```

### Helper Method

| Method | Return | Deskripsi |
|--------|--------|-----------|
| `l(string $handle)` | `string` | Ambil language string berdasarkan key |

## Cara Kerja Relation

Image CRUD mendeteksi **relation value** dari URL secara otomatis. Kamu tidak perlu
memparsing URL atau memfilter data secara manual — library menanganinya.

### Alur

1. **Controller panggil `setRelationField('category_id')`**
   — memberitahu library kolom foreign key mana yang digunakan.

2. **Library deteksi segmen numerik di URL**
   — method `getState()` memeriksa URI segments. Jika ada segmen numerik setelah
     method name, segmen tersebut dianggap sebagai relation value.

   Contoh URL: `/image-crud-demo/relation/2/ajax_list`
   - Segment setelah method `relation`: `['2', 'ajax_list']`
   - Segmen pertama (`'2'`) adalah numerik → `$relationValue = 2`
   - Segmen kedua (`'ajax_list'`) adalah action → AJAX refresh

3. **Filter query otomatis**
   — `getPhotos(2)` menambahkan `WHERE category_id = 2` ke query database.

4. **Upload dengan relation**
   — Saat upload melalui `/image-crud-demo/relation/upload_file/2`,
     file baru otomatis diberi `category_id = 2` oleh `insertTable()`.

### Tanpa filter

Kunjungi `/image-crud-demo/relation` (tanpa angka) — library tidak menerapkan
WHERE clause, semua gambar tampil.

## Struktur Database (Image CRUD)

```sql
-- Simple gallery
example_1 (
    id  INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(250)
);

-- With ordering
example_2 (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    url      VARCHAR(250),
    priority INT              -- urutan tampilan
);

-- With relation + ordering
example_3 (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    url         VARCHAR(250),
    category_id INT,          -- foreign key ke kategori
    priority    INT
);

-- With title + ordering
example_4 (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    title    VARCHAR(250),    -- judul gambar (editable)
    url      VARCHAR(250),
    priority INT
);
```

Migration: `app/Database/Migrations/2026-06-06-180000_CreateImageCrudTables.php`

## File Structure (Image CRUD)

```
app/
├── Config/
│   └── ImageCrud.php              # Konfigurasi (maxWidth, maxHeight, language)
├── Controllers/
│   └── ImageCrudDemo.php          # Demo controller (4 method demo)
├── Database/Migrations/
│   └── 2026-06-06-180000_CreateImageCrudTables.php  # Migration 4 tabel
├── Language/en/
│   └── ImageCrud.php              # English language strings
├── Libraries/
│   └── ImageCrud.php              # Main library (~1179 baris)
└── Views/image_crud/
    └── list.php                   # Gallery view template

public/assets/image_crud/
├── config/
│   └── translit_chars.php         # Transliterasi karakter asing
├── css/
│   ├── colorbox.css               # Lightbox styles
│   ├── fineuploader.css           # Uploader styles
│   └── photogallery.css           # Gallery styles
├── images/colorbox/               # Lightbox image assets
├── js/
│   ├── jquery.colorbox-min.js     # ColorBox v1.3.19
│   └── jquery.fineuploader-3.5.0.min.js  # FineUploader v3.5
└── languages/
    ├── english.php                # English UI strings (assets)
    └── indonesian.php             # Indonesian UI strings (assets)
```

## State & AJAX Flow

Library menggunakan state machine berbasis URL:

```
URL: /image-crud-demo/simple
State: list → render full page

URL: /image-crud-demo/simple/ajax_list
State: list + ajax → render only gallery HTML (AJAX response)

URL: POST /image-crud-demo/simple/upload_file
State: upload_file → handle upload, simpan file, insert DB, response JSON

URL: /image-crud-demo/simple/delete_file/5
State: delete_file → hapus file dari disk & DB, AJAX refresh

URL: /image-crud-demo/simple/ordering
State: ordering → update kolom priority, AJAX refresh

URL: POST /image-crud-demo/simple/insert_title
State: insert_title → update kolom title, AJAX refresh
```

State dideteksi oleh method `getState()` yang memeriksa URI segments.
Router method name digunakan untuk memisahkan base route dari action segments.

---

## Repository Management

For bug reports and feature requests, use the [main repository](https://github.com/triasfahrudin/grocery-crud) issues.

This demo repository is part of the **Grocery CRUD & Image CRUD ecosystem** for CodeIgniter 4.
