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
              <h5 class="m-b-10">ITEM PERIOD</h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home.landingpage') }}">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Master Data</li>
              <li class="breadcrumb-item active" aria-current="page">Item Period</li>
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

      <div class="mb-3">
        <a href="{{ route('item_periods.index', ['sort' => 'item_id']) }}"
          class="btn btn-sm {{ request('sort') === 'item_id' ? 'btn-primary' : 'btn-outline-secondary' }}">
            Sort by Item ID
        </a>
        <a href="{{ route('item_periods.index', ['sort' => 'period_number']) }}"
          class="btn btn-sm {{ request('sort') === 'period_number' ? 'btn-primary' : 'btn-outline-secondary' }}">
            Sort by Period Number
        </a>
      </div>

      @if ($records->isEmpty())
        <div class="alert alert-warning">Tambahkan Data Terlebih Dahulu</div>
      @else
        @if(request('sort') === 'item_id')
          {{-- Group by Item ID --}}
          <div class="accordion" id="itemPeriodAccordion">
            @foreach ($records as $itemId => $group)
              @php
                  $itemName = $group->first()->item?->item_name ?? '-';
              @endphp
              <div class="card mb-2">
                <div class="card-header" id="headingItem{{ $itemId }}">
                  <h2 class="mb-0">
                    <button class="btn btn-link text-left w-100 d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapseItem{{ $itemId }}" aria-expanded="true" aria-controls="collapseItem{{ $itemId }}">
                      <span><strong>{{ $itemId }}</strong> - {{ $itemName }}</span>
                      <span class="badge bg-secondary">{{ $group->count() }} periode</span>
                    </button>
                  </h2>
                </div>
                <div id="collapseItem{{ $itemId }}" class="collapse" aria-labelledby="headingItem{{ $itemId }}" data-bs-parent="#itemPeriodAccordion">
                  <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                      <thead>
                        <tr>
                          <th>Item ID</th>
                          <th>Nama</th>
                          <th>Period Number</th>
                          <th>Gross Requirement</th>
                          <th>Projected Inventory</th>
                          <th>Planned Order Receipt</th>
                          <th>Planned Order Release</th>
                          <th>Created At</th>
                          <th>Updated At</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($group as $itemPeriod)
                          <tr>
                            <td>{{ $itemPeriod->item_id }}</td>
                            <td>{{ $itemPeriod->item?->item_name ?? '-' }}</td>
                            <td>{{ $itemPeriod->period_number }}</td>
                            <td>{{ $itemPeriod->gross_requirement }}</td>
                            <td>{{ $itemPeriod->projected_inventory }}</td>
                            <td>{{ $itemPeriod->planned_order_receipt }}</td>
                            <td>{{ $itemPeriod->planned_order_release }}</td>
                            <td>{{ $itemPeriod->created_at }}</td>
                            <td>{{ $itemPeriod->updated_at }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @elseif(request('sort') === 'period_number')
          {{-- Group by Period Number --}}
          <div class="accordion" id="periodAccordion">
            @foreach ($records as $periodNumber => $group)
              <div class="card mb-2">
                <div class="card-header" id="headingPeriod{{ $periodNumber }}">
                  <h2 class="mb-0">
                    <button class="btn btn-link text-left w-100 d-flex justify-content-between align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePeriod{{ $periodNumber }}" aria-expanded="true" aria-controls="collapsePeriod{{ $periodNumber }}">
                      <span><strong>Periode {{ $periodNumber }}</strong></span>
                      <span class="badge bg-secondary">{{ $group->count() }} item</span>
                    </button>
                  </h2>
                </div>
                <div id="collapsePeriod{{ $periodNumber }}" class="collapse" aria-labelledby="headingPeriod{{ $periodNumber }}" data-bs-parent="#periodAccordion">
                  <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                      <thead>
                        <tr>
                          <th>Item ID</th>
                          <th>Item Name</th>
                          <th>Gross Requirement</th>
                          <th>Projected Inventory</th>
                          <th>Planned Order Receipt</th>
                          <th>Planned Order Release</th>
                          <th>Created At</th>
                          <th>Updated At</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($group as $itemPeriod)
                          <tr>
                            <td>{{ $itemPeriod->item_id }}</td>
                            <td>{{ $itemPeriod->item?->item_name ?? '-' }}</td>
                            <td>{{ $itemPeriod->gross_requirement }}</td>
                            <td>{{ $itemPeriod->projected_inventory }}</td>
                            <td>{{ $itemPeriod->planned_order_receipt }}</td>
                            <td>{{ $itemPeriod->planned_order_release }}</td>
                            <td>{{ $itemPeriod->created_at }}</td>
                            <td>{{ $itemPeriod->updated_at }}</td>
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
      @endif
    </div>
    <div class="page-block">
      <a href="{{ route('items.create') }}" class="btn btn-success">Tambah Data</a>
    </div>
  </div>
</div>
@endsection