<?php

namespace App\Http\Controllers\public;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class IndexController extends Controller
{
    /**
     * Отображение главной публичной страницы с ценами, графиком и калькуляторами.
     */
    public function index(Request $request)
    {
        // 1. Получаем все типы золотых слитков

        return view('public.index');
    }

    public function contact(Request $request)
    {
        return view('public.contact');
    }

    public function countUser(Request $request)
    {
        return response(User::count());
    }
}
