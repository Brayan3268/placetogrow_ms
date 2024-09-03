<?php

namespace App\Http\Controllers;

use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use App\Http\Requests\UpdateUsersuscriptionRequest;
use App\Models\Usersuscription;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class UsersuscriptionController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->authorize('update', Usersuscription::class);
        UserSuscriptionPll::save_user_suscription($request);

        return redirect()->route('suscriptions.index')
            ->with('status', 'Users suscription created successfully!')
            ->with('class', 'bg-green-500');
    }

    public function show(Usersuscription $usersuscription)
    {
        //
    }

    public function edit(Usersuscription $usersuscription)
    {
        //
    }

    public function update(UpdateUsersuscriptionRequest $request, Usersuscription $usersuscription)
    {
        //
    }

    public function destroy(string $reference, int $user_id)
    {
        UserSuscriptionPll::delete_user_suscription($reference, $user_id);

        return redirect()->route('suscriptions.index')
            ->with('status', 'Suscription deleted successfully')
            ->with('class', 'bg-green-500');
    }
}
