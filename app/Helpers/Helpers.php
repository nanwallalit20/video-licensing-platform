<?php

namespace App\Helpers;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Str;

class Helpers {
    /**
     * Create a unique slug for a given model table and name.
     *
     * @param string $modelClass The fully qualified model class name.
     * @param string $name The name to create the slug from.
     * @param string $column The column to check for uniqueness (default: 'slug').
     * @return string The unique slug.
     */
    public static function createUniqueSlug(string $modelClass, string $name, string $column = 'slug'): string {
        $slug = Str::slug($name);
        if ($modelClass::where($column, $slug)->exists()) {
            $slug = Str::slug($name) . '-' . uniqid();
        }
        return $slug;
    }

    /**
     * @param Mailable $mailable
     * @param string $to
     * @param $cc
     * @param $bcc
     * @return bool
     */
    public static function sendMail(Mailable $mailable, string $to, $cc = null, $bcc = null): bool {
        try {
            Mail::to($to)->cc($cc)->bcc($bcc)->send($mailable);
            return true;
        } catch (\Exception $e) {
            Log::error('Error Sending Email: ' . $e->getMessage());
            return false;
        }
    }


    /**
     * @param $date1
     * @param $date2
     * @param $inputFormat
     * @param $outputFormat
     * @return mixed
     */
    public static function differenceBetweenDateTime($date1, $date2, $inputFormat = 'Y-m-d H:i:s', $outputFormat = 'total_seconds'): mixed {
        $first = \DateTime::createFromFormat($inputFormat, $date1);
        $second = \DateTime::createFromFormat($inputFormat, $date2);

        if (!$first) {
            throw new \InvalidArgumentException("Invalid date format for date1: '$date1' with format '$inputFormat'.");
        }
        if (!$second) {
            throw new \InvalidArgumentException("Invalid date format for date2: '$date2' with format '$inputFormat'.");
        }

        $diff = $first->diff($second);

        switch ($outputFormat) {
            case 'y':
                return $diff->y;
            case 'm':
                return $diff->m;
            case 'd':
                return $diff->d;
            case 'h':
                return $diff->h;
            case 'i':
                return $diff->i;
            case 's':
                return $diff->s;
            case 'days':
                return $diff->days; //total days
            case 'total_seconds':
                return abs($second->getTimestamp() - $first->getTimestamp());
            case 'dateinterval':
                return $diff; // Return the DateInterval object itself
            case 'formatted':
                return $diff->format('%Y-%m-%d %H:%i:%S');
            default:
                throw new \InvalidArgumentException("Invalid return format: '$outputFormat'.");
        }

    }

}
