{{-- Add/Edit Quest GIF Modal --}}
<div class="modal" :class="{'modal-open': showQuestGifModal}">
    <div class="modal-box w-11/12 max-w-lg">
        <h3 class="font-bold text-lg" x-text="isEdit ? 'Редактировать запись' : 'Добавить запись'"></h3>
        <form :action="isEdit ? '{{ url('client/quest_gifs') }}/' + questGifData.id : '{{ route('client.quest_gifs.store') }}'" method="POST" class="py-4 space-y-4">
            @csrf
            <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">

            <div class="form-control">
                <label for="id_quest" class="label"><span class="label-text">Гость</span></label>
                <select name="id_quest" x-model="questGifData.id_quest" class="select select-bordered w-full" required>
                    <option disabled value="">Выберите гостя</option>
                    @foreach ($groupedGuests as $folderName => $guests)
                        <optgroup label="📁 {{ $folderName }}">
                            @foreach ($guests as $guest)
                                <option value="{{ $guest->id }}">{{ $loop->iteration }}. {{ $guest->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="form-control">
                <label for="price" class="label"><span class="label-text">Price</span></label>
                <input type="number" name="price" x-model="questGifData.price" step="0.01" class="input input-bordered w-full" required>
            </div>
            <div class="form-control">
                <label for="info" class="label"><span class="label-text">Info</span></label>
                <input type="text" name="info" x-model="questGifData.info" class="input input-bordered w-full">
            </div>
            <div class="form-control">
                <label for="description" class="label"><span class="label-text">Description</span></label>
                <textarea name="description" x-model="questGifData.description" class="textarea textarea-bordered w-full"></textarea>
            </div>
            <div class="form-control">
                <label for="date" class="label"><span class="label-text">Date</span></label>
                <input type="date" name="date" x-model="questGifData.date" class="input input-bordered w-full">
            </div>

            <div class="modal-action">
                <button type="button" @click="showQuestGifModal = false" class="btn btn-ghost">Отмена</button>
                <button type="submit" class="btn btn-primary" x-text="isEdit ? 'Сохранить' : 'Создать'"></button>
            </div>
        </form>
        <button @click="showQuestGifModal = false" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
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
