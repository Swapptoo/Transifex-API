<?php declare(strict_types=1);

namespace Mautic\Transifex\Tests\Connector;

use Mautic\Transifex\Connector\Projects;
use Mautic\Transifex\Exception\InvalidConfigurationException;
use Mautic\Transifex\Tests\ApiConnectorTestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Test class for \Mautic\Transifex\Connector\Projects.
 */
final class ProjectsTest extends ApiConnectorTestCase
{
    /**
     * @testdox createProject() returns a Response object indicating a successful API connection
     */
    public function testCreateProject(): void
    {
        $this->prepareSuccessTest(201);

        // Additional options
        $options = [
            'long_description'   => 'My test project',
            'private'            => true,
            'homepage'           => 'http://www.example.com',
            'trans_instructions' => 'http://www.example.com/instructions.html',
            'tags'               => 'joomla, mautic',
            'maintainers'        => 'joomla',
            'team'               => 'translators',
            'auto_join'          => true,
            'license'            => 'other_open_source',
            'fill_up_resources'  => false,
            'repository_url'     => 'http://www.example.com',
            'organization'       => 'mautic',
            'archived'           => false,
            'type'               => 1,
        ];

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->createProject(
            'Mautic Transifex',
            'mautic-transifex',
            'Test Project',
            'en_US',
            $options
        );

        $this->assertCorrectRequestAndResponse('/api/2/projects/', 'POST', 201);
    }

    /**
     * @testdox createProject() returns a Response object indicating a failed API connection
     */
    public function testCreateProjectFailureForABadRequest(): void
    {
        $this->prepareFailureTest();

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->createProject(
            'Mautic Transifex',
            'mautic-transifex',
            'Test Project',
            'en_US',
            ['repository_url' => 'https://www.mautic.com']
        );

        $this->assertCorrectRequestAndResponse('/api/2/projects/', 'POST', 500);
    }

    /**
     * @testdox createProject() throws an InvalidConfigurationException when an invalid license is specified
     */
    public function testCreateProjectsBadLicense(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->createProject(
            'Mautic Transifex',
            'mautic-transifex',
            'Test Project',
            'en_US',
            ['license' => 'failure']
        );
    }

    /**
     * @testdox createProject() throws an InvalidConfigurationException when required fields are missing
     */
    public function testCreateProjectFailureForMissingFields(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->createProject(
            'Mautic Transifex',
            'mautic-transifex',
            'Test Project',
            'en_US'
        );
    }

    /**
     * @testdox deleteProject() returns a Response object indicating a successful API connection
     */
    public function testDeleteProject(): void
    {
        $this->prepareSuccessTest(204);

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->deleteProject('mautic-transifex');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic-transifex', 'DELETE', 204);
    }

    /**
     * @testdox deleteProject() returns a Response object indicating a failed API connection
     */
    public function testDeleteProjectFailure(): void
    {
        $this->prepareFailureTest();

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->deleteProject('mautic-transifex');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic-transifex', 'DELETE', 500);
    }

    /**
     * @testdox getOrganizationProjects() returns a Response object indicating a successful API connection
     */
    public function testGetOrganizationProjects(): void
    {
        $this->prepareSuccessTest();

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getOrganizationProjects('mautic');

        $this->assertCorrectRequestAndResponse('/organizations/mautic/projects/');

        $this->assertSame(
            'api.transifex.com',
            $this->client->getRequest()->getUri()->getHost(),
            'The API request did not use the new api subdomain.'
        );
    }

    /**
     * @testdox Calling any method after getOrganizationProjects() calls the correct API endpoint
     */
    public function testGetOrganizationProjectsThenGetProjects(): void
    {
        $this->prepareSuccessTest();

        $projects = new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options);

        $projects->getOrganizationProjects('mautic');

        $this->assertSame(
            'api.transifex.com',
            $this->client->getRequest()->getUri()->getHost(),
            'The API request did not use the new api subdomain.'
        );

        $this->prepareSuccessTest();

        $projects->getProjects();

        $this->assertSame(
            'www.transifex.com',
            $this->client->getRequest()->getUri()->getHost(),
            'The API request did not switch back to the www subdomain.'
        );
    }

    /**
     * @testdox getOrganizationProjects() returns a Response object indicating a failed API connection
     */
    public function testGetOrganizationProjectsFailure(): void
    {
        $this->prepareFailureTest();

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getOrganizationProjects('mautic');

        $this->assertCorrectRequestAndResponse('/organizations/mautic/projects/', 'GET', 500);

        $this->assertSame(
            'api.transifex.com',
            $this->client->getRequest()->getUri()->getHost(),
            'The API request did not use the new api subdomain.'
        );
    }

    /**
     * @testdox The API URI is reset when an Exception is thrown by getOrganizationProjects()
     */
    public function testGetOrganizationProjectsResetsApiUriOnException(): void
    {
        $this->client = new class() implements ClientInterface {
            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                throw new class('Testing') extends \RuntimeException implements ClientExceptionInterface {
                };
            }
        };

        $projects = new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options);

        try {
            $projects->getOrganizationProjects('mautic');

            $this->fail(\sprintf('A %s should be thrown.', ClientExceptionInterface::class));
        } catch (ClientExceptionInterface $exception) {
            // I don't think the options should be a public thing on connectors so use Reflection to get into them for this assertion
            $reflection = new \ReflectionClass($projects);

            $optionsProperty = $reflection->getProperty('options');
            $optionsProperty->setAccessible(true);

            $options = $optionsProperty->getValue($projects);

            $this->assertSame(
                'https://www.transifex.com',
                $options['base_uri'],
                'The API request did not switch back to the www subdomain.'
            );
        }
    }

    /**
     * @testdox getProject() returns a Response object indicating a successful API connection
     */
    public function testGetProject(): void
    {
        $this->prepareSuccessTest();

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getProject('mautic-transifex', true);

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic-transifex/');

        $this->assertSame(
            'details',
            $this->client->getRequest()->getUri()->getQuery(),
            'The API request did not include the expected query string.'
        );
    }

    /**
     * @testdox getProject() returns a Response object indicating a failed API connection
     */
    public function testGetProjectFailure(): void
    {
        $this->prepareFailureTest();

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getProject('mautic-transifex', true);

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic-transifex/', 'GET', 500);
    }

    /**
     * @testdox getProjects() returns a Response object indicating a successful API connection
     */
    public function testGetProjects(): void
    {
        $this->prepareSuccessTest();

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getProjects();

        $this->assertCorrectRequestAndResponse('/api/2/projects/');
    }

    /**
     * @testdox getProjects() returns a Response object indicating a failed API connection
     */
    public function testGetProjectsFailure(): void
    {
        $this->prepareFailureTest();

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getProjects();

        $this->assertCorrectRequestAndResponse('/api/2/projects/', 'GET', 500);
    }

    /**
     * @testdox updateProject() returns a Response object indicating a successful API connection
     */
    public function testUpdateProject(): void
    {
        $this->prepareSuccessTest();

        // Additional options
        $options = [
            'long_description'   => 'My test project',
            'private'            => true,
            'homepage'           => 'http://www.example.com',
            'trans_instructions' => 'http://www.example.com/instructions.html',
            'tags'               => 'joomla, mautic',
            'maintainers'        => 'joomla',
            'team'               => 'translators',
            'auto_join'          => true,
            'license'            => 'other_open_source',
            'fill_up_resources'  => false,
            'repository_url'     => 'http://www.example.com',
            'organization'       => 'mautic',
            'archived'           => false,
            'type'               => 1,
        ];

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->updateProject('mautic-transifex', $options);

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic-transifex/', 'PUT');
    }

    /**
     * @testdox updateProject() returns a Response object indicating a failed API connection
     */
    public function testUpdateProjectFailure(): void
    {
        $this->prepareFailureTest();

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->updateProject(
            'mautic-transifex',
            ['long_description' => 'My test project']
        );

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic-transifex/', 'PUT', 500);
    }

    /**
     * @testdox updateProject() throws a RuntimeException when there is no data to send to the API
     */
    public function testUpdateProjectRuntimeException(): void
    {
        $this->expectException(\RuntimeException::class);

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->updateProject('mautic-transifex', []);
    }

    /**
     * @testdox updateProject() throws an InvalidConfigurationException when an invalid license is specified
     */
    public function testUpdateProjectBadLicense(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        (new Projects($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->updateProject('mautic-transifex', ['license' => 'failure']);
    }
}
