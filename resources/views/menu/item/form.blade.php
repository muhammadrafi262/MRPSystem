@extends('layouts.heading')

@section('content')
<div class="pc-container">
  <div class="pc-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h5 class="m-b-10">
                @if ($action == 'edit')
                ITEM Edit
                @else
                Tambah Data Item
                @endif
              </h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home.landingpage') }}">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Master Data</li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{ route('items.index') }}">Item</a></li>
              <li class="breadcrumb-item active" aria-current="page">
                @if ($action == 'edit')
                Edit
                @else
                Tambah Data
                @endif
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="card">
      @if (session('error'))
          <div class="alert alert-danger">
              {{ session('error') }}
          </div>
      @endif
      @if (session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
      @endif
      <div class="card-header">
        <h5>
                @if ($action == 'edit')
                Edit Data
                @else
                Tambah Data
                @endif
        </h5>
      </div>
      <div class="card-body">
        <form 
            action="
                @if ($action == 'create') 
                    {{ route($resource . '.store') }}
                @else 
                    @if ($resource == 'boms')
                        {{ route($resource . '.update', ['parent_id' => $record->item_id, 'child_id' => $record->component_id]) }}
                    @else
                        {{ route($resource . '.update', $record->getKey()) }}
                    @endif
                @endif
            " 
            method="POST">
            @csrf
            @if($action == 'edit')
                @method('PUT')
            @endif
            @foreach ($record->getFillable() as $field)
                <div class="form-group row">
                    <label class="col-form-label col-lg-4 col-sm-12 text-lg-end" for="{{ $field }}">{{ str_replace('_', ' ', $field) }}</label>
                    <div class="col-lg-6 col-md-11 col-sm-12">
                    @if ($field === 'tipe')
                        <select name="tipe" id="tipe" class="form-control">
                            <option value="statis" {{ old('tipe', $record->tipe) === 'statis' ? 'selected' : '' }}>Statis</option>
                            <option value="dinamis" {{ old('tipe', $record->tipe) === 'dinamis' ? 'selected' : '' }}>Dinamis</option>
                        </select>
                    @elseif(str_contains($field, '_date'))
                        <input 
                            type="text" 
                            name="{{ $field }}" 
                            id="{{ $field }}" 
                            class="form-control flatpickr" 
                            value="{{ old($field, $record->$field) }}" 
                            placeholder="YYYY-MM-DD"
                            {{ $action == 'edit' && $field === $record->getKeyName() ? 'readonly' : '' }}
                        >
                    @elseif(is_numeric(old($field, $record->$field)))
                        <input 
                            type="number" 
                            name="{{ $field }}" 
                            id="{{ $field }}" 
                            class="form-control" 
                            value="{{ old($field, $record->$field) }}"
                            {{ $action == 'edit' && $field === $record->getKeyName() ? 'readonly' : '' }}
                        >
                    @else
                        <input 
                            type="text" 
                            name="{{ $field }}" 
                            id="{{ $field }}" 
                            class="form-control" 
                            value="{{ old($field, $record->$field) }}"
                            {{ $action == 'edit' && $field === $record->getKeyName() ? 'readonly' : '' }}
                        >
                    @endif
                    </div>
                </div>
            @endforeach
                
            <button type="submit" class="btn btn-primary">{{ ucfirst($action) }}</button>
            <a href="{{ route($resource . '.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr('.flatpickr', { dateFormat: 'Y-m-d' });
    });
</script>
@endpush