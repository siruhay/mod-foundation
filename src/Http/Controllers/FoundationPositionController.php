<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationPosition;
use Module\Foundation\Http\Resources\PositionCollection;
use Module\Foundation\Http\Resources\PositionShowResource;

class FoundationPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', FoundationPosition::class);

        return new PositionCollection(
            FoundationPosition::applyMode($request->mode)
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
        Gate::authorize('create', FoundationPosition::class);

        $request->validate([]);

        return FoundationPosition::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationPosition $foundationPosition
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationPosition $foundationPosition)
    {
        Gate::authorize('show', $foundationPosition);

        return new PositionShowResource($foundationPosition);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationPosition $foundationPosition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationPosition $foundationPosition)
    {
        Gate::authorize('update', $foundationPosition);

        $request->validate([]);

        return FoundationPosition::updateRecord($request, $foundationPosition);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationPosition $foundationPosition
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationPosition $foundationPosition)
    {
        Gate::authorize('delete', $foundationPosition);

        return FoundationPosition::deleteRecord($foundationPosition);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationPosition $foundationPosition
     * @return \Illuminate\Http\Response
     */
    public function restore(FoundationPosition $foundationPosition)
    {
        Gate::authorize('restore', $foundationPosition);

        return FoundationPosition::restoreRecord($foundationPosition);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationPosition $foundationPosition
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(FoundationPosition $foundationPosition)
    {
        Gate::authorize('destroy', $foundationPosition);

        return FoundationPosition::destroyRecord($foundationPosition);
    }
}
