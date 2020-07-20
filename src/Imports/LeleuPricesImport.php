<?php

namespace DFM\Shipping\Imports;

use DFM\Shipping\Models\LeleuPrice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Webkul\Core\Models\CountryState;

class LeleuPricesImport implements ToCollection
{
    /**
     * @var array
     */
    private $volumes = [];

    /**
     * @param  Collection  $rows
     */
    public function collection(Collection $rows)
    {
        LeleuPrice::truncate();

        foreach ($rows as $key => $row) {
            if ($row->filter()->isEmpty() || $row[0] == 'PAYS' || (blank($row[0]) && blank($row[1]))) {
                if (blank($row[0]) && blank($row[1])) {
                    for ($i = 2; $i < count($row); $i++) {
                        $this->volumes[$i] = (int) $row[$i];
                    }
                }

                continue;
            }

            foreach ($this->volumes as $key => $value) {
                $data = [];

                if (($countryCode = $row[0]) == 'F') {
                    $stateCode = ltrim($row[1], '0');

                    if ($state = CountryState::where([
                        ['country_code', 'FR'],
                        ['code', $stateCode]
                    ])->first()) {
                        $data = [
                            'weight'   => (int) $value,
                            'price'    => (double) $row[$key],
                            'state_id' => (int) $state->id,
                        ];
                    }
                } else {
                    $data = [
                        'weight'   => (int) $value,
                        'price'    => (double) $row[$key],
                        'state_id' => null,
                    ];
                }

                LeleuPrice::create($data);
            }
        }
    }
}
