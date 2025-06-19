<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationPosmap;
use Module\Foundation\Http\Resources\PosmapCollection;
use Module\Foundation\Http\Resources\PosmapShowResource;

class FoundationPosmapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', FoundationPosmap::class);

        return new PosmapCollection(
            FoundationPosmap::applyMode($request->mode)
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
        Gate::authorize('create', FoundationPosmap::class);

        $request->validate([]);

        return FoundationPosmap::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationPosmap $foundationPosmap
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationPosmap $foundationPosmap)
    {
        Gate::authorize('show', $foundationPosmap);

        return new PosmapShowResource($foundationPosmap);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationPosmap $foundationPosmap
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationPosmap $foundationPosmap)
    {
        Gate::authorize('update', $foundationPosmap);

        $request->validate([]);

        return FoundationPosmap::updateRecord($request, $foundationPosmap);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationPosmap $foundationPosmap
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationPosmap $foundationPosmap)
    {
        Gate::authorize('delete', $foundationPosmap);

        return FoundationPosmap::deleteRecord($foundationPosmap);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationPosmap $foundationPosmap
     * @return \Illuminate\Http\Response
     */
    public function restore(FoundationPosmap $foundationPosmap)
    {
        Gate::authorize('restore', $foundationPosmap);

        return FoundationPosmap::restoreRecord($foundationPosmap);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationPosmap $foundationPosmap
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(FoundationPosmap $foundationPosmap)
    {
        Gate::authorize('destroy', $foundationPosmap);

        return FoundationPosmap::destroyRecord($foundationPosmap);
    }
}
