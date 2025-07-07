<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class IngredientInRecipeRepository extends Repository
{

    public function findByRecipe($recipe)
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->equals('recipe', $recipe)
        )->execute();
    }
 


}
