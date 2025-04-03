@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Welcome, {{ $patient->name }}</h2>

    <div class="card mt-3">
        <div class="card-header">Patient Details</div>
        <div class="card-body">
            <p><strong>Email:</strong> {{ $patient->user->email }}</p>
            <p><strong>Phone:</strong> {{ $patient->phone }}</p>
            <p><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</p>
            <p><strong>Date of Birth:</strong> {{ $patient->date_of_birth }}</p>
            <p><strong>Address:</strong> {{ $patient->address }}</p>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">Book an Appointment</div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('appointments.book') }}">
                @csrf
                <div class="mb-3">
                    <label for="doctor_id">Select Doctor</label>
                    <select name="doctor_id" class="form-control" required>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="date">Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="time">Time</label>
                    <input type="time" name="time" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Book Appointment</button>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">Your Appointments</div>
        <div class="card-body">
            @if($appointments->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->doctor->name }}</td>
                            <td>{{ $appointment->date }}</td>
                            <td>{{ $appointment->time }}</td>
                            <td>{{ ucfirst($appointment->status) }}</td>
                            <td>
                                <form method="POST" action="{{ route('appointments.update', $appointment->id) }}" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="date" name="date" class="form-control d-inline w-50" required>
                                    <input type="time" name="time" class="form-control d-inline w-50" required>
                                    <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                </form>

                                <form method="POST" action="{{ route('appointments.cancel', $appointment->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                </form>
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
