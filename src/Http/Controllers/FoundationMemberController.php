<?php

namespace Module\Foundation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Module\Foundation\Models\FoundationMember;
use Module\Foundation\Models\FoundationCommunity;
use Module\Foundation\Http\Resources\MemberCollection;
use Module\Foundation\Http\Resources\MemberShowResource;

class FoundationMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, FoundationCommunity $foundationCommunity)
    {
        Gate::authorize('view', FoundationMember::class);

        return new MemberCollection(
            $foundationCommunity
                ->members()
                ->with(['position', 'service'])
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
        Gate::authorize('create', FoundationMember::class);

        $request->validate([
            'slug' => 'required|min_digits:16|numeric'
        ]);

        return FoundationMember::storeRecord($request, $foundationCommunity);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @param  \Module\Foundation\Models\FoundationMember $foundationMember
     * @return \Illuminate\Http\Response
     */
    public function show(FoundationCommunity $foundationCommunity, FoundationMember $foundationMember)
    {
        Gate::authorize('show', $foundationMember);

        return new MemberShowResource($foundationMember);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @param  \Module\Foundation\Models\FoundationMember $foundationMember
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoundationCommunity $foundationCommunity, FoundationMember $foundationMember)
    {
        Gate::authorize('update', $foundationMember);

        $request->validate([]);

        return FoundationMember::updateRecord($request, $foundationMember, $foundationCommunity);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Module\Foundation\Models\FoundationCommunity $foundationCommunity
     * @param  \Module\Foundation\Models\FoundationMember $foundationMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoundationCommunity $foundationCommunity, FoundationMember $foundationMember)
    {
        Gate::authorize('delete', $foundationMember);

        return FoundationMember::deleteRecord($foundationMember);
    }

    /**
     * Restore the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationMember $foundationMember
     * @return \Illuminate\Http\Response
     */
    public function restore(FoundationCommunity $foundationCommunity, FoundationMember $foundationMember)
    {
        Gate::authorize('restore', $foundationMember);

        return FoundationMember::restoreRecord($foundationMember);
    }

    /**
     * Force Delete the specified resource from soft-delete.
     *
     * @param  \Module\Foundation\Models\FoundationMember $foundationMember
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(FoundationCommunity $foundationCommunity, FoundationMember $foundationMember)
    {
        Gate::authorize('destroy', $foundationMember);

        return FoundationMember::destroyRecord($foundationMember);
    }
}
