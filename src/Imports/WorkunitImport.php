<?php

namespace Module\Foundation\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\Foundation\Models\FoundationWorkunit;

class WorkunitImport implements ToCollection, WithHeadingRow
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
        $this->command->info('foundation:workunits_table');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            $record = (object) $row->toArray();

            /** CREATE NEW RECORD */
            $model = new FoundationWorkunit();
            $model->name = $record->name;
            $model->slug = sha1(str($record->name . ' ' . $record->village_id)->slug());
            $model->scope = $record->scope;
            $model->village_id = $record->village_id;
            $model->parent_id = $record->parent_id;
            $model->save();
        }

        $this->command->getOutput()->progressFinish();
    }
}
