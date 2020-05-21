<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\User;
use App\CompraBoleto;

class UserTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'viajesAnteriores'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $usuario)
    {
        return $usuario->toArray();
    }
}
