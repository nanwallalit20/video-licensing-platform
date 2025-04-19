<?php

namespace App\Console\Commands;

use App\Helpers\FileUploadHelper;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteOldS3Files extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 's3:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete files older than 2 days from an S3 bucket.";

    /**
     * Execute the console command.
     */
    public function handle() {

        // TODO: Delete local temp if available

        // Get the S3 configuration (using Laravel's config)
        $s3Client = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $bucket = env('AWS_BUCKET'); // Get your bucket name
        $prefix = FileUploadHelper::DEFAULT_DIRECTORY ?? 'temp'; // Optional path prefix within the bucket

        try {
            $objects = $s3Client->listObjectsV2([
                'Bucket' => $bucket,
                'Prefix' => $prefix, // Use the prefix if defined
            ]);

            $contents = $objects['Contents'] ?? [];

            while (true) { // Handle pagination of results
                foreach ($contents as $object) {
                    $lastModified = $object['LastModified'];
                    $fileAge = Carbon::instance($lastModified); // Use Carbon::instance

                    if ($fileAge->diffInDays(Carbon::now()) >= 2) {
                        try {
                            $s3Client->deleteObject([
                                'Bucket' => $bucket,
                                'Key' => $object['Key'],
                            ]);
                            $this->info("Deleted file: s3://{$bucket}/{$object['Key']}");
                        } catch (AwsException $e) {
                            $this->error("Error deleting file s3://{$bucket}/{$object['Key']}: " . $e->getMessage());
                            Log::error($e);
                        }
                    }
                }

                if (!$objects['IsTruncated']) {
                    break; // No more results to fetch
                }

                $continuationToken = $objects['NextContinuationToken'];
                $objects = $s3Client->listObjectsV2([
                    'Bucket' => $bucket,
                    'Prefix' => $prefix,
                    'ContinuationToken' => $continuationToken,
                ]);

            }

        } catch (AwsException $e) {
            $this->error("Error listing objects: " . $e->getMessage());
            Log::error($e);
            return 1; // Indicate an error
        }

        $this->info('S3 cleanup completed.');
        return 0;
    }
}
