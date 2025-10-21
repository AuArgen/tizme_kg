<!-- Folder Modal -->
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

<!-- Guest Modal -->
<div class="modal" :class="{'modal-open': showGuestModal}">
    <div class="modal-box w-11/12 max-w-lg">
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
                    <option :value="null">Без папки</option>
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
                <button type="submit" class="btn btn-primary" x-text="isEdit ? 'Сохранить' : 'Добавить'"></button>
            </div>
        </form>
        <button @click="showGuestModal = false" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </div>
</div>

<!-- Delete Confirmation Modal -->
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

<!-- Invitation Modal -->
<div class="modal" :class="{'modal-open': showInvitationModal}">
    <div class="modal-box w-11/12 max-w-2xl">
        <h3 class="font-bold text-lg">Чакыруу #<span x-text="invitationGuest.name"></span></h3>

        <div x-show="invitationLoading" class="text-center p-8">
            <span class="loading loading-lg loading-spinner"></span>
            <p>Сүрөт даярдалууда...</p>
        </div>

        <div x-show="!invitationLoading" class="space-y-4">
            <canvas id="invitationCanvas" class="w-full h-auto rounded-lg shadow-lg"></canvas>
            <div class="modal-action">
                <button type="button" @click="shareInvitation()" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" /></svg>
                    WhatsApp'ка жөнөтүү
                </button>
            </div>
        </div>

        <button @click="showInvitationModal = false" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </div>
</div>
