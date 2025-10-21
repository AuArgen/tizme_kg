@extends('public.layout.base')

@section('title', 'Мои Гости')

@push('styles')
<style>
    /* Custom font for invitations */
    @font-face {
        font-family: 'Anastasia Script';
        src: url('{{ asset("public/font/AnastasiaScript.ttf") }}') format('truetype');
    }
</style>
@endpush

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

                    {{-- Search and Filters --}}
                    <div class="mb-4 p-4 bg-base-200 rounded-lg">
                        <form action="{{ route('client.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
                            <div class="form-control w-full md:flex-grow">
                                <input type="text" name="search" placeholder="Поиск по имени или телефону..." value="{{ $searchQuery ?? '' }}" class="input input-bordered w-full">
                            </div>
                            <div class="form-control w-full md:w-auto">
                                <select name="per_page" onchange="this.form.submit()" class="select select-bordered w-full">
                                    @foreach([10, 100, 200, 500, 1000, 2000, 10000] as $p)
                                        <option value="{{ $p }}" @if($p == $perPage) selected @endif>{{ $p }} на странице</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-full md:w-auto">Поиск</button>
                             @if(request()->has('search') || request()->has('folder_id'))
                                <a href="{{ route('client.index') }}" class="btn btn-ghost">Сбросить</a>
                            @endif
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
                                    <th>{{ $loop->iteration + $guests->firstItem() - 1 }}</th>
                                    <td>
                                        <div class="font-bold">{{ $guest->name }}</div>
                                        @if($guest->nickname)<div class="text-sm opacity-60">{{ $guest->nickname }}</div>@endif
                                    </td>
                                    <td>{{ $guest->phone }}</td>
                                    <td>{{ $guest->folder->name ?? '' }}</td>
                                    <td class="text-right space-x-1">
                                        <div class="dropdown dropdown-end">
                                            <label tabindex="0" class="btn btn-primary btn-sm">Чакыруу</label>
                                            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 z-[10]">
                                                <li><a @click.prevent="openInvitationModal({{ json_encode($guest) }}, '{{ asset('assets/fon_one.png') }}')">Чакыруу 1</a></li>
                                                <li><a @click.prevent="openInvitationModal({{ json_encode($guest) }}, '{{ asset('assets/fon_two.png') }}')">Чакыруу 2</a></li>
                                            </ul>
                                        </div>
                                        <button @click="openGuestModal(true, {{ json_encode($guest) }})" class="btn btn-ghost btn-sm btn-square" title="Редактировать">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                        </button>
                                        <button @click="openDeleteModal('{{ route('client.guests.destroy', $guest) }}', 'гостя {{ $guest->name }}')" class="btn btn-ghost btn-sm btn-square text-error" title="Удалить">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-8">У вас пока нет гостей. Нажмите "Добавить гостя", чтобы начать.</td></tr>
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
        <div class="drawer-side mt-[60px] mb:mt-[0]">
            <label for="my-drawer-2" class="drawer-overlay"></label>
            <div class="p-4 w-80 min-h-full bg-base-200 text-base-content">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Папки</h2>
                    <button @click="openFolderModal()" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" /></svg>
                        Создать
                    </button>
                </div>

                <ul class="menu text-base">
                    <li><a href="{{ route('client.index') }}" @class(['active' => !request('folder_id')])>Все гости</a></li>
                    @foreach ($folders as $folder)
                        @include('client.component.folder_item', ['folder' => $folder])
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    @include('client.component.modals')

</div>

<script>
function guestManager() {
    return {
        showFolderModal: false,
        showGuestModal: false,
        showDeleteModal: false,
        showInvitationModal: false,
        isEdit: false,
        deleteUrl: '',
        deleteItemName: '',
        folderData: {},
        guestData: {},
        invitationGuest: {},
        invitationImageUrl: '',
        invitationLoading: true,
        generatedImageBlob: null,

        openFolderModal(isEdit = false, folder = null, parentId = null) {
            this.isEdit = isEdit;
            this.folderData = isEdit && folder ? { ...folder } : { id: null, name: '', info: '', id_parent: parentId };
            this.showFolderModal = true;
        },

        openGuestModal(isEdit = false, guest = null, folderId = null) {
            this.isEdit = isEdit;
            this.guestData = isEdit && guest ? { ...guest } : { id: null, name: '', nickname: '', phone: '', info: '', id_folder: folderId };
            this.showGuestModal = true;
        },

        openDeleteModal(url, name) {
            this.deleteUrl = url;
            this.deleteItemName = name;
            this.showDeleteModal = true;
        },

        async openInvitationModal(guest, imageUrl) {
            this.invitationGuest = guest;
            this.invitationImageUrl = imageUrl;
            this.showInvitationModal = true;
            this.invitationLoading = true;
            this.generatedImageBlob = null;

            await this.$nextTick(); // Wait for canvas to be in the DOM
            this.generateInvitation();
        },

        generateInvitation() {
            const canvas = document.getElementById('invitationCanvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();
            img.crossOrigin = "Anonymous"; // Handle potential CORS issues
            img.src = this.invitationImageUrl;

            img.onload = () => {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);

                // --- Text Styling ---
                const guestName = this.invitationGuest.name;
                const fontSize = 57;
                const font = `'Anastasia Script', cursive`;
                ctx.font = `${fontSize}px ${font}`;
                ctx.fillStyle = '#fff'; // Text color
                ctx.textAlign = 'center';

                // --- Positioning ---
                // Convert 6cm to pixels (assuming 96 DPI)
                const topMarginCm = 12.5;
                const cmToPx = 37.795; // 1cm = 37.795px at 96 DPI
                const yPosition = topMarginCm * cmToPx;
                const xPosition = canvas.width / 2 - 50;

                ctx.fillText(guestName, xPosition, yPosition);

                // Store the generated image as a blob
                canvas.toBlob((blob) => {
                    this.generatedImageBlob = blob;
                    this.invitationLoading = false;
                }, 'image/png');
            };
            img.onerror = () => {
                console.error("Could not load image for canvas.");
                this.invitationLoading = false;
            };
        },

        async shareInvitation() {
            if (!this.generatedImageBlob) {
                alert('Сүрөт даяр эмес. / Изображение не готово.');
                return;
            }

            const guestName = this.invitationGuest.name;
            const fileName = `invitation-${guestName.replace(/\s+/g, '_')}.png`;
            const file = new File([this.generatedImageBlob], fileName, { type: 'image/png' });

            if (navigator.share) {
                try {
                    await navigator.share({
                        title: `Чакыруу - ${guestName}`,
                        text: `Урматтуу ${guestName}, сизди тойго чакырабыз!`,
                        files: [file]
                    });
                } catch (error) {
                    console.error('Error sharing:', error);
                }
            } else {
                alert('Бул браузерде бөлүшүү мүмкүн эмес. / Функция поделиться не поддерживается в этом браузере.');
            }
        }
    };
}
</script>
@endsection
