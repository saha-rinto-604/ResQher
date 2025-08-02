@extends('layout.admin')

@section('styles')
    <style>
        .document-images-wrapper {
            display: flex;
            gap: 10px;
        }

        .document-image {
            position: relative;
            width: 60px;
            height: 60px;
            overflow: hidden;
            border-radius: 5px;
        }

        .document-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .document-image p {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            font-size: 10px;
            text-align: center;
            margin: 0;
            padding: 2px 0;
        }

        .hoverable {
            cursor: pointer;

            &:hover {
                color: var(--primary);
            }
        }
    </style>
@endsection

@section('contents')
    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-15">
        <div class="d-flex align-items-center gap-8">
            <div class="icon text-title text-23">
                <i class="ri-terminal-line"></i>
            </div>
            <h6 class="card-title text-18">Mange Volunteers</h6>
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
                    <th>Location</th>
                    <th>Status</th>
                    <th class="text-start">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($volunteers ?? [] as $volunteer)
                    <tr>
                        <td class="min-w-45">{{ $loop->iteration }}</td>
                        <td class="min-w-45">{{ $volunteer->id }}</td>
                        <td>{{ $volunteer->name }}</td>
                        <td>{{ $volunteer->username }}</td>
                        <td>{{ $volunteer->email }}</td>
                        <td>{{ $volunteer->phone ?? '' }}</td>
                        <td>
                            @if($volunteer->documents)
                                <div class="document-images-wrapper align-items-center">
                                    <div class="document-image">
                                        <a href="{{ asset("assets/uploads/volunteer/documents/{$volunteer->documents?->nid_front_side}") }}" target="_blank">
                                            <img src="{{ asset("assets/uploads/volunteer/documents/{$volunteer->documents?->nid_front_side}") }}"
                                                 alt="NID Front Side" class="document-image-view" width="60" height="60">
                                            <p>NID - Front</p>
                                        </a>
                                    </div>

                                    <div class="document-image">
                                        <a href="{{ asset("assets/uploads/volunteer/documents/{$volunteer->documents?->nid_back_side}") }}" target="_blank">
                                            <img src="{{ asset("assets/uploads/volunteer/documents/{$volunteer->documents?->nid_back_side}") }}"
                                                 alt="NID Back Side" class="document-image-view" width="60" height="60">
                                            <p>NID - Back</p>
                                        </a>
                                    </div>

                                    <div class="document-image">
                                        <a href="{{ asset("assets/uploads/volunteer/documents/{$volunteer->documents?->student_id_card}") }}" target="_blank">
                                            <img src="{{ asset("assets/uploads/volunteer/documents/{$volunteer->documents?->student_id_card}") }}"
                                                 alt="Student ID Card" class="document-image-view" width="60" height="60">
                                            <p>Student ID</p>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($volunteer->documents)
                                <p class="location-coordinate" data-lat="{{ $volunteer->documents?->latitude }}" data-lng="{{ $volunteer->documents?->longitude }}">{{ $volunteer->documents?->latitude }}, {{ $volunteer->documents?->longitude }}</p>
                            @endif
                        </td>
                        <td class="{{ $volunteer->documents?->approved ? 'text-success' : 'text-warning' }}">{{ $volunteer->documents?->approved ? 'Approved' : 'Not Approved' }}</td>
                        <td>
                            @if(! $volunteer->documents?->approved)
                                <a class="btn btn-success-fill btn-sm"
                                   href="{{ route('admin.volunteer.approve', $volunteer->id) }}">
                                    Approve
                                </a>
                            @else
                                <a class="btn btn-danger-fill btn-sm"
                                   href="{{ route('admin.volunteer.cancel', $volunteer->id) }}">
                                    Cancel
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            function getLocationName () {
                let coordinate_markup = $('.location-coordinate');

                if (coordinate_markup.length > 0) {
                    coordinate_markup.each(function (index, value) {
                        let coordinate = $(value).data('lat') + ',' + $(value).data('lng');

                        if (coordinate) {
                            let apiKey = 'AIzaSyCz8_vQnXWI4K3xbDTLtpDGkp82rG3FZZU';
                            let url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${coordinate}&key=${apiKey}`;

                            $.ajax({
                                url: url,
                                method: 'GET',
                                success: function (response) {
                                    if (response.status === 'OK') {
                                        let results = response.results;
                                        let address = results.length > 0 ? results[0].formatted_address : 'Location not found';

                                        $(value).text(address);
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.error('Error fetching location name:', error);
                                }
                            })
                        }

                        $(value).addClass('hoverable');
                        $(value).on('click', function () {
                            let lat = $(this).data('lat');
                            let lng = $(this).data('lng');
                            let mapUrl = `https://www.google.com/maps/@${lat},${lng},15z`;
                            window.open(mapUrl, '_blank');
                        });
                    });
                }
            }

            setTimeout(() => {
                getLocationName();
            }, 1000);
        });
    </script>
@endsection
