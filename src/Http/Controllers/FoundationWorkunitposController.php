<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationPosition;
use Module\Foundation\Models\FoundationWorkunit;
use Module\Foundation\Http\Resources\PositionCollection;
use Module\Foundation\Http\Resources\PositionShowResource;

class FoundationWorkunitposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, FoundationWorkunit $foundationWorkunit)
    {
        Gate::authorize('view', FoundationPosition::class);

        return new PositionCollection(
            $foundationWorkunit
                ->positions()
                ->with(['officer'])
                ->applyMode($request->mode)
                ->filter($request->filters)
                ->search($request->findBy)
                ->sortBy($request->sortBy, $request->sortDesc)
                ->paginate($request->itemsPerPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FoundationWorkunit $foundationWorkunit)
    {
        Gate::authorize('create', FoundationPosition::class);

        $request->validate([]);

        return FoundationPosition::storeRecord($request, $foundationWorkunit);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @param  \Module\Foundation\Models\FoundationPosition $foundationPosition
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationWorkunit $foundationWorkunit, FoundationPosition $foundationPosition)
    {
        Gate::authorize('show', $foundationPosition);

        return new PositionShowResource($foundationPosition);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @param  \Module\Foundation\Models\FoundationPosition $foundationPosition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationWorkunit $foundationWorkunit, FoundationPosition $foundationPosition)
    {
        Gate::authorize('update', $foundationPosition);

        $request->validate([]);

        return FoundationPosition::updateRecord($request, $foundationPosition);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @param  \Module\Foundation\Models\FoundationPosition $foundationPosition
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationWorkunit $foundationWorkunit, FoundationPosition $foundationPosition)
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
    public function restore(FoundationWorkunit $foundationWorkunit, FoundationPosition $foundationPosition)
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
    public function forceDelete(FoundationWorkunit $foundationWorkunit, FoundationPosition $foundationPosition)
    {
        Gate::authorize('destroy', $foundationPosition);

        return FoundationPosition::destroyRecord($foundationPosition);
    }
}
