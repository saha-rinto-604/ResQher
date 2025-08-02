<?php

namespace App\Http\Controllers;

use App\Enums\UserTypeEnum;
use App\Events\AlertProcessed;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function dashboard()
    {
        return view('user.dashboard');
    }

    public function profile()
    {
        $user_id = auth()->user()->id;
        $victim = DB::select("SELECT * FROM users WHERE id = ? LIMIT 1", [$user_id]);

        if ($victim) {
            $victim = $victim[0];
        } else {
            $victim = null;
        }

        $victim_details = DB::select("SELECT * FROM victim_details WHERE user_id = ? LIMIT 1", [$user_id]);

        if ($victim_details) {
            $victim_details = $victim_details[0];
        } else {
            $victim_details = null;
        }

        return view('user.profile', ['victim' => $victim, 'victim_details' => $victim_details]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nid_front_side' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nid_back_side' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_id_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string|max:255',
            'emergency_contact_1' => 'required|string|max:15',
            'emergency_contact_2' => 'nullable|string|max:15',
            'emergency_contact_3' => 'nullable|string|max:15',
        ]);

        $nid_front_side = $request->file('nid_front_side');
        $nid_back_side = $request->file('nid_back_side');
        $student_id_card = $request->file('student_id_card');
        $address = $request->input('address');
        $emergency_contact_1 = $request->input('emergency_contact_1');
        $emergency_contact_2 = $request->input('emergency_contact_2') ?? null;
        $emergency_contact_3 = $request->input('emergency_contact_3') ?? null;

        $nid_front_side_name = time() . '_nid_front.' . $nid_front_side->getClientOriginalExtension();
        $nid_back_side_name = time() . '_nid_back.' . $nid_back_side->getClientOriginalExtension();

        if ($student_id_card) {
            $student_id_card_name = time() . '_student_id.' . $student_id_card->getClientOriginalExtension();
        }

        $user_id = auth()->user()->id;

        $query = "INSERT INTO victim_details (user_id, nid_front_side, nid_back_side, student_id_card, address, emergency_contact_1, emergency_contact_2, emergency_contact_3) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE nid_front_side = ?, nid_back_side = ?, student_id_card = ?, address = ?, emergency_contact_1 = ?, emergency_contact_2 = ?, emergency_contact_3 = ?";

        $params = [
            $user_id,
            $nid_front_side_name,
            $nid_back_side_name,
            $student_id_card ? $student_id_card_name : null,
            $address,
            $emergency_contact_1,
            $emergency_contact_2,
            $emergency_contact_3,

            $nid_front_side_name,
            $nid_back_side_name,
            $student_id_card ? $student_id_card_name : null,
            $address,
            $emergency_contact_1,
            $emergency_contact_2,
            $emergency_contact_3
        ];

        DB::insert($query, $params);

        if (DB::getPdo()->lastInsertId()) {
            $nid_front_side->move(public_path('assets/uploads/user/documents'), $nid_front_side_name);
            $nid_back_side->move(public_path('assets/uploads/user/documents'), $nid_back_side_name);

            if ($student_id_card) {
                $student_id_card->move(public_path('assets/uploads/user/documents'), $student_id_card_name);
            }

            return back()->with('success', 'Documents uploaded successfully.');
        } else {
            return back()->with('error', 'Failed to upload documents. Please try again.');
        }
    }

    public function storeLocation(Request $request)
    {
        abort_if($request->method() != 'POST', 403);
        abort_if(auth()->check() == false, 403);

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user_id = auth()->user()->id;

        if ($user_id) {
            $user = DB::select("SELECT * FROM users WHERE id = ? LIMIT 1", [$user_id]);

            if (!empty($user)) {
                $user = $user[0];

                if ($user->type == UserTypeEnum::User->value) {
                    DB::insert("INSERT INTO victim_locations (user_id, latitude, longitude, last_updated_at, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW(), NOW())", [
                        $user->id,
                        $validated['latitude'],
                        $validated['longitude']
                    ]);

                    return response()->json([
                        'status' => true,
                        'message' => 'Location updated successfully'
                    ]);
                }
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to update location'
        ]);
    }

    public function stopLocation(Request $request)
    {
        abort_if($request->method() != 'POST', 403);
        abort_if(auth()->check() == false, 403);

        $validated = $request->validate([
            'reason' => 'nullable|string|max:255'
        ]);

        $user_id = auth()->user()->id;

        if ($user_id) {
            // get the latest victim location for the user
            $victim_location = DB::select("SELECT * FROM victim_locations WHERE user_id = ? ORDER BY created_at ASC LIMIT 1", [$user_id]);

            if ($victim_location) {
                $victim_location = $victim_location[0];

                // store the victim location in incident_histories
                DB::insert("INSERT INTO incident_histories (user_id, latitude, longitude, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW())", [
                    $victim_location->user_id,
                    $victim_location->latitude,
                    $victim_location->longitude,
                    $validated['reason'] ?? '',
                    $victim_location->created_at
                ]);

                // delete the latest victim location
                DB::delete("DELETE FROM victim_locations WHERE user_id = ?", [$victim_location->user_id]);

                return response()->json([
                    'status' => true,
                    'message' => 'Location stopped successfully'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to stop location'
        ]);
    }

    public function getHelpersCoordinate()
    {
        $user_id = auth()->user()->id;

        $victim_location = DB::select("SELECT * FROM victim_locations WHERE user_id = ? ORDER BY created_at DESC LIMIT 1", [$user_id]);
        if ($victim_location) {
            $victim_location = $victim_location[0];
        } else {
            $victim_location = null;
        }

        if ($victim_location) {
            $victim_lat = $victim_location->latitude;
            $victim_lon = $victim_location->longitude;

            $volunteers = DB::select("
                    SELECT *,
                           (
                             6371 * 2 *
                             ASIN(
                               SQRT(
                                 POWER(SIN(RADIANS(latitude - ?)/2), 2) +
                                 COS(RADIANS(?)) * COS(RADIANS(latitude)) *
                                 POWER(SIN(RADIANS(longitude - ?)/2), 2)
                               )
                             )
                           ) AS distance
                    FROM volunteer_details
                    WHERE approved = 1 AND availability = 1
                    HAVING distance <= 1
                ", [$victim_lat, $victim_lat, $victim_lon]
            );

            if ($volunteers) {
                foreach ($volunteers as $volunteer) {
                    $user = DB::select("SELECT * FROM users WHERE id = ? LIMIT 1", [$volunteer->user_id]);
                    if ($user) {
                        $user = $user[0];
                        $volunteer->name = $user->name;
                        $volunteer->email = $user->email;
                        $volunteer->type = $user->type;
                    }
                }
            } else {
                $volunteers = [];
            }
        }

        return response()->json([
            'status' => true,
            'helpers_count' => count($volunteers ?? []),
            'helpers' => $volunteers ?? []
        ]);
    }

    public function incidentHistory()
    {
        return view('user.history');
    }

    public function getIncidentHistory(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $incident_histories = DB::select("
                    SELECT *,
                           (
                             6371 * 2 *
                             ASIN(
                               SQRT(
                                 POWER(SIN(RADIANS(latitude - ?)/2), 2) +
                                 COS(RADIANS(?)) * COS(RADIANS(latitude)) *
                                 POWER(SIN(RADIANS(longitude - ?)/2), 2)
                               )
                             )
                           ) AS distance
                    FROM incident_histories
                    HAVING distance <= 5
                ", [$latitude, $latitude, $longitude]
        );

        return response()->json([
            'status' => true,
            'histories' => $incident_histories
        ]);
    }
}
