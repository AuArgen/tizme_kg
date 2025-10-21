@props(['folder', 'currentFolderId'])

@php
    // Calculate total guests recursively. This requires the relationship to be eager loaded.
    $guestCount = $folder->guests_count;
    if ($folder->relationLoaded('childrenRecursive')) {
        foreach ($folder->childrenRecursive as $child) {
            // This is a simplified count. For deep recursion, an accessor on the model is better.
            $guestCount += $child->guests_count;
        }
    }
@endphp

<li>
    <details open>
        <summary class="flex justify-between items-center pr-2">
            <a href="{{ route('client.index', ['folder_id' => $folder->id]) }}"
               class="flex-grow p-2 rounded-md hover:bg-base-300/60 {{ $currentFolderId == $folder->id ? 'bg-base-300 font-bold' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" /></svg>
                <span>{{ $folder->name }}</span>
                <span class="text-xs opacity-60 ml-1">({{ $guestCount }})</span>
            </a>
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost btn-xs btn-circle"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"></path></svg></label>
                <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52 z-[10]" @click.stop>
                    <li><a @click.prevent="openGuestModal(false, null, {{ $folder->id }})"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>Добавить гостя</a></li>
                    <li><a @click.prevent="openFolderModal(false, null, {{ $folder->id }})"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" /></svg>Создать подпапку</a></li>
                    <div class="divider my-1"></div>
                    <li><a @click.prevent="openFolderModal(true, {{ htmlspecialchars(json_encode($folder)) }})"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>Редактировать</a></li>
                    <li><a @click.prevent="openDeleteModal('{{ route('client.folders.destroy', $folder) }}', 'папку {{ e($folder->name) }}')" class="text-error"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" /></svg>Удалить</a></li>
                </ul>
            </div>
        </summary>
        @if(count($folder->childrenRecursive) > 0)
            <ul class="menu-sm pl-4">
                @foreach($folder->childrenRecursive as $childFolder)
                    @include('client.component.folder-item', ['folder' => $childFolder, 'currentFolderId' => $currentFolderId])
                @endforeach
            </ul>
        @endif
    </details>
</li>
