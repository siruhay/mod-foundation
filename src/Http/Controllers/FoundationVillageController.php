<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationVillage;
use Module\Foundation\Http\Resources\VillageCollection;
use Module\Foundation\Http\Resources\VillageShowResource;

class FoundationVillageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', FoundationVillage::class);

        return new VillageCollection(
            FoundationVillage::applyMode($request->mode)
                ->filter($request->filters)
                ->search($request->findBy)
                ->sortBy($request->sortBy)
                ->paginate($request->itemsPerPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('create', FoundationVillage::class);

        $request->validate([]);

        return FoundationVillage::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationVillage $foundationVillage
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationVillage $foundationVillage)
    {
        Gate::authorize('show', $foundationVillage);

        return new VillageShowResource($foundationVillage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationVillage $foundationVillage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationVillage $foundationVillage)
    {
        Gate::authorize('update', $foundationVillage);

        $request->validate([]);

        return FoundationVillage::updateRecord($request, $foundationVillage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationVillage $foundationVillage
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationVillage $foundationVillage)
    {
        Gate::authorize('delete', $foundationVillage);

        return FoundationVillage::deleteRecord($foundationVillage);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationVillage $foundationVillage
     * @return \Illuminate\Http\Response
     */
    public function restore(FoundationVillage $foundationVillage)
    {
        Gate::authorize('restore', $foundationVillage);

        return FoundationVillage::restoreRecord($foundationVillage);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationVillage $foundationVillage
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(FoundationVillage $foundationVillage)
    {
        Gate::authorize('destroy', $foundationVillage);

        return FoundationVillage::destroyRecord($foundationVillage);
    }
}
