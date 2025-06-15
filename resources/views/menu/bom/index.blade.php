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
              <h5 class="m-b-10">BOM</h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home.landingpage') }}">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Master Data</li>
              <li class="breadcrumb-item active" aria-current="page">BOM</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="page-block">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if ($records->isEmpty())
        <div class="alert alert-warning">Tambahkan Data Terlebih Dahulu</div>
      @else
        <div class="accordion" id="bomAccordion">
          @foreach ($records as $itemId => $group)
            @php
              $itemName = $group->first()->item?->item_name ?? '-';
            @endphp
            <div class="card mb-2">
              <div class="card-header" id="heading{{ $itemId }}">
                <h2 class="mb-0">
                  <button class="btn btn-link text-left w-100 d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $itemId }}" aria-expanded="true" aria-controls="collapse{{ $itemId }}">
                    <span><strong>{{ $itemId }}</strong> - {{ $itemName }}</span>
                    <span class="badge bg-secondary">{{ $group->count() }} komponen</span>
                  </button>
                </h2>
              </div>
              <div id="collapse{{ $itemId }}" class="collapse" aria-labelledby="heading{{ $itemId }}" data-bs-parent="#bomAccordion">
                <div class="card-body p-0">
                  <table class="table table-bordered table-striped mb-0">
                    <thead>
                      <tr>
                        <th>Level</th>
                        <th>Component ID</th>
                        <th>Component Name</th>
                        <th>BOM Multiplier</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($group as $bom)
                        <tr>
                          <td>{{ $bom->level }}</td>
                          <td>{{ $bom->component_id }}</td>
                          <td>{{ $bom->component?->item_name ?? '-' }}</td>
                          <td>{{ number_format($bom->bom_multiplier, 6) }}</td>
                          <td>
                            @php
                              $key = ['parent_id' => $bom->item_id, 'child_id' => $bom->component_id];
                            @endphp
                            <a href="{{ route('boms.edit', $key) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('boms.destroy', $key) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this item?')">
                              @csrf
                              @method('DELETE')
                              <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
    <div class="page-block">
      <a href="{{ route('boms.create') }}" class="btn btn-success">Tambah Data</a>
    </div>
  </div>
</div>
@endsection