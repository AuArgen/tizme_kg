<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with a list of users.
     */
    public function index()
    {
        $users = User::withCount('guests')->paginate(15);

        return view('admin.index', [
            'users' => $users,
        ]);
    }

    /**
     * Display the guests for a specific user.
     */
    public function userGuests(User $user)
    {
        $guests = $user->guests()->paginate(15);

        return view('admin.user_guests', [
            'user' => $user,
            'guests' => $guests,
        ]);
    }
}
