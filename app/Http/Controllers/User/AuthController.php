<?php

namespace App\Http\Controllers\User;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ],[
            'username.required' => 'Username or email is required.',
        ]);

        $field = filter_var($validated['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (auth()->attempt([$field => $validated['username'], 'password' => $validated['password']], true)) {
            $user = auth()->user();

            return match ($user->type) {
                UserTypeEnum::Admin->value => to_route('admin.dashboard'),
                UserTypeEnum::User->value => to_route('user.dashboard'),
                UserTypeEnum::Volunteer->value => to_route('volunteer.dashboard'),
                UserTypeEnum::LawEnforcement->value => to_route('law-enforcement.dashboard'),
                default => to_route('login')->with('error', 'The registration failed. Please try again later.'),
            };
        }

        return back()->withInput()->withErrors([
            'username' => 'The user may not exist or the username and password are incorrect.',
        ]);
    }

    public function showRegisterForm()
    {
        $user_types = UserTypeEnum::getItems();

        return view('register', ['user_types' => $user_types]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|same:password',
            'account_type' => 'required|in:'.implode(',', UserTypeEnum::getValues(true)),
        ]);

        $username = explode('@', $validated['email'])[0];

        DB::insert(
            "insert into users (name, username, phone, email, password, type, created_at, updated_at) values (?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [$validated['name'], $username, $validated['phone'], $validated['email'], Hash::make($validated['password']), $validated['account_type']]
        );

        $user = DB::select('select * from users where email = ?', [$validated['email']]);

        if (empty($user)) {
            return back()->with('error', 'The registration failed. Please try again later.');
        }

        $user = $user[0];

        auth()->loginUsingId($user->id, true);

        $sendable_route = match ($user->type) {
            UserTypeEnum::Admin->value => to_route('admin.dashboard'),
            UserTypeEnum::User->value => to_route('user.dashboard'),
            UserTypeEnum::Volunteer->value => to_route('volunteer.dashboard'),
            UserTypeEnum::LawEnforcement->value => to_route('law-enforcement.dashboard'),
            default => to_route('login')->with('error', 'The registration failed. Please try again later.'),
        };

        return $sendable_route;
    }

    public function logout()
    {
        $user = auth()->user();
        if ($user->type === UserTypeEnum::Volunteer->value) {
            DB::update('update volunteer_details set availability = 0 where user_id = ?', [$user->id]);
        } else if ($user->type === UserTypeEnum::LawEnforcement->value) {
            DB::update('update lawenforcement_details set availability = 0 where user_id = ?', [$user->id]);
        }

        auth()->logout();
        return to_route('login');
    }
}
