<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationPosition;
use Module\Foundation\Models\FoundationCommunity;
use Module\Foundation\Http\Resources\PositionCollection;
use Module\Foundation\Http\Resources\PositionShowResource;

class FoundationCommunityposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, FoundationCommunity $foundationCommunity)
    {
        Gate::authorize('view', FoundationPosition::class);

        return new PositionCollection(
            $foundationCommunity
                ->positions()
                ->withDepth()
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
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FoundationCommunity $foundationCommunity)
    {
        Gate::authorize('create', FoundationPosition::class);

        $request->validate([]);

        return FoundationPosition::storeRecord($request, $foundationCommunity);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @param  \Module\Foundation\Models\FoundationPosition $foundationPosition
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationCommunity $foundationCommunity, FoundationPosition $foundationPosition)
    {
        Gate::authorize('show', $foundationPosition);

        return new PositionShowResource($foundationPosition);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @param  \Module\Foundation\Models\FoundationPosition $foundationPosition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationCommunity $foundationCommunity, FoundationPosition $foundationPosition)
    {
        Gate::authorize('update', $foundationPosition);

        $request->validate([]);

        return FoundationPosition::updateRecord($request, $foundationPosition);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @param  \Module\Foundation\Models\FoundationPosition $foundationPosition
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationCommunity $foundationCommunity, FoundationPosition $foundationPosition)
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
    public function restore(FoundationCommunity $foundationCommunity, FoundationPosition $foundationPosition)
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
    public function forceDelete(FoundationCommunity $foundationCommunity, FoundationPosition $foundationPosition)
    {
        Gate::authorize('destroy', $foundationPosition);

        return FoundationPosition::destroyRecord($foundationPosition);
    }
}
