@extends('layouts.main')

@section('title', 'Заявки на игры')

@section('content')
<div class="admin-suggestions">
    <h1>Заявки на игры</h1>

    @if(session('success'))
        <div class="suggestion-success">{{ session('success') }}</div>
    @endif

    @if($suggestions->isEmpty())
        <p>Заявок пока нет.</p>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Название игры</th>
                    <th>Дата</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suggestions as $suggestion)
                    <tr>
                        <td>{{ $suggestion->id }}</td>
                        <td>{{ $suggestion->title }}</td>
                        <td>{{ $suggestion->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.suggestions.destroy', $suggestion) }}" method="POST" onsubmit="return confirm('Удалить заявку?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-wrapper">
            {{ $suggestions->links() }}
        </div>
    @endif
</div>
@endsection