<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// ! File or Image Upload
function removeSpaces($string)
{
    return str_replace(' ', '', $string);
}
function getStatusHTML($data, $backgroundColor, $sliderTranslateX)
{
    $sliderStyles = "position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: transform 0.3s ease; transform: translateX($sliderTranslateX);";
    $status = '<div class="form-check form-switch" style="margin-left:40px; position: relative; width: 50px; height: 24px; background-color: '.$backgroundColor.'; border-radius: 12px; transition: background-color 0.3s ease; cursor: pointer;">';
    $status .= '<input onclick="showStatusChangeAlert('.$data->id.')" type="checkbox" class="form-check-input" id="customSwitch'.$data->id.'" getAreaid="'.$data->id.'" name="status" style="position: absolute; width: 100%; height: 100%; opacity: 0; z-index: 2; cursor: pointer;">';
    $status .= '<span style="'.$sliderStyles.'"></span>';
    $status .= '<label for="customSwitch'.$data->id.'" class="form-check-label" style="margin-left: 10px;"></label>';
    $status .= '</div>';

    return $status;
}

function getPageStatus($urls, $text = null)
{
    // Make sure it's always an array
    $urls = (array) $urls;

    foreach ($urls as $url) {
        if (Route::is($url)) {
            return $text ?? 'active';
        }
    }

    return '';
}

// ! File or Image Upload for server public folder
function fileUpload($file, string $folder, string $name, ?string $option = null): ?string
{
    if (! $file->isValid()) {
        return null;
    }

    $imageName = Str::slug($name).'.'.$file->extension();
    $path = public_path('uploads/'.$folder);
    if (! file_exists($path)) {
        mkdir($path, 0755, true);
    }
    $file->move($path, $imageName);

    return 'uploads/'.$folder.'/'.$imageName;
}

// ! File or Image Upload for storage disk
function fileUploadStorage($file, string $folder, string $name, string $disk = 'public'): ?string
{
    if (! $file || ! $file->isValid()) {
        return null;
    }

    $imageName = Str::slug($name).'.'.$file->extension();
    $folder = trim($folder, '/');
    $targetDir = trim('uploads/'.$folder, '/');

    $storedPath = Storage::disk($disk)->putFileAs($targetDir, $file, $imageName);
    if (! $storedPath) {
        return null;
    }

    return 'storage/'.ltrim(str_replace('\\', '/', $storedPath), '/');
}

// ! File or Image Delete
function fileDelete(string $path): void
{
    if (file_exists($path)) {
        unlink($path);
    }
}

// ! Generate Slug
function makeSlug($model, string $title): string
{
    $slug = Str::slug($title);
    while ($model::where('slug', $slug)->exists()) {
        $randomString = Str::random(5);
        $slug = Str::slug($title).'-'.$randomString;
    }

    return $slug;
}

// ! JSON Response
function jsonResponse(bool $status, string $message, int $code, $data = null, bool $paginate = false, $paginateData = null): JsonResponse
{
    $response = [
        'status' => $status,
        'message' => $message,
        'code' => $code,
    ];

    if ($paginate && ! empty($paginateData)) {
        $response['data'] = $data;
        $response['pagination'] = [
            'current_page' => $paginateData->currentPage(),
            'last_page' => $paginateData->lastPage(),
            'per_page' => $paginateData->perPage(),
            'total' => $paginateData->total(),
            'first_page_url' => $paginateData->url(1),
            'last_page_url' => $paginateData->url($paginateData->lastPage()),
            'next_page_url' => $paginateData->nextPageUrl(),
            'prev_page_url' => $paginateData->previousPageUrl(),
            'from' => $paginateData->firstItem(),
            'to' => $paginateData->lastItem(),
            // 'path' => $paginateData->path(),
        ];
    } elseif ($paginate && ! empty($data)) {
        $response['data'] = $data->items();
        $response['pagination'] = [
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
            'total' => $data->total(),
            'first_page_url' => $data->url(1),
            'last_page_url' => $data->url($data->lastPage()),
            'next_page_url' => $data->nextPageUrl(),
            'prev_page_url' => $data->previousPageUrl(),
            'from' => $data->firstItem(),
            'to' => $data->lastItem(),
        ];
    } elseif ($data !== null) {
        $response['data'] = $data;
    }

    return response()->json($response, $code);
}

function jsonErrorResponse(string $message, int $code = 400, array $errors = []): JsonResponse
{
    $response = [
        'status' => false,
        'message' => $message,
        'code' => $code,
        'errors' => $errors,
    ];

    return response()->json($response, $code);
}

// Add this method in your ChatController
function validationError($errors)
{
    return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $errors,
    ], 422); // 422 is HTTP status for Unprocessable Entity
}
