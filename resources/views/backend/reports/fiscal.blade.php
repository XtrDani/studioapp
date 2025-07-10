@extends('adminlte::page')

@section('title', 'Raport Fiscal - Venituri')

@section('content_header')
<h1>Raport Fiscal - Venituri</h1>
@stop

@section('content')
<form method="GET" class="mb-4">
    <div class="form-row align-items-end">
        <div class="form-group col-md-3">
            <label for="from_date">De la</label>
            <input type="date" class="form-control" name="from_date" id="from_date" value="{{ request('from_date') }}">
        </div>
        <div class="form-group col-md-3">
            <label for="to_date">Până la</label>
            <input type="date" class="form-control" name="to_date" id="to_date" value="{{ request('to_date') }}">
        </div>
        <div class="form-group col-md-2">
            <button type="submit" class="btn btn-primary">Filtrează</button>
        </div>
    </div>
</form>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Venituri din programări finalizate</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Data</th>
                    <th>Client</th>
                    <th>Serviciu</th>
                    <th>Sumă</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $appointment->booking_date }}</td>
                        <td>{{ $appointment->name }}</td>
                        <td>{{ $appointment->service->title ?? '-' }}</td>
                        <td>{{ number_format($appointment->amount, 2) }} RON</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">Total venituri:</th>
                    <th>{{ number_format($total, 2) }} RON</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@stop