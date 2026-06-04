<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Grocery CRUD Configuration for CI4 Integration.
 *
 * Copy this file to app/Config/GroceryCrud.php and customize.
 */
class GroceryCrud extends BaseConfig
{
    /**
     * Default theme for rendering.
     */
    public string $defaultTheme = 'bootstrap5';

    /**
     * Items per page.
     */
    public int $perPage = 25;

    /**
     * Use Datatables for advanced listing.
     */
    public bool $useDatatables = true;

    /**
     * Enable export functionality.
     */
    public bool $enableExport = true;

    /**
     * Default language (english, indonesian).
     */
    public string $defaultLanguage = 'indonesian';

    /**
     * Default upload path (relative to public/ or writable/).
     */
    public string $uploadPath = 'writable/uploads/';

    /**
     * Maximum upload file size in KB.
     */
    public int $maxUploadSize = 2048;

    /**
     * Allowed file types for upload.
     */
    public string $allowedFileTypes = 'jpg|jpeg|png|gif|pdf|doc|docx|xlsx|csv';

    /**
     * Whether to encrypt uploaded filenames.
     */
    public bool $encryptFileName = true;
}
