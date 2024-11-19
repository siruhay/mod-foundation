<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationOfficial;
use Module\Foundation\Http\Resources\OfficialCollection;
use Module\Foundation\Http\Resources\OfficialShowResource;

class FoundationOfficialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', FoundationOfficial::class);

        return new OfficialCollection(
            FoundationOfficial::applyMode($request->mode)
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
        Gate::authorize('create', FoundationOfficial::class);

        $request->validate([
            'slug' => 'required|min_digits:16|numeric'
        ]);

        return FoundationOfficial::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationOfficial $foundationOfficial
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationOfficial $foundationOfficial)
    {
        Gate::authorize('show', $foundationOfficial);

        return new OfficialShowResource($foundationOfficial);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationOfficial $foundationOfficial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationOfficial $foundationOfficial)
    {
        Gate::authorize('update', $foundationOfficial);

        $request->validate([]);

        return FoundationOfficial::updateRecord($request, $foundationOfficial);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationOfficial $foundationOfficial
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationOfficial $foundationOfficial)
    {
        Gate::authorize('delete', $foundationOfficial);

        return FoundationOfficial::deleteRecord($foundationOfficial);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationOfficial $foundationOfficial
     * @return \Illuminate\Http\Response
     */
    public function restore(FoundationOfficial $foundationOfficial)
    {
        Gate::authorize('restore', $foundationOfficial);

        return FoundationOfficial::restoreRecord($foundationOfficial);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationOfficial $foundationOfficial
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(FoundationOfficial $foundationOfficial)
    {
        Gate::authorize('destroy', $foundationOfficial);

        return FoundationOfficial::destroyRecord($foundationOfficial);
    }
}
