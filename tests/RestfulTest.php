<?php
namespace MoeFront\RestfulTests;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\TestCase;

class RestfulTest extends TestCase
{
    private static $client;

    public static function setUpBeforeClass()
    {
        self::$client = new Client([
            'base_uri' => 'http://' . getenv('WEB_SERVER_HOST') . ':' . getenv('WEB_SERVER_PORT'),
            'http_errors' => false,
        ]);
    }

    public function testPosts()
    {
        $response = self::$client->get('/api/posts');
        $result = json_decode($response->getBody(), true);

        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('page', $result['data']);
        $this->assertArrayHasKey('pages', $result['data']);
        $this->assertArrayHasKey('count', $result['data']);
        $this->assertArrayHasKey('pageSize', $result['data']);
        $this->assertArrayHasKey('dataSet', $result['data']);
    }

    public function testPages()
    {
        $response = self::$client->get('/api/pages');
        $result = json_decode($response->getBody(), true);

        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('count', $result['data']);
        $this->assertArrayHasKey('dataSet', $result['data']);
    }

    public function testCategories()
    {
        $response = self::$client->get('/api/categories');
        $result = json_decode($response->getBody(), true);

        $this->assertEquals('success', $result['status']);
    }

    public function testTags()
    {
        $response = self::$client->get('/api/tags');
        $result = json_decode($response->getBody(), true);

        $this->assertEquals('success', $result['status']);
    }

    public function testPost()
    {
        $response = self::$client->get('/api/post', ['query' => ['cid' => 1]]);
        $result = json_decode($response->getBody(), true);

        $this->assertEquals('success', $result['status']);
        $this->assertTrue(is_array($result['data']));
    }

    public function testComments()
    {
        $response = self::$client->get('/api/comments', ['query' => ['cid' => 1]]);
        $result = json_decode($response->getBody(), true);

        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('page', $result['data']);
        $this->assertArrayHasKey('pages', $result['data']);
        $this->assertArrayHasKey('count', $result['data']);
        $this->assertArrayHasKey('pageSize', $result['data']);
        $this->assertArrayHasKey('dataSet', $result['data']);
    }

    public function testComment()
    {
        // without token
        $response = self::$client->post('/api/comment', [
            RequestOptions::JSON => [
                'cid' => 1,
                'text' => '233',
                'author' => 'test',
                'mail' => 'test@qq.com',
            ],
        ]);
        $result = json_decode($response->getBody(), true);
        $this->assertEquals('error', $result['status']);

        // with token and invalid form value
        // TODO local server would down

        // $response = self::$client->get('/api/post', ['query' => ['cid' => 1]]);
        // $result = json_decode($response->getBody(), true);
        // $response = self::$client->post('/api/comment', [
        //     RequestOptions::JSON => [
        //         'cid' => 1,
        //         'text' => '233',
        //         'author' => 'test',
        //         'mail' => 'testqq.com',
        //         'token' => $result['data']['csrfToken'],
        //     ],
        // ]);
        // $result = json_decode($response->getBody(), true);
        // $this->assertEquals('error', $result['status']);

    }

    public function testSettings()
    {
        $response = self::$client->get('/api/settings');
        $result = json_decode($response->getBody(), true);

        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('title', $result['data']);
        $this->assertArrayHasKey('description', $result['data']);
        $this->assertArrayHasKey('keywords', $result['data']);
        $this->assertArrayHasKey('timezone', $result['data']);
    }
}
