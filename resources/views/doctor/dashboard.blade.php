@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Doctor Dashboard</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mt-3">
        <div class="card-header">Filter Appointments</div>
        <div class="card-body">
            <form method="GET" action="{{ route('doctor.dashboard') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="patient_name" class="form-control" placeholder="Patient Name" value="{{ request('patient_name') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">Appointments</div>
        <div class="card-body">
            @if($appointments->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                            <th>Upload Medicine List</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->patient->name }}</td>
                            <td>{{ $appointment->date }}</td>
                            <td>{{ $appointment->time }}</td>
                            <td>{{ ucfirst($appointment->status) }}</td>
                            <td>
                                @if($appointment->status === 'Pending')
                                    <form method="POST" action="{{ route('appointments.accept', $appointment->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                    </form>

                                    <form method="POST" action="{{ route('appointments.reject', $appointment->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">Reject</button>
                                    </form>
                                @endif
                            </td>
                            <td>
                                @if($appointment->status === 'Accepted')
                                    <form method="POST" action="{{ route('appointments.upload-medicine', $appointment->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="medicine_list" accept=".pdf,.txt" required class="form-control">
                                        <button type="submit" class="btn btn-primary btn-sm mt-1">Upload</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No appointments found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
