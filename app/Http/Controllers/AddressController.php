<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->get();
        return response()->json($addresses);
    }

    // Menyimpan alamat baru
    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'detail' => 'required|string',
        ]);

        $address = new Address($validated);
        $address->user_id = Auth::id();
        $address->save();

        return response()->json(['message' => 'Alamat berhasil disimpan', 'address' => $address], 201);

    } catch (\Throwable $e) {
        Log::error("Address Store Error: " . $e->getMessage());
        return response()->json(['message' => 'Terjadi kesalahan saat menyimpan alamat'], 500);
    }
}


    // Menampilkan alamat tertentu
    public function show($id)
{
    $address = Address::where('user_id', Auth::id())
        ->where('id', $id)
        ->firstOrFail();

    return response()->json($address);
}

    public function getDefault()
    {
        $address = Address::where('user_id', Auth::id())
            ->orderBy('id', 'asc')
            ->firstOrFail();

        return response()->json($address);
    }




    // Update alamat tertentu
    public function update(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'receiver_name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'province' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'district' => 'sometimes|required|string|max:255',
            'postal_code' => 'sometimes|required|string|max:10',
            'detail' => 'sometimes|required|string',
        ]);

        $address->update($request->all());

        return response()->json(['message' => 'Alamat berhasil diperbarui', 'address' => $address]);
    }

    // Hapus alamat
    public function destroy($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();

        return response()->json(['message' => 'Alamat berhasil dihapus']);
    }
}
