@extends('public.layout.base')

@section('title', 'Гости пользователя ' . $user->name)

@section('content')
<div class="max-w-7xl mx-auto my-2 py-8 sm:py-12 min-h-screen">
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center">
                <h1 class="card-title text-2xl">Гости пользователя: {{ $user->name }}</h1>
                <a href="{{ route('admin.index') }}" class="btn btn-ghost">Назад к пользователям</a>
            </div>

            <div class="overflow-x-auto mt-6">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Никнейм</th>
                            <th>Телефон</th>
                            <th>Папка</th>
                            <th>Түзүлгөн убактысы</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guests as $guest)
                            <tr class="hover">
                                <th>{{ $guest->id }}</th>
                                <td>{{ $guest->name }}</td>
                                <td>{{ $guest->nickname }}</td>
                                <td>{{ $guest->phone }}</td>
                                <td>{{ $guest->folder->name ?? 'N/A' }}</td>
                                <td>{{ $guest->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8">У этого пользователя нет гостей.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $guests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
