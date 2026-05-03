<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class VersionPreviewController extends Controller
{
    /**
     * Display the coming-soon preview for missing future-version routes.
     */
    public function show(Request $request, string $version, ?string $any = null): View
    {
        $launchPhases = [
            [
                'title' => 'Design Refresh',
                'description' => 'Cleaner workflows, stronger information hierarchy, and lighter navigation.',
            ],
            [
                'title' => 'Module Upgrades',
                'description' => 'New modules can be shipped version by version without affecting the stable panel.',
            ],
            [
                'title' => 'Safer Rollout',
                'description' => 'Future routes can stay isolated until the version is ready to go live.',
            ],
        ];

        $previewStats = [
            ['label' => 'Version', 'value' => strtoupper($version) . ' Preview'],
            ['label' => 'Status', 'value' => 'Coming Soon'],
            ['label' => 'Requested Path', 'value' => $any ? '/' . $any : '/'],
        ];

        return view('backend.v2.preview.coming-soon', [
            'launchPhases' => $launchPhases,
            'previewStats' => $previewStats,
            'requestedVersion' => strtoupper($version),
            'requestedPath' => $any ? '/' . $any : '/',
        ]);
    }
}
