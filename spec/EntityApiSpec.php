<?php

namespace spec\Tgallice\Wit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Tgallice\Wit\Api;
use Tgallice\Wit\Client;
use Tgallice\Wit\Model\EntityValue;

class EntityApiSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Tgallice\Wit\EntityApi');
    }

    function it_should_get_entities($client, ResponseInterface $response)
    {
        $body = '
            [
                "wit$amount_of_money",
                "wit$contact",
                "wit$datetime",
                "wit$on_off",
                "wit$phrase_to_translate",
                "wit$temperature"
            ]
        ';

        $expected = json_decode($body, true);
        $response->getBody()->willReturn($body);
        $client->get('/entities')->willReturn($response);

        $this->get()->shouldReturn($expected);
    }

    function it_should_create_entities($client, ResponseInterface $response, EntityValue $entityValue)
    {
        $body = '
            {
                "name" : "favorite_city",
                "lang" : "en",
                "lookups" : [ "keywords" ],
                "builtin" : false,
                "doc" : "A city that I like",
                "id" : "5418abc7-cc68-4073-ae9e-3a5c3c81d965"
            }
        ';

        $expected = json_decode($body, true);
        $response->getBody()->willReturn($body);
        $client->post('/entities', [
            'doc' => 'description',
            'id' => 'favorite_city',
            'values' => [$entityValue],
            'lookups' => 'lookups',
        ])->willReturn($response);

        $this->create('favorite_city', [$entityValue], 'description', 'lookups')->shouldReturn($expected);
    }

    function it_should_get_an_entity($client, ResponseInterface $response)
    {
        $body = '
            {
                "builtin" : false,
                "doc" : "User-defined entity",
                "id" : "571979db-f6ac-4820-bc28-a1e0787b98fc",
                "lang" : "en",
                "lookups" : [ "keywords", "free-text" ],
                "name" : "first_name",
                "values" : [
                    {"value" : "Willy",
                    "expressions" : [ "Willy" ]
                    }, {
                    "value" : "Laurent",
                    "expressions" : [ "Laurent" ]
                    }, {
                    "value" : "Julien",
                    "expressions" : [ "Julien" ]
                    }, {
                    "value" : "Alex",
                    "expressions" : [ "Alex" ]
                    }
                ]
            }
        ';

        $expected = json_decode($body, true);
        $response->getBody()->willReturn($body);
        $client->get('/entities/first_name')->willReturn($response);

        $this->get('first_name')->shouldReturn($expected);
    }

    function it_should_update_an_entity($client, ResponseInterface $response, EntityValue $entityValue)
    {
        $body = '
            {
                "builtin" : false,
                "doc" : "These are cities worth going to",
                "name" : "favorite_city",
                "id" : "5418abc7-cc68-4073-ae9e-3a5c3c81d965",
                "lang" : "en",
                "lookups" : [ "keywords" ]
            }
        ';

        $expected = json_decode($body, true);
        $response->getBody()->willReturn($body);
        $client->put('/entities/favorite_city', [
            'values' => [$entityValue],
            'doc' => 'description',
            'id' => 'id',
        ])->willReturn($response);

        $this->update('favorite_city', [$entityValue], 'description', 'id')->shouldReturn($expected);
    }

    function it_should_delete_an_entity($client, ResponseInterface $response)
    {
        $body = '
            {
                "deleted" : "5418abc7-cc68-4073-ae9e-3a5c3c81d965"
            }
        ';

        $expected = json_decode($body, true);
        $response->getBody()->willReturn($body);
        $client->delete('/entities/favorite_city')->willReturn($response);

        $this->delete('favorite_city')->shouldReturn($expected);
    }

    function it_should_add_a_value_to_an_entity($client, ResponseInterface $response, EntityValue $entityValue)
    {
        $body = '
            {
                "builtin" : false,
                "doc" : "These are cities worth going to",
                "exotic" : false,
                "id" : "57475251-ba5a-412b-85ec-3ab6f778d6fa",
                "lang" : "en",
                "lookups" : [ "keywords" ],
                "name" : "favorite_city"
            }
        ';

        $expected = json_decode($body, true);
        $response->getBody()->willReturn($body);
        $client->send('POST', '/entities/favorite_city/values', $entityValue)->willReturn($response);

        $this->addValue('favorite_city', $entityValue)->shouldReturn($expected);
    }

    function it_should_delete_a_value_to_an_entity($client, ResponseInterface $response)
    {
        $body = '';

        $expected = json_decode($body, true);
        $response->getBody()->willReturn($body);
        $client->delete('/entities/favorite_city/values/Paris')->willReturn($response);

        $this->deleteValue('favorite_city', 'Paris')->shouldReturn($expected);
    }

    function it_should_add_an_expression_to_an_entity_value($client, ResponseInterface $response)
    {
        $body = '
            {
                "builtin" : false,
                "doc" : "These are cities worth going to",
                "exotic" : false,
                "id" : "57475251-ba5a-412b-85ec-3ab6f778d6fa",
                "lang" : "en",
                "lookups" : [ "keywords" ],
                "name" : "favorite_city"
            }
        ';

        $expected = json_decode($body, true);
        $response->getBody()->willReturn($body);
        $client->post('/entities/favorite_city/values/Paris/expressions', [
            'expression' => 'Camembert city',
        ])->willReturn($response);

        $this->addExpression('favorite_city', 'Paris', 'Camembert city')->shouldReturn($expected);
    }

    function it_should_delete_an_expression_to_an_entity_value($client, ResponseInterface $response)
    {
        $body = '
            {
                "deleted" : "Camembert city"
            }
        ';

        $expected = json_decode($body, true);
        $response->getBody()->willReturn($body);
        $client->delete('/entities/favorite_city/values/Paris/expressions/Camembert city')->willReturn($response);

        $this->deleteExpression('favorite_city', 'Paris', 'Camembert city')->shouldReturn($expected);
    }
}
