<?php

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Carbon\Carbon;


function numberToWords($number)
{
    $f = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
    return ucwords($f->format($number));
}

function calculateBookingDays($checkIn, $checkOut, $checkOutTime)
{
    $start = Carbon::parse($checkIn);
    $end = Carbon::parse($checkOut);

    if ($end->lessThanOrEqualTo($start)) {
        return 0;
    }

    // Difference in calendar dates only
    $days = $start->copy()->startOfDay()->diffInDays(
        $end->copy()->startOfDay()
    );

    // Checkout after branch checkout time?
    $checkout = Carbon::parse($checkOutTime);
    $checkoutMinutes = ($end->hour * 60) + $end->minute;
    $branchCheckoutMinutes = ($checkout->hour * 60) + $checkout->minute;

    if ($checkoutMinutes > $branchCheckoutMinutes) {
        $days++;
    }

    return max(1, $days);
}


/*
 * ------------------------------------------------------------------------
 * Convert a date from dd-mm-yyyy (e.g., 25-12-2025) to yyyy-mm-dd (e.g., 2025-12-25)
 * ------------------------------------------------------------------------
 * @param string $date The input date string
 * @param string $inputFormat The format of the input date (default: 'd-m-Y')
 * @param string $outputFormat The desired output format (default: 'Y-m-d')
 * @return string The formatted date or null on failure
 */

if (!function_exists('convertDateFormat')) {
    function convertDateFormat($date, $inputFormat = 'd-m-Y', $outputFormat = 'Y-m-d')
    {
        try {
            $dateTime = DateTime::createFromFormat($inputFormat, $date);

            // Check if parsing was successful
            if ($dateTime === false) {
                Log::error("convertDateFormat: Failed to parse date '{$date}' with format '{$inputFormat}'");
                throw new Exception('Invalid date format');
            }

            return $dateTime->format($outputFormat);
        } catch (Exception $e) {
            Log::error('convertDateFormat Exception: ' . $e->getMessage());
            return null;
        }
    }
}


if (!function_exists('uploadFiles')) {
    function uploadFiles(Request $request, $param, $folder)
    {
        $imageNameArr = [];
        if ($request->hasFile($param)) {
            Storage::disk('public')->exists($folder) || Storage::disk('public')->makeDirectory($folder);
            if (is_array($request->file($param))) {
                foreach ($request->file($param) as $file) {
                    $imageName = Storage::disk('public')->putFile($folder, $file);
                    array_push($imageNameArr, $imageName);
                }
            } else {
                $imageName = Storage::disk('public')->putFile($folder, $request->file($param));
                array_push($imageNameArr, $imageName);
            }
        }
        return implode(',', $imageNameArr);
    }
}

if (!function_exists('uploadWebpImage')) {
    function uploadWebpImage($files, string $folder = 'images', bool $multiple = false, $oldFile = null, int $quality = 90)
    {
        if (!$multiple) {
            // Delete old file if exists
            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            $filename = Str::uuid() . '.webp';

            // For Intervention Image v2 (most compatible)
            $image = Image::make($files)->encode('webp', $quality);

            Storage::disk('public')->put("{$folder}/{$filename}", (string) $image);

            return "{$folder}/{$filename}";
        } else {
            // Multiple files
            $paths = [];
            foreach ($files as $file) {
                $filename = Str::uuid() . '.webp';

                $image = Image::make($file)->encode('webp', $quality);

                Storage::disk('public')->put("{$folder}/{$filename}", (string) $image);
                $paths[] = "{$folder}/{$filename}";
            }
            return $paths;
        }
    }
}
if (!function_exists('deleteFiles')) {
    function deleteFiles($fileName)
    {
        try {
            $fileNameArr = is_array($fileName) ? $fileName : explode(',', $fileName);

            foreach ($fileNameArr as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        } catch (\Exception $e) {
            return 'Unable to delete files.';
        }
    }
}


if (!function_exists('displayImage')) {
    function displayImage($path, $fallback = 'images/noimage.jpg')
    {
        if ($path && (Storage::disk('public')->exists($path) || file_exists(public_path($path)))) {
            return URL::to(Storage::url($path));
        }

        return URL::to($fallback);
    }
}




if (! function_exists('limit_words')) {
    function limit_words($text, $words = 100, $end = '...')
    {
        return Str::words($text, $words, $end);
    }
}



  function setting_value($key, $defaultValue = '')
    {
        $val = Setting::where('setting_name', $key)->value('setting_value');
        $rel = !empty($val) ? $val : $defaultValue;
        return $rel;
    }
