<?php

namespace SchenkeIo\ApiClient\Tests\Feature\Github;

#use SchenkeIo\ApiClient\Tests\GitHubClient;
use SchenkeIo\ApiClient\Tests\TestCase;

class ApiClientGithubTest extends TestCase
{
    public function testGithubClientGet()
    {
        $url = 'https://api.github.com/repos/schenke-io/api-client/contents/readme.md';
        $client = new GitHubClient($url);
        $this->assertIsArray($client->get());
    }
    //
}
