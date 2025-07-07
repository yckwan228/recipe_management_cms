<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Domain\Model;

/**
 * Recipe
 */
class Recipe extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * The name of the recipe
     *
     * @var string
     */
    protected $recipeName = '';

    /**
     * The total calories of the recipe
     *
     * @var int
     */
    protected $totalCalories = 0;

    /**
     * The Benutzer (User) associated with the recipe
     *
     * @var \Recipes\RecipeManagement\Domain\Model\Benutzer
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $benutzer = null;

    /**
     * Ingredients in Recipe (Relation to IngredientInRecipe)
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Recipes\RecipeManagement\Domain\Model\IngredientInRecipe>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $ingredientsInRecipe;

    /**
     * Constructor to initialize ObjectStorage
     */
    public function __construct()
    {
        $this->ingredientsInRecipe = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Get the name of the recipe
     *
     * @return string
     */
    public function getRecipeName(): string
    {
        return $this->recipeName;
    }

    /**
     * Set the name of the recipe
     *
     * @param string $recipeName
     * @return void
     */
    public function setRecipeName(string $recipeName): void
    {
        $this->recipeName = $recipeName;
    }

    /**
     * Get the total calories of the recipe
     *
     * @return int
     */
    public function getTotalCalories(): int
    {
        return $this->totalCalories;
    }

    /**
     * Set the total calories of the recipe
     *
     * @param int $totalCalories
     * @return void
     */
    public function setTotalCalories(int $totalCalories): void
    {
        $this->totalCalories = $totalCalories;
    }

    /**
     * Get the Benutzer associated with the recipe
     *
     * @return \Recipes\RecipeManagement\Domain\Model\Benutzer|null
     */
    public function getBenutzer(): ?Benutzer
    {
        if ($this->benutzer instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
            return $this->benutzer->_loadRealInstance();
        }
    
        return $this->benutzer;
    }

    /**
     * Set the Benutzer (User) for the recipe
     *
     * @param \Recipes\RecipeManagement\Domain\Model\Benutzer $benutzer
     * @return void
     */
    public function setBenutzer(Benutzer $benutzer): void
    {
        $this->benutzer = $benutzer;
    }

    /**
     * Get all ingredients in the recipe
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Recipes\RecipeManagement\Domain\Model\IngredientInRecipe>
     */
    public function getIngredientsInRecipe()
    {
        return $this->ingredientsInRecipe;
    }

    /**
     * Set ingredients for the recipe
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Recipes\RecipeManagement\Domain\Model\IngredientInRecipe> $ingredientsInRecipe
     */
    public function setIngredientsInRecipe(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $ingredientsInRecipe)
    {
        $this->ingredientsInRecipe = $ingredientsInRecipe;
    }

    /**
     * Add a single ingredient to the recipe
     *
     * @param \Recipes\RecipeManagement\Domain\Model\IngredientInRecipe $ingredientInRecipe
     */
    public function addIngredientInRecipe(\Recipes\RecipeManagement\Domain\Model\IngredientInRecipe $ingredientInRecipe)
    {
        $this->ingredientsInRecipe->attach($ingredientInRecipe);
    }

    /**
 * Portionsanzahl für das Rezept
 *
 * @var int
 */
protected $portions = 1;

/**
 * Gibt die Portionsanzahl zurück
 *
 * @return int
 */
public function getPortions(): int
{
    return $this->portions;
}

/**
 * Setzt die Portionsanzahl
 *
 * @param int $portions
 * @return void
 */
public function setPortions(int $portions): void
{
    $this->portions = $portions;
}

}
