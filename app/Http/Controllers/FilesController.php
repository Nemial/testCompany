<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midnite81\Xml2Array\Xml2Array;
use ZipArchive;

class FilesController extends Controller
{
    public function index(Request $request)
    {
            $url = $request->input('webSite');
            $parsed = parse_url($url);
            $conn_id = ftp_connect($parsed['host']);
            $path = $parsed['path'];
            $login = 'free';
            $password = 'free';
            ftp_login($conn_id, $login, $password);
            $fileFTPList = ftp_nlist($conn_id, $path);

            # Create work directory
            $tempDir = 'temp';
            $zipDir = implode('/', [$tempDir, 'zip']);
            $unZipDir = implode('/', [$tempDir, 'unzip']);
            if (!file_exists($tempDir)) {
                mkdir($tempDir);
            }
            if (!file_exists($zipDir)) {
                mkdir($zipDir);
            }
            if (!file_exists($unZipDir)) {
                mkdir($unZipDir);
            }

            # Download all files
            foreach ($fileFTPList as $serverFile) {
                $filename = pathinfo($serverFile, PATHINFO_BASENAME);
                $localFile = implode('/', [$zipDir, $filename]);
                ftp_get($conn_id, $localFile, $serverFile);
            }

            #Close ftp connection
            ftp_close($conn_id);

            #Unzip all files into unzip directory
            $zipFileList = array_filter(
                scandir($zipDir),
                function ($file) {
                    return pathinfo($file, PATHINFO_EXTENSION) === 'zip';
                }
            );
            $zip = new ZipArchive();
            foreach ($zipFileList as $zipFile) {
                $fullPathFile = implode('/', [$zipDir, $zipFile]);
                $zip->open($fullPathFile);
                $zip->extractTo($unZipDir);
                $zip->close();
            }

            #Add FilesController to files table
            $fileList = array_filter(
                scandir($unZipDir),
                function ($file) {
                    return pathinfo($file, PATHINFO_EXTENSION) === 'xml';
                }
            );

            foreach ($fileList as $fileXML) {
                $fullPathFile = implode('/', [$unZipDir, $fileXML]);
                $xmlString = file_get_contents($fullPathFile);
                $xml = Xml2Array::create($xmlString);
                $collection = $xml->toCollection();
                DB::table('files')->insert(['jsonData' => $collection->toJson()]);
            }

            #clear temp directory
            foreach (scandir($unZipDir) as $file) {
                $fullPathFile = implode('/', [$unZipDir, $file]);
                if (is_file($fullPathFile)) {
                    unlink($fullPathFile);
                }
            }
            rmdir($unZipDir);

            foreach (scandir($zipDir) as $file) {
                $fullPathFile = implode('/', [$zipDir, $file]);
                if (is_file($fullPathFile)) {
                    unlink($fullPathFile);
                }
            }
            rmdir($zipDir);
            rmdir($tempDir);
        $count = DB::table('files')->count();
        return view('files', compact('count'));
    }
}
