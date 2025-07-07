<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class IngredientInRecipeTest extends UnitTestCase
{
    /**
     * @var \Recipes\RecipeManagement\Domain\Model\IngredientInRecipe|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \Recipes\RecipeManagement\Domain\Model\IngredientInRecipe::class,
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
    public function getQuantityInGramReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getQuantityInGram()
        );
    }

    /**
     * @test
     */
    public function setQuantityInGramForIntSetsQuantityInGram(): void
    {
        $this->subject->setQuantityInGram(12);

        self::assertEquals(12, $this->subject->_get('quantityInGram'));
    }

    /**
     * @test
     */
    public function getRecipeReturnsInitialValueForRecipe(): void
    {
        self::assertEquals(
            null,
            $this->subject->getRecipe()
        );
    }

    /**
     * @test
     */
    public function setRecipeForRecipeSetsRecipe(): void
    {
        $recipeFixture = new \Recipes\RecipeManagement\Domain\Model\Recipe();
        $this->subject->setRecipe($recipeFixture);

        self::assertEquals($recipeFixture, $this->subject->_get('recipe'));
    }

    /**
     * @test
     */
    public function getIngredientallidReturnsInitialValueForIngredientGeneral(): void
    {
        self::assertEquals(
            null,
            $this->subject->getIngredientallid()
        );
    }

    /**
     * @test
     */
    public function setIngredientallidForIngredientGeneralSetsIngredientallid(): void
    {
        $ingredientallidFixture = new \Recipes\RecipeManagement\Domain\Model\IngredientGeneral();
        $this->subject->setIngredientallid($ingredientallidFixture);

        self::assertEquals($ingredientallidFixture, $this->subject->_get('ingredientallid'));
    }
}
