<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class BenutzerTest extends UnitTestCase
{
    /**
     * @var \Recipes\RecipeManagement\Domain\Model\Benutzer|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \Recipes\RecipeManagement\Domain\Model\Benutzer::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getBenutzerNameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getBenutzerName()
        );
    }

    /**
     * @test
     */
    public function setBenutzerNameForStringSetsBenutzerName(): void
    {
        $this->subject->setBenutzerName('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('benutzerName'));
    }

    /**
     * @test
     */
    public function getBenutzerPasswordReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getBenutzerPassword()
        );
    }

    /**
     * @test
     */
    public function setBenutzerPasswordForStringSetsBenutzerPassword(): void
    {
        $this->subject->setBenutzerPassword('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('benutzerPassword'));
    }

    /**
     * @test
     */
    public function getBenutzerEmailReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getBenutzerEmail()
        );
    }

    /**
     * @test
     */
    public function setBenutzerEmailForStringSetsBenutzerEmail(): void
    {
        $this->subject->setBenutzerEmail('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('benutzerEmail'));
    }
}
