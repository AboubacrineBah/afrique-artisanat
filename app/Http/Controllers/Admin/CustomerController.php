<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', UserRole::Customer->value)
            ->withCount('orders')
            ->latest()
            ->paginate(config('shop.admin_per_page'));

        return view('admin.customers.index', compact('customers'));
    }
}
