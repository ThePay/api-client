<?php

namespace ThePay\ApiClient\Model;

final class Project
{
    /** @var int */
    private $projectId;
    /** @var string */
    private $projectUrl;
    /** @var string */
    private $accountIban;

    /**
     * @param int $projectId
     * @param string $projectUrl
     * @param string $accountIban
     */
    public function __construct($projectId, $projectUrl, $accountIban)
    {
        $this->projectId = $projectId;
        $this->projectUrl = $projectUrl;
        $this->accountIban = $accountIban;
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @return string
     */
    public function getProjectUrl()
    {
        return $this->projectUrl;
    }

    /**
     * @return string
     */
    public function getAccountIban()
    {
        return $this->accountIban;
    }
}
