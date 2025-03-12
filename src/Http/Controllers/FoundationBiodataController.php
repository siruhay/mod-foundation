<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationBiodata;
use Module\Foundation\Http\Resources\BiodataCollection;
use Module\Foundation\Http\Resources\BiodataShowResource;

class FoundationBiodataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', FoundationBiodata::class);

        return new BiodataCollection(
            FoundationBiodata::applyMode($request->mode)
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
        Gate::authorize('create', FoundationBiodata::class);

        $request->validate([]);

        return FoundationBiodata::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationBiodata $foundationBiodata
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationBiodata $foundationBiodata)
    {
        Gate::authorize('show', $foundationBiodata);

        return new BiodataShowResource($foundationBiodata);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationBiodata $foundationBiodata
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationBiodata $foundationBiodata)
    {
        Gate::authorize('update', $foundationBiodata);

        $request->validate([]);

        return FoundationBiodata::updateRecord($request, $foundationBiodata);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationBiodata $foundationBiodata
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationBiodata $foundationBiodata)
    {
        Gate::authorize('delete', $foundationBiodata);

        return FoundationBiodata::deleteRecord($foundationBiodata);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationBiodata $foundationBiodata
     * @return \Illuminate\Http\Response
     */
    public function restore(FoundationBiodata $foundationBiodata)
    {
        Gate::authorize('restore', $foundationBiodata);

        return FoundationBiodata::restoreRecord($foundationBiodata);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationBiodata $foundationBiodata
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(FoundationBiodata $foundationBiodata)
    {
        Gate::authorize('destroy', $foundationBiodata);

        return FoundationBiodata::destroyRecord($foundationBiodata);
    }
}
