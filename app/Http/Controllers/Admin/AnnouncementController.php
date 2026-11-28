<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:all,draft,published'
        ]);

        $search = trim($validated['search'] ?? '');
        $status = $validated['status'] ?? 'all';

        // Statistics
        $totalAnnouncements = Announcement::count();

        $publishedAnnouncements = Announcement::published()
            ->count();

        $draftAnnouncements = Announcement::where(
            'status', 'draft'
        )->count();

        // Announcement list
        $announcements = Announcement::with('author')

            ->when($search !== '', function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where(
                        'title',
                        'like',
                        '%' . $search . '%'
                    )->orWhere(
                        'content',
                        'like',
                        '%' . $search . '%'
                    );
                });
            })

            ->when($status !== 'all', function ($query) use ($status) {

                $query->where('status', $status);

            })

            ->latest()
            ->orderByDesc('id')
            ->paginate(6)
            ->withQueryString();

        return view('admin.announcements.index', compact(
            'announcements',
            'search',
            'status',
            'totalAnnouncements',
            'publishedAnnouncements',
            'draftAnnouncements'
        ));
    }

    public function create()
    {
        return view('admin.announcements.form', [
            'announcement' => new Announcement()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateAnnouncement($request);

        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $this->storeImage($request);
        }

        try {

            Announcement::create([
                'user_id' => $request->user()->id,

                'title' => $validated['title'],

                'content' => $validated['content'],

                'image' => $imagePath,

                'status' => $validated['status'],

                'published_at' =>
                    $validated['status'] === 'published'
                        ? now()
                        : null
            ]);

        } catch (Throwable $exception) {

            if ($imagePath) {

                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function show(Announcement $announcement)
    {
        $announcement->load('author');

        return view('announcements.show', [
            'announcement' => $announcement,
            'isAdmin' => true
        ]);
    }

    public function edit(Announcement $announcement)
    {
        return view(
            'admin.announcements.form',
            compact('announcement')
        );
    }

    public function update(
        Request $request,
        Announcement $announcement
    ) {

        $validated = $this->validateAnnouncement($request);

        $oldImage = $announcement->image;

        $newImage = $oldImage;

        $uploadedImage = null;

        // Replace image
        if ($request->hasFile('image')) {

            $uploadedImage = $this->storeImage($request);

            $newImage = $uploadedImage;

        } elseif ($request->boolean('remove_image')) {

            $newImage = null;
        }

        // Preserve publication date when editing
        $publishedAt = $validated['status'] === 'published'
            ? ($announcement->published_at ?? now())
            : null;

        try {

            $announcement->update([
                'title' => $validated['title'],

                'content' => $validated['content'],

                'image' => $newImage,

                'status' => $validated['status'],

                'published_at' => $publishedAt
            ]);

        } catch (Throwable $exception) {

            if ($uploadedImage) {

                Storage::disk('public')->delete($uploadedImage);
            }

            throw $exception;
        }

        // Delete old image after successful update
        if ($oldImage && $oldImage !== $newImage) {

            Storage::disk('public')->delete($oldImage);
        }

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        $image = $announcement->image;

        $announcement->delete();

        if ($image) {

            Storage::disk('public')->delete($image);
        }

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    private function validateAnnouncement(
        Request $request
    ): array {

        return $request->validate([
            'title' => 'required|string|max:150',

            'content' => 'required|string|max:10000',

            'status' => 'required|in:draft,published',

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            'remove_image' => 'nullable|boolean'

        ], [
            'title.required' =>
                'Judul pengumuman wajib diisi.',

            'title.max' =>
                'Judul maksimal 150 karakter.',

            'content.required' =>
                'Isi pengumuman wajib diisi.',

            'content.max' =>
                'Isi pengumuman maksimal 10.000 karakter.',

            'status.required' =>
                'Status publikasi wajib dipilih.',

            'image.image' =>
                'File harus berupa gambar.',

            'image.mimes' =>
                'Format gambar harus JPG, PNG, atau WEBP.',

            'image.max' =>
                'Ukuran gambar maksimal 2 MB.'
        ]);
    }

    private function storeImage(Request $request): string
    {
        $path = $request->file('image')->store(
            'announcements',
            'public'
        );

        if (!$path) {

            throw new RuntimeException(
                'Gagal menyimpan gambar pengumuman.'
            );
        }

        return $path;
    }
}
