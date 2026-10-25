<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        return view('account.addresses', ['addresses' => $request->user()->addresses]);
    }

    public function store(AddressRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        // La première adresse enregistrée devient l'adresse par défaut
        $data['is_default'] = $data['is_default'] || ! $user->addresses()->exists();

        $address = $user->addresses()->create($data);
        $this->keepSingleDefault($address);

        return redirect()->route('account.addresses.index')->with('success', 'Adresse ajoutée.');
    }

    public function update(AddressRequest $request, Address $address)
    {
        $this->authorizeOwner($request, $address);

        $address->update($request->validated());
        $this->keepSingleDefault($address);

        return redirect()->route('account.addresses.index')->with('success', 'Adresse mise à jour.');
    }

    public function destroy(Request $request, Address $address)
    {
        $this->authorizeOwner($request, $address);

        $wasDefault = $address->is_default;
        $address->delete();

        // Si l'adresse par défaut disparaît, la plus ancienne restante prend le relais
        if ($wasDefault) {
            $request->user()->addresses()->first()?->update(['is_default' => true]);
        }

        return redirect()->route('account.addresses.index')->with('success', 'Adresse supprimée.');
    }

    private function authorizeOwner(Request $request, Address $address): void
    {
        abort_unless($address->user_id === $request->user()->id, 404);
    }

    /** Une seule adresse par défaut par client. */
    private function keepSingleDefault(Address $address): void
    {
        if ($address->is_default) {
            Address::where('user_id', $address->user_id)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }
    }
}
