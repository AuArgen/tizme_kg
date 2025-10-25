@extends('public.layout.base')

@section('title', 'Панель Администратора - Пользователи')

@section('content')
<div class="max-w-7xl mx-auto my-2 py-8 sm:py-12 min-h-screen">
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h1 class="card-title text-2xl">Пользователи</h1>

            <div class="overflow-x-auto mt-6">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Количество гостей</th>
                            <th class="text-right">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="hover">
                                <th>{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->guests_count }}</td>
                                <td class="text-right">
                                    <a href="{{ route('admin.users.guests', $user) }}" class="btn btn-sm btn-primary">Посмотреть гостей</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8">Пользователи не найдены.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
