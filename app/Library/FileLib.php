<?php

namespace App\Library;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Image;

// use Intervention\Image\Drivers\Gd\Driver;
// Imagick uses less RAM but I can't find it for PHP 8.2 7-Mar-2024
// use Intervention\Image\Drivers\Imagick\Driver;

class FileLib
{
    /*
     * Thumbnail Settings
     */
    static int $thumbWidth = 112;  // 7  * 16 = 112

    const FILE_MAX_SIZE_BYTES            = 7168;
    const THUMB_SUPPORTED_EXTENSIONS     = ['png', 'jpg', 'jpeg', 'webp'];
    const THUMB_SUPPORTED_EXTENSIONS_CSV = 'png,jpg,jpeg,webp';
    const THUMB_PREFIX                   = 'th_';
    const THUMB_SUFFIX                   = '.webp';

    const DISK_VALID_REGEX   = '/^[a-zA-Z0-9]+$/'; // + one or more
    const FOLDER_VALID_REGEX = '/^[a-zA-Z0-9]*$/'; // * 0 or more
    // Allow filename to include folder name and directory separators
    const FILENAME_VALID_REGEX = '/^[a-zA-Z0-9\-_\\/]{1,128}\.[a-zA-Z]{3,4}$/';

    // Chris D. 3-Mar-2024  DEV NOTE:
    // I had some issues with explode() and \DIRECTORY_SEPARATOR, so I'm using the literal '/' in all OS

    /**
     * Get the full path for the file's thumbnail
     *
     * @param string $disk
     * @param string $folder
     * @param string $filename Support the filename containing a slash (\ or /)
     * @return string
     */
    static function getThumbPath(string $disk, string $folder, string $filename): string
    {
        list($folder, $filename) = static::cleanUniformFolderFilename($folder, $filename);
        return Storage::disk($disk)->path(static::pathPartial($folder, static::generateThumbFilename($filename)));
    }

    /**
     * Create a partial path using the given folder and filename
     * e.g. $folder = 'clients', $filename = 'abc.png'; // result: "clients/abc.png"
     * e.g. $folder = '', $filename = 'clients/abc.png'; // result: "clients/abc.png"
     * e.g. $folder = 'awesome', $filename = 'clients/abc.png'; // result: "clients/abc.png"
     * e.g. $folder = 'awesome', $filename = 'clients/abc.png'; // result: "awesome/clients/abc.png"
     */
    static function pathPartial(string $folder, string $filename): string
    {
        list($folder, $filename) = static::cleanUniformFolderFilename($folder, $filename);
        return ($folder ? rtrim($folder, '\\/').'/' : '').$filename;
    }

    /**
     * Replace slashes with '/'
     * Make $filename only contain a filename (in case $filename contains a slash).
     * Any folder/path portions found in $filename will be moved to the end of $folder.
     *
     * @param string $folder
     * @param string $filename Supports filename containing a slash (\ or /)
     * @return array [ $folder, $filename ]
     */
    static function cleanUniformFolderFilename(string $folder, string $filename): array
    {
        // clean, make uniform
        $filename = \str_replace('\\/', '/', $filename);
        $filenameParts = \explode('/', $filename);
        $folder = rtrim($folder, '\\/');

        // update filename & folder
        if(count($filenameParts)) {
            $filename = \array_pop($filenameParts);
            // add to folder
            if(count($filenameParts)) {
                $folder = ($folder !== '' ? $folder.'/' : '').\implode('/', $filenameParts);
            }
        }

        return [$folder, $filename];
    }

    /**
     * Return file extension from string filename. Does not include the '.'
     */
    public static function getFileExtension(string $fullPathToFile): string
    {
        return pathinfo($fullPathToFile, PATHINFO_EXTENSION);
    }

    /**
     * Generate a thumbnail for existing image in a storage disk.
     *
     * @param string $disk
     * @param string $folder
     * @param string $filename
     * @return string Full Thumb File Path
     */
    static function generateThumb(string $disk, string $folder, string $filename): string
    {
        Log::debug(__CLASS__." generateThumb(disk: $disk, folder: $folder, filename: $filename)");
        try {
            $fullFilePath = Storage::disk($disk)->path(FileLib::pathPartial($folder, $filename));
            if(!\file_exists($fullFilePath)) {
                throw new \Exception('File does not exist $fullFilePath: '.$fullFilePath);
            }
            $fullThumbFilePath = FileLib::getThumbPath($disk, $folder, $filename);
            $ext = \strtolower(FileLib::getFileExtension($fullFilePath));
            // Check extension
            if(\in_array($ext, static::THUMB_SUPPORTED_EXTENSIONS)) {
                \ini_set('memory_limit', '512M');
                // Generate thumb from image
                // https://image.intervention.io/
                // create image manager with desired driver
                $manager = new ImageManager(new Driver());
                // read image from file system
                Log::debug(" - Read file fullFilePath: $fullFilePath");
                $image = $manager->read($fullFilePath);
                Log::debug(' - $image type: '.\gettype($image));
                Log::debug(' - $image width: '.$image->width());
                Log::debug(' - $image height: '.$image->height());
                // read all exif info
                $exif = $image->exif();
                Log::debug(' - $image EXIF: '.json_encode($exif));
                // resize image proportionally to width
                $image->scale(width: static::$thumbWidth);
                // save modified image in new format
                Log::debug(" - fullThumbFilePath: $fullThumbFilePath");
                $image->toWebp()->save($fullThumbFilePath);
            }
            else {
                // Ext Not supported
                // - generate a simple thumb placeholder
                $string = substr(\strtoupper($ext), 0, 5); // limit max chars
                $im = imagecreate(100, 120);
                $color = imagecolorallocate($im, 220, 220, 220);
                imagefill($im, 0, 0, $color);
                $px = (imagesx($im) - 7.5 * strlen($string)) / 2;
                $color = imagecolorallocate($im, 22, 22, 22);
                imagestring($im, 4, $px, 50, $string, $color);
                // output to file
                imagepng($im, $fullThumbFilePath, 3, \PNG_FILTER_AVG);
                // free RAM
                imagedestroy($im);
            }
        }
        catch(\Throwable $ex) {
            Log::error(__CLASS__.": Failed to generateThumb(disk: $disk, folder: $folder, filename: $filename)");
            Log::error($ex);
            return '';
        }
        return $fullThumbFilePath;
    }

    static function generateThumbFilename(string $filename): string
    {
        return self::THUMB_PREFIX.$filename.self::THUMB_SUFFIX;
    }

    static function fileSizeK(string $disk, string $folder, string $filename): float|null
    {
        list($folder, $filename) = static::cleanUniformFolderFilename($folder, $filename);
        $thFilename = static::generateThumbFilename($filename);
        $relativePath = $folder.'/'.$thFilename;
        if(Storage::disk($disk)->exists($relativePath)) {
            return round(Storage::disk($disk)->fileSize($relativePath) / 1000, 2);
        }
        return null;
    }

    static function loadFileUrl(string $disk, string $folder, string $filename, int $thumb = 0, bool $cacheBust = false): string
    {
        list($folder, $filename) = FileLib::cleanUniformFolderFilename($folder, $filename);
        $r =
            [
                $disk,
                'folder'    => $folder,
                'filename'  => $filename,
                'thumb'     => $thumb,
                'cacheBust' => ($cacheBust ? time() : null),
                'sec'       => SecurityLib::createToken($folder.$filename)
            ];
        return route('file-load', $r);
    }

    /**
     * Replace any unwanted characters in the filename.
     *
     * @param $filename
     * @return string
     * @throws \Exception
     */
    public static function cleanFilename($filename)
    {
        if($filename == '') {
            return $filename;
        }
        // param check
        if(!is_string($filename)) {
            throw new \Exception('Filename needs to be a string.');
        }
        // not too long!
        if(strlen($filename) > 255) {
            $filename = substr($filename, 0, 255);
        }
        // clean unwanted characters
        $filename = preg_replace('/[^\\.A-Za-z0-9_-]/', '-', $filename, -1); // -1 = no limit default
        do {
            $filenameLen = strlen($filename);
            $filename = str_replace('..', '.', $filename);
            // Repeat until no changes are made
        } while($filenameLen != strlen($filename));

        return $filename;
    }

    /**
     * @param string $text
     * @param string $ext
     * @return string Eg. cabd6d547dcfeb0e9c29660ed4b23e3dc3e44148ddf87f431399035f99dc1146.png
     */
    static function generateSecureFileName(string $text, string $ext = ''): string
    {
        return hash('sha256', $text.time().rand(999999, 999999999).rand(999999, 999999999)).($ext===''?'':'.'.$ext);
    }

}
