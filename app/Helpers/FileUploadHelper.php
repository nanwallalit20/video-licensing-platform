<?php

namespace App\Helpers;

use Aws\CloudFront\CloudFrontClient;
use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use stdClass;

class FileUploadHelper {
    const DEFAULT_DIRECTORY = 'temp';
    const TRANSCODE_DIRECTORY = 'transcode';


    /**
     * @return bool
     */
    public static function isDiskS3(): bool {
        return config('filesystems.default') == 's3';
    }

    public static function cloudFrontSignedUrl($resourcePath, $cacheTime) {

        $distributionDomain = env('AWS_CLOUDFRONT_URL'); // Replace with your CloudFront distribution domain
        $keyPairId = env('AWS_CLOUDFRONT_KEY_PAIR_ID');
        $privateKeyPath = file_get_contents(public_path('key/rsa.pem'));

        $cloudfrontClient = new CloudFrontClient([
            'version' => 'latest',
            'region' => 'us-east-1', // Adjust as needed
        ]);


        $resourceUrl = rtrim($distributionDomain, '/') . '/' . ltrim($resourcePath, '/'); // Replace with your CloudFront URL

        return $cloudfrontClient->getSignedUrl([
            'url' => $resourceUrl,
            'expires' => $cacheTime,
            'key_pair_id' => $keyPairId, // Replace with your key pair ID
            'private_key' => $privateKeyPath // Path to your private key
        ]);
    }

    /**
     * @param string $fileName
     * @param string|null $path
     * @return stdClass|null
     */
    public static function filePathUrl(string $fileName, string $path = null): object|null {
        if (!$fileName) {
            return null;
        }

        /**
         * Caching
         * Create Cache Key
         */
        $cacheKey = str_replace('/', '_', $path) . '_' . str_replace('.', '_', $fileName);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $path = $path ? trim($path, '/') : self::DEFAULT_DIRECTORY;
        $transCodeFilePart = explode('.', $fileName);
        if (count($transCodeFilePart) <= 1) {
            return null;
        }

        // Transcode file info
        $transCodeFileDirectory = $transCodeFilePart[0];
        $transCodeFileName = $transCodeFileDirectory . '.' . AWSMediaConvert::MEDIA_CONVERT_EXTENSION;
        $filePath = trim($path, '/') . '/' . trim(self::TRANSCODE_DIRECTORY, '/') . '/' . $transCodeFileDirectory;
        $fullPath = $filePath . '/' . $transCodeFileName;


        // Normal File info
        $is_transcode = false;
        if (!Storage::exists($fullPath)) {
            $fullPath = trim($path, '/') . '/' . $fileName;
            if (!Storage::exists($fullPath)) {
                return null;
            }
        } else {
            $is_transcode = true;
            $path = trim($filePath, '/');
            $fileName = $transCodeFileName;
        }

        $cacheTime = 60;
        if (self::isDiskS3()) {
            $cacheMinute = $is_transcode ? 200 : $cacheTime;
            $cacheTime = now()->addMinutes($cacheMinute);

//            $url = self::cloudFrontSignedUrl($fullPath, $cacheMinute);
//            $url = $is_transcode ? self::cloudFrontSignedUrl($fullPath, $cacheMinute) : Storage::temporaryUrl($fullPath, $cacheTime);
            $url = Storage::url($fullPath);
        } else {
            $url = Storage::url($fullPath);
        }


        $obj = new stdClass();
        $obj->path = $path;
        $obj->url = $url;
        $obj->name = $fileName;

        /**
         * Create Cache
         */
        Cache::store(config('cache.default'))->add($cacheKey, $obj, $cacheTime);
        return $obj;
    }

    /**
     * @param UploadedFile $file
     * @return object|null
     */
    public static function uploadFile(UploadedFile $file): object|null {
        // Determine file extension and generate unique file name
        $extension = $file->getClientOriginalExtension();
        $fileName = uniqid() . '.' . $extension;

        // Store the file on the specified disk and path
        $filePath = self::DEFAULT_DIRECTORY;
        Storage::putFileAs($filePath, $file, $fileName);

        // Return both the path and the name of the file
        return self::filePathUrl($fileName, $filePath);
    }

    /**
     * @param string $fileName name of the file
     * @param string $path path where to move, it will be final path of the file
     * @param bool $transCode
     * @return object|null
     */
    public static function moveFile(string $fileName, string $path, bool $transCode = false): object|null {
        $sourcePath = self::DEFAULT_DIRECTORY . '/' . $fileName;
        $destinationPath = trim($path, '/') . '/' . $fileName;

        if (Storage::exists($sourcePath)) {
            // Transcode from temp directory to destination file
            if ($transCode && self::isDiskS3()) {
                // Transcode file from temp directory
                Queue::push(function () use ($path, $fileName, $sourcePath, $destinationPath) {
                    // Do not use self as it will be an instance of Queue
                    // First convert video to specific format and move to destination/transcode
                    AWSMediaConvert::transCodeFile($path, $fileName);
                    // Then copy original file to destination
                    // do not use move because above will only create job and will not wait to finish transcoding
                    Storage::copy($sourcePath, $destinationPath);
                });

            } else {
                Queue::push(function () use ($sourcePath, $destinationPath) {
                    // move file to original location or transcode file if enabled.
                    Storage::move($sourcePath, $destinationPath);
                });

            }
        }

        return self::filePathUrl($fileName, $path);
    }

    /**
     *  ▗▄▖ ▗▖  ▗▖▗▖ ▗▖  ▗▖     ▗▄▖ ▗▄▄▄ ▗▖  ▗▖ ▗▄▖ ▗▖  ▗▖ ▗▄▄▖▗▄▄▄▖▗▄▄▄     ▗▖ ▗▖ ▗▄▄▖▗▄▄▄▖▗▄▄▖     ▗▖ ▗▖▗▄▄▖ ▗▄▄▄  ▗▄▖▗▄▄▄▖▗▄▄▄▖    ▗▄▄▄▖▗▖ ▗▖▗▄▄▄▖ ▗▄▄▖    ▗▄▄▄▖▗▖ ▗▖▗▖  ▗▖ ▗▄▄▖▗▄▄▄▖▗▄▄▄▖ ▗▄▖ ▗▖  ▗▖
     * ▐▌ ▐▌▐▛▚▖▐▌▐▌  ▝▚▞▘     ▐▌ ▐▌▐▌  █▐▌  ▐▌▐▌ ▐▌▐▛▚▖▐▌▐▌   ▐▌   ▐▌  █    ▐▌ ▐▌▐▌   ▐▌   ▐▌ ▐▌    ▐▌ ▐▌▐▌ ▐▌▐▌  █▐▌ ▐▌ █  ▐▌         █  ▐▌ ▐▌  █  ▐▌       ▐▌   ▐▌ ▐▌▐▛▚▖▐▌▐▌     █    █  ▐▌ ▐▌▐▛▚▖▐▌
     * ▐▌ ▐▌▐▌ ▝▜▌▐▌   ▐▌      ▐▛▀▜▌▐▌  █▐▌  ▐▌▐▛▀▜▌▐▌ ▝▜▌▐▌   ▐▛▀▀▘▐▌  █    ▐▌ ▐▌ ▝▀▚▖▐▛▀▀▘▐▛▀▚▖    ▐▌ ▐▌▐▛▀▘ ▐▌  █▐▛▀▜▌ █  ▐▛▀▀▘      █  ▐▛▀▜▌  █   ▝▀▚▖    ▐▛▀▀▘▐▌ ▐▌▐▌ ▝▜▌▐▌     █    █  ▐▌ ▐▌▐▌ ▝▜▌
     * ▝▚▄▞▘▐▌  ▐▌▐▙▄▄▖▐▌      ▐▌ ▐▌▐▙▄▄▀ ▝▚▞▘ ▐▌ ▐▌▐▌  ▐▌▝▚▄▄▖▐▙▄▄▖▐▙▄▄▀    ▝▚▄▞▘▗▄▄▞▘▐▙▄▄▖▐▌ ▐▌    ▝▚▄▞▘▐▌   ▐▙▄▄▀▐▌ ▐▌ █  ▐▙▄▄▖      █  ▐▌ ▐▌▗▄█▄▖▗▄▄▞▘    ▐▌   ▝▚▄▞▘▐▌  ▐▌▝▚▄▄▖  █  ▗▄█▄▖▝▚▄▞▘▐▌  ▐▌
     *
     * ▒▒▒▒▒▒▒▒▒▒▒▄▄▄▄░▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ▒▒▒▒▒▒▒▒▒▄██████▒▒▒▒▒▄▄▄█▄▒▒▒▒▒▒▒▒▒▒
     * ▒▒▒▒▒▒▒▄██▀░░▀██▄▒▒▒▒████████▄▒▒▒▒▒▒
     * ▒▒▒▒▒▒███░░░░░░██▒▒▒▒▒▒█▀▀▀▀▀██▄▄▒▒▒
     * ▒▒▒▒▒▄██▌░░░░░░░██▒▒▒▒▐▌▒▒▒▒▒▒▒▒▀█▄▒
     * ▒▒▒▒▒███░░▐█░█▌░██▒▒▒▒█▌▒▒▒▒▒▒▒▒▒▒▀▌
     * ▒▒▒▒████░▐█▌░▐█▌██▒▒▒██▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ▒▒▒▐████░▐░░░░░▌██▒▒▒█▌▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ▒▒▒▒████░░░▄█░░░██▒▒▐█▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ▒▒▒▒████░░░██░░██▌▒▒█▌▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ▒▒▒▒████▌░▐█░░███▒▒▒█▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ▒▒▒▒▐████░░▌░███▒▒▒██▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ▒▒▒▒▒████░░░███▒▒▒▒█▌▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ▒▒▒██████▌░████▒▒▒██▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ▒▐████████████▒▒███▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ▒█████████████▄████▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ██████████████████▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ██████████████████▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * █████████████████▀▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * █████████████████▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ████████████████▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     * ████████████████▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
     *
     * @param $fileName
     * @param $path
     * @param bool $transCoded
     * @return void
     */
    public static function deleteFile($fileName, $path = null, bool $transCoded = false): void {

        if (!$fileName) {
            return;
        }

        $fileNameParts = explode('.', $fileName);
        if (count($fileNameParts) <= 1) {
            return;
        }

        // Remove From Temp Directory only file not directory
        $tempPath = trim(self::DEFAULT_DIRECTORY, '/') . '/' . $fileName;
        if (Storage::exists($tempPath) && !Storage::directoryExists($tempPath)) {
            Storage::delete($tempPath);
        }


        if (!$path) {
            return;
        }

        // Remove Path Related file
        $filePath = trim($path, '/') . '/' . $fileName;
        if (Storage::exists($filePath) && !Storage::directoryExists($filePath)) {
            Storage::delete($filePath);
        }

        if ($transCoded && self::isDiskS3()) {
            // Remove file directory for trans coded file
            $fileDirectory = trim($path, '/') . '/' . trim(self::TRANSCODE_DIRECTORY, '/') . '/' . $fileNameParts[0];
            if (Storage::directoryExists($fileDirectory)) {
                Storage::deleteDirectory($fileDirectory);
            }
        }
    }


    /**
     * @param UploadedFile $file
     * @param $chunkIndex
     * @param $totalChunks
     * @param $uploadId
     * @return array|false[]|object|stdClass|null
     */
    public static function chunkUploadFile(UploadedFile $file, $chunkIndex, $totalChunks, $uploadId) {

        if (!self::isDiskS3()) {
            return self::uploadFile($file);
        }

        $fileName = $file->getClientOriginalName();
        $s3Client = new S3Client([
            'region' => config('filesystems.disks.s3.region'),
            'version' => 'latest',
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);

        $bucket = config('filesystems.disks.s3.bucket');
        $key = self::DEFAULT_DIRECTORY . '/chunk/' . $fileName; // Final S3 key

        if (!$uploadId) {
            // Initiate a multipart upload
            $result = $s3Client->createMultipartUpload([
                'Bucket' => $bucket,
                'Key' => $key,
            ]);
            $uploadId = $result['UploadId'];
        }


        try {
            // Upload the chunk as a part
            $s3Client->uploadPart([
                'Bucket' => $bucket,
                'Key' => $key,
                'UploadId' => $uploadId,
                'PartNumber' => $chunkIndex + 1, // Part numbers start from 1
                'Body' => fopen($file->getRealPath(), 'r'), // Use stream
                'ContentLength' => filesize($file->getRealPath()), // Important for large files
            ]);

            if ($chunkIndex == $totalChunks - 1) {
                $partResult = $s3Client->listParts(['Bucket' => $bucket, 'Key' => $key, 'UploadId' => $uploadId]);
                $parts = $partResult->get('Parts');

                // Determine file extension and generate unique file name
                $s3Client->completeMultipartUpload([
                    'Bucket' => $bucket,
                    'Key' => $key,
                    'UploadId' => $uploadId,
                    'MultipartUpload' => [
                        'Parts' => $parts,
                    ],
                ]);
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = self::DEFAULT_DIRECTORY . '/' . $fileName;
                Storage::move($key, $destinationPath);
                return ['status' => true, 'file' => self::filePathUrl($fileName)];
            }

            return ['status' => true, 'uploadId' => $uploadId];
        } catch (\Exception $e) {
            // Handle exceptions (log, return error response)
            return ['status' => false]; // 500 status code
        }
    }

}
