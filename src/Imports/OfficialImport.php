<?php

namespace Module\Foundation\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\Foundation\Models\FoundationOfficial;
use Module\Foundation\Models\FoundationVillage;

class OfficialImport implements ToCollection, WithHeadingRow
{
    /**
     * The construct function
     *
     * @param [type] $command
     * @param string $mode
     */
    public function __construct(protected $command) {}

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
            $village    = FoundationVillage::firstWhere('slug', $record->village_id);

            /** CREATE NEW RECORD */
            $model = new FoundationOfficial();
            $model->name = $record->name;
            $model->phone = $record->phone;
            $model->gender_id = $record->gender_id;
            $model->position_id = $record->position_id;
            $model->village_id = optional($village)->id;
            $model->subdistrict_id = optional($village)->subdistrict_id;
            $model->regency_id = optional($village)->regency_id;
            $model->save();
        }

        $this->command->getOutput()->progressFinish();
    }
}
