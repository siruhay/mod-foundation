<?php

namespace Module\Foundation\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\Foundation\Models\FoundationPosition;
use Module\Foundation\Models\FoundationWorkunit;
use Module\Foundation\Models\FoundationOrganization;

class OrganizationImport implements ToCollection, WithHeadingRow
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
        $this->command->info('foundation:organization_table');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            $record = (object) $row->toArray();

            /** CREATE NEW RECORD */
            $model = new FoundationOrganization();
            $model->name = $record->name;
            $model->slug = sha1(str($record->name . ' ' . $record->scope)->slug());
            $model->scope = $record->scope;
            $model->posmap_id = $record->posmap_id;
            $model->parent_id = $record->parent_id;
            $model->save();
        }

        $this->command->getOutput()->progressFinish();


        /**
         * SEED POSITION BY ORGANIZATION
         */
        $workunits = FoundationWorkunit::whereNotNull('village_id')->get();

        $this->command->info('foundation:positions_table');
        $this->command->getOutput()->progressStart(count($workunits));

        foreach ($workunits as $workunit) {
            $this->command->getOutput()->progressAdvance();

            foreach (FoundationOrganization::where('scope', 'DESA')->orderBy('_lft')->get() as $organization) {
                $model = new FoundationPosition();

                $model->name = $organization->name;
                $model->posmap_id = $organization->posmap_id;
                $model->village_id = $workunit->village_id;
                $model->workunitable_id = $workunit->id;
                $model->workunitable_type = get_class($workunit);
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
                    $parent = FoundationPosition::where('village_id', $workunit->village_id)
                        ->where('workunitable_id', $workunit->id)
                        ->where('organization_id', $organization->parent_id)
                        ->first();
                }

                $model->slug = sha1(str($organization->id . ' ' . $workunit->village_id . ' ' . $workunit->id)->slug());
                $model->parent_id = $parent ? $parent->id : null;
                $model->save();
            }
        }

        $this->command->getOutput()->progressFinish();
    }
}
