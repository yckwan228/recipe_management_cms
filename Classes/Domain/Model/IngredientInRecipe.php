<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Domain\Model;

class IngredientInRecipe extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * @var int
     */
    protected $quantityInGram = 0;

    /**
     * @var \Recipes\RecipeManagement\Domain\Model\Recipe
     */
    protected $recipe = null;

    /**
     * @var \Recipes\RecipeManagement\Domain\Model\IngredientGeneral
     */
    protected $ingredient = null;

    public function getQuantityInGram(): int
    {
        return $this->quantityInGram;
    }

    public function setQuantityInGram(int $quantityInGram): void
    {
        $this->quantityInGram = $quantityInGram;
    }

    public function getRecipe(): ?\Recipes\RecipeManagement\Domain\Model\Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(\Recipes\RecipeManagement\Domain\Model\Recipe $recipe): void
    {
        $this->recipe = $recipe;
    }

    public function getIngredient(): ?\Recipes\RecipeManagement\Domain\Model\IngredientGeneral
    {
        return $this->ingredient;
    }

    public function setIngredient(\Recipes\RecipeManagement\Domain\Model\IngredientGeneral $ingredient): void
    {
        $this->ingredient = $ingredient;
    }

    public function getCaloriesPer100g(): float
    {
        return $this->ingredient ? $this->ingredient->getCaloriesPer100g() : 0.0;
    }
}
