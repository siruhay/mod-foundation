<?php

namespace Module\Foundation\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\Foundation\Models\FoundationBiodata;
use Module\Foundation\Models\FoundationVillage;
use Module\Foundation\Models\FoundationOfficial;
use Module\Foundation\Models\FoundationPosition;
use Module\Foundation\Models\FoundationWorkunit;

class OfficialImport implements ToCollection, WithHeadingRow
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
        $this->command->info('foundation:official_table');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            $record     = (object) $row->toArray();
            $village    = FoundationVillage::find($record->village_id);
            $workunit   = FoundationWorkunit::firstWhere('village_id', $record->village_id);

            if (!$workunit) {
                Log::info($record->village_id);
                continue;
            }

            /** CREATE NEW RECORD */
            $model = FoundationBiodata::firstWhere('phone', $record->handphone);

            if ($record->handphone && !$model) {
                $model = new FoundationBiodata();
                $model->name = $record->name;
                $model->phone = $record->handphone;
                $model->kind = 'NON-ASN';
                $model->type = 'DESA';
                $model->gender_id = $record->gender_id;

                $model->village_id = optional($village)->id;
                $model->subdistrict_id = optional($village)->district_id;
                $model->regency_id = optional($village)->regency_id;

                $model->workunitable_type = get_class($workunit);
                $model->workunitable_id = $workunit->id;

                if ($position = FoundationPosition::where('workunitable_type', get_class($workunit))
                    ->where('workunitable_id', $workunit->id)
                    ->where('organization_id', $record->position_id)
                    ->first()) {
                    $model->position_id = $position->id;
                }

                $model->save();

                if ($position && $position->position_type === 'STRUCTURAL') {
                    $position->officer_id = $model->id;
                    $position->save();
                }
            }
        }

        $this->command->getOutput()->progressFinish();
    }
}
