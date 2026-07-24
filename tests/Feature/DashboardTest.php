<?php

use Illuminate\Support\Facades\Route;
use Module\Foundation\Models\FoundationBiodata;
use Module\Foundation\Models\FoundationCommunity;
use Module\Foundation\Models\FoundationOrganization;

function foundationDashboardUri(): string
{
    $route = collect(Route::getRoutes())->first(
        fn ($route) => str_contains($route->getActionName(), 'Foundation\Http\Controllers\DashboardController@index')
            && ! str_contains($route->getActionName(), 'MyFoundation')
    );

    return '/' . ltrim($route->uri(), '/');
}

it('returns foundation KPI counts', function () {
    $baseline = [
        'communities' => FoundationCommunity::count(),
        'officials' => FoundationBiodata::where('type', 'OPD')->count(),
        'members' => FoundationBiodata::where('type', 'LKD')->count(),
        'organizations' => FoundationOrganization::count(),
    ];

    $communities = FoundationCommunity::factory()->count(2)->create();

    FoundationBiodata::factory()->type('OPD')->count(3)->create([
        'workunitable_id' => $communities->first()->id,
    ]);
    FoundationBiodata::factory()->type('LKD')->count(4)->create([
        'workunitable_id' => $communities->first()->id,
    ]);

    $user = licensedUser('foundation-superadmin');

    $response = $this->actingAs($user)->getJson(foundationDashboardUri());

    $response->assertOk();
    expect($response->json('record.totalCommunities'))->toBe($baseline['communities'] + 2);
    expect($response->json('record.totalOfficials'))->toBe($baseline['officials'] + 3);
    expect($response->json('record.totalMembers'))->toBe($baseline['members'] + 4);
    expect($response->json('record.totalOrganizations'))->toBe($baseline['organizations']);
});
