<?php

namespace Module\Foundation\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\Foundation\Models\FoundationVillage;
use Module\Foundation\Models\FoundationCommunity;

class CommunityImport implements ToCollection, WithHeadingRow
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
        $this->command->info('foundation:community_table');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            $record     = (object) $row->toArray();
            $village    = FoundationVillage::firstWhere('slug', $record->village_id);

            /** CREATE NEW RECORD */
            $model = new FoundationCommunity();
            $model->name = $record->name;
            $model->slug = sha1(str($record->name)->slug());
            $model->communitymap_id = $record->communitymap_id;
            $model->village_id = optional($village)->id;
            $model->subdistrict_id = optional($village)->subdistrict_id;
            $model->regency_id = optional($village)->regency_id;
            $model->save();
        }

        $this->command->getOutput()->progressFinish();
    }
}
