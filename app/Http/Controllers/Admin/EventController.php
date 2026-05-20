<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $eventSearch = $request->input('event_search');
        $categorySearch = $request->input('category_search');

        // Memakai relasi dan pengaturan limit paginasi (10 entri per halaman)
        $events = Event::with('category')
            ->when($eventSearch, function ($query) use ($eventSearch) {
                $query->where('title', 'LIKE', '%' . $eventSearch . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::withCount('events')
            ->when($categorySearch, function ($query) use ($categorySearch) {
                $query->where('name', 'LIKE', '%' . $categorySearch . '%');
            })
            ->latest()
            ->get();

        return view('admin.events.index', compact('events', 'categories', 'eventSearch', 'categorySearch'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Menerapkan validasi data request dari pengguna
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255|unique:events,title',
            'description' => 'required|string|min:10',
            'date' => 'required|date|after:today',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle file upload untuk poster
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $path = $file->store('events', 'public');
            $data['poster_path'] = $path;
        }

        // Menyimpan data yang telah divalidasi ke dalam tabel menggunakan Model
        Event::create($data);
        return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil ditambahkan.');
 }
    

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255|unique:events,title,' . $event->id,
            'description' => 'required|string|min:10',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle file upload untuk poster
        if ($request->hasFile('poster')) {
            // Hapus file lama jika ada
            if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
                Storage::disk('public')->delete($event->poster_path);
            }
            
            $file = $request->file('poster');
            $path = $file->store('events', 'public');
            $data['poster_path'] = $path;
        }

        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Data event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Hapus file poster jika ada
        if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Data event berhasil dihapus secara permanen.');
    }
}
