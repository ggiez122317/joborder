<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BrandAssetController extends Controller
{
    public function logo(): BinaryFileResponse
    {
        $path = base_path('logo.png');

        if (!file_exists($path)) {
            $path = public_path('assets/profile-placeholder.svg');
        }

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function loginBackground(): BinaryFileResponse
    {
        $path = base_path('bg.jpg');

        if (!file_exists($path)) {
            $path = public_path('assets/profile-placeholder.svg');
        }

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
