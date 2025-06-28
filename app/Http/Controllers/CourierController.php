<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourierController extends Controller
{
    // Tampilkan semua data pengguna dan alamat (untuk backend)
    public function index(Request $request)
    {
        $query = Address::with('user');

        if ($request->has('status') && in_array($request->status, ['tambah', 'dikirim', 'terkirim'])) {
            $query->where('status', $request->status);
        }

        $addresses = $query->get();

        return view('admin.courier.index', compact('addresses'));
    }

    // API untuk menampilkan semua alamat (frontend mobile)
    public function apiIndex()
    {
        $addresses = Address::with('user')->get();

        $formatted = $addresses->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->receiver_name,
                'address' => $item->detail . ', ' . $item->district . ', ' . $item->city . ', ' . $item->province . ' (' . $item->postal_code . ')',
                'user_name' => $item->user->name ?? '',
                'phone' => $item->phone,
                'status' => $item->status ?? 'tambah',
            ];
        });

        return response()->json($formatted);
    }

    // Update status pengiriman
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:addresses,id',
            'status' => 'required|in:tambah,dikirim,terkirim'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }

        $address = Address::findOrFail($request->address_id);
        $address->status = $request->status;
        $address->save();

        return response()->json(['status' => true, 'message' => 'Status updated successfully']);
    }

    // Detail satu alamat pengguna
    public function apiDetail($id)
    {
        $address = Address::with('user')->find($id);

        if (!$address) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json([
            'id' => $address->id,
            'receiver_name' => $address->receiver_name,
            'phone' => $address->phone,
            'full_address' => $address->detail . ', ' . $address->district . ', ' . $address->city . ', ' . $address->province . ' (' . $address->postal_code . ')',
            'user' => $address->user->name,
            'status' => $address->status ?? 'tambah'
        ]);
    }
}
