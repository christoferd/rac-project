<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\FileLib;
use App\Library\SecurityLib;
use App\Library\StringLib;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileLoadController extends Controller
{
    public function loadFile(string $disk): StreamedResponse
    {
        $folder = request()->query('folder', '');
        $filename = request()->query('filename', '');
        $thumb = (request()->query('thumb', false) == '1');
        $securityToken = request()->query('sec', '');
        SecurityLib::checkToken($folder.$filename, $securityToken);

        // Validate Request
        StringLib::validateRegexOrAbort($disk, FileLib::DISK_VALID_REGEX);
        StringLib::validateRegexOrAbort($folder, FileLib::FOLDER_VALID_REGEX);
        StringLib::validateRegexOrAbort($filename, FileLib::FILENAME_VALID_REGEX);

        Log::debug(__CLASS__." loadFile(disk: $disk, folder: $folder, filename: $filename, thumb: $thumb): StreamedResponse");

        // Relative path to storage/$disk
        list($folder, $filename) = FileLib::cleanUniformFolderFilename($folder, $filename);
        if($thumb) {
            $filename = FileLib::generateThumbFilename($filename);
        }

        $filePath = $folder.'/'.$filename;
        if(!Storage::disk($disk)->exists($filePath)) {
            \abort(404, 'File not found.');
        }

        // Header Info
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control
        // max-age seconds (15552000 = 6 months)
        $maxAge = 15552000;
        $filenameSimple = \str_replace(['/', '\\'], '_', $filePath);
        $fileSize = Storage::disk($disk)->fileSize($filePath);

        $headers = array(
            // 3-Mar-2024 This Content-Type currently works for images jpg/jpeg/png/webp
            'Content-Type'        => 'image/'.FileLib::getFileExtension($filename),
            'Expires'             => gmdate('D, d M Y H:i:s \G\M\T', (time() + $maxAge)),
            'Cache-Control'       => 'private, max-age='.$maxAge,
            // 'Cache-Control'             => 'public', // , must-revalidate, post-check=0, pre-check=0',
            'Content-Length'      => $fileSize,
            // The first parameter in the HTTP context is either inline
            // (default value, indicating it can be displayed inside the Web page, or as the Web page)
            'Content-Disposition' => 'inline; filename="'.$filenameSimple.'"',
        );

        return Storage::disk($disk)->download($filePath, $filenameSimple, $headers);
    }

    public function viewImage(string $disk): View
    {
        $folder = request()->query('folder', '');
        $filename = request()->query('filename', '');
        $securityToken = request()->query('sec', '');
        SecurityLib::checkToken($folder.$filename, $securityToken);

        // Validate params
        StringLib::validateRegexOrAbort($disk, FileLib::DISK_VALID_REGEX);
        StringLib::validateRegexOrAbort($folder, FileLib::FOLDER_VALID_REGEX);
        StringLib::validateRegexOrAbort($filename, FileLib::FILENAME_VALID_REGEX);

        $url = route('file-load', [
            $disk,
            'folder'   => $folder,
            'filename' => $filename,
            'sec'      => SecurityLib::createToken($folder.$filename)]);

        Log::debug("viewImage Disk: $disk, folder: $folder, filename: $filename, Url: $url");

        return view('file_load/view_image', ['url' => $url]);
    }

    public function inline(Request $request, string $mediaUUID, string $securityToken)
    {
        SecurityLib::checkToken(md5($mediaUUID), $securityToken);
        $media = Media::findByUuid($mediaUUID);
        return $media->toInlineResponse($request);
    }
}
