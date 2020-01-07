<?php declare(strict_types=1);

namespace Mautic\Transifex\Tests\Connector;

use Mautic\Transifex\Connector\Statistics;
use Mautic\Transifex\Tests\ApiConnectorTestCase;

/**
 * Test class for \Mautic\Transifex\Connector\Statistics.
 */
final class StatisticsTest extends ApiConnectorTestCase
{
    /**
     * @testdox getStatistics() returns a Response object indicating a successful API connection
     */
    public function testGetStatistics(): void
    {
        $this->prepareSuccessTest();

        (new Statistics($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getStatistics('mautic', 'mautic-transifex');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/stats/');
    }

    /**
     * @testdox getStatistics() returns a Response object indicating a failed API connection
     */
    public function testGetStatisticsFailure(): void
    {
        $this->prepareFailureTest();

        (new Statistics($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getStatistics('mautic', 'mautic-transifex');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/stats/', 'GET', 500);
    }
}
