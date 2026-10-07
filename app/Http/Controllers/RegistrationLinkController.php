<?php

namespace App\Http\Controllers;

use App\Models\RegistrationLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationLinkController extends Controller
{
    public function index(): JsonResponse
    {
        $links = RegistrationLink::query()
            ->latest()
            ->get()
            ->map(fn (RegistrationLink $link) => $this->payload($link))
            ->all();

        return response()->json(['links' => $links]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:100'],
            'expiry_days' => ['required', 'integer', 'in:1,7,30,0'],
            'max_uses' => ['nullable', 'integer', 'min:1', 'max:1000'],
        ]);

        $expiryDays = (int) $validated['expiry_days'];

        $link = RegistrationLink::create([
            'token' => Str::random(40),
            'label' => $validated['label'] ? trim($validated['label']) : null,
            'expires_at' => $expiryDays > 0 ? now()->addDays($expiryDays) : null,
            'max_uses' => filled($validated['max_uses'] ?? null) ? (int) $validated['max_uses'] : null,
            'created_by' => $request->user()->id,
        ]);

        return response()->json(['link' => $this->payload($link)], 201);
    }

    public function destroy(RegistrationLink $link): JsonResponse
    {
        $link->update(['is_active' => false]);

        return response()->json(['revoked' => true]);
    }

    private function payload(RegistrationLink $link): array
    {
        return [
            'label' => $link->label,
            'url' => route('register.form', $link),
            'expires_at' => $link->expires_at?->toDateTimeString(),
            'expires_human' => $link->expires_at?->diffForHumans(),
            'max_uses' => $link->max_uses,
            'uses' => $link->uses,
            'is_active' => $link->is_active,
            'is_valid' => $link->isValid(),
            'revoke_url' => route('admin.registration-links.destroy', $link),
        ];
    }
}
