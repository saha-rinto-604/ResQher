@extends('layout.volunteer')

@section('styles')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{config('app.google_map_api_key')}}&loading=async&libraries=maps,geometry,places,marker&v=beta"
        defer></script>

    <style>
        body, html {
            height: 100%;
            margin: 0;
        }

        .alerts-notification-wrapper {
            height: 75vh;
            padding: 20px;
            padding-right: 10px;
            background-color: rgba(0, 113, 93, 0.06);
            border-radius: 5px;
            overflow-y: auto;
            scroll-behavior: smooth;

            /* For thin scrollbar on supported browsers */
            scrollbar-width: thin; /* Firefox */
            scrollbar-color: transparent transparent;
        }

        .alerts-notification-wrapper:hover {
            scrollbar-color: rgba(220, 53, 69, 0.44) transparent;
        }

        .sos-card {
            padding: 15px;
            border-left: 5px solid transparent;
            transition: transform 0.2s;
            border-radius: 5px;
        }

        .sos-card:hover {
            border-left: 5px solid #dc3545;
        }

        .sos-card .card-body {
            padding: 0;
        }

        .sos-time {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .alert-icon {
            font-size: 1.5rem;
            color: #dc3545;
        }

        /*#map-container {*/
        /*    position: relative;*/
        /*    width: 100%;*/
        /*    height: 100dvh;*/
        /*}*/

        #map {
            width: 100%;
            height: 75vh;
        }

        .marker-label {
            margin-bottom: 50px;
        }
    </style>
@endsection

@section('contents')
    <div class="row">
        <div class="col-md-3">
            <h1 class="text-center mb-2 fs-3 alert-section-title">Active SOS Alerts</h1>
            <div class="alerts-notification-wrapper">
                <div class="d-flex flex-column gap-15">
                    <div class="card sos-card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h5 class="card-title mb-0 text-dark">Alert from <span
                                            class="text-danger">Unknown</span></h5>
                                    <p class="sos-time mt-2">0 minutes ago</p>
                                    <a href="#" class="btn btn-sm btn-danger mt-3">View Location</a>
                                </div>
                                <span class="alert-icon">🚨</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(! $user?->approved ?? true)
            <div class="col-md-9">
                <div class="alert alert-danger d-flex justify-content-start align-items-center gap-15">
                    <i class="ri-error-warning-line" style="font-size: 30px"></i>
                    <h5>Your Account is Not Verified Yet. Kindly Wait for Admins to Verify Your Documents.</h5>
                </div>
            </div>
        @else
            <div class="col-md-9">
                <div id="map-container">
                    <div id="map"></div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            let active_victim_id = 0;
            let alert_count = 0;

            const getLatestVictims = () => {
                $.ajax({
                    url: "{{ route('volunteer.dashboard.latest-victims') }}",
                    type: "GET",
                    success: function (response) {
                        if (response.status) {
                            let sos_cards = '';
                            $.each(response.latest_victims, function (index, value) {
                                let button_text = active_victim_id === value.user_id ? 'Viewing This Location' : 'View Location';
                                let button_class = active_victim_id === value.user_id ? 'btn btn-sm btn-dark mt-3' : 'btn btn-sm btn-danger mt-3';
                                let card_style = active_victim_id === value.user_id ? 'border-left: 5px solid #000000' : '';

                                sos_cards += `
                                    <div class="card sos-card shadow-sm" style="${card_style}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <h5 class="card-title mb-0 text-dark">Alert from <span class="text-danger">${value.name}</span></h5>
                                                    <p class="sos-time mt-2">${value.alert_time}</p>
                                                    <a href="javascript:void(0)" class="view-location-btn ${button_class}" data-victim-id="${value.user_id}" data-latitude="${value.latitude}" data-longitude="${value.longitude}">${button_text}</a>
                                                </div>
                                                <span class="alert-icon">🚨</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });

                            if (sos_cards !== '') {
                                $('.alert-section-title').text(`Active SOS Alerts (${response.latest_victims_count})`);
                                $('.alerts-notification-wrapper .d-flex').html(sos_cards);

                                alert_count = response.latest_victims_count;
                            } else {
                                $('.alerts-notification-wrapper .d-flex').html(`
                                    <div class="card sos-card shadow-sm">
                                        <div class="card-body">
                                            <p class="text-center">No active SOS alert</p>
                                        </div>
                                    </div>
                                `);

                                $('.alert-section-title').text('Active SOS Alerts');
                                alert_count = 0;
                            }

                            if (alert_count === 0) {
                                selfReInit();
                            }
                        } else {
                            console.error("Error fetching latest victims:", response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching alerts:", error);
                    }
                });
            };

            getLatestVictims();
            setInterval(getLatestVictims, 3000); // Fetch every 5 seconds

            var map;
            var myMarker;
            var victimMarker;
            var currentLat = null;
            var currentLng = null;
            var myLat = null;
            var myLng = null;

            function initMap() {
                let userLocation = {lat: currentLat, lng: currentLng};

                map = new google.maps.Map(document.getElementById('map'), {
                    center: userLocation,
                    zoom: 16
                });

                navigator.geolocation.getCurrentPosition(function (position) {
                    myLat = position.coords.latitude;
                    myLng = position.coords.longitude;

                    let myLocation = {lat: myLat, lng: myLng};

                    myMarker = new google.maps.Marker({
                        position: myLocation,
                        map: map,
                        animation: google.maps.Animation.DROP
                    });
                });

                victimMarker = new google.maps.Marker({
                    position: userLocation,
                    map: map,
                    title: "victim location",
                    label: {
                        text: "Victim",
                        color: "#FF0000",
                        fontSize: "13px",
                        fontWeight: "bold",
                        className: "marker-label"
                    },
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 8,
                        fillColor: "#FF0000",
                        fillOpacity: 1,
                        strokeWeight: 10,
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.2,
                    }
                });
            }

            function getLocation(victim_id) {
                $.ajax({
                    url: `{{ route('volunteer.dashboard.victim-coordinate') }}`,
                    type: 'POST',
                    data: {
                        victim_id: victim_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status) {
                            currentLat = parseFloat(response.latitude);
                            currentLng = parseFloat(response.longitude);
                        } else {
                            console.error("Error fetching location:", response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching location:", error);
                    }
                });
            }

            function updateSelfLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        myLat = position.coords.latitude;
                        myLng = position.coords.longitude;

                        $.ajax({
                            url: "{{ route('volunteer.dashboard.update-self-location') }}",
                            type: "POST",
                            data: {
                                latitude: myLat,
                                longitude: myLng,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.status) {
                                    console.log('Location updated self successfully:', response.message);
                                } else {
                                    console.error("Error updating self location");
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('Error updating self location:', error);
                            }
                        });
                    });
                } else {
                    console.log("Geolocation is not supported by this browser.");
                }
            }

            function updateMarkerPosition() {
                if (currentLat !== null && currentLng !== null) {
                    let victimCoordinate = {lat: currentLat, lng: currentLng};
                    victimMarker.setPosition(victimCoordinate);

                    map.setCenter(victimCoordinate);
                }

                let selfCoordinate = {lat: myLat, lng: myLng};
                myMarker.setPosition(selfCoordinate);
            }

            var intervalId = null;
            $(document).on('click', '.view-location-btn', function () {
                let el = $(this);
                let victim_id = el.data('victim-id');
                let latitude = el.data('latitude');
                let longitude = el.data('longitude');

                el.text('Loading...');
                active_victim_id = victim_id;

                currentLat = parseFloat(latitude);
                currentLng = parseFloat(longitude);

                initMap();

                if (intervalId) {
                    clearInterval(intervalId);
                }

                intervalId = setInterval(() => {
                    getLocation(victim_id);
                    updateSelfLocation();
                    updateMarkerPosition();
                }, 3000);
            });

            function selfInit() {
                if (navigator.geolocation) {
                    setTimeout(() => {
                        navigator.geolocation.getCurrentPosition(function (position) {
                            let selfLocation = {lat: position.coords.latitude, lng: position.coords.longitude};

                            let myMap = new google.maps.Map(document.getElementById('map'), {
                                center: selfLocation,
                                zoom: 16
                            });

                            new google.maps.Marker({
                                position: selfLocation,
                                map: myMap,
                                animation: google.maps.Animation.DROP
                            });
                        });
                    }, 1000);
                }

                updateSelfLocation();
            }
            selfInit();

            function selfReInit() {
                navigator.geolocation.getCurrentPosition(function (position) {
                    let mlat = position.coords.latitude;
                    let mlng = position.coords.longitude;

                    let selfLocation = {lat: mlat, lng: mlng};

                    if (myMarker) {
                        myMarker.setPosition(selfLocation);

                        if (victimMarker) {
                            victimMarker.setMap(null);
                        }
                    }
                });
            }
        });
    </script>
@endsection
