<?php

namespace ddroche\shasta\resources;

use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\httpclient\Response;

class Project extends ShastaResource
{
    public function getResource()
    {
        return 'project';
    }

    /**
     * Get Project
     *
     * @return Response
     *
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function getProject()
    {
        $response = $this->getRequest()
            ->setMethod('GET')
            ->setUrl("$this->resource")
            ->send();

        return $response;
    }

    /**
     * Edit Project
     *
     * @param array $body
     *
     * @return Response
     *
     * @throws InvalidConfigException
     */
    public function setProject($body)
    {
        $response = $this->getRequest()
            ->setMethod('PATCH')
            ->setUrl("$this->resource")
            ->setData($body)
            ->send();

        return $response;
    }
}
