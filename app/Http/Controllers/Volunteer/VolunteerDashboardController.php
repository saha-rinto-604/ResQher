<?php

namespace App\Http\Controllers\Volunteer;

use App\Events\AlertProcessed;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VolunteerDashboardController extends Controller
{
    public function dashboard()
    {
        $user_id = auth()->user()->id;

        $user = DB::select('
                                SELECT volunteer_details.*, users.id, users.phone
                                FROM volunteer_details
                                INNER JOIN users ON users.id = volunteer_details.user_id
                                WHERE users.id = ?', [$user_id]
        );

        if ($user) {
            $user = $user[0];
        } else {
            $user = null;
        }

        return view('volunteer.dashboard', [
            'user' => $user,
        ]);
    }

    public function getLatestVictims()
    {
        $user_id = auth()->user()->id;
        $self_details = DB::select('SELECT id,availability FROM volunteer_details WHERE user_id = ?', [$user_id]);
        if ($self_details) {
            $self_details = $self_details[0];
        } else {
            $self_details = null;
        }

        $nearVictims = [];
        if ($self_details) {
            if ($self_details->availability) {
                $latestVictims = DB::select('
                SELECT v.*
                FROM victim_locations v
                JOIN (
                    SELECT user_id, MAX(created_at) AS latest_time
                    FROM victim_locations
                    GROUP BY user_id
                ) latest
                ON v.user_id = latest.user_id
                AND v.created_at = latest.latest_time
        ');

                if (!empty($latestVictims)) {
                    $myData = DB::select('SELECT id,latitude,longitude FROM volunteer_details WHERE user_id = ?', [auth()->user()->id]);
                    if (empty($myData)) {
                        $myData = null;
                    } else {
                        $myData = $myData[0];
                    }

                    if ($myData) {
                        $myLat = $myData->latitude;
                        $myLon = $myData->longitude;

                        foreach ($latestVictims as $index => $victim) {
                            $is_near = isVictimNear(
                                currentLat: $myLat,
                                currentLon: $myLon,
                                targetLat: $victim->latitude,
                                targetLon: $victim->longitude,
                            );

                            if ($is_near) {
                                $victim_user = DB::select('SELECT name FROM users WHERE id = ?', [$victim->user_id]);
                                if ($victim_user) {
                                    $victim_user = $victim_user[0];
                                    $victim->name = $victim_user->name;
                                } else {
                                    $victim->name = 'Unknown';
                                }

                                $victim->alert_time = Carbon::parse($victim->created_at)->diffForHumans(short: true, parts: 2);

                                $nearVictims[$index] = $victim;
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'latest_victims' => $nearVictims,
            'latest_victims_count' => count($nearVictims),
        ]);
    }

    public function documents()
    {
        $user_id = auth()->user()->id;

        $volunteer_details = DB::select('
                                SELECT volunteer_details.*, users.id, users.phone
                                FROM volunteer_details
                                INNER JOIN users ON users.id = volunteer_details.user_id
                                WHERE users.id = ?', [$user_id]
        );

        if ($volunteer_details) {
            $volunteer_details = $volunteer_details[0];
        } else {
            $volunteer_details = null;
        }

        return view('volunteer.documents', [
            'volunteer_details' => $volunteer_details,
        ]);
    }

    public function uploadDocuments(Request $request)
    {
        $request->validate([
            'nid_front_side' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'nid_back_side' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'student_id_card' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'coordinate' => 'required|string|max:255',
        ]);

//        $phone_number = str_replace([" ", "+88"], "", $request->phone_number);
//        $phone_number = Str::replaceStart("88", "", $phone_number);

        $coordinate = explode(",", $request->coordinate);
        $latitude = trim($coordinate[0]);
        $longitude = trim($coordinate[1]);

        $nid_front_side = $request->file('nid_front_side');
        $nid_back_side = $request->file('nid_back_side');
        $student_id_card = $request->file('student_id_card');

        $nid_front_side_name = time() . '_nid_front.' . $nid_front_side->getClientOriginalExtension();
        $nid_back_side_name = time() . '_nid_back.' . $nid_back_side->getClientOriginalExtension();

        if ($student_id_card) {
            $student_id_card_name = time() . '_student_id.' . $student_id_card->getClientOriginalExtension();
        }

        $user_id = auth()->user()->id;

        $query = "INSERT INTO volunteer_details (user_id, nid_front_side, nid_back_side, student_id_card, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE nid_front_side = ?, nid_back_side = ?, student_id_card = ?, latitude = ?, longitude = ?";

        $params = [
            $user_id,
            $nid_front_side_name,
            $nid_back_side_name,
            $student_id_card ? $student_id_card_name : null,
            $latitude,
            $longitude,

            $nid_front_side_name,
            $nid_back_side_name,
            $student_id_card ? $student_id_card_name : null,
            $latitude,
            $longitude
        ];

        DB::insert($query, $params);

        if (DB::getPdo()->lastInsertId()) {
            $nid_front_side->move(public_path('assets/uploads/volunteer/documents'), $nid_front_side_name);
            $nid_back_side->move(public_path('assets/uploads/volunteer/documents'), $nid_back_side_name);

            if ($student_id_card) {
                $student_id_card->move(public_path('assets/uploads/volunteer/documents'), $student_id_card_name);
            }

            return back()->with('success', 'Documents uploaded successfully.');
        } else {
            return back()->with('error', 'Failed to upload documents. Please try again.');
        }
    }

    public function updateAvailability()
    {
        $volunteer_details = DB::select('SELECT * FROM volunteer_details WHERE user_id = ?', [auth()->user()->id]);
        if ($volunteer_details) {
            $volunteer_details = $volunteer_details[0];
        } else {
            $volunteer_details = null;
        }

        if ($volunteer_details) {
            $availability = $volunteer_details->availability;

            DB::update('UPDATE volunteer_details SET availability = ? WHERE user_id = ?', [!$availability, auth()->user()->id]);

            return response()->json(['status' => true, 'message' => 'Availability updated successfully.']);
        }

        return response()->json(['status' => false, 'message' => 'Failed to update availability.']);
    }

    public function getVictimCoordinate(Request $request)
    {
        $validated = $request->validate([
            'victim_id' => 'required|integer',
        ]);

        $victim_id = $validated['victim_id'];

        $victim = DB::select('SELECT * FROM victim_locations WHERE user_id = ? ORDER BY id desc', [$victim_id]);
        if ($victim) {
            $victim = $victim[0];
            $latitude = $victim->latitude;
            $longitude = $victim->longitude;

            return response()->json([
                'status' => true,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);
        } else {
            return response()->json(['status' => false, 'message' => 'Victim not found.']);
        }
    }

    public function updateSelfLocation(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude = $validated['latitude'];
        $longitude = $validated['longitude'];
        $user_id = auth()->user()->id;

        DB::update('UPDATE volunteer_details SET latitude = ?, longitude = ?, updated_at = NOW() WHERE user_id = ?', [$latitude, $longitude, $user_id]);

        return response()->json(['status' => true, 'message' => 'Location updated successfully.']);
    }
}
