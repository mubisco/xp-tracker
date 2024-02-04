<?php

declare(strict_types=1);

namespace XpTracker\Tests\Acceptance;

use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
final class DemoContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    /** @var Response|null */
    private $response;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When a demo scenario sends a request to :path
     */
    public function aDemoScenarioSendsARequestTo(string $path): void
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived(): void
    {
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }
    }

     /**
     * @Then a :expectedStatusCode status code should be received
     */
    public function aStatusCodeShouldBeReceived(int $expectedStatusCode): void
    {
        $statusCode = $this->response?->getStatusCode();
        Assert::assertEquals($expectedStatusCode, $statusCode);
    }

    /**
     * @When a post request is sent to :url with data
     */
    public function aPostRequestIsSentToWithData(string $url, TableNode $table): void
    {
        $dataArray = $table->getRowsHash();
        $content = json_encode($dataArray, JSON_THROW_ON_ERROR);
        $this->response = $this->kernel->handle(
            Request::create($url, 'POST', [], [], [], [], $content)
        );
    }
}
