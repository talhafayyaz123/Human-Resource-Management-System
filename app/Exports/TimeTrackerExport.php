<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class TimeTrackerExport implements WithMultipleSheets
{
    protected $selected_dates;
    protected $time_tracker_data;

    public function __construct($selected_dates, $time_tracker_data)
    {
        $this->selected_dates = $selected_dates;
        $this->time_tracker_data = $time_tracker_data;
    }

    public function sheets(): array
    {
        $sheets = [];

        // First sheet
        $sheets[] = new class($this->selected_dates, $this->time_tracker_data) implements FromCollection, WithHeadings, WithTitle
        {
            protected $selected_dates;
            protected $time_tracker_data;

            public function __construct($selected_dates, $time_tracker_data)
            {
                $this->selected_dates = $selected_dates;
                $this->time_tracker_data = $time_tracker_data;
            }

            /**
             * @return string
             */
            public function title(): string
            {
                return 'Summary Data';
            }

            public function headings(): array
            {
                return [
                    'Date',
                    'Start Time',
                    'End Time',
                    'Break Hours',
                    'Worked Hours',
                    'Hours Difference'
                ];
            }

            public function collection()
            {
                $collection_data = [];
                foreach ($this->selected_dates as $selected_date) {
                    $date = $selected_date['date'];
                    $data = $this->time_tracker_data->where('date', $date);

                    if ($data->isEmpty()) {
                        $collection_data[] = [
                            $date,
                            '',
                            '',
                            '',
                            '',
                            -$selected_date['expectedHours'],
                        ];
                        continue;
                    }

                    $break_hours = $data->where('type', 'break')->sum(function ($item) {
                        if (isset($item->start_time) && isset($item->end_time)) {
                            $end_time = Carbon::parse($item->end_time);
                            $start_time = Carbon::parse($item->start_time);
                            return $end_time->diffInSeconds($start_time);
                        }
                    }) / 3600 ?? '0';


                    $worked_hours = $data->whereNotIn("type", ["break", "take-from-overdue"])->sum(function ($item) {
                        if (isset($item->start_time) && isset($item->end_time)) {
                            $end_time = Carbon::parse($item->end_time);
                            $start_time = Carbon::parse($item->start_time);
                            return $end_time->diffInSeconds($start_time);
                        }
                    }) / 3600 ?? '0';

                    $min_start_time = min($data->pluck('start_time')->toArray());
                    $max_end_time = max($data->pluck('end_time')->toArray());

                    $collection_data[] = [
                        $date,
                        $min_start_time,
                        $max_end_time,
                        $break_hours,
                        $worked_hours,
                        $worked_hours - $selected_date['expectedHours'],
                    ];
                }

                return collect($collection_data);
            }
        };

        // Second sheet
        $sheets[] = new class($this->time_tracker_data) implements FromCollection, WithHeadings, WithTitle
        {
            protected $time_tracker_data;

            public function __construct($time_tracker_data)
            {
                $this->time_tracker_data = $time_tracker_data;
            }

            /**
             * @return string
             */
            public function title(): string
            {
                return 'Detailed Information';
            }

            public function headings(): array
            {
                return [
                    'Date',
                    'Start Time',
                    'End Time',
                    'Type',
                    'Description',
                    'Hours Difference'
                ];
            }

            public function collection()
            {
                $collection = [];
                foreach ($this->time_tracker_data as $item) {
                    if (empty($item)) {
                        continue;
                    }

                    $end_time = Carbon::parse($item->end_time);
                    $start_time = Carbon::parse($item->start_time);
                    $collection[] = [
                        $item->date,
                        $item->start_time,
                        $item->end_time,
                        $item->type,
                        $item->description,
                        $end_time->diffInSeconds($start_time) / 3600,
                    ];
                }

                return collect($collection);
            }
        };

        return $sheets;
    }
}
