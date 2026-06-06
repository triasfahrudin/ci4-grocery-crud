<?php

/**
 * Image CRUD for CodeIgniter 4
 *
 * A CodeIgniter 4 library that creates an instant photo gallery CRUD automatically
 * with just a few lines of code. Ported from the original CI3 library
 * by John Skoumbourdis (scoumbourdis).
 *
 * Original: https://github.com/scoumbourdis/image-crud
 *
 * LICENSE
 * Image CRUD is released with dual licensing, using the GPL v3 and the MIT license.
 *
 * @package     Image CRUD for CI4
 * @author      John Skoumbourdis <scoumbourdisj@gmail.com> (original CI3 version)
 * @link        https://github.com/scoumbourdis/image-crud
 * @license     https://github.com/scoumbourdis/image-crud/blob/master/license-image-crud.txt
 */

namespace App\Libraries;

use Config\Database;
use Config\Services;

class ImageCrud
{
    protected ?string $tableName = null;
    protected ?string $priorityField = null;
    protected string $urlField = 'url';
    protected ?string $titleField = null;
    protected ?string $relationField = null;
    protected string $subject = 'Record';
    protected string $imagePath = '';
    protected string $primaryKey = 'id';
    protected string $thumbnailPrefix = 'thumb__';
    protected string $viewsAsString = '';
    protected array $cssFiles = [];
    protected array $jsFiles = [];

    protected int $maxWidth = 1024;
    protected int $maxHeight = 768;

    /* Unsetters */
    protected bool $unsetDelete = false;
    protected bool $unsetUpload = false;

    protected ?string $language = null;
    protected array $langStrings = [];

    protected array $where = [];

    protected ?string $thumbnailPath = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        helper('url');
        helper('filesystem');

        $config = config('ImageCrud');
        if ($config) {
            $this->maxWidth  = $config->maxWidth ?? 1024;
            $this->maxHeight = $config->maxHeight ?? 768;
            if ($this->language === null) {
                $this->language = $config->defaultLanguage ?? 'english';
            }
        }
    }

    /**
     * Set the database table name.
     */
    public function setTable(string $tableName): self
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * Add a WHERE clause.
     */
    public function where($key, $value = null, bool $escape = true): self
    {
        $this->where[] = [$key, $value, $escape];
        return $this;
    }

    /**
     * Set relation field (foreign key).
     */
    public function setRelationField(string $fieldName): self
    {
        $this->relationField = $fieldName;
        return $this;
    }

    /**
     * Set ordering (priority) field.
     */
    public function setOrderingField(string $fieldName): self
    {
        $this->priorityField = $fieldName;
        return $this;
    }

    /**
     * Set primary key field name.
     */
    public function setPrimaryKeyField(string $fieldName): self
    {
        $this->primaryKey = $fieldName;
        return $this;
    }

    /**
     * Set subject (singular label).
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set the URL/image field name.
     */
    public function setUrlField(string $urlField): self
    {
        $this->urlField = $urlField;
        return $this;
    }

    /**
     * Set the title field name.
     */
    public function setTitleField(string $titleField): self
    {
        $this->titleField = $titleField;
        return $this;
    }

    /**
     * Set the image upload path (relative to FCPATH or absolute).
     */
    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    /**
     * Set maximum image width.
     */
    public function setMaxWidth(int $value): self
    {
        $this->maxWidth = $value;
        return $this;
    }

    /**
     * Set maximum image height.
     */
    public function setMaxHeight(int $value): self
    {
        $this->maxHeight = $value;
        return $this;
    }

    /**
     * Set thumbnail prefix.
     */
    public function setThumbnailPrefix(string $prefix): self
    {
        $this->thumbnailPrefix = $prefix;
        return $this;
    }

    /**
     * Unset the delete operation from the gallery.
     */
    public function unsetDelete(): self
    {
        $this->unsetDelete = true;
        return $this;
    }

    /**
     * Unset the upload functionality from the gallery.
     */
    public function unsetUpload(): self
    {
        $this->unsetUpload = true;
        return $this;
    }

    /**
     * Register a CSS file.
     */
    public function setCss(string $cssFile): self
    {
        $this->cssFiles[sha1($cssFile)] = base_url($cssFile);
        return $this;
    }

    /**
     * Register a JS file.
     */
    public function setJs(string $jsFile): self
    {
        $this->jsFiles[sha1($jsFile)] = base_url($jsFile);
        return $this;
    }

    /**
     * Set language.
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Get language string.
     */
    public function l(string $handle): string
    {
        return $this->getLangString($handle);
    }

    /**
     * Get language string by handle.
     */
    public function getLangString(string $handle): string
    {
        return $this->langStrings[$handle] ?? $handle;
    }

    /**
     * Get registered CSS files.
     */
    public function getCssFiles(): array
    {
        return $this->cssFiles;
    }

    /**
     * Get registered JS files.
     */
    public function getJsFiles(): array
    {
        return $this->jsFiles;
    }

    // ---------------------------------------------------------------
    //  Internal Methods
    // ---------------------------------------------------------------

    /**
     * Load language strings.
     */
    private function loadLanguage(): void
    {
        $langFile = ROOTPATH . 'public/assets/image_crud/languages/' . ($this->language ?? 'english') . '.php';
        if (is_file($langFile)) {
            $lang = include $langFile;
            if (is_array($lang)) {
                foreach ($lang as $handle => $string) {
                    if (!isset($this->langStrings[$handle])) {
                        $this->langStrings[$handle] = $string;
                    }
                }
            }
        }

        // Also try the app/Language fallback
        $langFile2 = APPPATH . 'Language/' . ($this->language ?? 'en') . '/ImageCrud.php';
        if (is_file($langFile2)) {
            $lang2 = include $langFile2;
            if (is_array($lang2)) {
                foreach ($lang2 as $handle => $string) {
                    if (!isset($this->langStrings[$handle])) {
                        $this->langStrings[$handle] = $string;
                    }
                }
            }
        }

        // Default English fallback
        if (empty($this->langStrings)) {
            $langFile3 = APPPATH . 'Language/en/ImageCrud.php';
            if (is_file($langFile3)) {
                $lang3 = include $langFile3;
                if (is_array($lang3)) {
                    $this->langStrings = $lang3;
                }
            }
        }
    }

    /**
     * Render a library view (captured to string).
     */
    private function libraryView(string $view, array $vars = []): string
    {
        $viewFile = ROOTPATH . 'public/assets/image_crud/views/' . $view . '.php';
        if (!is_file($viewFile)) {
            $viewFile = APPPATH . 'Views/image_crud/' . $view . '.php';
        }
        if (!is_file($viewFile)) {
            throw new \RuntimeException('Unable to load the requested view file: ' . $view);
        }

        extract($vars);
        ob_start();
        include $viewFile;
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }

    /**
     * Get the layout object with output and assets.
     */
    private function getLayout(): object
    {
        return (object) [
            'output'    => $this->viewsAsString,
            'jsFiles'   => $this->jsFiles,
            'cssFiles'  => $this->cssFiles,
        ];
    }

    /**
     * Upload a file.
     */
    private function uploadFile(string $uploadDir): string|false
    {
        $regExp = '/(\.|\\/)(gif|jpeg|jpg|png|webp)$/i';

        $uploadPath = $uploadDir . '/';

        $options = [
            'upload_dir'       => $uploadPath,
            'param_name'       => 'qqfile',
            'upload_url'       => base_url($uploadDir) . '/',
            'accept_file_types' => $regExp,
        ];

        $uploadHandler = new ImageUploadHandler($options);
        $uploaderResponse = $uploadHandler->post();

        if (is_array($uploaderResponse)) {
            foreach ($uploaderResponse as &$response) {
                unset($response->delete_url, $response->delete_type);
            }

            $uploadResponse = $uploaderResponse[0] ?? false;
        } else {
            $uploadResponse = false;
        }

        if (!empty($uploadResponse) && isset($uploadResponse->name)) {
            $filename = $uploadResponse->name;
            $path = $uploadDir . '/' . $filename;

            // Resize if larger than max dimensions
            if (is_file($path)) {
                [$width, $height] = getimagesize($path);
                if ($width > $this->maxWidth || $height > $this->maxHeight) {
                    $this->resizeImage($path, $this->maxWidth, $this->maxHeight);
                }
            }

            return $filename;
        }

        return false;
    }

    /**
     * Resize an image using GD.
     */
    private function resizeImage(string $filePath, int $maxW, int $maxH): void
    {
        [$origW, $origH, $type] = getimagesize($filePath);
        $ratio = min($maxW / $origW, $maxH / $origH);

        if ($ratio >= 1) {
            return;
        }

        $newW = (int) round($origW * $ratio);
        $newH = (int) round($origH * $ratio);

        $src = $this->imageCreateFrom($filePath, $type);
        if (!$src) {
            return;
        }

        $dst = imagecreatetruecolor($newW, $newH);
        if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_GIF], true)) {
            imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        $this->imageWrite($dst, $filePath, $type);
        imagedestroy($src);
        imagedestroy($dst);
    }

    /**
     * Create a GD image resource from file.
     */
    private function imageCreateFrom(string $filePath, int $type)
    {
        return match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($filePath),
            IMAGETYPE_PNG  => @imagecreatefrompng($filePath),
            IMAGETYPE_GIF  => @imagecreatefromgif($filePath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($filePath),
            default        => false,
        };
    }

    /**
     * Write a GD image to file.
     */
    private function imageWrite($image, string $filePath, int $type): void
    {
        match ($type) {
            IMAGETYPE_JPEG => imagejpeg($image, $filePath, 90),
            IMAGETYPE_PNG  => imagepng($image, $filePath, 9),
            IMAGETYPE_GIF  => imagegif($image, $filePath),
            IMAGETYPE_WEBP => imagewebp($image, $filePath, 90),
            default        => null,
        };
    }

    /**
     * Change priority ordering.
     */
    private function changePriority(array $postArray): void
    {
        $db = Database::connect();
        $counter = 1;
        foreach ($postArray as $photoId) {
            $db->table($this->tableName)
                ->where($this->primaryKey, $photoId)
                ->update([$this->priorityField => $counter]);
            $counter++;
        }
    }

    /**
     * Insert/update title.
     */
    private function insertTitle(string $primaryKey, string $value): void
    {
        $db = Database::connect();
        $db->table($this->tableName)
            ->where($this->primaryKey, $primaryKey)
            ->update([$this->titleField => $value]);
    }

    /**
     * Insert a file record into the database.
     */
    private function insertTable(string $fileName, ?string $relationId = null): void
    {
        $db = Database::connect();
        $insert = [$this->urlField => $fileName];
        if ($relationId !== null) {
            $insert[$this->relationField] = $relationId;
        }
        $db->table($this->tableName)->insert($insert);
    }

    /**
     * Delete a file (from disk and database).
     */
    private function deleteFile(string $id): void
    {
        $db = Database::connect();
        $row = $db->table($this->tableName)
            ->where($this->primaryKey, $id)
            ->get()
            ->getRow();

        if ($row && isset($row->{$this->urlField})) {
            $filename = $row->{$this->urlField};
            @unlink($this->imagePath . '/' . $filename);
            @unlink($this->imagePath . '/' . $this->thumbnailPrefix . $filename);
        }

        $db->table($this->tableName)
            ->where($this->primaryKey, $id)
            ->delete();
    }

    /**
     * Get the delete URL for an image.
     */
    private function getDeleteUrl(string $value): string
    {
        $stateInfo = $this->getState();
        $baseUrl = rtrim(site_url($this->getRouteBase()), '/');
        return $baseUrl . '/delete_file/' . $value;
    }

    /**
     * Convert foreign characters to ASCII.
     */
    private function convertForeignCharacters(string $str): string
    {
        static $translit = null;

        if ($translit === null) {
            $translitFile = ROOTPATH . 'public/assets/image_crud/config/translit_chars.php';
            if (is_file($translitFile)) {
                $translit = include $translitFile;
            } else {
                $translit = [];
            }
        }

        if (empty($translit)) {
            return $str;
        }

        return preg_replace(array_keys($translit), array_values($translit), $str);
    }

    /**
     * Create thumbnail using GD.
     */
    private function createThumbnail(string $sourcePath, string $thumbPath): void
    {
        if (!is_file($sourcePath)) {
            return;
        }

        [$origW, $origH, $type] = getimagesize($sourcePath);

        $thumbW = 90;
        $thumbH = 60;

        $src = $this->imageCreateFrom($sourcePath, $type);
        if (!$src) {
            return;
        }

        // Crop to center
        $srcRatio = $origW / $origH;
        $dstRatio = $thumbW / $thumbH;

        if ($srcRatio > $dstRatio) {
            $cropW = (int) round($origH * $dstRatio);
            $cropH = $origH;
            $srcX = (int) round(($origW - $cropW) / 2);
            $srcY = 0;
        } else {
            $cropW = $origW;
            $cropH = (int) round($origW / $dstRatio);
            $srcX = 0;
            $srcY = (int) round(($origH - $cropH) / 2);
        }

        $dst = imagecreatetruecolor($thumbW, $thumbH);
        if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_GIF], true)) {
            imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $thumbW, $thumbH, $cropW, $cropH);
        $this->imageWrite($dst, $thumbPath, $type);
        imagedestroy($src);
        imagedestroy($dst);
    }

    /**
     * Get the current route base (controller/method) from URI segments.
     */
    private function getRouteBase(): string
    {
        $request = service('request');
        $uri = $request->getUri();
        $segments = $uri->getSegments();

        if (empty($segments)) {
            return '';
        }

        return implode('/', $segments);
    }

    /**
     * Determine the current state from the request.
     */
    private function getState(): ?object
    {
        $request = service('request');
        $uri     = $request->getUri();
        $segments = $uri->getSegments();

        $routeBase = $this->getRouteBase();

        // Find action segments
        // Try to find the method in segments using router info
        try {
            $router = service('router');
            $methodName = $router->methodName();
        } catch (\Throwable) {
            $methodName = '';
        }

        $actionSegments = [];

        if (!empty($methodName)) {
            // Find method position in segments
            $methodIdx = false;
            foreach ($segments as $i => $seg) {
                if ($seg === $methodName) {
                    $methodIdx = $i;
                    break;
                }
            }

            if ($methodIdx !== false) {
                $actionSegments = array_slice($segments, $methodIdx + 1);
            } else {
                // Method not found in segments (e.g., default routing)
                $actionSegments = [];
            }
        } else {
            // No router info available, try to deduce
            // If last segment matches known actions, treat as action
            $last = !empty($segments) ? end($segments) : '';
            $knownActions = ['ajax_list', 'upload_file', 'delete_file', 'ordering', 'insert_title'];
            if (in_array($last, $knownActions, true)) {
                $actionSegments = $segments;
            }
        }

        // Clean empty segments
        $actionSegments = array_values(array_filter($actionSegments, fn($s) => $s !== ''));

        // Determine state based on action segments
        $firstAction = $actionSegments[0] ?? '';

        switch (true) {
            case ($firstAction === 'upload_file'):
                $file_name = '';
                // Sanitize filename from query param
                $qqFile = $request->getGet('qqfile') ?? $request->getPost('qqfile') ?? '';
                if (!empty($qqFile)) {
                    $oldFileName = $this->convertForeignCharacters($qqFile);
                    $newFileName = '';
                    $max = strlen($oldFileName);
                    for ($i = 0; $i < $max; $i++) {
                        if (preg_match('/^[A-Za-z0-9.\-_]+$/', $oldFileName[$i])) {
                            $newFileName .= strtolower($oldFileName[$i]);
                        } else {
                            $newFileName .= '-';
                        }
                    }
                    $file_name = substr(substr(uniqid(), 9, 13) . '-' . $newFileName, 0, 100);
                }

                $state = ['name' => 'upload_file', 'file_name' => $file_name];
                if (isset($actionSegments[1]) && is_numeric($actionSegments[1])) {
                    $state['relation_value'] = $actionSegments[1];
                }
                return (object) $state;

            case ($firstAction === 'delete_file' && isset($actionSegments[1]) && is_numeric($actionSegments[1])):
                return (object) ['name' => 'delete_file', 'id' => $actionSegments[1]];

            case ($firstAction === 'ordering'):
                return (object) ['name' => 'ordering'];

            case ($firstAction === 'insert_title'):
                return (object) ['name' => 'insert_title'];

            default:
                // List state
                $uploadUrl       = site_url($routeBase . '/upload_file');
                $ajaxListUrl     = site_url($routeBase . '/ajax_list');
                $orderingUrl     = site_url($routeBase . '/ordering');
                $insertTitleUrl  = site_url($routeBase . '/insert_title');

                $state = [
                    'name'              => 'list',
                    'upload_url'        => $uploadUrl,
                    'ajax_list_url'     => $ajaxListUrl,
                    'ordering_url'      => $orderingUrl,
                    'insert_title_url'  => $insertTitleUrl,
                ];

                // Check if there's a relation value (numeric segment before action)
                if ($firstAction !== '' && is_numeric($firstAction)) {
                    $state['relation_value'] = $firstAction;
                    // Rebuild AJAX URL with relation value
                    $state['ajax_list_url'] = site_url($routeBase . '/' . $firstAction . '/ajax_list');
                    $state['upload_url']    = site_url($routeBase . '/upload_file/' . $firstAction);
                }

                $state['ajax'] = $firstAction === 'ajax_list' || (isset($actionSegments[1]) && $actionSegments[1] === 'ajax_list');

                return (object) $state;
        }
    }

    /**
     * Get photos from the database.
     */
    private function getPhotos(?string $relationValue = null): array
    {
        $db = Database::connect();
        $builder = $db->table($this->tableName);

        if (!empty($this->priorityField)) {
            $builder->orderBy($this->priorityField);
        }

        foreach ($this->where as $w) {
            $builder->where($w[0], $w[1], $w[2] ?? true);
        }

        if ($relationValue !== null && !empty($this->relationField)) {
            $builder->where($this->relationField, $relationValue);
        }

        $results = $builder->get()->getResult();

        $thumbnailUrlPath = $this->thumbnailPath ?? $this->imagePath;

        $finalResults = [];
        foreach ($results as $num => $row) {
            $imageFilename = $row->{$this->urlField} ?? '';

            if (empty($imageFilename)) {
                continue;
            }

            // Create thumbnail if it doesn't exist
            $sourcePath = $this->imagePath . '/' . $imageFilename;
            $thumbPath  = $this->imagePath . '/' . $this->thumbnailPrefix . $imageFilename;

            if (is_file($sourcePath) && !is_file($thumbPath)) {
                $this->createThumbnail($sourcePath, $thumbPath);
            }

            $row->image_url     = base_url($this->imagePath . '/' . rawurlencode($imageFilename));
            $row->thumbnail_url = base_url($this->imagePath . '/' . rawurlencode($this->thumbnailPrefix . $imageFilename));
            $row->delete_url    = $this->getDeleteUrl($row->{$this->primaryKey});

            $finalResults[] = $row;
        }

        return $finalResults;
    }

    // ---------------------------------------------------------------
    //  Main Render Method
    // ---------------------------------------------------------------

    /**
     * Render the Image CRUD gallery.
     *
     * Call this from your controller method:
     *   return $crud->render();
     *
     * @return object|string|null Returns layout object for list view, or outputs directly for AJAX/actions.
     */
    public function render(): object|string|null
    {
        $this->loadLanguage();

        $stateInfo = $this->getState();

        if (empty($stateInfo)) {
            return null;
        }

        switch ($stateInfo->name) {
            case 'list':
                $photos = $stateInfo->relation_value ?? null;
                $photosData = $this->getPhotos($photos);

                $viewHtml = $this->libraryView('list', [
                    'upload_url'        => $stateInfo->upload_url,
                    'insert_title_url'  => $stateInfo->insert_title_url,
                    'photos'            => $photosData,
                    'ajax_list_url'     => $stateInfo->ajax_list_url,
                    'ordering_url'      => $stateInfo->ordering_url,
                    'primary_key'       => $this->primaryKey,
                    'title_field'       => $this->titleField,
                    'unset_delete'      => $this->unsetDelete,
                    'unset_upload'      => $this->unsetUpload,
                    'has_priority_field' => $this->priorityField !== null,
                    'crud'              => $this,
                ]);

                $this->viewsAsString .= $viewHtml;

                if (!empty($stateInfo->ajax)) {
                    // AJAX call - output only the gallery HTML
                    $response = $this->getLayout()->output;
                    echo $response;
                    exit;
                }

                return $this->getLayout();

            case 'upload_file':
                if ($this->unsetUpload) {
                    throw new \RuntimeException('This user is not allowed to do this operation');
                }

                $fileName = $this->uploadFile($this->imagePath);

                if ($fileName !== false) {
                    $thumbSrc  = $this->imagePath . '/' . $fileName;
                    $thumbDest = $this->imagePath . '/' . $this->thumbnailPrefix . $fileName;
                    if (is_file($thumbSrc)) {
                        $this->createThumbnail($thumbSrc, $thumbDest);
                    }
                    $this->insertTable($fileName, $stateInfo->relation_value ?? null);
                    $result = true;
                } else {
                    $result = false;
                }

                echo json_encode(['success' => $result]);
                exit;

            case 'delete_file':
                if ($this->unsetDelete) {
                    throw new \RuntimeException('This user is not allowed to do this operation');
                }

                $this->deleteFile($stateInfo->id);

                $referer = service('request')->getServer('HTTP_REFERER') ?? '/';
                header('Location: ' . $referer);
                exit;

            case 'ordering':
                $postData = service('request')->getPost('photos') ?? [];
                $this->changePriority($postData);
                exit;

            case 'insert_title':
                $pk   = service('request')->getPost('primary_key') ?? '';
                $val  = service('request')->getPost('value') ?? '';
                $this->insertTitle($pk, $val);
                exit;

            default:
                return null;
        }
    }
}

// ===============================================================
//  ImageUploadHandler (adapted from jQuery File Upload)
// ===============================================================

/**
 * Image Upload Handler for FineUploader compatibility.
 *
 * Based on the jQuery File Upload Plugin PHP Example 5.5
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 * Licensed under the MIT license
 */
class ImageUploadHandler
{
    private array $options;

    public function __construct(?array $options = null)
    {
        $this->options = [
            'script_url'        => $this->getFullUrl() . '/' . basename(__FILE__),
            'upload_dir'        => dirname(__FILE__) . '/files/',
            'upload_url'        => $this->getFullUrl() . '/files/',
            'param_name'        => 'files',
            'max_file_size'     => null,
            'min_file_size'     => 1,
            'accept_file_types' => '/.+$/i',
            'max_number_of_files' => null,
            'discard_aborted_uploads' => true,
            'orient_image'      => false,
            'image_versions'    => [],
        ];

        if (is_array($options)) {
            $this->options = array_merge($this->options, $options);
        }
    }

    private function getFullUrl(): string
    {
        $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $script = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        return ($https ? 'https://' : 'http://') . $host . substr($script, 0, strrpos($script, '/'));
    }

    private function getFileObject(string $file_name): ?\stdClass
    {
        $file_path = $this->options['upload_dir'] . $file_name;
        if (is_file($file_path) && $file_name[0] !== '.') {
            $file = new \stdClass();
            $file->name = $file_name;
            $file->size = filesize($file_path);
            $file->url  = $this->options['upload_url'] . rawurlencode($file->name);
            foreach ($this->options['image_versions'] as $version => $options) {
                if (is_file($options['upload_dir'] . $file_name)) {
                    $file->{$version . '_url'} = $options['upload_url'] . rawurlencode($file->name);
                }
            }
            $file->delete_url   = $this->options['script_url'] . '?file=' . rawurlencode($file->name);
            $file->delete_type  = 'DELETE';
            return $file;
        }
        return null;
    }

    private function getFileObjects(): array
    {
        $dir = $this->options['upload_dir'];
        if (!is_dir($dir)) {
            return [];
        }
        return array_values(array_filter(
            array_map([$this, 'getFileObject'], scandir($dir))
        ));
    }

    private function hasError($uploaded_file, \stdClass $file, ?string $error): ?string
    {
        if ($error) {
            return $error;
        }
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            return 'acceptFileTypes';
        }
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
            $file_size = filesize($uploaded_file);
        } else {
            $file_size = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
        }
        if ($this->options['max_file_size'] && (
            $file_size > $this->options['max_file_size'] ||
            $file->size > $this->options['max_file_size'])
        ) {
            return 'maxFileSize';
        }
        if ($this->options['min_file_size'] && $file_size < $this->options['min_file_size']) {
            return 'minFileSize';
        }
        if (is_int($this->options['max_number_of_files']) &&
            count($this->getFileObjects()) >= $this->options['max_number_of_files']) {
            return 'maxNumberOfFiles';
        }
        return null;
    }

    private function trimFileName(string $name, string $type): string
    {
        $file_name = trim(basename(stripslashes($name)), ".\x00..\x20");
        if (strpos($file_name, '.') === false &&
            preg_match('/^image\/(gif|jpe?g|png|webp)/', $type, $matches)) {
            $file_name .= '.' . $matches[1];
        }
        $file_name = substr(uniqid(), -5) . '-' . preg_replace("/([^a-zA-Z0-9.\-_]+?){1}/i", '-', $file_name);
        return $file_name;
    }

    private function orientImage(string $file_path): bool
    {
        $exif = @exif_read_data($file_path);
        $orientation = (int) ($exif['Orientation'] ?? 1);
        if (!in_array($orientation, [3, 6, 8], true)) {
            return false;
        }
        $image = @imagecreatefromjpeg($file_path);
        if (!$image) {
            return false;
        }
        $image = match ($orientation) {
            3 => @imagerotate($image, 180, 0),
            6 => @imagerotate($image, 270, 0),
            8 => @imagerotate($image, 90, 0),
            default => $image,
        };
        $success = imagejpeg($image, $file_path);
        @imagedestroy($image);
        return $success;
    }

    private function handleFileUpload($uploaded_file, string $name, int $size, string $type, ?string $error): \stdClass
    {
        $file = new \stdClass();
        $file->name = $this->trimFileName($name, $type);
        $file->size = $size;
        $file->type = $type;
        $error = $this->hasError($uploaded_file, $file, $error);

        if (!$error && $file->name) {
            $file_path = $this->options['upload_dir'] . $file->name;
            $append_file = !$this->options['discard_aborted_uploads'] &&
                is_file($file_path) && $file->size > filesize($file_path);
            clearstatcache();

            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                if ($append_file) {
                    file_put_contents($file_path, fopen($uploaded_file, 'r'), FILE_APPEND);
                } else {
                    move_uploaded_file($uploaded_file, $file_path);
                }
            } else {
                file_put_contents(
                    $file_path,
                    fopen('php://input', 'r'),
                    $append_file ? FILE_APPEND : 0
                );
            }

            $file_size = filesize($file_path);
            if ($file_size === $file->size) {
                if ($this->options['orient_image']) {
                    $this->orientImage($file_path);
                }
                $file->url = $this->options['upload_url'] . rawurlencode($file->name);
                foreach ($this->options['image_versions'] as $version => $options) {
                    $file->{$version . '_url'} = $options['upload_url'] . rawurlencode($file->name);
                }
            } elseif ($this->options['discard_aborted_uploads']) {
                unlink($file_path);
                $file->error = 'abort';
            }
            $file->size = $file_size;
            $file->delete_url  = $this->options['script_url'] . '?file=' . rawurlencode($file->name);
            $file->delete_type = 'DELETE';
        } else {
            $file->error = $error;
        }

        return $file;
    }

    public function get(): void
    {
        $file_name = isset($_REQUEST['file']) ? basename(stripslashes($_REQUEST['file'])) : null;
        $info = $file_name ? $this->getFileObject($file_name) : $this->getFileObjects();
        header('Content-type: application/json');
        echo json_encode($info);
    }

    public function post(): ?array
    {
        if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
            return $this->delete();
        }

        $upload = $_FILES[$this->options['param_name']] ?? null;
        $info = [];

        if ($upload && is_array($upload['tmp_name'])) {
            foreach ($upload['tmp_name'] as $index => $value) {
                $info[] = $this->handleFileUpload(
                    $upload['tmp_name'][$index],
                    $_SERVER['HTTP_X_FILE_NAME'] ?? $upload['name'][$index],
                    (int) ($_SERVER['HTTP_X_FILE_SIZE'] ?? $upload['size'][$index]),
                    $_SERVER['HTTP_X_FILE_TYPE'] ?? $upload['type'][$index],
                    $upload['error'][$index]
                );
            }
        } elseif ($upload || isset($_SERVER['HTTP_X_FILE_NAME'])) {
            $info[] = $this->handleFileUpload(
                $upload['tmp_name'] ?? null,
                $_SERVER['HTTP_X_FILE_NAME'] ?? $upload['name'],
                (int) ($_SERVER['HTTP_X_FILE_SIZE'] ?? $upload['size']),
                $_SERVER['HTTP_X_FILE_TYPE'] ?? $upload['type'],
                $upload['error'] ?? null
            );
        }

        header('Vary: Accept');

        $redirect = isset($_REQUEST['redirect']) ? stripslashes($_REQUEST['redirect']) : null;
        if ($redirect) {
            $json = json_encode($info);
            header('Location: ' . sprintf($redirect, rawurlencode($json)));
            return null;
        }

        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (str_contains($accept, 'application/json')) {
            header('Content-type: application/json');
        } else {
            header('Content-type: text/plain');
        }

        return $info;
    }

    public function delete(): array
    {
        $file_name = isset($_REQUEST['file']) ? basename(stripslashes($_REQUEST['file'])) : null;
        $file_path = $this->options['upload_dir'] . $file_name;
        $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
        if ($success) {
            foreach ($this->options['image_versions'] as $version => $options) {
                $file = $options['upload_dir'] . $file_name;
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        header('Content-type: application/json');
        echo json_encode($success);
        return [];
    }
}
