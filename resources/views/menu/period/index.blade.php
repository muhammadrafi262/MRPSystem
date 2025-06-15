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
              <h5 class="m-b-10">PERIOD</h5>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('home.landingpage') }}">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Master Data</li>
              <li class="breadcrumb-item active" aria-current="page">Period</li>
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
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              @foreach ($records->first()->getAttributes() as $key => $value)
                <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
              @endforeach
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($records as $record)
              <tr>
                @foreach ($record->getAttributes() as $value)
                  <td>{{ $value }}</td>
                @endforeach
                <td>
                  <a href="{{ route('periods.edit', $record->getKey()) }}" class="btn btn-sm btn-primary">Edit</a>
                  <form action="{{ route('periods.destroy', $record->getKey()) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this item?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
    <div class="page-block">
      <a href="{{ route('periods.create') }}" class="btn btn-success">Tambah Data</a>
    </div>
  </div>
</div>
@endsection