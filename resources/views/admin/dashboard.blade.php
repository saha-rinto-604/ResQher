@extends('layout.admin')

@section('styles')

@endsection

@section('contents')
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary rounded">
                <div class="card-body d-flex justify-content-between">
                    <h5 class="mb-0 text-white">Total Volunteers</h5>
                    <h5 class="fs-6 mb-0 text-white">{{ $total_volunteers }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success rounded">
                <div class="card-body d-flex justify-content-between">
                    <h5 class="mb-0 text-white">Approved Volunteers</h5>
                    <h5 class="fs-6 mb-0 text-white">{{ $approved_volunteers }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning rounded">
                <div class="card-body d-flex justify-content-between">
                    <h5 class="mb-0 text-white">Pending Volunteers</h5>
                    <h5 class="fs-6 mb-0 text-white">{{ $pending_volunteers }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card bg-primary rounded">
                <div class="card-body d-flex justify-content-between">
                    <h5 class="mb-0 text-white">Total Law Enforcement</h5>
                    <h5 class="fs-6 mb-0 text-white">{{ $total_volunteers }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success rounded">
                <div class="card-body d-flex justify-content-between">
                    <h5 class="mb-0 text-white">Approved Law Enforcement</h5>
                    <h5 class="fs-6 mb-0 text-white">{{ $approved_volunteers }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning rounded">
                <div class="card-body d-flex justify-content-between">
                    <h5 class="mb-0 text-white">Pending Law Enforcement</h5>
                    <h5 class="fs-6 mb-0 text-white">{{ $pending_volunteers }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card bg-primary rounded">
                <div class="card-body d-flex justify-content-between">
                    <h5 class="mb-0 text-white">Total Victims</h5>
                    <h5 class="fs-6 mb-0 text-white">{{ $total_victims }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger rounded">
                <div class="card-body d-flex justify-content-between">
                    <h5 class="mb-0 text-white">Total Incidents</h5>
                    <h5 class="fs-6 mb-0 text-white">{{ $total_incidents }}</h5>
                </div>
            </div>
        </div>
    </div>
@endsection
