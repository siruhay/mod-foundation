<?php

namespace Module\Foundation\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Module\Foundation\Models\FoundationPosition;
use Module\Foundation\Models\FoundationWorkunit;

class PositionImport implements ToCollection, WithHeadingRow
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
        $this->command->info('foundation:position_table');
        $this->command->getOutput()->progressStart(count($rows));

        $villages = [];
        $institutions = [];

        foreach ($rows as $row) {
            $this->command->getOutput()->progressAdvance();

            $record = (object) $row->toArray();

            if ($record->scope === 'DESA') {
                array_push($villages, $record);
            } else {
                array_push($institutions, $record);
            }
        }

        Cache::put('institutions', $institutions);

        foreach (FoundationWorkunit::whereNotNull('village_id')->get() as $workunit) {
            foreach ($villages as $position) {
                $model = new FoundationPosition();

                $model->name = $position->name;
                $model->slug = sha1(str($position->name . ' ' . $workunit->village_id)->slug());
                $model->posmap_id = $position->posmap_id;
                $model->village_id = $workunit->village_id;
                $model->workunit_id = $workunit->id;

                if ($position->parent_id) {
                    foreach ($villages as $position) {

                    }
                }

                switch ($position->posmap_id) {
                    case 7:
                        # code...
                        break;

                    case 19:
                        # code...
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        $this->command->getOutput()->progressFinish();
    }
}
