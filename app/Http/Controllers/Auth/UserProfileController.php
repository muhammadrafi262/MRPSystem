<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'string|max:255',
            'current_password' => 'required_with:password|string',
            'password' => 'nullable|string|confirmed|min:8',
            'avatar' => 'nullable|image|max:2048', // JPEG/PNG
        ]);

        // Update name
        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        // Update password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Password lama salah'], 403);
            }
            $user->password = Hash::make($request->password);
        }

        // Update avatar
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Folder tempat simpan avatar user, misal: avatars/{user_id}/filename.png
            $folder = 'avatars/' . $user->id;

            // Pastikan folder ada
            $filePath = public_path($folder);
            if (!file_exists($filePath)) {
                mkdir($filePath, 0777, true);
            }

            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            // Simpan file di folder tersebut
            $file->move($filePath, $filename);

            // Simpan path relatif ke avatar di database (folder + filename)
            $user->avatar = $user->id . '/' . $filename;
        }

        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar_url' => $user->avatar ? url('/files/avatars/' . $user->avatar) : null,
            ],
        ]);
    }

    // Endpoint untuk ambil user profile (termasuk avatar)
    public function show(Request $request)
    {
        $user = $request->user();

        $avatarUrl = $user->avatar
            ? url('/files/avatars/' . $user->avatar)
            : null;

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar_url' => $avatarUrl,
        ]);
    }
}
