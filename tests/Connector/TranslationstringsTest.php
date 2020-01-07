<?php declare(strict_types=1);

namespace Mautic\Transifex\Tests\Connector;

use Mautic\Transifex\Connector\Translationstrings;
use Mautic\Transifex\Tests\ApiConnectorTestCase;

/**
 * Test class for \Mautic\Transifex\Connector\Translationstrings.
 */
final class TranslationstringsTest extends ApiConnectorTestCase
{
    /**
     * @testdox getPseudolocalizationStrings() returns a Response object indicating a successful API connection
     */
    public function testGetPseudolocalizationStrings(): void
    {
        $this->prepareSuccessTest();

        (new Translationstrings($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getPseudolocalizationStrings('mautic', 'mautic-transifex');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/pseudo/');

        $this->assertSame(
            'pseudo_type=MIXED',
            $this->client->getRequest()->getUri()->getQuery(),
            'The API request did not include the expected query string.'
        );
    }

    /**
     * @testdox getPseudolocalizationStrings() returns a Response object indicating a failed API connection
     */
    public function testGetPseudolocalizationStringsFailure(): void
    {
        $this->prepareFailureTest();

        (new Translationstrings($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getPseudolocalizationStrings('mautic', 'mautic-transifex');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/pseudo/', 'GET', 500);
    }

    /**
     * @testdox getStrings() returns a Response object indicating a successful API connection
     */
    public function testGetStrings(): void
    {
        $this->prepareSuccessTest();

        (new Translationstrings($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getStrings('mautic', 'mautic-transifex', 'en_US');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/translation/en_US/strings/');
    }

    /**
     * @testdox getStrings() requesting full details returns a Response object indicating a successful API connection
     */
    public function testGetStringsDetails(): void
    {
        $this->prepareSuccessTest();

        (new Translationstrings($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getStrings('mautic', 'mautic-transifex', 'en_US', true);

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/translation/en_US/strings/');

        $this->assertSame(
            'details',
            $this->client->getRequest()->getUri()->getQuery(),
            'The API request did not include the expected query string.'
        );
    }

    /**
     * @testdox getStrings() requesting full details and the key returns a Response object indicating a successful API connection
     */
    public function testGetStringsDetailsKey(): void
    {
        $this->prepareSuccessTest();

        (new Translationstrings($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getStrings(
            'mautic',
            'mautic-transifex',
            'en_US',
            true,
            ['key' => 'Yes']
        );

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/translation/en_US/strings/');

        $this->assertSame(
            'details&key=Yes',
            $this->client->getRequest()->getUri()->getQuery(),
            'The API request did not include the expected query string.'
        );
    }

    /**
     * @testdox getStrings() requesting full details, key, and context returns a Response object indicating a successful API connection
     */
    public function testGetStringsDetailsKeyContext(): void
    {
        $this->prepareSuccessTest();

        (new Translationstrings($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getStrings(
            'mautic',
            'mautic-transifex',
            'en_US',
            true,
            ['key' => 'Yes', 'context' => 'Something']
        );

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/translation/en_US/strings/');

        $this->assertSame(
            'details&key=Yes&context=Something',
            $this->client->getRequest()->getUri()->getQuery(),
            'The API request did not include the expected query string.'
        );
    }

    /**
     * @testdox getStrings() requesting the key and context returns a Response object indicating a successful API connection
     */
    public function testGetStringsKeyContext(): void
    {
        $this->prepareSuccessTest();

        (new Translationstrings($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getStrings(
            'mautic',
            'mautic-transifex',
            'en_US',
            false,
            ['key' => 'Yes', 'context' => 'Something']
        );

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/translation/en_US/strings/');

        $this->assertSame(
            'key=Yes&context=Something',
            $this->client->getRequest()->getUri()->getQuery(),
            'The API request did not include the expected query string.'
        );
    }

    /**
     * @testdox getStrings() requesting a given context returns a Response object indicating a successful API connection
     */
    public function testGetStringsContext(): void
    {
        $this->prepareSuccessTest();

        (new Translationstrings($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getStrings(
            'mautic',
            'mautic-transifex',
            'en_US',
            false,
            ['context' => 'Something']
        );

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/translation/en_US/strings/');

        $this->assertSame(
            'context=Something',
            $this->client->getRequest()->getUri()->getQuery(),
            'The API request did not include the expected query string.'
        );
    }

    /**
     * @testdox getStrings() returns a Response object indicating a failed API connection
     */
    public function testGetStringsFailure(): void
    {
        $this->prepareFailureTest();

        (new Translationstrings($this->client, $this->requestFactory, $this->streamFactory, $this->uriFactory, $this->options))->getStrings('mautic', 'mautic-transifex', 'en_US');

        $this->assertCorrectRequestAndResponse('/api/2/project/mautic/resource/mautic-transifex/translation/en_US/strings/', 'GET', 500);
    }
}
