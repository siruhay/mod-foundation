<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationSubdistrict;
use Module\Foundation\Http\Resources\SubdistrictCollection;
use Module\Foundation\Http\Resources\SubdistrictShowResource;

class FoundationSubdistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', FoundationSubdistrict::class);

        return new SubdistrictCollection(
            FoundationSubdistrict::applyMode($request->mode)
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
        Gate::authorize('create', FoundationSubdistrict::class);

        $request->validate([]);

        return FoundationSubdistrict::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationSubdistrict $foundationSubdistrict
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationSubdistrict $foundationSubdistrict)
    {
        Gate::authorize('show', $foundationSubdistrict);

        return new SubdistrictShowResource($foundationSubdistrict);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationSubdistrict $foundationSubdistrict
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationSubdistrict $foundationSubdistrict)
    {
        Gate::authorize('update', $foundationSubdistrict);

        $request->validate([]);

        return FoundationSubdistrict::updateRecord($request, $foundationSubdistrict);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationSubdistrict $foundationSubdistrict
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationSubdistrict $foundationSubdistrict)
    {
        Gate::authorize('delete', $foundationSubdistrict);

        return FoundationSubdistrict::deleteRecord($foundationSubdistrict);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationSubdistrict $foundationSubdistrict
     * @return \Illuminate\Http\Response
     */
    public function restore(FoundationSubdistrict $foundationSubdistrict)
    {
        Gate::authorize('restore', $foundationSubdistrict);

        return FoundationSubdistrict::restoreRecord($foundationSubdistrict);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationSubdistrict $foundationSubdistrict
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(FoundationSubdistrict $foundationSubdistrict)
    {
        Gate::authorize('destroy', $foundationSubdistrict);

        return FoundationSubdistrict::destroyRecord($foundationSubdistrict);
    }

    /**
     * villages function
     *
     * @param FoundationSubdistrict $foundationSubdistrict
     * @return void
     */
    public function villages(FoundationSubdistrict $foundationSubdistrict)
    {
        return $foundationSubdistrict->villages()->forCombo();
    }
}
