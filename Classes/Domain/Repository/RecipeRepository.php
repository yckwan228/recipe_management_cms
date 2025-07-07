<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

class RecipeRepository extends Repository
{
    /**
     * Find recipes by Benutzer ID
     *
     * @param \Recipes\RecipeManagement\Domain\Model\Benutzer $benutzer
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByBenutzer(\Recipes\RecipeManagement\Domain\Model\Benutzer $benutzer)
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->equals('benutzer', $benutzer)
        )->execute();
    }

    /**
     * Find a recipe by UID and ensure that ingredients are loaded.
     *
     * @param int $recipeUid
     * @return \Recipes\RecipeManagement\Domain\Model\Recipe|null
     */
    public function findByUidWithIngredients(int $recipeUid)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('uid', $recipeUid)
        );
        $query->setLimit(1);
    
        // QuerySettings für Relationen anpassen
        $querySettings = $query->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $querySettings->setIgnoreEnableFields(true);
        $querySettings->setIncludeDeleted(false);
        $query->setQuerySettings($querySettings);
    
        /** @var \Recipes\RecipeManagement\Domain\Model\Recipe $recipe */
        $recipe = $query->execute()->getFirst();
    
        if ($recipe !== null) {
            // Manuelles Laden der Zutaten
            $ingredients = $this->loadIngredientsForRecipe($recipeUid);
            $recipe->_setProperty('ingredientsInRecipe', $ingredients);
        }
    
        return $recipe;
    }
    
    protected function loadIngredientsForRecipe(int $recipeUid)
    {
        $queryBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
            ->getConnectionForTable('tx_recipemanagement_domain_model_ingredientinrecipe') // ✅ Richtiger Tabellenname
            ->createQueryBuilder();
    
        $rows = $queryBuilder
            ->select(
                'iir.uid AS ingredientInRecipeUid',
                'iir.quantity_in_gram',
                'iir.recipe',
                'ig.uid AS ingredientUid',
                'ig.ingredient_name',
                'ig.calories_per_100g'
            )
            ->from('tx_recipemanagement_domain_model_ingredientinrecipe', 'iir') // ✅ Haupttabelle
            ->leftJoin(
                'iir',
                'tx_recipemanagement_domain_model_ingredientgeneral', // ✅ Verknüpfte Tabelle
                'ig',
                $queryBuilder->expr()->eq('iir.ingredient', 'ig.uid')
            )
            ->where(
                $queryBuilder->expr()->eq('iir.recipe', $queryBuilder->createNamedParameter($recipeUid, \PDO::PARAM_INT))
            )
            ->execute()
            ->fetchAll(); // ✅ TYPO3 v10-kompatibel
    
        $ingredientsStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    
        // Rezept-Objekt manuell erstellen
        $recipe = new \Recipes\RecipeManagement\Domain\Model\Recipe();
        $recipe->_setProperty('uid', $recipeUid);
    
        foreach ($rows as $row) {
            /** @var \Recipes\RecipeManagement\Domain\Model\IngredientInRecipe $ingredientInRecipe */
            $ingredientInRecipe = new \Recipes\RecipeManagement\Domain\Model\IngredientInRecipe();
            $ingredientInRecipe->_setProperty('uid', $row['ingredientInRecipeUid']);
            $ingredientInRecipe->setQuantityInGram((int) $row['quantity_in_gram']); // ✅ Menge korrekt setzen
            $ingredientInRecipe->setRecipe($recipe); // ✅ Rezept zuweisen
    
            // IngredientGeneral erstellen und verknüpfen
            $ingredientGeneral = new \Recipes\RecipeManagement\Domain\Model\IngredientGeneral();
            $ingredientGeneral->_setProperty('uid', $row['ingredientUid']);
            $ingredientGeneral->setIngredientName($row['ingredient_name']);
            $ingredientGeneral->setCaloriesPer100g((float) $row['calories_per_100g']); // ✅ Kalorien korrekt setzen
    
            $ingredientInRecipe->setIngredient($ingredientGeneral);
    
            // Hinzufügen zur ObjectStorage
            $ingredientsStorage->attach($ingredientInRecipe);
        }
    
        return $ingredientsStorage;
    }
    

    
}
