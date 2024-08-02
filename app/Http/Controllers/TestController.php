<?php

namespace App\Http\Controllers;

use App\Events\FileUploadedEvent;
use App\Library\FileLib;
use App\Library\SecurityLib;
use App\Library\SessionAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Storage;

class TestController
{
    // Url: /test-session-alerts
    public function testSessionAlerts()
    {
        SessionAlert::info('This is an info Session Alert.');
        SessionAlert::error('This is an error Session Alert.');
        SessionAlert::success('This is an success Session Alert.');
        SessionAlert::warning('This is an warning Session Alert.');

        return view('blank', ['pageTitle' => __FUNCTION__]);
    }

    // Url: /test-logs
    public function testLogs()
    {
        $message = 'Test Logs '.time();
        $messages = [
            'Message: '.$message,
            'Write Log info',
            'Write Log error',
            'Write Log debug',
        ];

        Log::info($message);
        Log::error($message);
        Log::debug($message);

        return view('blank', ['pageTitle' => __FUNCTION__, 'messages' => $messages]);
    }

    // Url: /test-save-file/admin/test
    public function testSaveFile(string $disk, string $path = '')
    {
        $sourceFilePath = \public_path('assets/test-photo-1024x768.jpg');
        if(!\file_exists($sourceFilePath)) {
            throw new \Exception('Source file not found at: '.$sourceFilePath);
        }

        if(!\file_exists($sourceFilePath)) {
            throw new \Exception('The file does not exist - cannot load file: '.$sourceFilePath);
        }

        // Returns: "test/7Y3GBujOFvRWlijgVuGJWByCbEn4Mjd2gvSeOO31.png"
        // !!! NOTE: it returns the filename, PREFIXED! with the folder or relative path it is stored in!!!
        $newFileRelativePathToDisk = Storage::disk($disk)
                                            ->putFile($path, $sourceFilePath);

        if(empty($newFileRelativePathToDisk)) {
            throw new \Exception("Failed to save file to disk: $disk, path: $path");
        }

        // Event
        FileUploadedEvent::dispatch($disk, '', $newFileRelativePathToDisk);

        $url = route('file-load', [
            'admin',
            'filename' => $newFileRelativePathToDisk,
            'sec'      => SecurityLib::createToken($newFileRelativePathToDisk)]);

        dump("testSaveFile__ disk: $disk, path: $path");
        dump("sourceFilePath: $sourceFilePath");
        dump("newFileRelativePathToDisk: $newFileRelativePathToDisk");
        dump("file exists?: ".(Storage::disk($disk)->exists($newFileRelativePathToDisk) ? 'Y' : 'N'));
        dump("url to view new file: $url");

        // $pageTitle = __FUNCTION__.": $disk / $path";
        return view('file_load.view_image', ['url' => $url]);
    }

    function createImage()
    {
        header("Content-type: image/png");
        $string = 'PDF';
        $im = imagecreate(100, 120);
        $color = imagecolorallocate($im, 220, 220, 220);
        imagefill($im, 0, 0, $color);
        $px = (imagesx($im) - 7.5 * strlen($string)) / 2;
        $color = imagecolorallocate($im, 22, 22, 22);
        imagestring($im, 4, $px, 50, $string, $color);
        imagepng($im); // output to screen
        imagedestroy($im);
        exit();
    }

    // /test-hammerjs-image-mobile
    public function testHammerJsImageMobile()
    {
        return view('test.test-hammerjs-image-mobile', ['pageTitle' => 'aaaaaaa']);
    }

    public function scrolling()
    {
        return view('test.scrolling');
    }
}
