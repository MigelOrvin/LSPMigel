<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255'
        ]);

        $search = trim($validated['search'] ?? '');

        $announcements = Announcement::published()

            ->with('author')

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

            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        return view('patient.announcements.index', compact(
            'announcements',
            'search'
        ));
    }

    public function show(Announcement $announcement)
    {
        abort_unless(
            $announcement->status === 'published'
                && $announcement->published_at
                && $announcement->published_at->lte(now()),
            404
        );

        $announcement->load('author');

        return view('announcements.show', [
            'announcement' => $announcement,
            'isAdmin' => false
        ]);
    }
}
