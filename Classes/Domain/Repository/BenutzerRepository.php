<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Domain\Repository;


/**
 * This file is part of the "Recipe Management" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2024 
 */

/**
 * The repository for Benutzers
 */
class BenutzerRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function findOneByBenutzerName(string $benutzerName): ?\Recipes\RecipeManagement\Domain\Model\Benutzer
    {
        $query = $this->createQuery();
        return $query->matching(
            $query->equals('benutzerName', $benutzerName)
        )->execute()->getFirst();
    }

    public function findByUid($uid): ?\Recipes\RecipeManagement\Domain\Model\Benutzer
    {
        return $this->findByIdentifier($uid);
    }
}
