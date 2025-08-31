<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminRoleController extends Controller
{
    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roles = AdminRole::withCount('yoneticiler')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:admin_roles',
            'description' => 'nullable|string',
        ]);

        $role = new AdminRole();
        $role->name = $request->name;
        $role->slug = Str::slug($request->name);
        $role->description = $request->description;
        $role->save();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $role = AdminRole::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $role = AdminRole::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:admin_roles,name,' . $role->id,
            'description' => 'nullable|string',
        ]);

        $role->name = $request->name;
        $role->slug = Str::slug($request->name);
        $role->description = $request->description;
        $role->save();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol başarıyla güncellendi.');
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $role = AdminRole::findOrFail($id);
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol başarıyla silindi.');
    }
}
