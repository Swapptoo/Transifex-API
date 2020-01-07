<?php declare(strict_types=1);

namespace Mautic\Transifex\Tests\Connector;

use Mautic\Transifex\Connector\Resources;
use Mautic\Transifex\Exception\InvalidConfigurationException;
use Mautic\Transifex\Exception\InvalidFileTypeException;
use Mautic\Transifex\Exception\MissingFileException;
use Mautic\Transifex\Tests\ApiConnectorTestCase;

/**
 * Test class for \Mautic\Transifex\Connector\Resources.
 */
final class ResourcesTest extends ApiConnectorTestCase
{
    /**
     * @testdox createResource() with inline content provided in the options returns a Response object indicating a successful API connection
     */
    public function testCreateResourceContent(): void
    {
        $this->prepareSuccessTest(201);

        // Additional options
        $options = [
            'accept_translations' => true,
            'category'            => 'whatever',
            'priority'            => 3,
            'content'             => 'Test="Test"',
        ];

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->createResource(
            'mautic-transifex',
            'Mautic Transifex Data',
            'mautic-transifex',
            'INI',
            $options
        );

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic-transifex/resources/', 'POST', 201);
    }

    /**
     * @testdox createResource() with an attached file in the options returns a Response object indicating a successful API connection
     */
    public function testCreateResourceFile(): void
    {
        $this->prepareSuccessTest(201);

        // Additional options
        $options = [
            'accept_translations' => true,
            'category'            => 'whatever',
            'priority'            => 3,
            'file'                => __DIR__ . '/../stubs/source.ini',
        ];

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->createResource(
            'mautic-transifex',
            'Mautic Transifex Data',
            'mautic-transifex',
            'INI',
            $options
        );

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic-transifex/resources/', 'POST', 201);
    }

    /**
     * @testdox createResource() with an attached file in the options that does not exist throws an MissingFileException
     */
    public function testCreateResourceFileDoesNotExist(): void
    {
        $this->expectException(MissingFileException::class);

        // Additional options
        $options = [
            'accept_translations' => true,
            'category'            => 'whatever',
            'priority'            => 3,
            'file'                => __DIR__ . '/stubs/does-not-exist.ini',
        ];

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->createResource(
            'mautic-transifex',
            'Mautic Transifex Data',
            'mautic-transifex',
            'INI',
            $options
        );
    }

    /**
     * @testdox createResource() returns a Response object indicating a failed API connection
     */
    public function testCreateResourceFailure(): void
    {
        $this->prepareFailureTest();

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->createResource(
            'mautic-transifex',
            'Mautic Transifex Data',
            'mautic-transifex',
            'INI',
            ['content' => 'Test="Test"']
        );

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic-transifex/resources/', 'POST', 500);
    }

    /**
     * @testdox deleteResource() returns a Response object indicating a successful API connection
     */
    public function testDeleteResource(): void
    {
        $this->prepareSuccessTest(204);

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->deleteResource('mautic', 'mautic-transifex');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex', 'DELETE', 204);
    }

    /**
     * @testdox deleteResource() returns a Response object indicating a failed API connection
     */
    public function testDeleteResourceFailure(): void
    {
        $this->prepareFailureTest();

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->deleteResource('mautic', 'mautic-transifex');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex', 'DELETE', 500);
    }

    /**
     * @testdox getResource() returns a Response object indicating a successful API connection
     */
    public function testGetResource(): void
    {
        $this->prepareSuccessTest();

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getResource('mautic', 'mautic-transifex', true);

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/');

        $this->assertSame(
            'details',
            $this->client->getRequest()->getUri()->getQuery(),
            'The API request did not include the expected query string.'
        );
    }

    /**
     * @testdox getResource() returns a Response object indicating a failed API connection
     */
    public function testGetResourceFailure(): void
    {
        $this->prepareFailureTest();

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getResource('mautic', 'mautic-transifex', true);

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/', 'GET', 500);
    }

    /**
     * @testdox getResourceContent() returns a Response object indicating a successful API connection
     */
    public function testGetResourceContent(): void
    {
        $this->prepareSuccessTest();

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getResourceContent('mautic', 'mautic-transifex');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/content/');
    }

    /**
     * @testdox getResourceContent() returns a Response object indicating a failed API connection
     */
    public function testGetResourceContentFailure(): void
    {
        $this->prepareFailureTest();

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getResourceContent('mautic', 'mautic-transifex');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/content/', 'GET', 500);
    }

    /**
     * @testdox getResources() returns a Response object indicating a successful API connection
     */
    public function testGetResources(): void
    {
        $this->prepareSuccessTest();

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getResources('mautic');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resources');
    }

    /**
     * @testdox getResources() returns a Response object indicating a failed API connection
     */
    public function testGetResourcesFailure(): void
    {
        $this->prepareFailureTest();

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getResources('mautic');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resources', 'GET', 500);
    }

    /**
     * @testdox updateResourceContent() with an attached file returns a Response object indicating a successful API connection
     */
    public function testUpdateResourceContentFile(): void
    {
        $this->prepareSuccessTest();

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->updateResourceContent(
            'mautic',
            'mautic-transifex',
            __DIR__ . '/../stubs/source.ini',
            'file'
        );

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/content/', 'PUT');
    }

    /**
     * @testdox updateResourceContent() with inline content returns a Response object indicating a successful API connection
     */
    public function testUpdateResourceContentString(): void
    {
        $this->prepareSuccessTest();

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->updateResourceContent(
            'mautic',
            'mautic-transifex',
            'TEST="Test"'
        );

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/content/', 'PUT');
    }

    /**
     * @testdox updateResourceContent() returns a Response object indicating a failed API connection
     */
    public function testUpdateResourceContentFailure(): void
    {
        $this->prepareFailureTest();

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->updateResourceContent('mautic', 'mautic-transifex', 'TEST="Test"');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/content/', 'PUT', 500);
    }

    /**
     * @testdox updateResourceContent() throws an InvalidConfigurationException when an invalid content type is specified
     */
    public function testUpdateResourceContentBadType(): void
    {
        $this->expectException(InvalidFileTypeException::class);

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->updateResourceContent(
            'mautic',
            'mautic-transifex',
            'TEST="Test"',
            'stuff'
        );
    }

    /**
     * @testdox updateResourceContent() throws an MissingFileException when a non-existing file is specified
     */
    public function testUpdateResourceContentUnexistingFile(): void
    {
        $this->expectException(MissingFileException::class);

        (new Resources($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->updateResourceContent(
            'mautic',
            'mautic-transifex',
            __DIR__ . '/stubs/does-not-exist.ini',
            'file'
        );
    }
}
