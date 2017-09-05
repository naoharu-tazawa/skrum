<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SGroupTreePathId
 *
 * @ORM\Table(name="s_group_tree_path_id")
 * @ORM\Entity
 */
class SGroupTreePathId
{
    /**
     * @var integer
     *
     * @ORM\Column(name="current_value", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $currentValue;



    /**
     * Get currentValue
     *
     * @return integer
     */
    public function getCurrentValue()
    {
        return $this->currentValue;
    }
}
