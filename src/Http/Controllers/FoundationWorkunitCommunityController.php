<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationWorkunit;
use Module\Foundation\Models\FoundationCommunity;
use Module\Foundation\Http\Resources\CommunityCollection;
use Module\Foundation\Http\Resources\CommunityShowResource;

class FoundationWorkunitCommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, FoundationWorkunit $foundationWorkunit)
    {
        Gate::authorize('view', FoundationCommunity::class);

        return new CommunityCollection(
            $foundationWorkunit
                ->communities()
                ->with(['subdistrict', 'village'])
                ->withCount('members')
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
        Gate::authorize('create', FoundationCommunity::class);

        $request->validate([]);

        return FoundationCommunity::storeRecord($request, $foundationWorkunit);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationWorkunit $foundationWorkunit, FoundationCommunity $foundationCommunity)
    {
        Gate::authorize('show', $foundationCommunity);

        return new CommunityShowResource($foundationCommunity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationWorkunit $foundationWorkunit, FoundationCommunity $foundationCommunity)
    {
        Gate::authorize('update', $foundationCommunity);

        $request->validate([]);

        return FoundationCommunity::updateRecord($request, $foundationCommunity);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationWorkunit $foundationWorkunit, FoundationCommunity $foundationCommunity)
    {
        Gate::authorize('delete', $foundationCommunity);

        return FoundationCommunity::deleteRecord($foundationCommunity);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @return \Illuminate\Http\Response
     */
    public function restore(FoundationWorkunit $foundationWorkunit, FoundationCommunity $foundationCommunity)
    {
        Gate::authorize('restore', $foundationCommunity);

        return FoundationCommunity::restoreRecord($foundationCommunity);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(FoundationWorkunit $foundationWorkunit, FoundationCommunity $foundationCommunity)
    {
        Gate::authorize('destroy', $foundationCommunity);

        return FoundationCommunity::destroyRecord($foundationCommunity);
    }
}
