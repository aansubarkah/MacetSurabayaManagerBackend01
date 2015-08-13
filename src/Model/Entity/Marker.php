<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Marker Entity.
 */
class Marker extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
