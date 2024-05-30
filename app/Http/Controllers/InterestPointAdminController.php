<?php

namespace App\Http\Controllers;

use App\Http\Resources\InterestPointResource;
use App\Models\InterestPoint;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InterestPointAdminController extends Controller
{
    /**
     * Protected by the admin middleware
     */
    public static function middleware(): array
    {
        return [
            'admin'
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Backoffice/InterestPoint/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Backoffice/InterestPoint/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $ip = InterestPoint::where('uuid', $uuid)->firstOrFail();
        $ip->load('pictures');
        return Inertia::render('Backoffice/InterestPoint/Show', [
            'interestpoint' => InterestPointResource::make($ip),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $ip = InterestPoint::where('uuid', $uuid)->firstOrFail();
        $ip->load('pictures');
        return Inertia::render('Backoffice/InterestPoint/Edit', [
            'interestpoint' => InterestPointResource::make($ip),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        InterestPoint::destroy($id);
    }
}
