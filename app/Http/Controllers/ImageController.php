<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    // List images (basic, minimal)
    public function index()
    {
        return JsonResource::collection(Image::latest()->paginate(15));
    }

    // Show single image
    public function show(Image $image)
    {
        return new JsonResource($image->load('imageable'));
    }

    // Store image record (minimal). Accepts either an uploaded file or a path string and imageable info
    public function store(Request $request)
    {
        $data = $request->validate([
            'file' => ['nullable', 'file', 'image'],
            'path' => ['nullable', 'string'],
            'imageable_type' => ['required', 'string'],
            'imageable_id' => ['required', 'integer'],
        ]);

        if (empty($data['file']) && empty($data['path'])) {
            return response()->json(['message' => 'Either file or path must be provided.'], 422);
        }

        // Resolve imageable model (only allow specific types for safety; currently User)
        $type = $data['imageable_type'];
        $allowed = [User::class];
        if (! in_array($type, $allowed, true)) {
            return response()->json(['message' => 'Unsupported imageable_type.'], 422);
        }

        $imageable = $type::query()->find($data['imageable_id']);
        if (! $imageable) {
            return response()->json(['message' => 'Related model not found.'], 404);
        }

        // Handle file upload if provided, otherwise use path
        $path = $data['path'] ?? null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('images', 'public');
        }

        $image = Image::create([
            'path' => $path,
            'imageable_type' => $type,
            'imageable_id' => $imageable->getKey(),
        ]);

        return (new JsonResource($image))->response()->setStatusCode(201);
    }

    // Delete image (and file if exists in public disk)
    public function destroy(Image $image)
    {
        // Try deleting stored file if it looks like a storage path
        if ($image->path && ! filter_var($image->path, FILTER_VALIDATE_URL)) {
            // best-effort; ignore failure
            try {
                Storage::disk('public')->delete($image->path);
            } catch (\Throwable $e) {
                // ignore
            }
        }

        $image->delete();
        return response()->noContent();
    }
}
