<?php

namespace ThePay\ApiClient\Model;

class PaymentIdentificator
{
    /** @var string  */
    private $uid;
    /** @var int */
    private $projectId;

    public function __construct(array $data)
    {
        $this->uid = $data['uid'];
        $this->projectId = $data['project_id'];
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'uid' => $this->uid,
            'projectId' => $this->projectId,
        );
    }
}
