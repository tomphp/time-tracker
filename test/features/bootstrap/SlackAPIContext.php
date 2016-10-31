<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Fig\Http\Message\StatusCodeInterface as HttpStatus;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class SlackAPIContext implements Context, SnippetAcceptingContext
{
    const SLACK_ENDPOINT = '/slack/slash-command-endpoint';
    const REST_ENDPOINT  = '/api/v1';

    /** Client */
    private $client;

    /** @var ResponseInterface */
    private $response;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri'        => getenv('SITE_URL'),
            'allow_redirects' => false,
        ]);
    }

    /**
     * @When Slack sends a command with an invalid token
     */
    public function issueSlackCommandWithInvalidToken()
    {
        try {
            $this->response = $this->client->post(
                self::SLACK_ENDPOINT,
                [
                    'form_params' => [
                        'token'        => 'THIS TOKEN IS INVALID',
                        'team_id'      => 'T0001',
                        'team_domain'  => 'example',
                        'channel_id'   => 'C2147483705',
                        'channel_name' => 'test',
                        'user_id'      => 'U9999999999',
                        'user_name'    => 'example_user',
                        'command'      => '/tt',
                        'text'         => 'some command',
                        'response_url' => 'https://hooks.slack.com/commands/1234/5678',
                    ],
                ]
            );
        } catch (ClientException $e) {
            $this->response = $e->getResponse();
        }
    }

    /**
     * @Then A forbidden response should be returned
     */
    public function assertResponseIsForbidden()
    {
        assertSame(HttpStatus::STATUS_FORBIDDEN, $this->response->getStatusCode());
    }
}
