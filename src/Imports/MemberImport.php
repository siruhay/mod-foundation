<?php

namespace Module\Foundation\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\Foundation\Models\FoundationMember;
use Module\Foundation\Models\FoundationVillage;
use Module\Foundation\Models\FoundationPosition;
use Module\Foundation\Models\FoundationWorkunit;
use Module\Foundation\Models\FoundationCommunity;
use Module\Foundation\Models\FoundationOrganization;

class MemberImport implements ToCollection, WithHeadingRow
{
    /**
     * The construct function
     *
     * @param [type] $command
     * @param string $mode
     */
    public function __construct(protected $command)
    {
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $this->command->info('foundation:member_table');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            $record     = (object) $row->toArray();
            $village    = FoundationVillage::find($record->village_id);
            $workunit   = FoundationWorkunit::firstWhere('village_id', $village->id);

            switch ($record->community_id) {
                case 'BPD':
                    $name           = 'BPD ' . $village->type . ' ' . $village->name;
                    $maps           = 1;
                    $citizen        = null;
                    $neighborhood   = null;
                    break;

                case 'LPM':
                    $name           = 'LPM ' . $village->type . ' ' . $village->name;
                    $maps           = 2;
                    $citizen        = null;
                    $neighborhood   = null;
                    break;

                case 'PKK':
                    $name           = 'PKK ' . $village->type . ' ' . $village->name;
                    $maps           = 3;
                    $citizen        = null;
                    $neighborhood   = null;
                    break;

                case 'RW':
                    $name           = 'RW ' . str_pad($record->citizen, 2, "0", STR_PAD_LEFT) . ' ' . $village->type . ' ' . $village->name;
                    $maps           = 4;
                    $citizen        = str_pad($record->citizen, 2, "0", STR_PAD_LEFT);
                    $neighborhood   = null;
                    break;

                default:
                    $name           = 'RT ' . str_pad($record->citizen, 2, "0", STR_PAD_LEFT) . '/' . str_pad($record->neighborhood, 3, "0", STR_PAD_LEFT) .  ' ' . $village->type . ' ' . $village->name;
                    $maps           = 5;
                    $citizen        = str_pad($record->citizen, 2, "0", STR_PAD_LEFT);
                    $neighborhood   = str_pad($record->neighborhood, 3, "0", STR_PAD_LEFT);
                    break;
            }

            $slug       = sha1($name);
            $community  = FoundationCommunity::firstWhere('slug', $slug);

            if (!$community) {
                $community = new FoundationCommunity();
                $community->name = $name;
                $community->slug = $slug;
                $community->communitymap_id = $maps;
                $community->village_id = $village->id;
                $community->workunit_id = $workunit->id;
                $community->subdistrict_id = $village->district_id;
                $community->regency_id = $village->regency_id;
                $community->citizen = $citizen;
                $community->neighborhood = $neighborhood;
                $community->save();

                /** CREATE POSITIONS */
                foreach (FoundationOrganization::where('scope', 'LKD')->orderBy('_lft')->get() as $organization) {
                    $model = new FoundationPosition();

                    $model->name = $organization->name;
                    $model->posmap_id = $organization->posmap_id;
                    $model->village_id = $community->village_id;
                    $model->workunitable_id = $community->id;
                    $model->workunitable_type = get_class($community);
                    $model->organization_id = $organization->id;

                    if ($workunit->scope === 'KELURAHAN') {
                        switch ($organization->posmap_id) {
                            case 7:
                                $model->name = 'LURAH';
                                break;

                            default:
                                $model->name = str_replace('DESA', 'KELURAHAN', $model->name);
                                break;
                        }
                    }

                    $parent = null;

                    if ($organization->parent_id) {
                        $parent = FoundationPosition::where('village_id', $community->village_id)
                            ->where('workunitable_id', $community->id)
                            ->where('workunitable_type', get_class($community))
                            ->where('organization_id', $organization->parent_id)
                            ->first();
                    }

                    $model->slug = sha1(str($organization->id . ' ' . $community->village_id . ' ' . $community->slug)->slug());
                    $model->parent_id = $parent ? $parent->id : null;
                    $model->save();
                }
            }

            /** CREATE NEW RECORD */
            // $model = FoundationMember::firstWhere('phone', $record->handphone);

            // if ($record->handphone && !$model) {
            //     $model = new FoundationMember();
            //     $model->name = $record->name;
            //     $model->phone = $record->handphone;
            //     $model->gender_id = $record->gender_id;
            //     $model->position_id = $record->position_id;
            //     $model->village_id = optional($village)->id;
            //     $model->subdistrict_id = optional($village)->district_id;
            //     $model->regency_id = optional($village)->regency_id;
            //     $model->community_id = optional($community)->id;
            //     $model->communitymap_id = $maps;
            //     $model->citizen = $record->citizen ? str_pad($record->citizen, 2, "0", STR_PAD_LEFT) : null;
            //     $model->neighborhood = $record->neighborhood ? str_pad($record->neighborhood, 3, "0", STR_PAD_LEFT) : null;
            //     $model->save();

            //     if ($model->position_id === 19) {
            //         $community->official_id = $model->id;
            //         $community->save();
            //     }
            // }
        }

        $this->command->getOutput()->progressFinish();
    }
}
