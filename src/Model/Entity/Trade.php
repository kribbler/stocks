<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Trade Entity
 *
 * @property int $id
 * @property int $stock_id
 * @property \Cake\I18n\Time $scrap_date
 * @property \Cake\I18n\Time $time
 * @property float $price
 * @property int $quantity
 * @property string $source
 * @property string $buyer
 * @property string $seller
 * @property string $initiator
 *
 * @property \App\Model\Entity\Stock $stock
 */
class Trade extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
