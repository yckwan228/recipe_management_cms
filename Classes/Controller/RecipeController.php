<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Controller;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

/**
 * RecipeController
 */
class RecipeController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var \Recipes\RecipeManagement\Domain\Repository\RecipeRepository
     */
    protected $recipeRepository;

    /**
     * @var \Recipes\RecipeManagement\Domain\Repository\IngredientsGeneralRepository
     */
    protected $ingredientRepository;

    /**
     * @var \Recipes\RecipeManagement\Domain\Repository\BenutzerRepository
     */
    protected $benutzerRepository;

      /**
 * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
 */
protected $persistenceManager;

/**
 * Injects the PersistenceManager
 *
 * @param \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager
 */
public function injectPersistenceManager(\TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager): void
{
    $this->persistenceManager = $persistenceManager;
}

    /**
     * Injects the Recipe repository
     *
     * @param \Recipes\RecipeManagement\Domain\Repository\RecipeRepository $recipeRepository
     */
    public function injectRecipeRepository(\Recipes\RecipeManagement\Domain\Repository\RecipeRepository $recipeRepository): void
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * Injects the Ingredient repository
     *
     * @param \Recipes\RecipeManagement\Domain\Repository\IngredientGeneralRepository $ingredientRepository
     */
    public function injectIngredientRepository(\Recipes\RecipeManagement\Domain\Repository\IngredientGeneralRepository $ingredientRepository): void
    {
        $this->ingredientRepository = $ingredientRepository;
    }

    /**
     * Injects the Benutzer repository
     *
     * @param \Recipes\RecipeManagement\Domain\Repository\BenutzerRepository $benutzerRepository
     */
    public function injectBenutzerRepository(\Recipes\RecipeManagement\Domain\Repository\BenutzerRepository $benutzerRepository): void
    {
        $this->benutzerRepository = $benutzerRepository;
    }

       /**
 * @var \Recipes\RecipeManagement\Domain\Repository\IngredientInRecipeRepository
 */
protected $ingredientInRecipeRepository;

/**
 * Injects the IngredientInRecipe repository
 *
 * @param \Recipes\RecipeManagement\Domain\Repository\IngredientInRecipeRepository $ingredientInRecipeRepository
 */
public function injectIngredientInRecipeRepository(\Recipes\RecipeManagement\Domain\Repository\IngredientInRecipeRepository $ingredientInRecipeRepository): void
{
    $this->ingredientInRecipeRepository = $ingredientInRecipeRepository;
}

    public function newRecipeAction(?string $recipeUuid = null, ?int $benutzerId = null): void
    {
        session_start();
    
        // 🔹 Falls $benutzerId nicht aus der Request kommt, versuchen aus der Session zu holen
        if (!$benutzerId) {
            $benutzerId = $_SESSION['loggedInUser'] ?? null;
        }
    
        // 🔹 Benutzer aus der Datenbank abrufen
        $benutzer = $this->benutzerRepository->findByUid($benutzerId);
        $_SESSION['loggedInUser'] = $benutzerId; 
         
        // 🔹 Neues Rezept in der Session setzen
        if ($recipeUuid && isset($_SESSION['temp_recipe'][$recipeUuid])) {
            $recipeData = $_SESSION['temp_recipe'][$recipeUuid];
        } else {
            $recipeUuid = uniqid('recipe_', true);
            $_SESSION['temp_recipe'][$recipeUuid] = [
                'uuid' => $recipeUuid,
                'title' => '',
                'benutzerId' => $benutzerId,
                'ingredients' => []
            ];
            $recipeData = $_SESSION['temp_recipe'][$recipeUuid];
        }
    
        //  Werte an die View übergeben
        $this->view->assignMultiple([
            'recipeUuid' => $recipeUuid,
            'recipeData' => $recipeData,
            'benutzerId' => $benutzerId,
            'benutzer' => $benutzer
        ]);
    }
    

    public function createRecipeAction(\Recipes\RecipeManagement\Domain\Model\Recipe $newRecipe, ?string $recipeUuid = null): void
{
    session_start();

    // Benutzer-ID aus der Session holen
    $benutzerId = $_SESSION['loggedInUser'] ?? null;
    if ($benutzerId === null) {
        $this->addFlashMessage('Fehlende Benutzer-ID!', '', AbstractMessage::ERROR);
        $this->redirect('newRecipe');
        return;
    }

    // Benutzer abrufen
    $benutzer = $this->benutzerRepository->findByUid($benutzerId);
    if ($benutzer === null) {
        $this->addFlashMessage('Benutzer nicht gefunden.', '', AbstractMessage::ERROR);
        $this->redirect('listRecipe');
        return;
    }

    // Rezept-Daten aus der Session abrufen
    if (empty($recipeUuid) || !isset($_SESSION['temp_recipe'][$recipeUuid])) {
        $this->addFlashMessage('Fehlende oder ungültige Rezept-UUID.', '', AbstractMessage::ERROR);
        $this->redirect('newRecipe');
        return;
    }

    $recipeData = $_SESSION['temp_recipe'][$recipeUuid];

    // Portionsanzahl setzen
    $portions = $this->request->hasArgument('portions') ? (int) $this->request->getArgument('portions') : 1;
    $newRecipe->setPortions($portions);
    $newRecipe->setBenutzer($benutzer);

    //  Rezept speichern, um eine `uid` zu erhalten
    $this->recipeRepository->add($newRecipe);
    $this->persistenceManager->persistAll();

    //  Jetzt existiert das Rezept in der Datenbank → Zutaten können gespeichert werden
    foreach ($recipeData['ingredients'] as $ingredientData) {
        $ingredient = $this->ingredientRepository->findByUid($ingredientData['ingredientId']);
        if ($ingredient) {
            $ingredientInRecipe = new \Recipes\RecipeManagement\Domain\Model\IngredientInRecipe();
            $ingredientInRecipe->setRecipe($newRecipe); //  Stelle sicher, dass `recipeID` gesetzt wird
            $ingredientInRecipe->setIngredient($ingredient);
            $calculatedQuantity = $ingredientData['quantity'] * $portions;
            $ingredientInRecipe->setQuantityInGram($calculatedQuantity);


            $this->ingredientInRecipeRepository->add($ingredientInRecipe);
        }
    }

    // Änderungen in der Datenbank speichern
    $this->persistenceManager->persistAll();

    // Rezept aus der Session entfernen
    unset($_SESSION['temp_recipe'][$recipeUuid]);

    // Erfolgsnachricht & Weiterleitung
    $this->addFlashMessage('Rezept und Zutaten erfolgreich gespeichert.');
    $this->redirect('listRecipe');
}

    

public function updateRecipeAction(int $recipeUid): void
{
    // Rezept-Objekt manuell laden
    $recipe = $this->recipeRepository->findByUid($recipeUid);
    if ($recipe === null) {
        $this->addFlashMessage('Fehler: Rezept nicht gefunden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->redirect('listRecipe');
        return;
    }

    // Debugging: Altes Rezept anzeigen
    \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump([
        'oldTitle' => $recipe->getRecipeName(),
        'oldPortions' => $recipe->getPortions(),
    ], 'Before Update');

    // Prüfen, ob Formulardaten vorhanden sind
    $formData = $this->request->getArguments();
    \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($formData, 'Received Form Data');

    // Sicherstellen, dass die Formulardaten für 'editRecipe' existieren
    if (isset($formData['editRecipe'])) {
        $recipeData = $formData['editRecipe'];

        // Rezept-Objekt mit neuen Werten aktualisieren
        if (!empty($recipeData['recipeName'])) {
            $recipe->setRecipeName($recipeData['recipeName']);
        }
        if (!empty($recipeData['portions'])) {
            $recipe->setPortions((int) $recipeData['portions']);
        }
    }

    // Debugging: Neue Werte nach Update
    \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump([
        'updatedTitle' => $recipe->getRecipeName(),
        'updatedPortions' => $recipe->getPortions(),
    ], 'After Update');

    // Änderungen speichern
    $this->recipeRepository->update($recipe);
    $this->persistenceManager->persistAll();
    $this->persistenceManager->clearState();

    // Erfolgsnachricht & Weiterleitung
    $this->addFlashMessage('Rezept erfolgreich aktualisiert.');
    $this->redirect('listRecipe', 'Recipe', null, ['benutzerId' => $recipe->getBenutzer()->getUid()]);
}



public function editRecipeAction(int $recipeUid): void
{
    $recipe = $this->recipeRepository->findByUidWithIngredients($recipeUid);


    if ($recipe === null) {
        $this->addFlashMessage(
            'Rezept nicht gefunden!',
            '',
            \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
        );
        $this->redirect('dashboard');
    }

    // Debugging für Zutaten
    \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($recipe->getIngredientsInRecipe());
   // exit();
    
    $this->view->assign('recipe', $recipe);
    
}

public function deleteRecipeAction(int $recipeUid): void
{

     // Zutaten aus der Datenbank entfernen
     $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
     ->getQueryBuilderForTable('tx_recipemanagement_domain_model_ingredientinrecipe');

 $queryBuilder
     ->delete('tx_recipemanagement_domain_model_ingredientinrecipe')
     ->where(
         $queryBuilder->expr()->eq('recipe', $queryBuilder->createNamedParameter($recipeUid, \PDO::PARAM_INT))
     )
     ->execute();

    // Rezept aus der Datenbank entfernen
    $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
        ->getQueryBuilderForTable('tx_recipemanagement_domain_model_recipe');
    
    $affectedRows = $queryBuilder
        ->delete('tx_recipemanagement_domain_model_recipe')
        ->where(
            $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($recipeUid, \PDO::PARAM_INT))
        )
        ->execute();

    if ($affectedRows > 0) {
        $this->addFlashMessage('Rezept erfolgreich gelöscht.');
    } else {
        $this->addFlashMessage('Das Rezept konnte nicht gefunden oder gelöscht werden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
    }

    // Zurück zur Rezeptliste
    $this->redirect('listRecipe');
}


public function showRecipeAction(\Recipes\RecipeManagement\Domain\Model\Recipe $recipe): void
{
    $benutzer = $recipe->getBenutzer();

    if ($benutzer === null) {
        $this->addFlashMessage('Kein Benutzer mit diesem Rezept verbunden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->redirect('list');
        return;
    }

    $ingredientsInRecipe = $this->ingredientInRecipeRepository->findByRecipe($recipe);

    // 🔹 Rezept-Portionsanzahl holen
    $portions = $recipe->getPortions();

    // 🔹 Nährwerte für das ganze Rezept berechnen
    $totalCarbs = 0;
    $totalProtein = 0;
    $totalFat = 0;

    foreach ($ingredientsInRecipe as $ingredientEntry) {
        $ingredient = $ingredientEntry->getIngredient();
        $quantity = $ingredientEntry->getQuantityInGram();

        // Berechnung unter Berücksichtigung der Portionsanzahl
        $carbs = ($ingredient->getCarbs() * $quantity * $portions) / 100;
        $protein = ($ingredient->getProtein() * $quantity * $portions) / 100;
        $fat = ($ingredient->getFat() * $quantity * $portions) / 100;

        // Gesamtwerte aufsummieren
        $totalCarbs += $carbs;
        $totalProtein += $protein;
        $totalFat += $fat;

        // Werte für jede Zutat speichern
        $ingredientEntry->calculatedCarbs = $carbs;
        $ingredientEntry->calculatedProtein = $protein;
        $ingredientEntry->calculatedFat = $fat;
    }

    $this->view->assignMultiple([
        'benutzer' => $benutzer,
        'recipe' => $recipe,
        'ingredientsInRecipe' => $ingredientsInRecipe,
        'totalCarbs' => $totalCarbs,
        'totalProtein' => $totalProtein,
        'totalFat' => $totalFat
    ]);
}


//\Recipes\RecipeManagement\Domain\Model\Recipe $recipe
public function listIngredientAction(string $recipeUuid): void
{   
    if (!$recipeUuid) {
        $this->addFlashMessage('Rezept-UUID fehlt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->redirect('newRecipe');
        return;
    }

    // Zutaten aus der Datenbank abrufen
    $ingredients = $this->ingredientRepository->findAll();

    // Debugging für die Konsole
    \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($ingredients, 'DEBUG: Ingredients from DB');

    // Überprüfen, ob Zutaten gefunden wurden
    if ($ingredients === null || count($ingredients) === 0) {
        $this->addFlashMessage('Keine Zutaten in der Datenbank gefunden!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
    }

    $this->view->assignMultiple([
        'entries' => $ingredients,
        'recipeUuid' => $recipeUuid
    ]);
}

public function listIngredientEditRecipeAction(int $recipeUid): void
{
    // Prüfen, ob die Rezept-ID vorhanden ist
    if (!$recipeUid) {
        $this->addFlashMessage('Rezept-ID fehlt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->redirect('editRecipe');
        return;
    }

    // Rezept aus der Datenbank abrufen
    $recipe = $this->recipeRepository->findByUid($recipeUid);
    if (!$recipe) {
        $this->addFlashMessage('Rezept nicht gefunden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->redirect('dashboard');
        return;
    }

    // Zutaten aus der Datenbank abrufen
    $ingredients = $this->ingredientRepository->findAll();

    // Debugging für die Konsole
    \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($ingredients, 'DEBUG: Ingredients from DB');

    // Überprüfen, ob Zutaten gefunden wurden
    if ($ingredients === null || count($ingredients) === 0) {
        $this->addFlashMessage('Keine Zutaten in der Datenbank gefunden!', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
    }

    $this->view->assignMultiple([
        'entries' => $ingredients,
        'recipeUid' => $recipeUid,
        'recipe' => $recipe, // Damit das Rezept für das Template verfügbar ist
    ]);
}


public function addIngredientAction(string $recipeUuid, int $ingredientId, int $quantity): void
{
    session_start();

    //  Benutzer-ID aus der Session holen
    $benutzerId = $_SESSION['loggedInUser'] ?? null;

    //  Prüfen, ob das Rezept existiert
    if (!isset($_SESSION['temp_recipe'][$recipeUuid])) {
        $this->redirect('newRecipe', null, null, ['benutzerId' => $benutzerId]);
        return;
    }

    //  Zutat abrufen
    $ingredient = $this->ingredientRepository->findByUid($ingredientId);
    if (!$ingredient) {
        $this->addFlashMessage('Zutat nicht gefunden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->redirect('listIngredient', null, null, ['recipeUuid' => $recipeUuid, 'benutzerId' => $benutzerId]);
        return;
    }

    // Rezeptname aus dem Request holen und in die Session speichern
    $recipeName = $this->request->hasArgument('recipeName') ? $this->request->getArgument('recipeName') : '';
    $_SESSION['temp_recipe'][$recipeUuid]['title'] = $recipeName;

    //  Prüfen, ob die Zutat bereits existiert
    foreach ($_SESSION['temp_recipe'][$recipeUuid]['ingredients'] as $existingIngredient) {
        if ($existingIngredient['ingredientId'] == $ingredientId) {
            $this->addFlashMessage('Diese Zutat wurde bereits hinzugefügt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->redirect('newRecipe', null, null, ['recipeUuid' => $recipeUuid, 'benutzerId' => $benutzerId]);
            return;
        }
    }

    //  Zutat zur Session hinzufügen
    $_SESSION['temp_recipe'][$recipeUuid]['ingredients'][] = [
        'ingredientId' => $ingredientId,
        'name' => $ingredient->getIngredientName(),
        'caloriesPer100g' => $ingredient->getCaloriesPer100g(), 
        'quantity' => $quantity
    ];
    
    // 🔹 Benutzer-ID in Session speichern, falls nicht vorhanden
        $_SESSION['temp_recipe'][$recipeUuid]['benutzerId'] = $benutzerId;

    // 🔹 Erfolgreiche Nachricht & Weiterleitung zur Rezept-Seite (mit Benutzer-ID)
    $this->addFlashMessage('Zutat erfolgreich hinzugefügt.');
    $this->redirect('newRecipe', null, null, ['recipeUuid' => $recipeUuid, 'benutzerId' => $benutzerId]);
}

public function addIngredientEditRecipeAction(int $recipeUid, int $ingredientId, int $quantity): void
{
    //  Rezept aus der Datenbank abrufen
    $recipe = $this->recipeRepository->findByUid($recipeUid);
    if (!$recipe) {
        $this->addFlashMessage('Rezept nicht gefunden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->redirect('editRecipe', null, null, ['recipeUid' => $recipeUid]);
        return;
    }

    // Zutat abrufen
    $ingredient = $this->ingredientRepository->findByUid($ingredientId);
    if (!$ingredient) {
        $this->addFlashMessage('Zutat nicht gefunden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->redirect('listIngredientEditRecipe', null, null, ['recipeUid' => $recipeUid]);
        return;
    }

    // Prüfen, ob die Zutat bereits existiert
    foreach ($recipe->getIngredientsInRecipe() as $existingIngredient) {
        if ($existingIngredient->getIngredient()->getUid() === $ingredientId) {
            $this->addFlashMessage('Diese Zutat wurde bereits hinzugefügt.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
            $this->redirect('editRecipe', null, null, ['recipeUid' => $recipeUid]);
            return;
        }
    }

    // Neue Zutat dem Rezept hinzufügen
    $ingredientInRecipe = new \Recipes\RecipeManagement\Domain\Model\IngredientInRecipe();
    $ingredientInRecipe->setRecipe($recipe);
    $ingredientInRecipe->setIngredient($ingredient);
    $ingredientInRecipe->setQuantityInGram($quantity);

    $this->ingredientInRecipeRepository->add($ingredientInRecipe);
    $this->persistenceManager->persistAll();

    // Erfolgreiche Nachricht & Weiterleitung zur Rezept-Seite
    $this->addFlashMessage('Zutat erfolgreich hinzugefügt.');
    $this->redirect('editRecipe', null, null, ['recipeUid' => $recipeUid]);
}


// maybe same as showRecipeList
public function listRecipeAction(): void
{
    // Benutzer aus der Session holen
    $userId = $GLOBALS['TSFE']->fe_user->getKey('ses', 'loggedInUser');
    $benutzer = $this->benutzerRepository->findByUid($userId);

    if ($benutzer) {
        // Rezepte für den Benutzer laden
        $recipes = $this->recipeRepository->findByBenutzer($benutzer);

        $this->view->assignMultiple([
            'recipes' => $recipes,
            'benutzer' => $benutzer
        ]);
    } else {
        $this->redirect('login', 'Benutzer');
    }
}

public function removeIngredientAction(string $recipeUuid, int $ingredientId): void
{
    session_start();

    // Prüfen, ob das Rezept in der Session existiert
    if (!isset($_SESSION['temp_recipe'][$recipeUuid])) {
        $this->redirect('newRecipe');
        return;
    }

    // Zutatenliste aus der Session laden
    $ingredients = &$_SESSION['temp_recipe'][$recipeUuid]['ingredients'];

    // Die Zutat aus der Liste entfernen
    foreach ($ingredients as $key => $ingredient) {
        if ($ingredient['ingredientId'] == $ingredientId) {
            unset($ingredients[$key]);
            break;
        }
    }

    // Flash-Nachricht für erfolgreiche Entfernung
    $this->addFlashMessage('Zutat erfolgreich entfernt.');

    // Weiterleitung zurück zur Rezept-Erstellungsseite
    $this->redirect('newRecipe', null, null, ['recipeUuid' => $recipeUuid]);
}

public function removeIngredientEditRecipeAction(int $recipeUid, int $ingredientId): void
{
    // Verbindung zur Datenbank herstellen
    $connection = GeneralUtility::makeInstance(ConnectionPool::class)
        ->getConnectionForTable('tx_recipemanagement_domain_model_ingredientinrecipe');

    // Überprüfen, ob eine passende Zutat existiert
    $queryBuilder = $connection->createQueryBuilder();
    $ingredientInRecipe = $queryBuilder
        ->select('*')
        ->from('tx_recipemanagement_domain_model_ingredientinrecipe')
        ->where(
            $queryBuilder->expr()->eq('recipe', $queryBuilder->createNamedParameter($recipeUid, \PDO::PARAM_INT)),
            $queryBuilder->expr()->eq('ingredient', $queryBuilder->createNamedParameter($ingredientId, \PDO::PARAM_INT))
        )
        ->execute()
        ->fetch();  // **Hier fetch() statt fetchAssociative()**

    if (!$ingredientInRecipe) {
        $this->addFlashMessage('Zutat im Rezept nicht gefunden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->redirect('editRecipe', null, null, ['recipeUid' => $recipeUid]);
        return;
    }

    // Die Zutat aus der Tabelle löschen
    $queryBuilder
        ->delete('tx_recipemanagement_domain_model_ingredientinrecipe')
        ->where(
            $queryBuilder->expr()->eq('recipe', $queryBuilder->createNamedParameter($recipeUid, \PDO::PARAM_INT)),
            $queryBuilder->expr()->eq('ingredient', $queryBuilder->createNamedParameter($ingredientId, \PDO::PARAM_INT))
        )
        ->execute();

    // Erfolgreiche Nachricht & Weiterleitung zur Rezept-Bearbeitungsseite
    $this->addFlashMessage('Zutat erfolgreich entfernt.');
    $this->redirect('editRecipe', null, null, ['recipeUid' => $recipeUid]);
}

protected function getTypo3Session(string $key)
{
    return $GLOBALS['TSFE']->fe_user->getKey('ses', $key);
}

protected function setTypo3Session(string $key, $value): void
{
    $GLOBALS['TSFE']->fe_user->setKey('ses', $key, $value);
    $GLOBALS['TSFE']->fe_user->storeSessionData(); // Session speichern
}

protected function removeTypo3Session(string $key): void
{
    $GLOBALS['TSFE']->fe_user->setKey('ses', $key, null);
    $GLOBALS['TSFE']->fe_user->storeSessionData();
}




}

