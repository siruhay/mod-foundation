<?php

use Illuminate\Support\Facades\Route;
use Module\Foundation\Http\Controllers\DashboardController;
use Module\Foundation\Http\Controllers\FoundationMemberController;
use Module\Foundation\Http\Controllers\FoundationVillageController;
use Module\Foundation\Http\Controllers\FoundationOfficialController;
use Module\Foundation\Http\Controllers\FoundationWorkunitController;
use Module\Foundation\Http\Controllers\FoundationCommunityController;
use Module\Foundation\Http\Controllers\FoundationSubdistrictController;
use Module\Foundation\Http\Controllers\FoundationWorkunitposController;
use Module\Foundation\Http\Controllers\FoundationCommunitymapController;
use Module\Foundation\Http\Controllers\FoundationCommunityposController;
use Module\Foundation\Http\Controllers\FoundationOrganizationController;
use Module\Foundation\Http\Controllers\FoundationWorkunitCommunityController;

Route::get('dashboard', [DashboardController::class, 'index']);
Route::get('report', [DashboardController::class, 'report']);

Route::resource('community', FoundationCommunityController::class)->parameters(['community' => 'foundationCommunity']);
Route::resource('organization', FoundationOrganizationController::class)->parameters(['organization' => 'foundationOrganization']);

Route::resource('workunit', FoundationWorkunitController::class)->parameters(['workunit' => 'foundationWorkunit']);
Route::resource('workunit.position', FoundationWorkunitposController::class)->parameters(['workunit' => 'foundationWorkunit', 'position' => 'foundationPosition']);
Route::resource('workunit.official', FoundationOfficialController::class)->parameters(['workunit' => 'foundationWorkunit', 'official' => 'foundationOfficial']);
Route::resource('workunit.community', FoundationWorkunitCommunityController::class)->parameters(['workunit' => 'foundationWorkunit', 'community' => 'foundationCommunity']);
Route::resource('community.position', FoundationCommunityposController::class)->parameters(['community' => 'foundationCommunity', 'position' => 'foundationPosition']);
Route::resource('community.member', FoundationMemberController::class)->parameters(['community' => 'foundationCommunity', 'position' => 'foundationPosition']);
Route::resource('communitymap', FoundationCommunitymapController::class)->parameters(['communitymap' => 'foundationCommunitymap']);

Route::get('subdistrict/{foundationSubdistrict}/villages', [FoundationSubdistrictController::class, 'villages']);
Route::resource('subdistrict', FoundationSubdistrictController::class)->parameters(['subdistrict' => 'foundationSubdistrict']);
Route::resource('subdistrict.village', FoundationVillageController::class)->parameters(['subdistrict' => 'foundationSubdistrict', 'village' => 'foundationVillage']);
