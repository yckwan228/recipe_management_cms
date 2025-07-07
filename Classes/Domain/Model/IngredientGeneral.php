<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Domain\Model;

/**
 * This file is part of the "Recipe Management" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2024
 */

/**
 * IngredientGeneral
 */
class IngredientGeneral extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * ingredientName
     *
     * @var string
     */
    protected $ingredientName = '';

    /**
 * Alias für ingredientName
 *
 * @return string
 */
public function getName(): string
{
    return $this->ingredientName;
}


    /**
     * unit
     *
     * @var string
     */
    protected $unit = 'g';

    /**
     * caloriesPer100g
     *
     * @var float
     */
    protected $caloriesPer100g = 0.0;

    /**
 * Alias für caloriesPer100g als caloriesPerGram
 *
 * @return float
 */
public function getCaloriesPerGram(): float
{
    return $this->caloriesPer100g;
}


    /**
     * carbs
     *
     * @var float
     */
    protected $carbs = 0.0;

    /**
     * protein
     *
     * @var float
     */
    protected $protein = 0.0;

    /**
     * fat
     *
     * @var float
     */
    protected $fat = 0.0;

    /**
     * Returns the ingredientName
     *
     * @return string
     */
    public function getIngredientName(): string
    {
        return $this->ingredientName;
    }

    /**
     * Sets the ingredientName
     *
     * @param string $ingredientName
     * @return void
     */
    public function setIngredientName(string $ingredientName): void
    {
        $this->ingredientName = $ingredientName;
    }

    /**
     * Returns the unit
     *
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * Sets the unit
     *
     * @param string $unit
     * @return void
     */
    public function setUnit(string $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * Returns the caloriesPer100g
     *
     * @return float
     */
    public function getCaloriesPer100g(): float
    {
        // Falls der Wert NULL oder 0 ist, setze einen Standardwert oder debugge den Wert
        if ($this->caloriesPer100g === null) {
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->caloriesPer100g, 'DEBUG: CaloriesPer100g');
            return 0.0;
        }
        return $this->caloriesPer100g;
    }
    

    /**
     * Sets the caloriesPer100g
     *
     * @param float $caloriesPer100g
     * @return void
     */
    public function setCaloriesPer100g(float $caloriesPer100g): void
    {
        $this->caloriesPer100g = $caloriesPer100g;
    }

    /**
     * Returns the carbs
     *
     * @return float
     */
    public function getCarbs(): float
    {
        return $this->carbs;
    }

    /**
     * Sets the carbs
     *
     * @param float $carbs
     * @return void
     */
    public function setCarbs(float $carbs): void
    {
        $this->carbs = $carbs;
    }

    /**
     * Returns the protein
     *
     * @return float
     */
    public function getProtein(): float
    {
        return $this->protein;
    }

    /**
     * Sets the protein
     *
     * @param float $protein
     * @return void
     */
    public function setProtein(float $protein): void
    {
        $this->protein = $protein;
    }

    /**
     * Returns the fat
     *
     * @return float
     */
    public function getFat(): float
    {
        return $this->fat;
    }

    /**
     * Sets the fat
     *
     * @param float $fat
     * @return void
     */
    public function setFat(float $fat): void
    {
        $this->fat = $fat;
    }
}
