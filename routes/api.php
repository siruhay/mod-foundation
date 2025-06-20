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

Route::get('dashboard', [DashboardController::class, 'index']);
Route::get('report', [DashboardController::class, 'report']);

Route::resource('workunit', FoundationWorkunitController::class)->parameters(['workunit' => 'foundationWorkunit']);
Route::resource('workunit.position', FoundationWorkunitposController::class)->parameters(['workunit' => 'foundationWorkunit', 'position' => 'foundationPosition']);
Route::resource('workunit.official', FoundationOfficialController::class)->parameters(['workunit' => 'foundationWorkunit', 'official' => 'foundationOfficial']);
Route::resource('workunit.community', FoundationCommunityController::class)->parameters(['workunit' => 'foundationWorkunit', 'community' => 'foundationCommunity']);
Route::resource('community.member', FoundationMemberController::class)->parameters(['community' => 'foundationCommunity', 'member' => 'foundationMember']);

// Route::resource('position', FoundationPositionController::class)->parameters(['position' => 'foundationPosition']);
Route::resource('communitymap', FoundationCommunitymapController::class)->parameters(['communitymap' => 'foundationCommunitymap']);
// Route::resource('community', FoundationCommunityController::class)->parameters(['community' => 'foundationCommunity']);

// Route::resource('official', FoundationOfficialController::class)->parameters(['official' => 'foundationOfficial']);

Route::get('subdistrict/{foundationSubdistrict}/villages', [FoundationSubdistrictController::class, 'villages']);
Route::resource('subdistrict', FoundationSubdistrictController::class)->parameters(['subdistrict' => 'foundationSubdistrict']);
Route::resource('subdistrict.village', FoundationVillageController::class)->parameters(['subdistrict' => 'foundationSubdistrict', 'village' => 'foundationVillage']);
