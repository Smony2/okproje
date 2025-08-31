<?php

namespace App\Http\Controllers\Avukat;

use App\Models\AvukatAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AvukatAccountController extends Controller
{
    public function index()
    {
        $accounts = AvukatAccount::all();
        return view('avukat.finance_accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('avukat.finance_accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'iban' => 'required|string|unique:finance_accounts,iban',
            'account_holder' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        AvukatAccount::create($validated);

        return redirect()->route('avukat.avukat-accounts.index')->with('success', 'Hesap başarıyla eklendi.');
    }

    public function edit(AvukatAccount $financeAccount)
    {
        return view('avukat.finance_accounts.edit', compact('financeAccount'));
    }

    public function update(Request $request, AvukatAccount $financeAccount)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'iban' => 'required|string|unique:finance_accounts,iban,' . $financeAccount->id,
            'account_holder' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $financeAccount->update($validated);

        return redirect()->route('avukat.avukat-accounts.index')->with('success', 'Hesap başarıyla güncellendi.');
    }

    public function destroy(AvukatAccount $financeAccount)
    {
        $financeAccount->delete();

        return redirect()->route('avukat.avukat-accounts.index')->with('success', 'Hesap başarıyla silindi.');
    }
}
