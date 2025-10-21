<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $searchQuery = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $folderId = $request->input('folder_id');

        // Get all folders with their guest counts for the sidebar
        $folders = Folder::where('id_user', $user->id)
            ->withCount('guests')
            ->with(['children' => function ($query) {
                $query->withCount('guests');
            }])
            ->whereNull('id_parent')
            ->get();

        // Base query for guests
        $guestsQuery = Guest::where('id_user', $user->id)->with('folder');

        // Apply search filter (case-insensitive)
        if ($searchQuery) {
            $guestsQuery->where(function ($q) use ($searchQuery) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchQuery) . '%'])
                  ->orWhereRaw('LOWER(phone) LIKE ?', ['%' . strtolower($searchQuery) . '%']);
            });
        }

        // Apply folder filter
        if ($folderId) {
            $guestsQuery->where('id_folder', $folderId);
        }

        $guests = $guestsQuery->latest()->paginate($perPage);

        return view('client.index', compact('folders', 'guests', 'perPage', 'searchQuery', 'folderId'));
    }

    public function storeFolder(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('folders')->where(function ($query) use ($user, $request) {
                    return $query->where('id_user', $user->id)
                                 ->where('id_parent', $request->input('id_parent'));
                }),
            ],
            'info' => 'nullable|string',
            'id_parent' => 'nullable|exists:folders,id,id_user,' . $user->id,
        ], [
            'name.required' => 'Папканын атын жазыңыз. / Введите название папки.',
            'name.unique' => 'Бул папкада мындай аталыштагы папка мурунтан эле бар. / Папка с таким названием уже существует в этой папке.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error_folder', 'Папканы сактоодо ката кетти. / Ошибка при сохранении папки.');
        }

        Folder::create([
            'name' => $request->name,
            'info' => $request->info ?? '',
            'id_parent' => $request->id_parent,
            'id_user' => $user->id,
        ]);

        return back()->with('success_folder', 'Папка ийгиликтүү түзүлдү. / Папка успешно создана.');
    }

    public function updateFolder(Request $request, Folder $folder)
    {
        $user = Auth::user();

        if ($folder->id_user !== $user->id) {
            return back()->with('error_folder', 'Сизде бул папканы өзгөртүүгө укугуңуз жок. / У вас нет прав на редактирование этой папки.');
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('folders')->where(function ($query) use ($user, $folder) {
                    return $query->where('id_user', $user->id)
                                 ->where('id_parent', $folder->id_parent);
                })->ignore($folder->id),
            ],
            'info' => 'nullable|string',
        ], [
            'name.required' => 'Папканын атын жазыңыз. / Введите название папки.',
            'name.unique' => 'Бул папкада мындай аталыштагы папка мурунтан эле бар. / Папка с таким названием уже существует в этой папке.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error_folder', 'Папканы жаңыртууда ката кетти. / Ошибка при обновлении папки.');
        }

        $folder->update([
            'name' => $request->name,
            'info' => $request->info ?? '',
        ]);

        return back()->with('success_folder', 'Папка ийгиликтүү жаңыртылды. / Папка успешно обновлена.');
    }

    public function destroyFolder(Folder $folder)
    {
        $user = Auth::user();

        if ($folder->id_user !== $user->id) {
            return back()->with('error_folder', 'Сизде бул папканы өчүрүүгө укугуңуз жок. / У вас нет прав на удаление этой папки.');
        }

        if ($folder->children()->count() > 0 || $folder->guests()->count() > 0) {
            return back()->with('error_folder', 'Папка бош эмес. Адегенде ичиндеги бардык папкаларды жана конокторду өчүрүңүз. / Папка не пуста. Сначала удалите все вложенные папки и гостей.');
        }

        $folder->delete();

        return back()->with('success_folder', 'Папка ийгиликтүү өчүрүлдү. / Папка успешно удалена.');
    }

    public function storeGuest(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                Rule::unique('guests')->where(function ($query) use ($user) {
                    return $query->where('id_user', $user->id);
                }),
            ],
            'nickname' => 'nullable|string',
            'info' => 'nullable|string',
            'id_folder' => 'nullable|exists:folders,id,id_user,' . $user->id,
        ], [
            'name.required' => 'Коноктун аты-жөнүн жазыңыз. / Введите имя гостя.',
            'phone.required' => 'Телефон номерин жазыңыз. / Введите номер телефона.',
            'phone.unique' => 'Бул телефон номери менен конок мурунтан эле бар. / Гость с таким номером телефона уже существует.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error_guest', 'Конокту сактоодо ката кетти. / Ошибка при сохранении гостя.');
        }

        Guest::create([
            'name' => $request->name,
            'nickname' => $request->nickname,
            'phone' => $request->phone,
            'info' => $request->info ?? '',
            'id_folder' => $request->id_folder,
            'id_user' => $user->id,
        ]);

        return back()->with('success_guest', 'Конок ийгиликтүү кошулду. / Гость успешно добавлен.');
    }

    public function updateGuest(Request $request, Guest $guest)
    {
        $user = Auth::user();

        if ($guest->id_user !== $user->id) {
            return back()->with('error_guest', 'Сизде бул конокту өзгөртүүгө укугуңуз жок. / У вас нет прав на редактирование этого гостя.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                Rule::unique('guests')->where(function ($query) use ($user) {
                    return $query->where('id_user', $user->id);
                })->ignore($guest->id),
            ],
            'nickname' => 'nullable|string',
            'info' => 'nullable|string',
            'id_folder' => 'nullable|exists:folders,id,id_user,' . $user->id,
        ], [
            'name.required' => 'Коноктун аты-жөнүн жазыңыз. / Введите имя гостя.',
            'phone.required' => 'Телефон номерин жазыңыз. / Введите номер телефона.',
            'phone.unique' => 'Бул телефон номери менен конок мурунтан эле бар. / Гость с таким номером телефона уже существует.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error_guest', 'Конокту жаңыртууда ката кетти. / Ошибка при обновлении гостя.');
        }

        $guest->update([
            'name' => $request->name,
            'nickname' => $request->nickname,
            'phone' => $request->phone,
            'info' => $request->info ?? '',
            'id_folder' => $request->id_folder,
        ]);

        return back()->with('success_guest', 'Конок ийгиликтүү жаңыртылды. / Гость успешно обновлен.');
    }

    public function destroyGuest(Guest $guest)
    {
        $user = Auth::user();

        if ($guest->id_user !== $user->id) {
            return back()->with('error_guest', 'Сизде бул конокту өчүрүүгө укугуңуз жок. / У вас нет прав на удаление этого гостя.');
        }

        $guest->delete();

        return back()->with('success_guest', 'Конок ийгиликтүү өчүрүлдү. / Гость успешно удален.');
    }

    public function uploadInvitation(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png|max:5048',
        ]);

        // Файлды сактоо
        $path = $request->file('image')->store('invitations', 'public');

        // Толук URL түзүү
        $fullUrl = asset('storage/' . $path);

        return response()->json(['url' => $fullUrl]);
    }

}
