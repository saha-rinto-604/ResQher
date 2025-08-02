<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        // get the total numbers of volunteers, approved volunteers and pending volunteers
        $total_volunteers = DB::select('select count(*) as total from users where type = ?', [UserTypeEnum::Volunteer->value]);
        $total_volunteers = empty($total_volunteers) ? 0 : $total_volunteers[0]->total;
        $approved_volunteers = DB::select('select count(*) as total from volunteer_details where approved = ?', [true]);
        $approved_volunteers = empty($approved_volunteers) ? 0 : $approved_volunteers[0]->total;
        $pending_volunteers = $total_volunteers - $approved_volunteers;

        // get the total number of law enforcement officers, approved and pending
        $total_law_enforcement = DB::select('select count(*) as total from users where type = ?', [UserTypeEnum::LawEnforcement->value]);
        $total_law_enforcement = empty($total_law_enforcement) ? 0 : $total_law_enforcement[0]->total;
        $approved_law_enforcement = DB::select('select count(*) as total from lawenforcement_details where approved = ?', [true]);
        $approved_law_enforcement = empty($approved_law_enforcement) ? 0 : $approved_law_enforcement[0]->total;
        $pending_law_enforcement = $total_law_enforcement - $approved_law_enforcement;

        // get total number of victims and their incident counts
        $total_victims = DB::select('select count(*) as total from users where type = ?', [UserTypeEnum::User->value]);
        $total_victims = empty($total_victims) ? 0 : $total_victims[0]->total;
        $total_incidents = DB::select('select count(*) as total from incident_histories');
        $total_incidents = empty($total_incidents) ? 0 : $total_incidents[0]->total;

        return view('admin.dashboard', [
            'total_volunteers' => $total_volunteers,
            'approved_volunteers' => $approved_volunteers,
            'pending_volunteers' => $pending_volunteers,

            'total_law_enforcement' => $total_law_enforcement,
            'approved_law_enforcement' => $approved_law_enforcement,
            'pending_law_enforcement' => $pending_law_enforcement,

            'total_victims' => $total_victims,
            'total_incidents' => $total_incidents,
        ]);
    }

    public function victims()
    {
        $all_victims = DB::select('select * from users where type = ?', [UserTypeEnum::User->value]);
        $victims = [];

        foreach ($all_victims as $victim) {
            $documents = DB::select('select * from victim_details where user_id = ?', [$victim->id]);
            if ($documents) {
                $documents = $documents[0];
            }
            $victim->documents = empty($documents) ? null : $documents;

            $incidents = DB::select('SELECT COUNT(*) as total FROM incident_histories WHERE user_id = ?', [$victim->id]);
            $victim->incident_count = empty($incidents) ? 0 : $incidents[0]->total;

            $victims[] = $victim;
        }

        return view('admin.users', compact('victims'));
    }

    public function volunteers()
    {
        $all_volunteers = DB::select('select * from users where type = ?', [UserTypeEnum::Volunteer->value]);
        $volunteers = [];

        foreach ($all_volunteers as $volunteer) {
            $documents = DB::select('select * from volunteer_details where user_id = ?', [$volunteer->id]);
            if ($documents) {
                $documents = $documents[0];
                $volunteer->documents = empty($documents) ? null : $documents;
                $volunteers[] = $volunteer;
            }
        }

        return view('admin.volunteers', compact('volunteers'));
    }

    public function volunteerApprove($id)
    {
        DB::statement('update volunteer_details set approved = ? where user_id = ?', [true, $id]);
        return redirect()->back()->with('success', 'Volunteer request approved successfully');
    }

    public function volunteerCancel($id)
    {
        DB::statement('update volunteer_details set approved = ? where user_id = ?', [false, $id]);
        return redirect()->back()->with('success', 'Volunteer request canceled successfully');
    }

    // law enforcement
    public function lawEnforcements()
    {
        $all_volunteers = DB::select('select * from users where type = ?', [UserTypeEnum::LawEnforcement->value]);
        $volunteers = [];

        foreach ($all_volunteers as $volunteer) {
            $documents = DB::select('select * from lawenforcement_details where user_id = ?', [$volunteer->id]);
            if ($documents) {
                $documents = $documents[0];
                $volunteer->documents = empty($documents) ? null : $documents;
                $volunteers[] = $volunteer;
            }
        }

        return view('admin.law-enforcement', compact('volunteers'));
    }

    public function lawEnforcementsApprove($id)
    {
        DB::statement('update lawenforcement_details set approved = ? where user_id = ?', [true, $id]);
        return redirect()->back()->with('success', 'Law Enforcement Personnel Request Approved Successfully');
    }

    public function lawEnforcementsCancel($id)
    {
        DB::statement('update lawenforcement_details set approved = ? where user_id = ?', [false, $id]);
        return redirect()->back()->with('success', 'Law Enforcement Personnel Request Canceled Successfully');
    }
}
