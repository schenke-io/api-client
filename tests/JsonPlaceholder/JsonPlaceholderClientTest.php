<?php

namespace SchenkeIo\ApiClient\Tests\JsonPlaceholder;

use PHPUnit\Framework\TestCase;
use SchenkeIo\ApiClient\Tests\JsonPlaceholder\JsonPlaceholderClient;

class JsonPlaceholderClientTest extends TestCase
{
    public function testJsonPlaceholderClientGet()
    {
        $client = new JsonPlaceholderClient('https://jsonplaceholder.typicode.com/');
        $result = $client->get('/posts/1');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);

    }

    public function testJsonPlaceholderClientPost()
    {
        $client = new JsonPlaceholderClient('https://jsonplaceholder.typicode.com/');
        $result = $client->post('/posts', [
            'title' => 'foo',
            'body' => 'bar',
            'userId' => 1,
        ]);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
    }
}
