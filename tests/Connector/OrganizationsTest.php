<?php declare(strict_types=1);

namespace Mautic\Transifex\Tests\Connector;

use Mautic\Transifex\Connector\Organizations;
use Mautic\Transifex\Tests\ApiConnectorTestCase;

/**
 * Test class for \Mautic\Transifex\Connector\Organizations.
 */
final class OrganizationsTest extends ApiConnectorTestCase
{
    /**
     * @testdox getOrganizations() returns a Response object indicating a successful API connection
     */
    public function testGetOrganizations(): void
    {
        $this->prepareSuccessTest();

        (new Organizations($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getOrganizations();

        $this->assertCorrectRequestAndResponse('/organizations/');

        $this->assertSame(
            'api.transifex.com',
            $this->client->getRequest()->getUri()->getHost(),
            'The API request did not use the new api subdomain.'
        );
    }

    /**
     * @testdox getFormats() returns a Response object indicating a failed API connection
     */
    public function testGetOrganizationsFailure(): void
    {
        $this->prepareFailureTest();

        (new Organizations($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getOrganizations();

        $this->assertCorrectRequestAndResponse('/organizations/', 'GET', 500);

        $this->assertSame(
            'api.transifex.com',
            $this->client->getRequest()->getUri()->getHost(),
            'The API request did not use the new api subdomain.'
        );
    }
}
