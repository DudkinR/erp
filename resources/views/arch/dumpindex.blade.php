@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Управління резервними копіями</h2>
        <form action="{{ route('archived-documents.dump.store') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-database-add"></i> Зробити резервну копію
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-archive"></i> Список резервних копій
        </div>
        <div class="card-body">
            @if(count($dumps) > 0)
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>                            
                            <th>Дата створення</th>
                            <th class="text-end">Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dumps as $dump)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $dump }}</td>
                                      <td class="text-end">
                                    <a href="{{ route('archived-documents.dump.show', $dump) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Переглянути
                                    </a>
                                    <form action="{{ route('archived-dump.destroy',  $dump) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Ви впевнені, що хочете видалити цей дамп?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Видалити
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">Резервних копій ще немає.</p>
            @endif
        </div>
    </div>
</div>
@endsection