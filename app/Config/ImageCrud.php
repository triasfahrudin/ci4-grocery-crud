<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Image CRUD Configuration
 *
 * Configuration for the Image CRUD library.
 * For language translations, see app/Language/en/ImageCrud.php
 */
class ImageCrud extends BaseConfig
{
    /**
     * Default language for Image CRUD UI strings.
     * Available languages are in public/assets/image_crud/languages/
     *
     * @var string
     */
    public string $defaultLanguage = 'english';

    /**
     * Maximum upload width in pixels.
     * Images wider than this will be resized.
     *
     * @var int
     */
    public int $maxWidth = 1024;

    /**
     * Maximum upload height in pixels.
     * Images taller than this will be resized.
     *
     * @var int
     */
    public int $maxHeight = 768;
}
