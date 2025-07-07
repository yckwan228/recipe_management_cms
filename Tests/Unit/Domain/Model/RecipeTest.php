<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class RecipeTest extends UnitTestCase
{
    /**
     * @var \Recipes\RecipeManagement\Domain\Model\Recipe|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \Recipes\RecipeManagement\Domain\Model\Recipe::class,
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
    public function getRecipeNameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getRecipeName()
        );
    }

    /**
     * @test
     */
    public function setRecipeNameForStringSetsRecipeName(): void
    {
        $this->subject->setRecipeName('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('recipeName'));
    }

    /**
     * @test
     */
    public function getTotalCaloriesReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getTotalCalories()
        );
    }

    /**
     * @test
     */
    public function setTotalCaloriesForIntSetsTotalCalories(): void
    {
        $this->subject->setTotalCalories(12);

        self::assertEquals(12, $this->subject->_get('totalCalories'));
    }

    /**
     * @test
     */
    public function getBenutzerReturnsInitialValueForBenutzer(): void
    {
        self::assertEquals(
            null,
            $this->subject->getBenutzer()
        );
    }

    /**
     * @test
     */
    public function setBenutzerForBenutzerSetsBenutzer(): void
    {
        $benutzerFixture = new \Recipes\RecipeManagement\Domain\Model\Benutzer();
        $this->subject->setBenutzer($benutzerFixture);

        self::assertEquals($benutzerFixture, $this->subject->_get('benutzer'));
    }
}
