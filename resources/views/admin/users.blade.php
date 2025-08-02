@extends('layout.admin')

@section('styles')
    <style>
        .view-image:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('contents')
    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-15">
        <div class="d-flex align-items-center gap-8">
            <div class="icon text-title text-23">
                <i class="ri-terminal-line"></i>
            </div>
            <h6 class="card-title text-18">Mange Victims</h6>
        </div>
    </div>

    <div class="card">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive min-h-400">
            <table class="">
                <thead>
                <tr>
                    <th class="min-w-45">#</th>
                    <th class="min-w-45">ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Identity Details</th>
                    <th>Emergency Details</th>
                    <th>Had Incident</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    @foreach($victims ?? [] as $victim)
                        <td class="min-w-45">{{ $loop->iteration }}</td>
                        <td class="min-w-45">{{ $victim->id }}</td>
                        <td>{{ $victim->name }}</td>
                        <td>{{ $victim->username }}</td>
                        <td>{{ $victim->email }}</td>
                        <td>{{ $victim->phone ?? '' }}</td>
                        <td>
                            @if($victim->documents)
                                <ul>
                                    <li><span class="font-500 text-title text-capitalize">NID - Front :</span> <a
                                            href="{{ asset("assets/uploads/user/documents/{$victim->documents?->nid_front_side}") }}" class="view-image" target="_blank">View</a></li>
                                    <li><span class="font-500 text-title text-capitalize">NID - Back :</span> <a
                                            href="{{ asset("assets/uploads/user/documents/{$victim->documents?->nid_back_side}") }}" class="view-image" target="_blank">View</a></li>
                                    <li><span class="font-500 text-title text-capitalize">Student ID :</span> <a
                                            href="{{ asset("assets/uploads/user/documents/{$victim->documents?->student_id_card}") }}" class="view-image" target="_blank">View</a></li>
                                </ul>
                            @endif
                        </td>
                        <td>
                            @if($victim->documents)
                                <ul>
                                    <li><span class="font-500 text-title text-capitalize">Address : {{ $victim->documents->address ?? '' }}</span></li>
                                    <li><span class="font-500 text-title text-capitalize">Emergency Contact 1 : {{ $victim->documents->emergency_contact_1 ?? '' }}</span></li>
                                    <li><span class="font-500 text-title text-capitalize">Emergency Contact 2 : {{ $victim->documents->emergency_contact_2 ?? '' }}</span></li>
                                    <li><span class="font-500 text-title text-capitalize">Emergency Contact 3 : {{ $victim->documents->emergency_contact_3 ?? '' }}</span></li>
                                </ul>
                            @endif
                        </td>
                        <td>
                            {{ $victim->incident_count }}
                        </td>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
