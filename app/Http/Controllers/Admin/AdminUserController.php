<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all farmers/users in the system.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', 'user')
            ->withCount(['lands', 'schedules', 'transactions'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $farmers = $query->paginate(12)->withQueryString();

        return view('admin.users.index', compact('farmers'));
    }

    /**
     * Show details of a specific farmer.
     */
    public function show(User $user): View
    {
        abort_if($user->role === 'admin', 404);

        $user->load(['lands', 'schedules.crop', 'transactions']);

        $totalIncome = (float) $user->transactions()->where('type', 'income')->sum('amount');
        $totalExpense = (float) $user->transactions()->where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        return view('admin.users.show', compact('user', 'totalIncome', 'totalExpense', 'netProfit'));
    }

    /**
     * Remove the specified farmer from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->role === 'admin', 403, 'Tidak dapat menghapus akun administrator.');

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun petani {$userName} beserta seluruh data terkait berhasil dihapus.");
    }
}
