<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationOfficial;
use Module\Foundation\Models\FoundationWorkunit;
use Module\Foundation\Http\Resources\OfficialCollection;
use Module\Foundation\Http\Resources\OfficialShowResource;

class FoundationOfficialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, FoundationWorkunit $foundationWorkunit)
    {
        Gate::authorize('view', FoundationOfficial::class);

        return new OfficialCollection(
            $foundationWorkunit
                ->officials()
                ->with(['position'])
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
        Gate::authorize('create', FoundationOfficial::class);

        $request->validate([
            'name' => 'required',
            'slug' => 'required|min_digits:16|numeric',
            'phone' => 'required|unique:Module\Foundation\Models\FoundationBiodata,phone',
        ]);

        return FoundationOfficial::storeRecord($request, $foundationWorkunit);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @param  \Module\Foundation\Models\FoundationOfficial $foundationOfficial
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationWorkunit $foundationWorkunit, FoundationOfficial $foundationOfficial)
    {
        Gate::authorize('show', $foundationOfficial);

        return new OfficialShowResource($foundationOfficial);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @param  \Module\Foundation\Models\FoundationOfficial $foundationOfficial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationWorkunit $foundationWorkunit, FoundationOfficial $foundationOfficial)
    {
        Gate::authorize('update', $foundationOfficial);

        $request->validate([
            'name' => 'required',
            'slug' => 'required|min_digits:16|numeric',
            'phone' => 'required',
        ]);

        return FoundationOfficial::updateRecord($request, $foundationOfficial);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationWorkunit $foundationWorkunit
     * @param  \Module\Foundation\Models\FoundationOfficial $foundationOfficial
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationWorkunit $foundationWorkunit, FoundationOfficial $foundationOfficial)
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
    public function restore(FoundationWorkunit $foundationWorkunit, FoundationOfficial $foundationOfficial)
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
    public function forceDelete(FoundationWorkunit $foundationWorkunit, FoundationOfficial $foundationOfficial)
    {
        Gate::authorize('destroy', $foundationOfficial);

        return FoundationOfficial::destroyRecord($foundationOfficial);
    }
}
