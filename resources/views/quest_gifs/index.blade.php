@extends('public.layout.base')

@section('title', 'Мои Quest GIFs')

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div x-data="questGifManager()" x-init="init()" class="max-w-7xl mx-auto my-2 py-8 sm:py-12 min-h-screen">

    {{-- Notification Area --}}
    <div class="px-4 mb-4 space-y-2">
        @if(session('success'))
            <div class="alert alert-success shadow-lg"><span><svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{ session('success') }}</span></div>
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
                        <h1 class="card-title text-2xl">Quest GIFs</h1>
                        <button @click="openQuestGifModal()" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                            Добавить запись
                        </button>
                    </div>

                    {{-- Search and Filters --}}
                    <div class="mb-4 p-4 bg-base-200 rounded-lg">
                        <form action="{{ route('client.quest_gifs.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
                            <div class="form-control w-full md:flex-grow">
                                <input type="text" name="search" placeholder="Поиск по гостю или информации..." value="{{ request('search') }}" class="input input-bordered w-full">
                            </div>
                            <button type="submit" class="btn btn-primary w-full md:w-auto">Поиск</button>
                             @if(request()->has('search'))
                                <a href="{{ route('client.quest_gifs.index') }}" class="btn btn-ghost">Сбросить</a>
                            @endif
                        </form>
                    </div>

                    {{-- Quest GIFs Table --}}
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Гость (Папка)</th>
                                    <th>Price</th>
                                    <th>Info</th>
                                    <th>Date</th>
                                    <th class="text-right">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questGifs as $questGif)
                                <tr class="hover">
                                    <td>
                                        {{ $questGif->guest->name ?? 'N/A' }}
                                        @if($questGif->guest && $questGif->guest->folder)
                                            <span class="text-sm opacity-60"> ({{ $questGif->guest->folder->name }})</span>
                                        @endif
                                    </td>
                                    <td>{{ $questGif->price }}</td>
                                    <td>{{ $questGif->info }}</td>
                                    <td>{{ $questGif->date }}</td>
                                    <td class="text-right space-x-1">
                                        <button @click="openQuestGifModal(true, {{ json_encode($questGif) }})" class="btn btn-ghost btn-sm btn-square" title="Редактировать">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                        </button>
                                        <button @click="openDeleteModal('{{ route('client.quest_gifs.destroy', $questGif) }}', 'запись #{{ $questGif->id }}')" class="btn btn-ghost btn-sm btn-square text-error" title="Удалить">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-8">Записей пока нет.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $questGifs->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar (can be used for filters or other content later) --}}
        <div class="drawer-side mt-[60px] md:mt-[0px]">
            <label for="my-drawer-2" class="drawer-overlay"></label>
            <div class="p-4 w-80 min-h-full bg-base-200 text-base-content">
                <h2 class="text-xl font-bold mb-4">Фильтры</h2>
                {{-- Filter content can be added here --}}
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('quest_gifs.modals')

</div>

<script>
function questGifManager() {
    return {
        showQuestGifModal: false,
        showDeleteModal: false,
        isEdit: false,
        deleteUrl: '',
        deleteItemName: '',
        questGifData: {},

        init() {
            @if($errors->any())
                this.isEdit = {{ old('_method') === 'PUT' ? 'true' : 'false' }};
                this.questGifData = {
                    id: {{ old('id') ?? 'null' }},
                    id_quest: '{{ old('id_quest') }}',
                    price: '{{ old('price') }}',
                    info: '{{ old('info') }}',
                    description: '{{ old('description') }}',
                    date: '{{ old('date') }}'
                };
                this.showQuestGifModal = true;
            @endif
        },

        openQuestGifModal(isEdit = false, questGif = null) {
            this.isEdit = isEdit;
            if (isEdit && questGif) {
                this.questGifData = { ...questGif };
            } else {
                this.questGifData = { id: null, id_quest: '', price: '', info: '', description: '', date: '' };
            }
            this.showQuestGifModal = true;
        },

        openDeleteModal(url, name) {
            this.deleteUrl = url;
            this.deleteItemName = name;
            this.showDeleteModal = true;
        }
    };
}
</script>
@endsection
