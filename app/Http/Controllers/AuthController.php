<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\News_category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function view_login()
    {
        return redirect('unauthenticated');
    }

    public function unauthenticated()
    {
        abort(403, 'Unauthorized');
        return view('unauthenticated');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors(['error' => 'Username dan Password tidak valid !']);
        }
    }

    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan error
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'preferences' => 'auto'
        ]);

        // Autentikasi user
        Auth::login($user);

        // Redirect ke halaman dashboard setelah login
        return redirect()->route('home')->with('success', 'Registrasi berhasil!');
    }

    public function profile(Request $request)
    {
        $id = $request->id;

        $user = User::findOrFail($id);
        if (Auth::user()->id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $news_category = News_category::all();

        $user_preferences = $user->preferences = json_decode($user->preferences);

        return view('profile', compact('user', 'news_category', 'user_preferences'));
    }

    public function edit_profile(Request $request)
    {
        $user = Auth::user();

        // Validasi input menggunakan Validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'category' => 'array',
        ]);

        // Jika validasi gagal, kembalikan dengan error
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update user
        $user->name = $request->name;
        $user->email = $request->email;

        // Jika password diisi, update password
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        // Simpan kategori sebagai JSON (jika perlu disimpan di satu kolom)
        $user->preferences = json_encode($request->category);

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
