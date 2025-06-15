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
                ORDER Edit
                @else
                Tambah Data ORDER
                @endif
              </h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home.landingpage') }}">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Master Data</li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{ route('orders.index') }}">Order</a></li>
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
                    {{ route($resource . '.update', $record->getKey()) }}
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
                    @if ($field === 'customer_id')
                        <select name="customer_id" id="customer_id" class="form-control">
                            <option value="">-- Pilih Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->customer_id }}" {{ old('customer_id', $record->customer_id) == $customer->customer_id ? 'selected' : '' }}>
                                    {{ $customer->customer_id }} {{ $customer->customer_name }}
                                </option>
                            @endforeach
                        </select>
                    @elseif ($field === 'period_number')
                        <select name="period_number" id="period_number" class="form-control">
                            <option value="">-- Pilih Periode --</option>
                            @foreach ($periods as $period)
                                <option value="{{ $period->period_number }}" {{ old('period_number', $record->period_number) == $period->period_number ? 'selected' : '' }}>
                                    {{ $period->period_number }} ({{ $period->start_date }} - {{ $period->end_date }})
                                </option>
                            @endforeach
                        </select>
                    @elseif ($field === 'item_id')
                        <select name="item_id" id="item_id" class="form-control">
                            <option value="">-- Pilih Item --</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->item_id }}" {{ old('item_id', $record->item_id) == $item->item_id ? 'selected' : '' }}>
                                    {{ $item->item_id }} {{ $item->item_name }}
                                </option>
                            @endforeach
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
@endsection
