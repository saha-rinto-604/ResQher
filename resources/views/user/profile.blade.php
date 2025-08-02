@extends('layout.user')

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
        .view-image:hover {
            text-decoration: underline;
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
                        @if($victim_details)
                            <div class="row mb-5">
                                <div class="col-sm-4">
                                    <div class="document-image-wrapper">
                                        <a href="{{ asset("assets/uploads/user/documents/{$victim_details->nid_front_side}") }}"
                                           target="_blank">
                                            <div class="image-wrapper">
                                                <img
                                                    src="{{ asset("assets/uploads/user/documents/{$victim_details->nid_front_side}") }}"
                                                    alt="NID Front Side" class="img-fluid document-image">
                                            </div>
                                            <p class="text-center mt-2 py-3">NID Front Side</p>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="document-image-wrapper">
                                        <a href="{{ asset("assets/uploads/user/documents/{$victim_details->nid_back_side}") }}"
                                           target="_blank">
                                            <div class="image-wrapper">
                                                <img
                                                    src="{{ asset("assets/uploads/user/documents/{$victim_details->nid_back_side}") }}"
                                                    alt="NID Back Side" class="img-fluid document-image">
                                            </div>
                                            <p class="text-center mt-2 py-3">NID Back Side</p>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="document-image-wrapper">
                                        <a href="{{ asset("assets/uploads/user/documents/{$victim_details->student_id_card}") }}"
                                           target="_blank">
                                            <div class="image-wrapper">
                                                <img
                                                    src="{{ asset("assets/uploads/user/documents/{$victim_details->student_id_card}") }}"
                                                    alt="NID Back Side" class="img-fluid document-image">
                                            </div>
                                            <p class="text-center mt-2 py-3">Student ID Card</p>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center gap-10">
                                <div class="">
                                    <p class="fs-6 mb-4 text-primary-emphasis">Name: {{ $victim->name }}</p>
                                    <p class="fs-6 mb-4 text-primary-emphasis">Username: {{ $victim->username }}</p>
                                    <p class="fs-6 mb-4 text-primary-emphasis">Phone Number: {{ $victim->phone }}</p>
                                    <p class="fs-6 mb-4 text-primary-emphasis">Email Address: {{ $victim->email }}</p>
                                </div>

                                <div class="">
                                    <p class="fs-6 mb-4 text-primary-emphasis">Emergency Phone
                                        Number 1: {{ $victim_details->emergency_contact_1 }}</p>
                                    <p class="fs-6 mb-4 text-primary-emphasis">Emergency Phone
                                        Number 1: {{ $victim_details->emergency_contact_2 }}</p>
                                    <p class="fs-6 mb-4 text-primary-emphasis">Emergency Phone
                                        Number 1: {{ $victim_details->emergency_contact_3 }}</p>
                                </div>
                            </div>
                        @else
                            <p class="fs-6 mb-4">You have not submitted your document yet. Please submit your document
                                to
                                continue.</p>

                            <p class="fs-6 mb-2">Kindly submit the following documents to continue the process:</p>
                            <ul>
                                <li>1. Image of your NID (Both side)</li>
                                <li>2. Student ID Card (If applicable)</li>
                            </ul>

                            <hr class="border-primary">
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

                            <form class="my-4" action="{{ route('user.profile') }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="nid-front">NID - Front Side<small
                                                class="mandatory-sign">*</small></label>
                                        <input type="file" class="form-control" id="nid-front" name="nid_front_side"
                                               accept="image/*">

                                        @if($victim_details?->nid_front_side)
                                            <a href="{{ asset('assets/uploads/user/documents/' . $victim_details->nid_front_side) }}"
                                               class="view-image" target="_blank">View NID Front Side</a>
                                        @endif
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="nid-back">NID - Back Side<small
                                                class="mandatory-sign">*</small></label>
                                        <input type="file" class="form-control" id="nid-back" name="nid_back_side"
                                               accept="image/*">
                                        @if($victim_details?->nid_back_side)
                                            <a href="{{ asset('assets/uploads/user/documents/' . $victim_details->nid_back_side) }}"
                                               class="view-image" target="_blank">View NID Back Side</a>
                                        @endif
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="student-id-card">Student ID Card</label>
                                        <input type="file" class="form-control" id="student-id-card"
                                               name="student_id_card" accept="image/*">
                                        @if($victim_details?->student_id_card)
                                            <a href="{{ asset('assets/uploads/user/documents/' . $victim_details->student_id_card) }}"
                                               class="view-image" target="_blank">View Student ID Card</a>
                                        @endif
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="address">Address<small
                                                class="mandatory-sign">*</small></label>
                                        <textarea class="form-control" name="address" id="address"
                                                  rows="5">{{ $victim_details->address ?? '' }}</textarea>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="emergency-contact-1">Emergency Contact Number 1</label>
                                        <input type="text" class="form-control" id="emergency-contact-1"
                                               name="emergency_contact_1"
                                               value="{{ $victim_details->emergency_contact_1 ?? '' }}">
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="emergency-contact-2">Emergency Contact Number 2</label>
                                        <input type="text" class="form-control" id="emergency-contact-2"
                                               name="emergency_contact_2"
                                               value="{{ $victim_details->emergency_contact_2 ?? '' }}">
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="emergency-contact-3">Emergency Contact Number 3</label>
                                        <input type="text" class="form-control" id="emergency-contact-3"
                                               name="emergency_contact_3"
                                               value="{{ $victim_details->emergency_contact_3 ?? '' }}">
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
