<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for IngredientGeneral
 */
class IngredientGeneralRepository extends Repository
{
    // Hier kannst du zusätzliche Methoden hinzufügen, falls notwendig
    public function findAllEntries(){
        $query = $this->createQuery();
        return $query->execute();
    }
}
