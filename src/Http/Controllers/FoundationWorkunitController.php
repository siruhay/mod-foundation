<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationWorkunit;
use Module\Foundation\Http\Resources\WorkunitCollection;
use Module\Foundation\Http\Resources\WorkunitShowResource;

class FoundationWorkunitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', FoundationWorkunit::class);

        return new WorkunitCollection(
            FoundationWorkunit::applyMode($request->mode)
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
        Gate::authorize('create', FoundationWorkunit::class);

        $request->validate([]);

        return FoundationWorkunit::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationWorkunit $foundationWorkunit)
    {
        Gate::authorize('show', $foundationWorkunit);

        return new WorkunitShowResource($foundationWorkunit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationWorkunit $foundationWorkunit)
    {
        Gate::authorize('update', $foundationWorkunit);

        $request->validate([]);

        return FoundationWorkunit::updateRecord($request, $foundationWorkunit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationWorkunit $foundationWorkunit)
    {
        Gate::authorize('delete', $foundationWorkunit);

        return FoundationWorkunit::deleteRecord($foundationWorkunit);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @return \Illuminate\Http\Response
     */
    public function restore(FoundationWorkunit $foundationWorkunit)
    {
        Gate::authorize('restore', $foundationWorkunit);

        return FoundationWorkunit::restoreRecord($foundationWorkunit);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(FoundationWorkunit $foundationWorkunit)
    {
        Gate::authorize('destroy', $foundationWorkunit);

        return FoundationWorkunit::destroyRecord($foundationWorkunit);
    }
}
