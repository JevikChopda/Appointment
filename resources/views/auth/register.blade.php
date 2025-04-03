@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Register</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="role">Register As</label>
                            <select name="role" class="form-control" required id="roleSelect">
                                <option value="patient">Patient</option>
                            </select>
                        </div>

                        <div id="patientFields">
                            <div class="mb-3">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="gender">Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="address">Address</label>
                                <textarea name="address" class="form-control"></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    <div class="text-center mt-3">
                        Already have an account? <a href="{{ route('login') }}">Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('roleSelect').addEventListener('change', function() {
        let patientFields = document.getElementById('patientFields');
        patientFields.style.display = this.value === 'patient' ? 'block' : 'none';
    });
</script>

@endsection
