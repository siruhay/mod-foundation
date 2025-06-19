<?php

namespace Module\Foundation\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\Foundation\Models\FoundationPosmap;

class PosmapImport implements ToCollection, WithHeadingRow
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
        $this->command->info('foundation:posmaps_table');
        $this->command->getOutput()->progressStart(count($rows));

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            $record = (object) $row->toArray();

            /** CREATE NEW RECORD */
            $model = new FoundationPosmap();
            $model->name = $record->name;
            $model->slug = sha1(str($record->name . ' ' . $record->scope)->slug());
            $model->scope = $record->scope;
            $model->save();
        }

        $this->command->getOutput()->progressFinish();
    }
}
