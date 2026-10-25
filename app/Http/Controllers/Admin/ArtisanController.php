<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArtisanRequest;
use App\Models\Artisan;
use Illuminate\Support\Facades\Storage;

class ArtisanController extends Controller
{
    public function index()
    {
        return view('admin.artisans.index', [
            'artisans' => Artisan::withCount('products')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.artisans.form', ['artisan' => new Artisan()]);
    }

    public function store(ArtisanRequest $request)
    {
        Artisan::create($this->artisanData($request));

        return redirect()->route('admin.artisans.index')->with('success', 'Artisan créé.');
    }

    public function edit(Artisan $artisan)
    {
        return view('admin.artisans.form', compact('artisan'));
    }

    public function update(ArtisanRequest $request, Artisan $artisan)
    {
        $artisan->update($this->artisanData($request, $artisan));

        return redirect()->route('admin.artisans.index')->with('success', 'Artisan mis à jour.');
    }

    /** Les produits de l'artisan sont conservés (artisan_id passe à NULL grâce à la clé étrangère). */
    public function destroy(Artisan $artisan)
    {
        $this->deleteFile($artisan->photo_path);
        $artisan->delete();

        return redirect()->route('admin.artisans.index')->with('success', 'Artisan supprimé.');
    }

    private function artisanData(ArtisanRequest $request, ?Artisan $artisan = null): array
    {
        $data = collect($request->validated())->except('photo')->all();

        if (blank($data['slug'] ?? null)) {
            unset($data['slug']);
        }

        if ($request->hasFile('photo')) {
            $this->deleteFile($artisan?->photo_path);
            $data['photo_path'] = $request->file('photo')->store('artisans', 'public');
        }

        return $data;
    }

    private function deleteFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'images/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
