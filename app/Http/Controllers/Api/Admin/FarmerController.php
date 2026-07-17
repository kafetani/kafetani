<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\FarmerResource;
use App\Models\Farmer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    /**
     * GET /api/admin/farmers
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'farmers' => FarmerResource::collection(Farmer::orderByDesc('created_at')->get()),
        ]);
    }

    /**
     * POST /api/admin/farmers
     */
    public function store(Request $request): JsonResponse
    {
        return $this->save($request);
    }

    /**
     * POST /api/admin/farmers/{farmer}
     */
    public function update(Request $request, Farmer $farmer): JsonResponse
    {
        return $this->save($request, $farmer);
    }

    /**
     * DELETE /api/admin/farmers/{farmer}
     */
    public function destroy(Farmer $farmer): JsonResponse
    {
        if ($farmer->avatar) {
            @unlink(public_path('farmers/' . $farmer->avatar));
        }

        $farmer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data petani berhasil dihapus!',
        ]);
    }

    private function save(Request $request, ?Farmer $farmer = null): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'contact'  => ['nullable', 'string', 'max:50'],
            'bio'      => ['nullable', 'string'],
            'avatar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $ext      = $request->file('avatar')->getClientOriginalExtension();
            $filename = uniqid('farmer_', true) . '.' . strtolower($ext);
            $request->file('avatar')->move(public_path('farmers'), $filename);

            if ($farmer && $farmer->avatar) {
                @unlink(public_path('farmers/' . $farmer->avatar));
            }

            $data['avatar'] = $filename;
        }

        if ($farmer) {
            $farmer->update($data);
            $message = 'Data petani berhasil diupdate!';
        } else {
            // Petani yang ditambahkan langsung oleh admin (lewat API) dianggap
            // otomatis terverifikasi, sama seperti alur web.
            $data['status'] = 'approved';
            $farmer = Farmer::create($data);
            $message = 'Data petani berhasil ditambahkan!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'farmer'  => new FarmerResource($farmer),
        ]);
    }
}
