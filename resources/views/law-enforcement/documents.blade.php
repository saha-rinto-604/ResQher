@extends('layout.law-enforcement')
@section('title', 'Law Enforcement Documents')

@section('styles')
    <style>
        .image-wrapper {
            overflow: hidden;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }

        .document-image {
            transition: transform 0.3s ease;
        }

        .document-image-wrapper:hover .document-image {
            transform: scale(1.05);
        }

        .mandatory-sign {
            color: red;
        }
    </style>
@endsection

@section('contents')
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card border border-primary">
                    <div class="card-header bg-primary text-white">
                        Submit or See Your Document
                    </div>
                    <div class="card-body">
                        @if($volunteer_details)
                            <p class="fs-6 mb-4 text-primary-emphasis">Your documents have been submitted successfully. You can check the
                                status of your documents here.</p>

                            <hr class="border-primary">

                            <div class="row mb-5">
                                <div class="col-sm-4">
                                    <div class="document-image-wrapper">
                                        <a href="{{ asset("assets/uploads/volunteer/documents/{$volunteer_details->nid_front_side}") }}" target="_blank">
                                            <div class="image-wrapper">
                                                <img src="{{ asset("assets/uploads/volunteer/documents/{$volunteer_details->nid_front_side}") }}"
                                                     alt="NID Front Side" class="img-fluid document-image">
                                            </div>
                                            <p class="text-center mt-2 py-3">NID Front Side</p>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="document-image-wrapper">
                                        <a href="{{ asset("assets/uploads/volunteer/documents/{$volunteer_details->nid_back_side}") }}" target="_blank">
                                            <div class="image-wrapper">
                                                <img src="{{ asset("assets/uploads/volunteer/documents/{$volunteer_details->nid_back_side}") }}"
                                                     alt="NID Back Side" class="img-fluid document-image">
                                            </div>
                                            <p class="text-center mt-2 py-3">NID Back Side</p>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="document-image-wrapper">
                                        <a href="{{ asset("assets/uploads/volunteer/documents/{$volunteer_details->student_id_card}") }}" target="_blank">
                                            <div class="image-wrapper">
                                                <img src="{{ asset("assets/uploads/volunteer/documents/{$volunteer_details->student_id_card}") }}"
                                                     alt="NID Back Side" class="img-fluid document-image">
                                            </div>
                                            <p class="text-center mt-2 py-3">Student ID Card</p>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center gap-10">
                                <p class="fs-6 mb-4 text-primary-emphasis">Phone Number: {{ $volunteer_details->phone }}</p>
                                <p class="fs-6 mb-4 text-primary-emphasis location-coordinate" data-lat="{{ $volunteer_details->latitude }}" data-lng="{{ $volunteer_details->longitude }}">Location Coordinate: {{ $volunteer_details->latitude }}, {{ $volunteer_details->longitude }}</p>
                                <p class="fs-6 mb-4 text-primary-emphasis">Status: <span class="{{$volunteer_details->approved ? 'text-success' : 'text-warning'}}">{{ $volunteer_details->approved ? 'Approved' : 'Not Approved' }}</span></p>
                            </div>
                        @else
                            <p class="fs-6 mb-4">You have not submitted your document yet. Please submit your document to
                                continue.</p>

                            <p class="fs-6 mb-2">Kindly submit the following documents to continue the process:</p>
                            <ul>
                                <li>1. Image of your NID (Both side)</li>
                                <li>2. Student ID Card (If applicable)</li>
                            </ul>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $index => $error)
                                            <li>{{ ++$index }}. {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

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

                            <form class="my-4" action="{{ route('volunteer.documents') }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="nid-front">NID - Front Side<small
                                                class="mandatory-sign">*</small></label>
                                        <input type="file" class="form-control" id="nid-front" name="nid_front_side"
                                               accept="image/*">
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="nid-back">NID - Back Side<small
                                                class="mandatory-sign">*</small></label>
                                        <input type="file" class="form-control" id="nid-back" name="nid_back_side"
                                               accept="image/*">
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="student-id-card">Student ID Card</label>
                                        <input type="file" class="form-control" id="student-id-card"
                                               name="student_id_card" accept="image/*">
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="student-id-card">Set Current Location<small class="mandatory-sign">*</small></label>
                                        <div class="d-flex justify-content-between gap-5">
                                            <input type="text" class="form-control" id="student-id-card"
                                                   name="coordinate" placeholder="ep: 23.6950085, 90.5034782"
                                                   value="{{ old('coordinate') }}">
                                            <button class="auto-detect-location btn btn-warning btn-sm" type="button">Auto Detect</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start mt-4">
                                    <button class="btn btn-primary-fill" type="submit">Submit</button>
                                </div>
                            </form>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // get current coordinates
            $(document).on('click', '.auto-detect-location', function (e) {
                e.preventDefault();

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        let latitude = position.coords.latitude;
                        let longitude = position.coords.longitude;
                        $('input[name=coordinate]').val(latitude + ', ' + longitude);
                    }, function (error) {
                        alert('Error getting location');
                    });
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            });

            function getLocationName () {
                let coordinate_markup = $('.location-coordinate');

                if (coordinate_markup.length > 0) {
                    let coordinate = coordinate_markup.data('lat') + ',' + coordinate_markup.data('lng');

                    if (coordinate) {
                        let apiKey = 'AIzaSyCz8_vQnXWI4K3xbDTLtpDGkp82rG3FZZU';
                        let url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${coordinate}&key=${apiKey}`;

                        $.ajax({
                            url: url,
                            method: 'GET',
                            success: function (response) {
                                console.log(response);
                                if (response.status === 'OK') {
                                    let results = response.results;
                                    let address = results.length > 0 ? results[0].formatted_address : 'Location not found';

                                    coordinate_markup.text('Location Coordinate: ' + address);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Error fetching location name:', error);
                            }
                        })
                    }
                }
            }

            setTimeout(() => {
                getLocationName();
            }, 1000);
        });
    </script>
@endsection
