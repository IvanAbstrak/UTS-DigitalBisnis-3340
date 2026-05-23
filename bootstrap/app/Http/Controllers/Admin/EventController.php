<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


//
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;


class EventController extends Controller
{
   public function index() {
        $events = Event::with('category')->latest()->get();
        return view('admin.events.index', compact('events'));
    }


    public function create() {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }


    public function store(Request $request) {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'date'        => 'required|date',
            'location'    => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'poster'      => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);


        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }


        Event::create($data);
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dibuat.');
    }


    public function edit(Event $event) {
        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }


    public function update(Request $request, Event $event) {
        $data = $request->validate([
            'category_id' => 'required',
            'title'       => 'required',
            'description' => 'required',
            'date'        => 'required',
            'location'    => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|numeric',
            'poster'      => 'nullable|image|max:2048',
        ]);


        if ($request->hasFile('poster')) {
            if ($event->poster_path) Storage::disk('public')->delete($event->poster_path);
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }


        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }


    public function destroy(Event $event) {
        if ($event->poster_path) Storage::disk('public')->delete($event->poster_path);
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }


}
