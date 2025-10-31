<?php

namespace App\Http\Controllers;

use App\Models\QuestGif;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestGifController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $searchQuery = $request->input('search');

        $query = QuestGif::where('id_user', $user->id)->with('guest.folder');

        if ($searchQuery) {
            $lowerSearchQuery = strtolower($searchQuery);

            $matchingGuestIds = Guest::where('id_user', $user->id)
                                     ->whereRaw('LOWER(name) LIKE ?', ['%' . $lowerSearchQuery . '%'])
                                     ->pluck('id')
                                     ->toArray();

            $query->where(function ($q) use ($lowerSearchQuery, $matchingGuestIds) {
                $q->whereRaw('LOWER(info) LIKE ?', ['%' . $lowerSearchQuery . '%'])
                  ->orWhereIn('id_quest', $matchingGuestIds);
            });
        }

        $questGifs = $query->latest()->paginate(10);

        $guests = Guest::where('id_user', $user->id)->with('folder')->get();
        $groupedGuests = $guests->groupBy(function ($guest) {
            return $guest->folder->name ?? '(Без папки)';
        });

        return view('quest_gifs.index', compact('questGifs', 'groupedGuests', 'searchQuery'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_quest' => 'required|integer|exists:guests,id,id_user,' . Auth::id(),
            'price' => 'required|numeric',
            'info' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['id_user'] = Auth::id();

        QuestGif::create($data);

        return redirect()->route('client.quest_gifs.index')
            ->with('success', 'Запись успешно создана.');
    }

    public function update(Request $request, QuestGif $questGif)
    {
        $request->validate([
            'id_quest' => 'required|integer|exists:guests,id,id_user,' . Auth::id(),
            'price' => 'required|numeric',
            'info' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $questGif->update($request->all());

        return redirect()->route('client.quest_gifs.index')
            ->with('success', 'Запись успешно обновлена.');
    }

    public function destroy(QuestGif $questGif)
    {
        $questGif->delete();

        return redirect()->route('client.quest_gifs.index')
            ->with('success', 'Запись успешно удалена.');
    }
}
