<?php

namespace App\Http\Controllers;

use App\Library\CacheLib;
use App\Library\FileLib;
use App\Library\SecurityLib;
use App\Library\StringLib;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class DevController extends Controller
{
    public function generateThumbnail()
    {
        try {
            // dump('START: public function generateThumbnail()');
            // Required
            $disk = request()->query('disk', '');
            // Optional
            $folder = request()->query('folder', '');
            // Required !!! This may contain slashes
            $filename = request()->query('filename', '');

            // Validate Request
            // dump('generateThumbnail() Validate Request...');
            StringLib::validateRegexOrAbort($disk, FileLib::DISK_VALID_REGEX);
            StringLib::validateRegexOrAbort($folder, FileLib::FOLDER_VALID_REGEX);
            StringLib::validateRegexOrAbort($filename, FileLib::FILENAME_VALID_REGEX);

            list($folder, $filename) = FileLib::cleanUniformFolderFilename($folder, $filename);
            // dump("generateThumbnail() folder: $folder, filename: $filename");
            $thumbFilePath = FileLib::generateThumb($disk, $folder, $filename);
            // dump("generateThumbnail() thumbFilePath: $thumbFilePath");

            $url = route('file-load', [
                $disk,
                'folder'    => $folder,
                'filename'  => $filename,
                'thumb'     => 1,
                // Chris D. 3-Mar-2024 - this is the only way I could get it to load the page (in Edge) and not load a cached version with 302 response.
                // I deleted history and still happened `:-o!
                'cachebust' => time(),
                'sec'       => SecurityLib::createToken($folder.$filename)]);

            // dump('END: public function generateThumbnail()');
            return Response::view('file_load/view_image', ['url' => $url, 'message' => 'Thumbnail generated: '.$thumbFilePath])
                           ->withHeaders(['Cache-Control', 'no-store, no-cache, must-revalidate']);
        }
        catch(\Throwable $ex) {
            Log::critical(sprintf('Unable to generate thumbnail for request: disk=%s | folder=%s | filename=%s',
                ($disk ?? ''), ($folder ?? ''), ($filename ?? '')));
            Log::critical($ex);
            return 'Exception ERROR: '.$ex->getMessage();
        }
    }

    public function cacheClear()
    {
        CacheLib::cacheClear();
    }

}
