<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class IngredientGeneralTest extends UnitTestCase
{
    /**
     * @var \Recipes\RecipeManagement\Domain\Model\IngredientGeneral|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \Recipes\RecipeManagement\Domain\Model\IngredientGeneral::class,
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
    public function getIngredientNameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getIngredientName()
        );
    }

    /**
     * @test
     */
    public function setIngredientNameForStringSetsIngredientName(): void
    {
        $this->subject->setIngredientName('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('ingredientName'));
    }

    /**
     * @test
     */
    public function getCaloriesPerGramReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getCaloriesPerGram()
        );
    }

    /**
     * @test
     */
    public function setCaloriesPerGramForIntSetsCaloriesPerGram(): void
    {
        $this->subject->setCaloriesPerGram(12);

        self::assertEquals(12, $this->subject->_get('caloriesPerGram'));
    }
}
