<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\AdminRole;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function editProfile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth('admin')->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // max 2MB, görüntü dosyaları
        ]);

        try {
            // İsim ve e-posta güncelleme
            $user->name = $request->name;
            $user->email = $request->email;

            // Avatar işleme
            if ($request->hasFile('avatar')) {
                // Eski avatar'ı sil
                if ($user->avatar && file_exists(public_path('uploads/avatars/' . $user->avatar))) {
                    unlink(public_path('uploads/avatars/' . $user->avatar));
                }

                // Yeni avatar'ı kaydet
                $avatar = $request->file('avatar');
                $avatarName = 'avatar_' . time() . '.' . $avatar->getClientOriginalExtension();
                $avatar->move(public_path('uploads/avatars'), $avatarName);
                $user->avatar = 'uploads/avatars/'.$avatarName;
            }

            $user->save();


            return redirect()->back()->with('success', 'Profil başarıyla güncellendi.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Profil güncellenirken bir hata oluştu.']);
        }
    }

    public function changePasswordForm()
    {
        return view('admin.sifredegistir');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:2|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Mevcut şifre yanlış.']);
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return redirect()->back()->with('success', 'Şifreniz başarıyla değiştirildi.');
    }

    // Admin listele
    public function index()
    {
        $admins = Admin::with('roles')->get(); // Rollerle birlikte çekiyoruz
        return view('admin.yoneticiler.index', compact('admins'));
    }

    // Admin ekleme formu
    public function create()
    {
        $roles = AdminRole::all();
        return view('admin.yoneticiler.create', compact('roles'));
    }

    // Admin kaydetme
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->has('is_active'),
        ]);

        // Roller atanıyor
        if ($request->roles) {
            $admin->roles()->sync($request->roles);
        }

        return redirect()->route('admin.yoneticiler.index')->with('success', 'Yönetici başarıyla eklendi.');
    }

    // Admin düzenleme formu
    public function edit(Admin $admin)
    {
        $roles = AdminRole::all();
        return view('admin.yoneticiler.edit', compact('admin', 'roles'));
    }

    // Admin güncelleme
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        $admin->is_active = $request->has('is_active');

        $admin->save();

        // Roller güncelleniyor
        $admin->roles()->sync($request->roles ?? []);

        return redirect()->route('admin.yoneticiler.index')->with('success', 'Yönetici başarıyla güncellendi.');
    }

    // Admin silme
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admin.yoneticiler.index')->with('success', 'Yönetici başarıyla silindi.');
    }
}
