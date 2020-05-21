<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\Viaje;

class ViajeTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Viaje $viaje)
    {
        return $viaje->toArray();
    }
}
