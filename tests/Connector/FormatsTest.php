<?php declare(strict_types=1);

namespace Mautic\Transifex\Tests\Connector;

use Mautic\Transifex\Connector\Formats;
use Mautic\Transifex\Tests\ApiConnectorTestCase;

/**
 * Test class for \Mautic\Transifex\Connector\Formats.
 */
final class FormatsTest extends ApiConnectorTestCase
{
    /**
     * @testdox getFormats() returns a Response object indicating a successful API connection
     */
    public function testGetFormats(): void
    {
        $this->prepareSuccessTest();

        (new Formats($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getFormats();

        $this->assertCorrectRequestAndResponse('/api/2/formats');
    }

    /**
     * @testdox getFormats() returns a Response object indicating a failed API connection
     */
    public function testGetFormatsFailure(): void
    {
        $this->prepareFailureTest();

        (new Formats($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getFormats();

        $this->assertCorrectRequestAndResponse('/api/2/formats', 'GET', 500);
    }
}
