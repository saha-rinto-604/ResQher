@extends('layout.user')

@section('styles')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{config('app.google_map_api_key')}}&libraries=geometry,places"></script>

    <style>
        #map-container {
            position: relative;
            width: 100%;
            height: 80dvh;
        }

        #map {
            width: 100%;
            height: 80dvh;
        }

        .map-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            pointer-events: none;
        }

        .sos-btn-wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 999;
        }

        .sos-btn-wrapper button {
            border-radius: 50%;
            padding: 50px 50px;
            font-size: 30px;
            font-weight: bold;
            border: 5px solid #ffdcdc;
            cursor: pointer;
        }

        #tracking {
            display: none;
            position: absolute;
            top: 15%;
            left: 50%;
            z-index: 999;
            transform: translate(-50%, -50%);
            background-color: #FF0000;
            padding: 2px 10px;
        }

        #tracking .text-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.6rem;
        }

        #tracking i {
            font-size: 15px;
            color: #FFFFFF;
            font-weight: 800;
        }

        #tracking p {
            font-size: 15px;
            color: #FFFFFF;
            font-weight: 800;
        }

        .marker-label {
            margin-bottom: 50px;
        }
    </style>
@endsection

@section('contents')
    <div class="sos-btn-wrapper text-center">
        <button class="btn btn-danger" id="startTracking">
            <div>SOS</div>
            <p class="text-white">Press <span>3</span> Times</p>
        </button>
    </div>

    <div id="tracking">
        <div class="text-wrapper">
            <i class="ri-live-line"></i>
            <p>Tracking..</p>
        </div>
    </div>

    <div id="map-container">
        <div id="map"></div>
        <div class="map-overlay"></div>
    </div>

    <div class="modal fade" id="stopSosAlertModal" tabindex="-1" aria-labelledby="sosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sosModalLabel">SOS Alert</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h5 class="text-danger">Are you sure you want to stop the SOS alert?</h5>

                    <div class="form-group mt-3">
                        <label for="sosReason">Reason for SOS Alert:</label>
                        <textarea class="form-control" id="sosReason" name="sos-reason" rows="3"
                                  placeholder="Enter reason (optional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmStopSos">Stop</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            var map;
            var marker;
            var userPath = []; // Store location history
            var currentLat = null;
            var currentLng = null;
            var helpers = [];

            function initMap() {
                navigator.geolocation.getCurrentPosition(function (position) {
                    currentLat = position.coords.latitude;
                    currentLng = position.coords.longitude;

                    let userLocation = {lat: currentLat, lng: currentLng};

                    map = new google.maps.Map(document.getElementById('map'), {
                        center: userLocation,
                        zoom: 16
                    });

                    marker = new google.maps.Marker({
                        position: userLocation,
                        map: map,
                        animation: google.maps.Animation.DROP
                    });

                }, function (error) {
                    console.warn("Geolocation error:", error);
                });
            }

            // Function to Get Current Coordinates
            let isLocationUpdated = true;

            function getCurrentCoordinates() {
                getCurrentLatLng((coords) => {
                    if (coords) {
                        let lat = coords.lat;
                        let lng = coords.lng;

                        if (currentLat !== lat || currentLng !== lng) {
                            console.log(currentLat, ':', lat, ' | ', currentLng, ': ', lng);

                            currentLat = lat;
                            currentLng = lng;

                            var latLng = new google.maps.LatLng(lat, lng);
                            marker.setPosition(latLng);
                            map.setCenter(latLng);

                            // Save location for route drawing
                            userPath.push(latLng);
                            if (userPath.length > 1) {
                                drawRoute(userPath);
                            }

                            isLocationUpdated = true;
                        }

                        if (isLocationUpdated) {
                            updateLocation();
                            isLocationUpdated = false;
                        }
                    }
                });
            }

            function getCurrentLatLng(callback) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    let lat = position.coords.latitude;
                    let lng = position.coords.longitude;
                    callback({lat: lat, lng: lng});
                }, function (error) {
                    callback(null);
                });
            }

            // Function to Send Coordinates to Backend
            function updateLocation() {
                if (currentLat !== null && currentLng !== null) {
                    $.ajax({
                        url: `{{ route('user.dashboard.location.store') }}`,
                        type: 'POST',
                        data: {
                            latitude: currentLat,
                            longitude: currentLng,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.status) {
                                console.log(response.message);
                            } else {
                                console.error("Error updating location:", response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error updating location:", error);
                        }
                    });
                } else {
                    toast.error("Unable to get current location. Please enable location services.");
                    setTimeout(() => {
                        location.reload();
                    }, 500)
                }
            }

            // Function to Draw Route on Map
            function drawRoute(userPath) {
                var route = new google.maps.Polyline({
                    path: userPath,
                    geodesic: true,
                    strokeColor: "#FF0000",
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                });
                route.setMap(map);
            }

            function getHelpers() {
                $.ajax({
                    url: `{{ route('user.dashboard.helpers-coordinates') }}`,
                    type: 'GET',
                    success: function (response) {
                        if (response.status) {
                            helpers = response.helpers;
                            console.log('getting helpers:', helpers);
                        } else {
                            console.error("Error fetching helpers:", response);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching helpers:", error);
                    }
                });
            }

            let helperMarkers = {};

            function getHelpersLocation() {
                if (helpers.length > 0) {
                    helpers.forEach((helper) => {
                        const id = helper.user_id;
                        const lat = parseFloat(helper.latitude);
                        const lng = parseFloat(helper.longitude);
                        const newPosition = new google.maps.LatLng(lat, lng);

                        if (helperMarkers[id]) {
                            helperMarkers[id].setPosition(newPosition);
                        } else {
                            helperMarkers[id] = new google.maps.Marker({
                                position: newPosition,
                                map: map,
                                label: {
                                    text: helper.name,
                                    color: "#007b3b",
                                    fontSize: "13px",
                                    fontWeight: "bold",
                                    className: "marker-label"
                                },
                                icon: {
                                    path: google.maps.SymbolPath.CIRCLE,
                                    scale: 8,
                                    fillColor: "#00dd69",
                                    fillOpacity: 1,
                                    strokeWeight: 10,
                                    strokeColor: "#00b454",
                                    strokeOpacity: 0.2,
                                },
                            });
                        }
                    });
                }
            }

            // check if browser supports geolocation
            if (navigator.geolocation) {
                initMap();
            } else {
                alert("Geolocation is not supported by this browser.");
            }

            let intervalId = null;
            let startTracking = () => {
                if (navigator.geolocation) {
                    intervalId = setInterval(() => {
                        getCurrentCoordinates();

                        getHelpers();
                        getHelpersLocation();
                    }, 2500);
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }

            let stopTracking = () => {
                clearInterval(intervalId);
                toggleTrackingButton();
                toggleOverlyButton();
                toggleStopTrackingButton();
                location.reload();
            }

            let toggleOverlyButton = () => {
                $('.sos-btn-wrapper').toggleClass('d-none');
                $('.map-overlay').toggleClass('d-none');
            }

            let toggleTrackingButton = () => {
                $('#tracking').toggle();
            }

            let toggleStopTrackingButton = () => {
                $('.stop-tracking-wrapper').toggle();
            }

            // todo: user must press sos button 3 times to start tracking
            let sosClickCount = 0;
            $(document).on('click', '#startTracking', function () {
                sosClickCount++;
                let startTrackingBtn = $('#startTracking p span');
                startTrackingBtn.text(3 - sosClickCount);

                if (sosClickCount === 3) {
                    toggleOverlyButton();
                    startTracking();
                    toggleTrackingButton();
                    toggleStopTrackingButton();
                    sosClickCount = 0;
                    startTrackingBtn.text(3);
                }
            });

            $(document).on('click', '#stopSosAlertModal button#confirmStopSos', function () {
                let modal = $('#stopSosAlertModal');
                let reason = $('textarea').val().trim() || '';

                $.ajax({
                    url: `{{ route('user.dashboard.location.stop') }}`,
                    type: 'POST',
                    data: {
                        reason: reason,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status) {
                            modal.modal('hide');
                            stopTracking();
                        } else {
                            console.error("Error stopping SOS alert:", response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error stopping SOS alert:", error);
                    }
                });
            });

            window.onbeforeunload = function (event) {
                if (intervalId) {
                    event.preventDefault();
                    return 'You have unsaved changes. Are you sure you want to leave?';
                }
            };
        });
    </script>
@endsection
