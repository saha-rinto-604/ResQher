@extends('layout.user')

@section('styles')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{config('app.google_map_api_key')}}&libraries=geometry,places"></script>

    <style>
        #map-container {
            position: relative;
            width: 100%;
            height: 70dvh;
        }

        #map {
            width: 100%;
            height: 70dvh;
        }

        .marker-label {
            margin-bottom: 50px;
        }


    </style>
@endsection

@section('contents')
    <h4 class="text-center">Incident Histories <span class="loading">Loading..</span></h4>

    <div id="map-container">
        <div id="map"></div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            var map;
            var currentLat = null;
            var currentLng = null;

            function initMap() {
                navigator.geolocation.getCurrentPosition(function (position) {
                    currentLat = position.coords.latitude;
                    currentLng = position.coords.longitude;

                    let userLocation = {lat: currentLat, lng: currentLng};

                    map = new google.maps.Map(document.getElementById('map'), {
                        center: userLocation,
                        zoom: 14,
                        gestureHandling: 'cooperative',
                        zoomControl: false
                    });
                }, function (error) {
                    console.warn("Geolocation error:", error);
                });
            }

            function getIncidentLocations() {
                $.ajax({
                    url: `{{ route('user.history.incident.histories') }}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        latitude: currentLat,
                        longitude: currentLng
                    },
                    success: function (response) {
                        if (response.status) {
                            const incidents = response.histories;

                            if (incidents.length > 0) {
                                setIncidentLocations(incidents);
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching helpers:", error);
                    }
                });
            }

            function setIncidentLocations(incidents) {
                incidents.forEach((incident) => {
                    const id = incident.user_id;
                    const lat = parseFloat(incident.latitude);
                    const lng = parseFloat(incident.longitude);
                    const newPosition = new google.maps.LatLng(lat, lng);

                    const circle = new google.maps.Circle({
                        strokeColor: "#ff0000",
                        strokeOpacity: 0.2,
                        strokeWeight: 2,
                        fillColor: "#ff0000",
                        fillOpacity: 0.2,
                        map: map,
                        center: newPosition,
                        radius: 300, // radius in meters
                    });

                    const radiusInDegrees = 300 / 101320; // Convert meters to degrees (approximate)
                    const labelPosition = new google.maps.LatLng(
                        lat + radiusInDegrees,
                        lng
                    );

                    let fontSize = incident.description.length > 20 ? "8px" : "12px";

                    const labelMarker = new google.maps.Marker({
                        position: labelPosition,
                        map: map,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 0, // invisible icon
                            fillOpacity: 0
                        },
                        label: {
                            text: incident.description.length > 0 ? incident.description : 'No description',
                            color: "#ff0000",
                            fontSize: fontSize,
                            fontWeight: "bold",
                            className: "danger-label"
                        }
                    });

                    // Initially hide the label
                    labelMarker.setVisible(false);

                    // Add hover event listeners to the circle
                    circle.addListener('mouseover', function() {
                        labelMarker.setVisible(true);
                    });

                    circle.addListener('mouseout', function() {
                        labelMarker.setVisible(false);
                    });

                    // Optional: Add click event to toggle label visibility
                    circle.addListener('click', function() {
                        labelMarker.setVisible(!labelMarker.getVisible());
                    });
                });
            }

            function toggleLoadingText() {
                $('.loading').hide();
            }

            initMap();
            setTimeout(() => {
                if (currentLat === null || currentLng === null) {
                    location.reload();
                }

                getIncidentLocations();
                toggleLoadingText();
            }, 3000);
        });
    </script>
@endsection
