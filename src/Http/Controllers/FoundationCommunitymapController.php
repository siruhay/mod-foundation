<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationCommunitymap;
use Module\Foundation\Http\Resources\CommunitymapCollection;
use Module\Foundation\Http\Resources\CommunitymapShowResource;

class FoundationCommunitymapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', FoundationCommunitymap::class);

        return new CommunitymapCollection(
            FoundationCommunitymap::applyMode($request->mode)
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
        Gate::authorize('create', FoundationCommunitymap::class);

        $request->validate([]);

        return FoundationCommunitymap::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationCommunitymap $foundationCommunitymap
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationCommunitymap $foundationCommunitymap)
    {
        Gate::authorize('show', $foundationCommunitymap);

        return new CommunitymapShowResource($foundationCommunitymap);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationCommunitymap $foundationCommunitymap
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationCommunitymap $foundationCommunitymap)
    {
        Gate::authorize('update', $foundationCommunitymap);

        $request->validate([]);

        return FoundationCommunitymap::updateRecord($request, $foundationCommunitymap);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationCommunitymap $foundationCommunitymap
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationCommunitymap $foundationCommunitymap)
    {
        Gate::authorize('delete', $foundationCommunitymap);

        return FoundationCommunitymap::deleteRecord($foundationCommunitymap);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationCommunitymap $foundationCommunitymap
     * @return \Illuminate\Http\Response
     */
    public function restore(FoundationCommunitymap $foundationCommunitymap)
    {
        Gate::authorize('restore', $foundationCommunitymap);

        return FoundationCommunitymap::restoreRecord($foundationCommunitymap);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationCommunitymap $foundationCommunitymap
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(FoundationCommunitymap $foundationCommunitymap)
    {
        Gate::authorize('destroy', $foundationCommunitymap);

        return FoundationCommunitymap::destroyRecord($foundationCommunitymap);
    }
}
