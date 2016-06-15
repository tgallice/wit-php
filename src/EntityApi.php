<?php

namespace Tgallice\Wit;

use Tgallice\Wit\Model\EntityValue;
use Tgallice\Wit\Model\Entity;

class EntityApi
{
    use ResponseHandler;

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param null|string $entityId
     *
     * @return mixed
     */
    public function get($entityId = null)
    {
        if (null !== $entityId) {
            $entityId = '/'.$entityId;
        }


        $response = $this->client->get(sprintf('/entities%s', $entityId));

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     * @param EntityValue[] $entityValues
     * @param null|string $description
     * @param null|string $newId
     *
     * @return mixed
     */
    public function update($entityId, array $entityValues = [], $description = null, $newId = null)
    {
        $data = [];

        if (!empty($entityValues)) {
            $data['values'] = $entityValues;
        }

        if (!empty($description)) {
            $data['doc'] = $description;
        }

        if (!empty($newId)) {
            $data['id'] = $newId;
        }

        $response = $this->client->put(sprintf('/entities/%s', $entityId), $data);

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     *
     * @return mixed
     */
    public function delete($entityId)
    {
        $response = $this->client->delete(sprintf('/entities/%s', $entityId));

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     * @param string $entityValue
     * @return mixed
     */
    public function deleteValue($entityId, $entityValue)
    {
        $response = $this->client->delete(sprintf('/entities/%s/values/%s', $entityId, $entityValue));

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     * @param EntityValue $entityValue
     *
     * @return mixed
     */
    public function addValue($entityId, EntityValue $entityValue)
    {
        $response = $this->client->send('POST', sprintf('/entities/%s/values', $entityId), $entityValue);

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     * @param string $entityValue
     * @param string $expression
     *
     * @return mixed
     */
    public function addExpression($entityId, $entityValue, $expression)
    {
        $response = $this->client->post(sprintf('/entities/%s/values/%s/expressions', $entityId, $entityValue), [
            'expression' => $expression,
        ]);

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     * @param string $entityValue
     * @param string $expression
     *
     * @return mixed
     */
    public function deleteExpression($entityId, $entityValue, $expression)
    {
        $response = $this->client->delete(sprintf('/entities/%s/values/%s/expressions/%s', $entityId, $entityValue, $expression));

        return $this->decodeResponse($response);
    }

    /**
     * @param string $entityId
     * @param EntityValue[] $entityValues
     * @param null|string $description
     * @param array $lookups
     *
     * @return mixed
     */
    public function create($entityId, array $entityValues = [], $description = null, $lookups = [Entity::LOOKUP_KEYWORDS])
    {
        $data = [
            'id' => $entityId,
            'values' => $entityValues,
            'doc' => $description,
            'lookups' => $lookups,
        ];

        $response = $this->client->post('/entities', $data);

        return $this->decodeResponse($response);
    }
}
