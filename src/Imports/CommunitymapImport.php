<?php

namespace Module\Foundation\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\Foundation\Models\FoundationCommunitymap;

class CommunitymapImport implements ToCollection, WithHeadingRow
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
        $this->command->info('foundation:communitymap_table');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            $record = (object) $row->toArray();

            /** CREATE NEW RECORD */
            $model = new FoundationCommunitymap();
            $model->name = $record->name;
            $model->slug = sha1(str($record->name)->slug());
            $model->short = $record->short;
            $model->save();
        }

        $this->command->getOutput()->progressFinish();
    }
}
