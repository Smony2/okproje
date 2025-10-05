<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = Subscription::orderBy('sort_order')->paginate(10);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.subscriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:subscriptions,slug',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'monthly_price' => 'nullable|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_jobs' => 'nullable|integer|min:1',
            'priority_support' => 'boolean',
            'advanced_analytics' => 'boolean',
            'custom_branding' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        if (empty($data['price']) && !empty($data['monthly_price'])) {
            $data['price'] = $data['monthly_price'];
        }
        Subscription::create($data);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription paketi başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        $subscription->load('avukats');
        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        return view('admin.subscriptions.edit', compact('subscription'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:subscriptions,slug,' . $subscription->id,
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'monthly_price' => 'nullable|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_jobs' => 'nullable|integer|min:1',
            'priority_support' => 'boolean',
            'advanced_analytics' => 'boolean',
            'custom_branding' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        if (empty($data['price']) && !empty($data['monthly_price'])) {
            $data['price'] = $data['monthly_price'];
        }
        $subscription->update($data);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription paketi başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        // Eğer bu subscription'ı kullanan avukatlar varsa silme
        if ($subscription->avukats()->count() > 0) {
            return redirect()->route('admin.subscriptions.index')
                ->with('error', 'Bu subscription paketini kullanan avukatlar bulunmaktadır. Önce avukatların subscription\'larını değiştirin.');
        }

        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription paketi başarıyla silindi.');
    }

    /**
     * Toggle subscription active status.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function toggle(Subscription $subscription)
    {
        $subscription->update(['is_active' => !$subscription->is_active]);

        $status = $subscription->is_active ? 'aktif' : 'pasif';
        
        return redirect()->route('admin.subscriptions.index')
            ->with('success', "Subscription paketi {$status} hale getirildi.");
    }
}