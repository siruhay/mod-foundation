# Foundation Module — Skill Reference

## 1. Module Overview

- **Name**: `Foundation` (module.json `name`)
- **Namespace**: `Module\Foundation` (PSR-4 root `src/`)
- **Composer package**: `monosoft/module-foundation`
- **Service provider**: `Module\Foundation\Providers\FoundationServiceProvider`
- **Priority**: `3` (module.json `priority`, controls boot order among modules)
- **Connection**: declared as `"connection": "platform"` in `module.json`, and every Eloquent model in the module hardcodes `protected $connection = 'platform';`
- **Purpose**: Foundation is the master-data / organizational backbone module of the platform. It manages the government/administrative hierarchy — work units (organizational units such as OPD/Desa/Kecamatan/Kelurahan), organizations, positions/posts, officials & community members (biodata), communities (Posyandu-style social groups), and geographic reference data (subdistricts/villages/regencies) scoped to this module. It also exposes a generic file upload/download endpoint and printable reports (PDF-style Blade views) for officials and communities.

This module is a **git submodule** living at `modules/foundation` inside a larger nwidart/laravel-modules-style monorepo (sibling modules include `modules/system`, and referenced-but-not-explored `modules/reference`, `modules/posyandu`, `modules/myfoundation`).

## 2. Dependencies

### Composer (`composer.json`)
- PHP `^8.0.2`
- `guzzlehttp/guzzle` `^7.2`
- Autoload: `Module\Foundation\` → `src/`, `ModuleFoundation\Seeders\` → `database/seeders`
- Uses `maatwebsite/excel` (`Maatwebsite\Excel\...`) for Excel import/seeding (not declared explicitly in this composer.json — inherited from the platform/root composer).

### Other modules referenced in code (cross-module coupling)
| Namespace | Used for |
|---|---|
| `Module\System\Models\SystemUser` | Auth user model, used in all Policies (`$user->hasPermission(...)`, `$user->hasLicenseAs(...)`) and seeders |
| `Module\System\Traits\{HasMeta,Filterable,Searchable,HasPageSetup}` | Shared Eloquent behavior traits (found at `modules/system/src/Traits/`) — mixed into nearly every Foundation model |
| `Module\System\Imports\BaseImport` | Generic Excel import helper used by `FoundationBaseSeeder` |
| `Monoland\Platform\DiscoverEvents` | Used by `EventServiceProvider` for auto-discovering event/listener pairs |
| `Module\Reference\Models\{ReferenceVillage,ReferenceSubdistrict}` | Used in `DashboardController::combos()` for cascading region dropdowns |
| `Module\Posyandu\Models\PosyanduService` | `FoundationMember::service()` belongsTo relation (`scope` column references a Posyandu service) |
| `Module\MyFoundation` | Referenced elsewhere in the codebase (grep hit on namespace prefix `Module\MyFoundation`) — likely a related/derivative module, not explored here |

Note: `FoundationFaith`, `FoundationGender`, `FoundationRegency`, `FoundationVillage`, `FoundationSubdistrict` models point at tables named `reference_*` (see §3) — i.e. Foundation reuses tables owned by the **Reference module's schema** but wraps them in its own local Eloquent models rather than importing `Module\Reference\Models\*` directly (except in `DashboardController`).

## 3. Data Model / Schema

All tables live on the `platform` connection and are prefixed `foundation_`. Migrations are in `database/migrations/`.

| Migration | Table | Key columns | Notes |
|---|---|---|---|
| `2025_05_23_133439_create_foundation_workunits.php` | `foundation_workunits` | `name`, `slug` (unique), `scope` enum(`BADAN,DESA,KECAMATAN,KELURAHAN,OPD,UPT`), `village_id`, `meta` (jsonb) | Uses `nestedSet()` (hierarchical tree, likely `kalnoy/nestedset`), `softDeletes` |
| `2025_05_23_133440_create_foundation_communitymaps.php` | `foundation_communitymaps` | `name`, `slug` (unique), `short` (unique, 10 chars), `meta` | Lookup/category table for community types |
| `2025_05_23_133441_create_foundation_communities.php` | `foundation_communities` | `name`, `slug`, `communitymap_id`, `workunit_id`, `village_id`, `subdistrict_id`, `regency_id`, `officer_id` (nullable), `citizen`, `neighborhood`, `scopes` (jsonb array e.g. `HEALTH,EDUCATION,SOCIAL,ENVIRONMENT,ORDER`), `meta` | Social/community groups tied to a work unit and geography |
| `2025_05_23_133442_create_foundation_posmaps.php` | `foundation_posmaps` | `name`, `slug`, `scope` enum(`BADAN,DESA,KECAMATAN,KELURAHAN,LKD,OPD,UPT`), `meta` | Lookup/category table for position types |
| `2025_05_23_133443_create_foundation_positions.php` | `foundation_positions` | `name` (text), `slug` (text, unique), `posmap_id`, `village_id`, `workunitable` (morphs — polymorphic owner: workunit/community/organization), `organization_id`, `officer_id`, `position_type` enum(`STRUCTURAL,FUNCTIONAL,EXECUTOR`), `meta` | Uses `nestedSet()` + `softDeletes`; polymorphic across workunits/communities/organizations |
| `2025_05_23_133444_create_foundation_biodatas.php` | `foundation_biodatas` | `name`, `slug` (NIK, unique), `phone` (unique), `birthdate`, `kind` enum(`ASN,NON-ASN`), `type` (`ASN/LKD/OPD/SPEAKER/BENEFICIARY`), `role` (`OPERATOR/MEMBER/CHAIRMAN/MODERATOR/FELLOW/SPEAKER`), `gender_id`, `faith_id`, `position_id`, `workunitable` (morphs), `address`, `village_id`, `subdistrict_id`, `regency_id`, `citizen`, `neighborhood`, `family_card_number` (KK), `scope`, `meta` | Base "person" table — parent of `FoundationOfficial` and `FoundationMember` via single-table-ish inheritance (see §4) |
| `2025_06_19_142339_create_foundation_organizations.php` | `foundation_organizations` | `name`, `slug`, `posmap_id`, `scope` enum, `position_type` enum, `meta` | Uses `nestedSet()` + `softDeletes` |

Other tables referenced but **not owned/migrated by this module** (models point to them, presumably migrated by the Reference/System modules): `reference_faiths`, `reference_genders`, `reference_regencies`, `reference_villages`, `reference_subdistricts`.

All tables use `jsonb('meta')` for extensible metadata (paired with `HasMeta` trait) and (except `biodatas`) `softDeletes()` + `timestamps()`.

## 4. Domain Entities / Models (`src/Models/`)

| Model | Table | Extends | Purpose / notable relations |
|---|---|---|---|
| `FoundationWorkunit` | `foundation_workunits` | `Model` | Organizational unit tree (nested set). `communities()` hasMany, `officials()`/`positions()` morphMany, `village()` belongsTo |
| `FoundationCommunity` | `foundation_communities` | `Model` | `communitymap()`, `subdistrict()`, `village()`, `official()` belongsTo; `members()`, `positions()` morphMany |
| `FoundationCommunitymap` | `foundation_communitymaps` | `Model` | Simple lookup/category for communities |
| `FoundationPosmap` | `foundation_posmaps` | `Model` | Simple lookup/category for positions/organizations |
| `FoundationPosition` | `foundation_positions` | `Model` | Nested-set position/post; `officer()` belongsTo `FoundationBiodata` |
| `FoundationOrganization` | `foundation_organizations` | `Model` | Nested-set organization entity |
| `FoundationBiodata` | `foundation_biodatas` | `Model` | Base "person" record. Relations: `gender()`, `faith()`, `position()`, `regency()`, `subdistrict()`, `village()` (all belongsTo), `user()` morphOne, `workunitable()` morphTo. Uses traits `Filterable`, `HasMeta`, `HasPageSetup`, `Searchable`, `SoftDeletes`. `$roles = ['foundation-biodata']` |
| `FoundationOfficial` | `foundation_biodatas` (inherited) | `FoundationBiodata` | Subtype via `booted()` global scope (likely filtering `type`/`kind` — file present but scope body not fully inspected beyond declaration) |
| `FoundationMember` | `foundation_biodatas` (inherited) | `FoundationBiodata` | Subtype with global scope `where('type', 'LKD')`. Adds `service()` belongsTo `Module\Posyandu\Models\PosyanduService` via `scope` column. Dispatches `TrainingMemberUpdated` event (imported) |
| `FoundationSubdistrict` | `reference_subdistricts` | `Model` | `regency()` belongsTo, `villages()` hasMany (`district_id`) |
| `FoundationVillage` | `reference_villages` | `Model` | Village/kelurahan-level reference data |
| `FoundationRegency` | `reference_regencies` | `Model` | Regency/kabupaten-level reference data |
| `FoundationFaith` | `reference_faiths` | `Model` | Religion lookup |
| `FoundationGender` | `reference_genders` | `Model` | Gender lookup |

**Pattern**: `FoundationOfficial` and `FoundationMember` are both subclasses of `FoundationBiodata` sharing the same table, differentiated by a global scope on the `type`/`kind` column (single-table inheritance). Both live in `src/Models/` as separate files.

**Shared traits** (from `Module\System\Traits`, defined in `modules/system/src/Traits/`):
- `HasMeta` — helpers around the `meta` jsonb column
- `Filterable` — powers `->filter($request->filters)` in controllers
- `Searchable` — powers `->search($request->findBy)`
- `HasPageSetup` — powers `mapHeaders()`/`mapFilters()`/`mapCombos()` style methods seen in models (e.g. `FoundationBiodata::mapHeaders()`, `FoundationCommunity::toFilterableArray()`/`mapFilters()`) and static CRUD helpers `storeRecord()`, `updateRecord()`, `deleteRecord()`, `restoreRecord()`, `destroyRecord()` called directly from controllers.

## 5. API Routes (`routes/api.php`)

Routes are mounted by `RouteServiceProvider::mapApiRoutes()` under `prefix('<system_modules.prefix>/api')`, `middleware(['api','auth:sanctum'])`, with an optional dynamic subdomain resolved from the `system_modules` table (`slug = 'foundation'`, cached via `Cache::flexible`). Web routes (`mapWebRoutes()`) are stubbed out/commented — this module is API-only today.

| Route | Controller@method | Purpose |
|---|---|---|
| `GET dashboard` | `DashboardController@index` | Stub (empty body, returns void) |
| `GET report` | `DashboardController@report` | Returns report setup JSON (no `type` param) or renders `foundation::reports.{type}` Blade view |
| `GET fetch-combos` | `DashboardController@combos` | Cascading region dropdown data (subdistricts by regency, villages by subdistrict) via `Module\Reference` models |
| `POST upload-document` | `DashboardController@upload` | Generic file upload to `Storage::disk('uploads')` |
| `GET upload-document` | `DashboardController@download` | Generic file download from `uploads` disk |
| `DELETE upload-document` | `DashboardController@destroy` | Generic file delete from `uploads` disk |
| `resource('community', ...)` | `FoundationCommunityController` | Standard CRUD + `restore`/`forceDelete` extensions (see §6 pattern), route param `foundationCommunity` |
| `resource('organization', ...)` | `FoundationOrganizationController` | CRUD, param `foundationOrganization` |
| `resource('workunit', ...)` | `FoundationWorkunitController` | CRUD, param `foundationWorkunit` |
| `resource('workunit.position', ...)` | `FoundationWorkunitposController` | Nested — positions under a workunit (`FoundationWorkunit::positions()` morph relation) |
| `resource('workunit.official', ...)` | `FoundationOfficialController` | Nested — officials under a workunit |
| `resource('workunit.community', ...)` | `FoundationWorkunitCommunityController` | Nested — communities under a workunit |
| `resource('community.position', ...)` | `FoundationCommunityposController` | Nested — positions under a community |
| `resource('community.member', ...)` | `FoundationMemberController` | Nested — members under a community |
| `resource('communitymap', ...)` | `FoundationCommunitymapController` | CRUD for community-map lookups |
| `GET subdistrict/{foundationSubdistrict}/villages` | `FoundationSubdistrictController@villages` | List villages for a subdistrict |
| `resource('subdistrict', ...)` | `FoundationSubdistrictController` | CRUD |
| `resource('subdistrict.village', ...)` | `FoundationVillageController` | Nested — villages under a subdistrict |

Other controllers present in `src/Http/Controllers/` not wired into `routes/api.php` (dead/legacy or wired elsewhere, e.g. via `Module\MyFoundation`): `FoundationOfficialControllerx.php`, `FoundationCommunityxController.php` — naming (`x` suffix) suggests experimental/deprecated duplicates.

## 6. Controller Pattern

Every standard resource controller (e.g. `FoundationCommunityController`, `src/Http/Controllers/FoundationCommunityController.php`) follows the same shape:

1. `index()` — `Gate::authorize('view', Model::class)`; builds query with eager loads, `applyMode($request->mode)`, `filter($request->filters)`, `search($request->findBy)`, `sortBy($request->sortBy)`, `paginate($request->itemsPerPage)`; wraps in a `*Collection` API Resource.
2. `store()` — `Gate::authorize('create', ...)`; delegates to `Model::storeRecord($request)` (static helper from `HasPageSetup`).
3. `show()` — `Gate::authorize('show', $model)`; returns a `*ShowResource`.
4. `update()` — `Gate::authorize('update', $model)`; delegates to `Model::updateRecord($request, $model)`.
5. `destroy()` — `Gate::authorize('delete', $model)`; delegates to `Model::deleteRecord($model)` (soft delete).
6. `restore()` — `Gate::authorize('restore', $model)`; `Model::restoreRecord($model)`.
7. `forceDelete()` — `Gate::authorize('destroy', $model)`; `Model::destroyRecord($model)` (hard delete).

Nested/relation controllers (e.g. `FoundationWorkunitposController`) follow the same pattern but scope the query through the parent model's relation (e.g. `$foundationWorkunit->positions()->withDepth()->...`).

## 7. API Resources (`src/Http/Resources/`)

Each entity has a 3-file trio: `*Collection` (paginated list wrapper), `*Resource` (list-row shape), `*ShowResource` (detail shape) — for `Biodata`, `Community`, `Communitymap`, `Member`, `Official`, `Organization`, `Position`, `Posmap`, `Subdistrict`, `Village`, `Workunit`.

## 8. Policies (`src/Policies/`)

One policy per major model, all following an identical shape (example `FoundationBiodataPolicy`, `src/Policies/FoundationBiodataPolicy.php`):

- `before(SystemUser $user, string $ability)` — grants all abilities if `$user->hasLicenseAs('foundation-superadmin')`
- `view`, `show`, `create`, `update`, `delete`, `restore`, `destroy` — each checks a discrete permission string of the form `{ability}-foundation-{entity}` (e.g. `view-foundation-biodata`, `destroy-foundation-community`)

Policies present: `FoundationBiodataPolicy`, `FoundationCommunityPolicy`, `FoundationCommunitymapPolicy`, `FoundationMemberPolicy`, `FoundationOfficialPolicy`, `FoundationOrganizationPolicy`, `FoundationPositionPolicy`, `FoundationPosmapPolicy`, `FoundationSubdistrictPolicy`, `FoundationVillagePolicy`, `FoundationWorkunitPolicy`.

**Policy auto-resolution**: `FoundationServiceProvider::boot()` registers a custom `Gate::guessPolicyNamesUsing()` closure that maps any `Module\X\Models\Y` class to `Module\X\Policies\YPolicy` — this convention applies platform-wide (any module following the same folder layout gets automatic policy resolution), not just Foundation.

Permission strings to know when integrating: `view-foundation-{entity}`, `show-foundation-{entity}`, `create-foundation-{entity}`, `update-foundation-{entity}`, `delete-foundation-{entity}`, `restore-foundation-{entity}`, `destroy-foundation-{entity}`, plus the superadmin bypass license `foundation-superadmin`.

## 9. Events (`src/Events/`)

| Event | Purpose |
|---|---|
| `TrainingMemberUpdated` | Dispatched (presumably) when a `FoundationMember` changes; carries `Model $model` and `array $abilities` |
| `TrainingOfficialUpdated` | Same pattern for `FoundationOfficial` |

No `Listeners` directory currently exists in `src/`, but `EventServiceProvider` (`src/Providers/EventServiceProvider.php`) is pre-wired to auto-discover listeners from `src/Listeners` via `Monoland\Platform\DiscoverEvents` if that directory is ever added — no code changes needed to add listeners, just drop files in `src/Listeners/`.

`FoundationServiceProvider` also auto-discovers Artisan commands from `src/Commands/` (directory does not currently exist) — same drop-in convention.

## 10. Data Import / Excel Seeding (`src/Imports/`)

`DataImport` (`WithMultipleSheets`, `WithChunkReading`, chunk size 5000) orchestrates per-entity importers, one sheet each: `WorkunitImport`, `CommunitymapImport`, `PosmapImport`, `OrganizationImport`, `MemberImport`, `OfficialImport`, `PositionImport` (files present in `src/Imports/`). Driven by `database/seeders/FoundationBaseSeeder.php`, which loads `database/masters/base-seeder.xlsx` via `Maatwebsite\Excel\Facades\Excel` and `Module\System\Imports\BaseImport`. A second workbook `database/masters/data-seeder.xlsx` backs `FoundationDataSeeder`.

## 11. Seeders (`database/seeders/`)

- `DatabaseSeeder` — entry point; runs `module:migrate Foundation`, then `FoundationBaseSeeder`, `FoundationDataSeeder`, `FoundationUserSeeder`, in that order.
- `FoundationBaseSeeder` — imports `base-seeder.xlsx` master data.
- `FoundationDataSeeder` — imports `data-seeder.xlsx` (not fully inspected, same Excel-import convention).
- `FoundationUserSeeder` — grants `foundation-superadmin` license to the `SystemUser` matching `env('ADMIN_EMAIL')`.
- Seeders live in namespace `ModuleFoundation\Seeders` (note: different from `Module\Foundation\...` used everywhere else — a deliberate PSR-4 split so `database/seeders` autoloads independently).

## 12. Frontend (`frontend/`)

Vue-based SPA module, mounted into the platform's frontend router via `frontend/router/index.js`, which registers path `/foundation` (`meta: { requiredAuth: true }`) with lazy-loaded chunk `"foundation"`. Layout shell: `frontend/pages/Base.vue`. Only the `dashboard` child route is currently **active**; all CRUD page routes for community/communitymap/official/organization/position/subdistrict/subdistrict-village/workunit(+nested) are present as `.vue` files under `frontend/pages/<entity>/{index.vue, crud/{create,edit,show,data}.vue}` but are **commented out** in the router (dead/pending routes) except `dashboard`. A `report/index.vue` page also exists.

Per-entity frontend folder convention (mirrors backend resource structure): `frontend/pages/<entity>/index.vue` (list shell) + `frontend/pages/<entity>/crud/{data,create,edit,show}.vue` (datatable, create form, edit form, detail view).

## 13. Server-side Views (`resources/views/`)

Blade views registered under the `foundation::` namespace (`loadViewsFrom` in `FoundationServiceProvider`):
- `resources/views/welcome.blade.php`
- `resources/views/reports/official.blade.php` — rendered by `DashboardController::report()` when `type=official`
- `resources/views/reports/community.blade.php` — rendered when `type=community`
- `resources/views/reports/css.blade.php` — shared print styling, likely `@include`d by the two report views

## 14. Notable Patterns & Conventions

- **Single-connection multi-module DB**: All models pin `protected $connection = 'platform';` — the whole platform shares one DB regardless of module boundaries.
- **Single-table inheritance**: `FoundationOfficial`/`FoundationMember` both back onto `foundation_biodatas`, disambiguated via Eloquent global scopes in `booted()`.
- **Polymorphic ownership**: `workunitable` morph columns on both `foundation_positions` and `foundation_biodatas` let a Position or Biodata attach to a Workunit, Community, or Organization interchangeably.
- **Nested sets**: `foundation_workunits`, `foundation_positions`, `foundation_organizations` all use `$table->nestedSet()` (kalnoy/nestedset) for hierarchical trees — expect `parent_id`/`lft`/`rgt`/`depth` columns and `withDepth()`/`children()`/`ancestors()` style queries (seen used as `->positions()->withDepth()` in `FoundationWorkunitposController`).
- **Static CRUD helpers on models**: Controllers never call `->save()`/`->delete()` directly; they call `Model::storeRecord($request)`, `::updateRecord($request, $model)`, `::deleteRecord($model)`, `::restoreRecord($model)`, `::destroyRecord($model)` — these come from the shared `HasPageSetup` trait in the System module, so business/validation logic for persistence is centralized there, not per-model.
- **Convention-based policy resolution**: adding a new `Module\Foundation\Models\Foo` model + `Module\Foundation\Policies\FooPolicy` class is automatically wired — no manual `Gate::policy()` registration needed.
- **Auto-discovery of Commands/Listeners**: dropping files into `src/Commands/` or `src/Listeners/` is sufficient; both are scanned at boot by `FoundationServiceProvider`/`EventServiceProvider`.
- **Dynamic domain/prefix routing**: API routes' domain and path prefix are resolved at runtime from a `system_modules` DB table (row keyed by `slug = 'foundation'`), cached briefly (`Cache::flexible([60, 3600])`) — this module is domain/prefix-configurable per deployment, not hardcoded.
- **`x`-suffixed controllers** (`FoundationOfficialControllerx`, `FoundationCommunityxController`) appear to be legacy/experimental variants not wired into `routes/api.php` — verify before reusing.

## 15. How to Extend / Integrate

- **Add a new CRUD entity**: create migration in `database/migrations/` (table `foundation_<name>`, `jsonb('meta')`, `softDeletes()`), model in `src/Models/` (traits `Filterable`, `Searchable`, `HasMeta`, `HasPageSetup`, `SoftDeletes`, `$connection = 'platform'`), matching `*Policy` in `src/Policies/`, `*Resource`/`*Collection`/`*ShowResource` trio in `src/Http/Resources/`, controller in `src/Http/Controllers/` following the 7-method pattern in §6, then register `Route::resource(...)` in `routes/api.php`.
- **Consume Foundation data from another module**: reference `Module\Foundation\Models\*` directly (as `Module\Posyandu` and `DashboardController` already reference `Module\Reference\Models\*`); respect the `platform` connection and existing policy/permission naming (`{ability}-foundation-{entity}`).
- **React to Foundation changes**: listen for `Module\Foundation\Events\TrainingMemberUpdated` / `TrainingOfficialUpdated` (create a listener anywhere with Laravel's standard event listener registration, or drop it into `src/Listeners/` for auto-discovery within this module itself).
- **Add an Artisan command**: place a class implementing a `Command` in `src/Commands/` (directory not yet created) with namespace `Module\Foundation\Commands\...` — it will be auto-registered.
- **Enable frontend CRUD pages**: uncomment the relevant route blocks in `frontend/router/index.js`; the `.vue` files already exist under `frontend/pages/<entity>/`.
- **Seed/import master data**: update `database/masters/base-seeder.xlsx` / `data-seeder.xlsx` and the corresponding `src/Imports/*Import.php` sheet classes; re-run `DatabaseSeeder`.
