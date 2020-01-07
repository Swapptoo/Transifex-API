<?php declare(strict_types=1);

namespace Mautic\Transifex\Tests;

use Mautic\Transifex\ApiConnector;
use Mautic\Transifex\FactoryInterface;
use Mautic\Transifex\Transifex;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Test class for \Mautic\Transifex\Transifex.
 */
final class TransifexTest extends TestCase
{
    /**
     * @var MockObject|FactoryInterface
     */
    private $apiFactory;

    /**
     * @var array
     */
    private $options;

    /**
     * @var Transifex
     */
    private $object;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->apiFactory = $this->createMock(FactoryInterface::class);
        $this->options    = ['api.username' => 'test', 'api.password' => 'test'];
        $this->object     = new Transifex($this->apiFactory, $this->options);
    }

    /**
     * @testdox get() returns an API connector
     */
    public function testGetFormats()
    {
        $this->apiFactory->expects($this->once())
            ->method('createApiConnector')
            ->willReturn($this->createMock(ApiConnector::class));

        $this->assertInstanceOf(
            ApiConnector::class,
            $this->object->get('formats')
        );
    }

    /**
     * @testdox getOption() and setOption() correctly manage the object's options
     */
    public function testSetAndGetOption()
    {
        $this->object->setOption('api.url', 'https://example.com/test');

        $this->assertSame(
            $this->object->getOption('api.url'),
            'https://example.com/test'
        );
    }
}
