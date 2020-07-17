<?php

namespace DFM\Shipping\Imports;

use DFM\Shipping\Models\CoupePrice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Webkul\Core\Models\CountryState;

/**
 * Class CoupePricesImport
 *
 * @package DFM\Shipping
 */
class CoupePricesImport implements ToCollection
{
    /**
     * @var array
     */
    private $weights = [];

    /**
     * @param  Collection  $rows
     */
    public function collection(Collection $rows)
    {
        CoupePrice::truncate();

        foreach ($rows as $key => $row) {
            if ($row->filter()->isEmpty() || $row[0] == 'DPT LOCALITE' || blank($row[0])) {
                if (blank($row[0])) {
                    for ($i = 1; $i < count($row); $i++) {
                        $this->weights[$i] = $row[$i];
                    }
                }

                continue;
            }

            foreach ($this->weights as $key => $value) {
                $stateCode = ltrim(explode(' ', $row[0], 2)[0], '0');
                $state = CountryState::where([
                    ['country_code', 'FR'],
                    ['code', $stateCode],
                ])->first();

                CoupePrice::create([
                    'weight'   => (int) $value,
                    'price'    => (double) $row[$key],
                    'state_id' => (int) $state->id,
                ]);
            }
        }
    }
}
