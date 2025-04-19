<?php

namespace App\Helpers;

use Aws\Exception\AwsException;
use Aws\MediaConvert\MediaConvertClient;
use Illuminate\Support\Facades\Storage;

class AWSMediaConvert {
    const MEDIA_CONVERT_EXTENSION = 'mpd'; // m3u8, mpd

    protected static function getConvertExtension(): string {
        return strtoupper(self::MEDIA_CONVERT_EXTENSION);
    }


    protected static function getOutputGroupSettings($destinationPath): array {

        $resourceId = uniqid();

        return match (self::MEDIA_CONVERT_EXTENSION) {
            'm3u8' => [
                'Type' => 'HLS_GROUP_SETTINGS',
                'HlsGroupSettings' => [
                    'Destination' => 's3://' . env('AWS_BUCKET') . '/' . $destinationPath, // $destinationPath should be defined
                    'SegmentLength' => 6,
                    'MinSegmentLength' => 1,
                    'Encryption' => [
                        'EncryptionMethod' => 'SAMPLE_AES',
                        'InitializationVectorInManifest' => "INCLUDE",
                        'SpekeKeyProvider' => [
                            'ResourceId' => $resourceId,
                            'SystemIds' => [
                                env('AWS_MEDIA_PLAYER_WIDEVINE_SYSTEM_ID'), // Widevine
                                env('AWS_MEDIA_PLAYER_PLAYREADY_SYSTEM_ID'), // PlayReady
                            ],
                            'Url' => env('AWS_DRM_KEY_PROVIDER_URL'), // speke_server_url
                        ],
                        'Type' => 'SPEKE',
                    ],
                ],
            ],
            'mpd' => [
                'Type' => 'DASH_ISO_GROUP_SETTINGS',
                'DashIsoGroupSettings' => [
                    'Destination' => 's3://' . env('AWS_BUCKET') . '/' . $destinationPath, // $destinationPath should be defined
                    'SegmentLength' => 30,
                    'FragmentLength' => 2,
                    'Encryption' => [
                        'SpekeKeyProvider' => [
                            'ResourceId' => $resourceId,
                            'SystemIds' => [
                                env('AWS_MEDIA_PLAYER_WIDEVINE_SYSTEM_ID'), // Widevine
                                env('AWS_MEDIA_PLAYER_PLAYREADY_SYSTEM_ID'), // PlayReady
                            ],
                            'Url' => env('AWS_DRM_KEY_PROVIDER_URL')
                        ]
                    ]
                ]
            ]
        };
    }

    /**
     * @param string $path
     * @param string $fileName
     * @return void
     */
    public static function transCodeFile(string $path, string $fileName): void {

        if (config('filesystems.default') != 's3') {
            return;
        }

        // Temp File Path
        $sourcePath = trim(FileUploadHelper::DEFAULT_DIRECTORY, '/') . '/' . $fileName;

        if (!$fileName || !Storage::exists($sourcePath)) {
            return;
        }

        $transCodeFilePart = explode('.', $fileName);
        if (count($transCodeFilePart) <= 1) {
            return;
        }

        $transCodeFileDirectory = $transCodeFilePart[0];

        /**
         * Please do not remove trailing slash.
         * Required trailing slash to create directory based on the file name.
         */
        $destinationPath = trim($path, '/') . '/' . trim(FileUploadHelper::TRANSCODE_DIRECTORY, '/') . '/' . $transCodeFileDirectory . '/';

        // Start transcoding of the file
        $mediaConvert = new MediaConvertClient([
            'version' => 'latest', //'latest' , '2017-08-29',
            'endpoint' => env('AWS_MEDIA_CONVERT_ENDPOINT'),
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $outputGroupSettings = self::getOutputGroupSettings($destinationPath);
        $jobSettings = [
            'Role' => env('AWS_MEDIA_CONVERT_ROLE'),
            'Settings' => [
                'TimecodeConfig' => [
                    'Source' => "ZEROBASED",
                ],
                'Inputs' => [
                    [
                        'FileInput' => 's3://' . env('AWS_BUCKET') . '/' . $sourcePath,
                        'AudioSelectors' => [
                            'Audio Selector 1' => [
                                'DefaultSelection' => 'DEFAULT',
                            ],
                        ],
                        'TimecodeSource' => "ZEROBASED",
                    ],
                ],// $sourcePath should be defined
                'OutputGroups' => [
                    [
                        'Name' => $transCodeFileDirectory, // $transCodeFileDirectory, 'DASH ISO'
                        'OutputGroupSettings' => $outputGroupSettings,
                        'Outputs' => [
                            // 144 output
                            self::video144Config(),
                            // 360 output
                            self::video360Config(),
                            // 720p output
                            self::video720Config(),
                            // 1080p output
                            self::video1080Config(),
                            // audio only
                            self::audioConfig()
                        ]
                    ],
                ],
                'FollowSource' => 1,
            ],
        ];

        try {
            $result = $mediaConvert->createJob($jobSettings);
            // Handle successful job creation
            $jobId = $result['Job']['Id'];
            echo "Job created with ID: " . $jobId . "\n";

        } catch (AwsException $e) {
            // Handle exceptions
            echo $e->getMessage() . "\n";
        }
    }

    protected static function video144Config(): array {
        return [
            'NameModifier' => '_144p',
            'VideoDescription' => [
                'Width' => 256,
                'Height' => 144,
                'CodecSettings' => [
                    'Codec' => 'H_264',
                    'H264Settings' => [
                        'RateControlMode' => 'QVBR',
                        'MaxBitrate' => 300000,
                        'SceneChangeDetect' => 'TRANSITION_DETECTION' //'ENABLED',
                    ],
                ],
            ],
            'ContainerSettings' => [
                'Container' => self::getConvertExtension(),
            ],
        ];
    }

    protected static function video360Config(): array {
        return [
            'NameModifier' => '_360p',
            'VideoDescription' => [
                'Width' => 640,
                'Height' => 360,
                'CodecSettings' => [
                    'Codec' => 'H_264',
                    'H264Settings' => [
                        'RateControlMode' => 'QVBR',
                        'MaxBitrate' => 750000,
                        'SceneChangeDetect' => 'TRANSITION_DETECTION' //'ENABLED',
                    ],
                ],
            ],
            'ContainerSettings' => [
                'Container' => self::getConvertExtension(),
            ],
        ];
    }

    protected static function video720Config(): array {
        return [
            'NameModifier' => '_720p',
            'VideoDescription' => [
                'Width' => 1280,
                'Height' => 720,
                'CodecSettings' => [
                    'Codec' => 'H_264',
                    'H264Settings' => [
                        'RateControlMode' => 'QVBR',
                        'MaxBitrate' => 3000000,
                        'SceneChangeDetect' => 'TRANSITION_DETECTION' //'ENABLED',
                    ],
                ],
            ],
            'ContainerSettings' => [
                'Container' => self::getConvertExtension(),
            ],
        ];
    }

    protected static function video1080Config(): array {
        return [
            'NameModifier' => '_1080p',
            'VideoDescription' => [
                'Width' => 1920,
                'Height' => 1080,
                'CodecSettings' => [
                    'Codec' => 'H_264',
                    'H264Settings' => [
                        'RateControlMode' => 'QVBR',
                        'MaxBitrate' => 5000000,
                        'SceneChangeDetect' => 'TRANSITION_DETECTION' //'ENABLED',
                    ],
                ],
            ],
            'ContainerSettings' => [
                'Container' => self::getConvertExtension(),
            ],
        ];
    }

    protected static function audioConfig(): array {
        return [
            'NameModifier' => '_audio',
            'AudioDescriptions' => [
                [
                    'CodecSettings' => [
                        'Codec' => 'AAC',
                        'AacSettings' => [
                            'Bitrate' => 96000,
                            'CodingMode' => 'CODING_MODE_2_0',
                            'SampleRate' => 48000,
                        ],
                    ],
                    'LanguageCodeControl' => 'FOLLOW_INPUT',
                    'AudioTypeControl' => 'FOLLOW_INPUT',
                    'AudioSourceName' => 'Audio Selector 1',
                ],
            ],
            'ContainerSettings' => [
                'Container' => self::getConvertExtension(),
            ],
        ];
    }

}
