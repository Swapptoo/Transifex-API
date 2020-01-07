<?php declare(strict_types=1);

namespace Mautic\Transifex\Tests;

use Mautic\Transifex\ApiFactory;
use Mautic\Transifex\Connector\Formats;
use Mautic\Transifex\Exception\UnknownApiConnectorException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * Test class for \Mautic\Transifex\ApiFactory.
 */
final class ApiFactoryTest extends TestCase
{
    /**
     * @var MockObject|ClientInterface
     */
    private $client;

    /**
     * @var MockObject|RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var MockObject|StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @var MockObject|UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var array
     */
    private $options;

    /**
     * @var ApiFactory
     */
    private $object;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->client         = $this->createMock(ClientInterface::class);
        $this->requestFactory = $this->createMock(RequestFactoryInterface::class);
        $this->streamFactory  = $this->createMock(StreamFactoryInterface::class);
        $this->uriFactory     = $this->createMock(UriFactoryInterface::class);
        $this->options        = ['api.username' => 'test', 'api.password' => 'test'];
        $this->object         = new ApiFactory($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory);
    }

    /**
     * @testdox get() throws an UnknownApiConnectorException for a non-existing object
     */
    public function testGetFake(): void
    {
        $this->expectException(UnknownApiConnectorException::class);

        $this->object->createApiConnector('fake');
    }

    /**
     * @testdox get() with the "formats" parameter returns an instance of the Formats object
     */
    public function testGetFormats(): void
    {
        $this->assertInstanceOf(
            Formats::class,
            $this->object->createApiConnector('formats')
        );
    }
}
