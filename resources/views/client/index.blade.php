@extends('public.layout.base')

@section('title', 'Мои Гости')

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div x-data="guestManager()" class="max-w-7xl mx-auto my-2 py-8 sm:py-12 min-h-screen">

    {{-- Notification Area --}}
    <div class="px-4 mb-4 space-y-2">
        @if(session('success_folder'))
            <div class="alert alert-success shadow-lg"><span><svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{ session('success_folder') }}</span></div>
        @endif
        @if(session('error_folder'))
            <div class="alert alert-error shadow-lg"><span><svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{ session('error_folder') }}</span></div>
        @endif
        @if(session('success_guest'))
            <div class="alert alert-success shadow-lg"><span><svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{ session('success_guest') }}</span></div>
        @endif
        @if(session('error_guest'))
            <div class="alert alert-error shadow-lg"><span><svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{ session('error_guest') }}</span></div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error shadow-lg">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span><b>Ошибка валидации:</b></span>
                    <ul class="list-disc pl-5 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

        {{-- Page Content --}}
        <div class="drawer-content flex flex-col p-4 gap-4">
            <div class="lg:hidden flex justify-start">
                <label for="my-drawer-2" class="btn btn-square btn-ghost drawer-button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </label>
            </div>

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                        <h1 class="card-title text-2xl">Мои Гости</h1>
                        <button @click="openGuestModal()" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                            Добавить гостя
                        </button>
                    </div>

                    {{-- Search and Pagination --}}
                    <div class="mb-4 p-4 bg-base-200 rounded-lg">
                        <form action="{{ route('client.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
                            <input type="hidden" name="folder_id" value="{{ $selectedFolderId }}">
                            <div class="form-control w-full md:flex-grow">
                                <input type="text" name="search" placeholder="Поиск по имени или телефону..." value="{{ $query ?? '' }}" class="input input-bordered w-full">
                            </div>
                            <div class="form-control w-full md:w-auto">
                                <select name="per_page" onchange="this.form.submit()" class="select select-bordered w-full">
                                    @foreach([10, 100, 200, 500, 1000, 2000, 10000] as $p)
                                        <option value="{{ $p }}" @if($p == $perPage) selected @endif>{{ $p }} на странице</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-full md:w-auto">Поиск</button>
                        </form>
                    </div>

                    {{-- Guests Table --}}
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Имя / Никнейм</th>
                                    <th>Телефон</th>
                                    <th>Папка</th>
                                    <th class="text-right">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($guests as $guest)
                                <tr class="hover">
                                    <th>{{ $loop->iteration + ($guests->currentPage() - 1) * $guests->perPage() }}</th>
                                    <td>
                                        <div class="font-bold">{{ $guest->name }}</div>
                                        @if($guest->nickname)<div class="text-sm opacity-60">{{ $guest->nickname }}</div>@endif
                                    </td>
                                    <td>{{ $guest->phone }}</td>
                                    <td>
                                        @if($guest->folder)
                                            <div class="badge badge-ghost">{{ $guest->folder->name }}</div>
                                        @else
                                            <span class="opacity-50">Без папки</span>
                                        @endif
                                    </td>
                                    <td class="text-right space-x-1">
                                        <button @click="openGuestModal(true, {{ json_encode($guest) }})" class="btn btn-ghost btn-sm btn-square" title="Редактировать">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                        </button>
                                        <button @click="openDeleteModal('{{ route('client.guests.destroy', $guest) }}', 'гостя {{ $guest->name }}')" class="btn btn-ghost btn-sm btn-square text-error" title="Удалить">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-8">В этой папке гостей нет или по вашему запросу ничего не найдено.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $guests->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar with Folders --}}
        <div class="drawer-side">
            <label for="my-drawer-2" class="drawer-overlay"></label>
            <div class="p-4 w-80 min-h-full bg-base-200 text-base-content">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-xl font-bold">Папки</h2>
                    <button @click="openFolderModal()" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" /></svg>
                        Создать
                    </button>
                </div>
                <ul class="menu text-base">
                    <li>
                        <a href="{{ route('client.index') }}" class="@if(!$selectedFolderId) active @endif">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" /></svg>
                            Все гости
                        </a>
                    </li>
                </ul>
                <div class="divider my-1"></div>
                @php
                    function renderFolders($folders, $selectedFolderId) {
                        echo '<ul class="menu text-base">';
                        foreach ($folders as $folder) {
                            $isActive = $selectedFolderId == $folder->id;
                            echo '<li>';
                            echo '<details ' . (count($folder->children) > 0 ? 'open' : '') . '>';
                            echo '<summary class="flex justify-between items-center p-2 rounded-lg">';
                            echo '<a href="' . route('client.index', ['folder_id' => $folder->id]) . '" class="flex-grow ' . ($isActive ? 'font-bold' : '') . '">';
                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" /></svg>';
                            echo '<span>' . e($folder->name) . '</span>';
                            echo '<span class="badge badge-ghost badge-sm ml-2">' . $folder->guests_count . '</span>';
                            echo '</a>';
                            // Dropdown menu for actions
                            echo '<div class="dropdown dropdown-end">';
                            echo '<label tabindex="0" class="btn btn-ghost btn-xs btn-circle"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"></path></svg></label>';
                            echo '<ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-56 z-[10]" @click.stop>';
                            echo '<li><a @click.prevent="openGuestModal(false, null, ' . $folder->id . ')"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>Добавить гостя</a></li>';
                            echo '<li><a @click.prevent="openFolderModal(false, null, ' . $folder->id . ')"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" /></svg>Создать подпапку</a></li>';
                            echo '<div class="divider my-1"></div>';
                            echo '<li><a @click.prevent="openFolderModal(true, ' . htmlspecialchars(json_encode($folder)) . ')"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>Редактировать</a></li>';
                            echo '<li><a @click.prevent="openDeleteModal(\'' . route('client.folders.destroy', $folder) . '\', \'папку ' . e($folder->name) . '\')" class="text-error"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg>Удалить</a></li>';
                            echo '</ul>';
                            echo '</div>';
                            echo '</summary>';
                            // Subfolders
                            if (count($folder->children) > 0) {
                                echo '<ul>';
                                renderFolders($folder->children, $selectedFolderId);
                                echo '</ul>';
                            }
                            echo '</details>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
                    renderFolders($folders, $selectedFolderId);
                @endphp
            </div>
        </div>
    </div>

    {{-- Folder Modal --}}
    <div class="modal" :class="{'modal-open': showFolderModal}">
        <div class="modal-box">
            <h3 class="font-bold text-lg" x-text="isEdit ? 'Редактировать папку' : 'Создать новую папку'"></h3>
            <form :action="isEdit ? '{{ url('client/folders') }}/' + folderData.id : '{{ route('client.folders.store') }}'" method="POST" class="py-4 space-y-4">
                @csrf
                <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">
                <input type="hidden" name="id_parent" :value="folderData.id_parent">

                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Название папки</span></label>
                    <input type="text" name="name" x-model="folderData.name" class="input input-bordered w-full" required>
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Описание (необязательно)</span></label>
                    <textarea name="info" x-model="folderData.info" class="textarea textarea-bordered w-full"></textarea>
                </div>
                <div class="modal-action">
                    <button type="button" @click="showFolderModal = false" class="btn btn-ghost">Отмена</button>
                    <button type="submit" class="btn btn-primary" x-text="isEdit ? 'Сохранить' : 'Создать'"></button>
                </div>
            </form>
            <button @click="showFolderModal = false" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </div>
    </div>

    {{-- Guest Modal --}}
    <div class="modal" :class="{'modal-open': showGuestModal}">
        <div class="modal-box">
            <h3 class="font-bold text-lg" x-text="isEdit ? 'Редактировать гостя' : 'Добавить нового гостя'"></h3>
            <form :action="isEdit ? '{{ url('client/guests') }}/' + guestData.id : '{{ route('client.guests.store') }}'" method="POST" class="py-4 space-y-4">
                @csrf
                <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">

                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Имя</span></label>
                    <input type="text" name="name" x-model="guestData.name" class="input input-bordered w-full" required>
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Телефон</span></label>
                    <input type="text" name="phone" x-model="guestData.phone" class="input input-bordered w-full" required>
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Никнейм (необязательно)</span></label>
                    <input type="text" name="nickname" x-model="guestData.nickname" class="input input-bordered w-full">
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Папка</span></label>
                    <select name="id_folder" x-model="guestData.id_folder" class="select select-bordered w-full">
                        <option :value="null">Выберите папка*</option>
                        @php
                            function renderFolderOptions($folders, $prefix = '') {
                                foreach ($folders as $folder) {
                                    echo '<option value="' . $folder->id . '">' . $prefix . e($folder->name) . '</option>';
                                    if (count($folder->children) > 0) {
                                        renderFolderOptions($folder->children, $prefix . '-- ');
                                    }
                                }
                            }
                            renderFolderOptions($folders);
                        @endphp
                    </select>
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Доп. инфо (необязательно)</span></label>
                    <textarea name="info" x-model="guestData.info" class="textarea textarea-bordered w-full"></textarea>
                </div>
                <div class="modal-action">
                    <button type="button" @click="showGuestModal = false" class="btn btn-ghost">Отмена</button>
                    <button type="submit" class="btn btn-primary" x-text="isEdit ? 'Сохранить' : 'Добавить'" onclick=" this.disabled = true; this.form.submit();"></button>
                </div>
            </form>
            <button @click="showGuestModal = false" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal" :class="{'modal-open': showDeleteModal}">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Подтвердите удаление</h3>
            <p class="py-4">Вы уверены, что хотите удалить <b x-text="deleteItemName"></b>? Это действие необратимо.</p>
            <form :action="deleteUrl" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-action">
                    <button type="button" @click="showDeleteModal = false" class="btn btn-ghost">Отмена</button>
                    <button type="submit" class="btn btn-error">Удалить</button>
                </div>
            </form>
            <button @click="showDeleteModal = false" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </div>
    </div>

</div>

<script>
function guestManager() {
    return {
        showFolderModal: false,
        showGuestModal: false,
        showDeleteModal: false,
        isEdit: false,
        deleteUrl: '',
        deleteItemName: '',
        folderData: {},
        guestData: {},

        openFolderModal(isEdit = false, folder = null, parentId = null) {
            this.isEdit = isEdit;
            if (isEdit && folder) {
                this.folderData = { ...folder };
            } else {
                this.folderData = { id: null, name: '', info: '', id_parent: parentId };
            }
            this.showFolderModal = true;
        },

        openGuestModal(isEdit = false, guest = null, folderId = null) {
            this.isEdit = isEdit;
            if (isEdit && guest) {
                this.guestData = { ...guest };
            } else {
                this.guestData = { id: null, name: '', nickname: '', phone: '', info: '', id_folder: folderId };
            }
            this.showGuestModal = true;
        },

        openDeleteModal(url, name) {
            this.deleteUrl = url;
            this.deleteItemName = name;
            this.showDeleteModal = true;
        },
    };
}
</script>
@endsection
