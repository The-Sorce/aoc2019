<?php
declare(strict_types=1);

namespace App\AocTasks\Year2023;

use App\AocTasks\AocTask;
use App\AocTasks\HelperClasses\Grid;
use App\AocTasks\HelperClasses\MultiarrayFunctions;

class Day3Part2 extends AocTask
{
    protected $dayName = 'Gear Ratios';

    public function run(): AocTask
    {
        $input = $this->getInput();

        $input_array = MultiarrayFunctions::text_to_multiarray($input);

        $grid = new Grid();
        $grid->setGrid($input_array);

        $gear_candidates = [];
        foreach ($grid->getGrid() as $y => $row) {
            $line = implode($row);
            preg_match_all('/(\d+)/', $line, $matches, PREG_OFFSET_CAPTURE);
            foreach ($matches[1] as $match) {
                $part_number = $match[0];
                $start_x = $match[1];
                $end_x = $start_x + strlen($part_number) - 1;
                echo "Number found: {$part_number} at x={$start_x}-{$end_x},y={$y}\n";

                $adjacent_cells = [];
                for ($x = $start_x; $x <= $end_x; $x++) {
                    $grid->setPos($x, $y);
                    $adjacent_cells = array_merge($adjacent_cells, $grid->findInAdjacentCells('*', true));
                }

                $adjacent_cells = array_unique($adjacent_cells, SORT_REGULAR);

                foreach ($adjacent_cells as $adjacent_cell) {
                    $gear_x = $adjacent_cell['x'];
                    $gear_y = $adjacent_cell['y'];
                    $gear_candidates_key = "{$gear_x},{$gear_y}";
                    if (!isset($gear_candidates[$gear_candidates_key])) {
                        $gear_candidates[$gear_candidates_key] = [];
                    }
                    $gear_candidates[$gear_candidates_key][] = $part_number;
                    echo "Marking part number {$part_number} for gear candidate at {$gear_candidates_key}\n";
                }
            }
        }

        $sum = 0;
        foreach ($gear_candidates as $gear_key => $numbers) {
            if (count($numbers) == 2) {
                $sum += array_product($numbers);
            }
        }
        echo "\n";

        $this->setResultDescription('Sum of gear ratios numbers');
        $this->setResult((string)$sum);

        return $this;
    }
}