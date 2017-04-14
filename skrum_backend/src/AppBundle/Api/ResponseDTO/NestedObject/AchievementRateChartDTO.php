<?php

namespace AppBundle\Api\ResponseDTO\NestedObject;

use JMS\Serializer\Annotation as JSON;

/**
 * 達成率チャートDTO

 * @JSON\ExclusionPolicy("none")
 */
class AchievementRateChartDTO
{
    /**
     * @var \DateTime
     *
     * @JSON\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $datetime;

    /**
     * @var string
     *
     * @JSON\Type("string")
     */
    private $achievementRate;

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return AchievementRateChartDTO
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set achievementRate
     *
     * @param string $achievementRate
     *
     * @return AchievementRateChartDTO
     */
    public function setAchievementRate($achievementRate)
    {
        $this->achievementRate = $achievementRate;

        return $this;
    }

    /**
     * Get achievementRate
     *
     * @return string
     */
    public function getAchievementRate()
    {
        return $this->achievementRate;
    }
}
