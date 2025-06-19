<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationOrganization;
use Module\Foundation\Http\Resources\OrganizationCollection;
use Module\Foundation\Http\Resources\OrganizationShowResource;

class FoundationOrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('view', FoundationOrganization::class);

        return new OrganizationCollection(
            FoundationOrganization::applyMode($request->mode)
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
        Gate::authorize('create', FoundationOrganization::class);

        $request->validate([]);

        return FoundationOrganization::storeRecord($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationOrganization $foundationOrganization
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationOrganization $foundationOrganization)
    {
        Gate::authorize('show', $foundationOrganization);

        return new OrganizationShowResource($foundationOrganization);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationOrganization $foundationOrganization
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationOrganization $foundationOrganization)
    {
        Gate::authorize('update', $foundationOrganization);

        $request->validate([]);

        return FoundationOrganization::updateRecord($request, $foundationOrganization);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationOrganization $foundationOrganization
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationOrganization $foundationOrganization)
    {
        Gate::authorize('delete', $foundationOrganization);

        return FoundationOrganization::deleteRecord($foundationOrganization);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationOrganization $foundationOrganization
     * @return \Illuminate\Http\Response
     */
    public function restore(FoundationOrganization $foundationOrganization)
    {
        Gate::authorize('restore', $foundationOrganization);

        return FoundationOrganization::restoreRecord($foundationOrganization);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationOrganization $foundationOrganization
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(FoundationOrganization $foundationOrganization)
    {
        Gate::authorize('destroy', $foundationOrganization);

        return FoundationOrganization::destroyRecord($foundationOrganization);
    }
}
