Wit.ai PHP sdk
==============

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tgallice/wit-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tgallice/wit-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/tgallice/wit-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tgallice/wit-php/?branch=master)
[![Build Status](https://travis-ci.org/tgallice/wit-php.svg?branch=master)](https://travis-ci.org/tgallice/wit-php)

This is an unofficial php sdk for [Wit.ai][1] and it's still in progress...

```
Wit.ai: Easily create text or voice based bots that humans can chat with on their preferred messaging platform.
```

## Install:

Via composer:

```
$ composer require tgallice/wit-php
```

## Usage:

Using the low level `Client`:

```php

require_once __DIR__.'/vendor/autoload.php';

use Tgallice\Wit\Client;

$client = new Client('app_token');

$response = $client->get('/message', [
    'q' => 'Hello I live in London',
]);

// Get the decoded body
$intent = json_decode((string) $response->getBody(), true);

```

You can used the `Message` api class to extract meaning of a sentence:

```php

require_once __DIR__.'/vendor/autoload.php';

use Tgallice\Wit\Client;
use Tgallice\Wit\MessageApi;

$client = new Client('app_token');
$api = new MessageApi($client);

$meaning = $api->extractMeaning('Hello I live in London');

```

## Conversation

The `Conversation` class provides an easy way to use the `converse` api and execute automatically the chaining steps :

First, you need to create an `ActionMapping` class to customize the actions behavior.

```php

namespace Custom;

class MyActionMapping extends ActionMapping
{
    /**
     * @inheritdoc
     */
    public function action($sessionId, $actionName, Context $context)
    {
        return call_user_func_array(array($this, $actionName), array($sessionId, $context));
    }

    /**
     * @inheritdoc
     */
    public function say($sessionId, $message, Context $context)
    {
        echo $message;
    }

     ....
}

```

And using it in the `Conversation` class. 

```php

require_once __DIR__.'/vendor/autoload.php';

use Tgallice\Wit\Client;
use Tgallice\Wit\ConverseApi;
use Tgallice\Wit\Conversation;
use Custom\MyActionMapping;

$client = new Client('app_token');
$api = new ConverseApi($client);
$actionMapping = new MyActionMapping();
$conversation = new Conversation($api, $actionMapping);

$context = $conversation->converse('session_id', 'Hello I live in London');

```

`Conversation::converse()` return the last available `Context`.

Some examples are describe in the [tgallice/php-wit-example][2] repository.

[1]: https://wit.ai
[2]: https://github.com/tgallice/wit-php-example
