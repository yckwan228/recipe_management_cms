<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Controller;

use TYPO3\CMS\Extbase\Annotation as Extbase;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * BenutzerController
 */
class BenutzerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var \Recipes\RecipeManagement\Domain\Repository\BenutzerRepository
     */
    protected $benutzerRepository;

    /**
     * @var \Recipes\RecipeManagement\Domain\Repository\RecipeRepository
     */
    protected $recipeRepository;

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
     * Injects the Benutzer repository
     *
     * @param \Recipes\RecipeManagement\Domain\Repository\BenutzerRepository $benutzerRepository
     */
    public function injectBenutzerRepository(\Recipes\RecipeManagement\Domain\Repository\BenutzerRepository $benutzerRepository): void
    {
        $this->benutzerRepository = $benutzerRepository;
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
     * action list
     * Zeigt eine Liste aller Benutzer
     * 
     */

     private function ensureUserIsLoggedIn(): void
{
    if (!$GLOBALS['TSFE']->fe_user->getKey('ses', 'loggedInUser')) {
        $this->redirect('login');
    }
}

    private function ensureUserHasAccessTo(\Recipes\RecipeManagement\Domain\Model\Benutzer $benutzer): void
{
    // Hole die UID des aktuell eingeloggten Benutzers aus der Session
    $loggedInUserId = $GLOBALS['TSFE']->fe_user->getKey('ses', 'loggedInUser');

    // Überprüfe, ob die UID des aktuellen Benutzers mit der UID des angeforderten Benutzers übereinstimmt
    if ($benutzer->getUid() !== (int)$loggedInUserId) {
        // Zugriff verweigert: Umleiten und Fehlermeldung anzeigen
        $this->addFlashMessage('Zugriff verweigert. Sie können nur Ihr eigenes Konto bearbeiten.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->redirect('dashboard'); // Zurück zum Dashboard oder zu einer sicheren Seite
    }
}

public function listAction(): void
{
    $this->ensureUserIsLoggedIn(); // Überprüft, ob der Benutzer eingeloggt ist

    echo "TEST";
    // Hole den aktuell eingeloggten Benutzer
    $userId = $GLOBALS['TSFE']->fe_user->getKey('ses', 'loggedInUser');
    $benutzer = $this->benutzerRepository->findByUid($userId);

    if ($benutzer) {
        $this->view->assign('benutzers', [$benutzer]); // Liste enthält nur den eingeloggten Benutzer
    } else {
        $this->redirect('login'); // Wenn kein Benutzer gefunden wird, leite zum Login um
    }
} 

    /**
     * action show
     * Zeigt Details eines Benutzers an
     *
     * @param \Recipes\RecipeManagement\Domain\Model\Benutzer $benutzer
     */
    public function showAction(\Recipes\RecipeManagement\Domain\Model\Benutzer $benutzer): void
    {
        $this->ensureUserIsLoggedIn(); // Überprüft, ob der Benutzer eingeloggt ist
        $this->ensureUserHasAccessTo($benutzer); // Überprüft, ob der Benutzer Zugriff hat
    
        $this->view->assign('benutzer', $benutzer);
    }
    
    /**
     * action new
     * Erstellt ein neues Benutzerformular
     */
    public function newAction(): void
    {
        $newBenutzer = new \Recipes\RecipeManagement\Domain\Model\Benutzer();
        $this->view->assign('newBenutzer', $newBenutzer);
    }
    
    public function registerAction(\Recipes\RecipeManagement\Domain\Model\Benutzer $newBenutzer = null): void
    {
        if ($newBenutzer === null) {
            $newBenutzer = $this->objectManager->get(\Recipes\RecipeManagement\Domain\Model\Benutzer::class);
        }
        $this->view->assign('newBenutzer', $newBenutzer);
    }

    /**
     * action create
     * Speichert einen neuen Benutzer
     */
    public function createAction(\Recipes\RecipeManagement\Domain\Model\Benutzer $newBenutzer): void
{
    //Passwort hashen
    $hashedPassword = password_hash($newBenutzer->getBenutzerPassword(), PASSWORD_DEFAULT);
    $newBenutzer->setBenutzerPassword($hashedPassword);

    // Benutzer speichern
    $this->benutzerRepository->add($newBenutzer);

    // Persistenz erzwingen
    $this->persistenceManager->persistAll();

    // Flash-Nachricht hinzufügen
    $this->addFlashMessage('Dein Konto wurde erfolgreich erstellt. Bitte logge dich ein.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);

    // Weiterleitung zur Login-Seite (NICHT zur showAction)
    $this->redirect('login');
}

    

    public function loginAction(string $benutzerName = '', string $password = ''): void
    {
        if ($benutzerName && $password) {
            $benutzer = $this->benutzerRepository->findOneByBenutzerName($benutzerName);
    
            if ($benutzer && password_verify($password, $benutzer->getBenutzerPassword())) {
                $GLOBALS['TSFE']->fe_user->setKey('ses', 'loggedInUser', $benutzer->getUid());
                $GLOBALS['TSFE']->fe_user->storeSessionData();
    
                // Erfolgreiches Login: Weiterleitung zum Dashboard
                $this->redirect('dashboard');
            } else {
                $this->addFlashMessage(
                    'Ungültige Anmeldedaten. Bitte versuchen Sie es erneut.',
                    '',
                    \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
                );
            }
        }
    
        // Zeige Login-Seite an
        $this->view->assign('message', 'Bitte loggen Sie sich ein.');
    }

    /**
     * action edit
     * Zeigt ein Bearbeitungsformular für einen Benutzer an
     */
    public function editAction(int $uid): void
    {
        $benutzer = $this->benutzerRepository->findByUid($uid);
    
        if ($benutzer === null) {
            $this->addFlashMessage(
                'Benutzer konnte nicht gefunden werden.',
                '',
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            $this->redirect('dashboard');
        }
    
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($benutzer);
    
        $this->view->assign('benutzer', $benutzer);
    }
    


    
     /* action update
     /* Speichert Änderungen an einem Benutzer

 /**
 */
public function updateAction(\Recipes\RecipeManagement\Domain\Model\Benutzer $benutzer): void
{
    // Debugging der POST-Daten
    \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($_POST);

    if ($benutzer === null || $benutzer->getUid() === null) {
        $this->addFlashMessage(
            'Das Benutzerobjekt wurde nicht korrekt übergeben.',
            '',
            \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
        );
        $this->redirect('dashboard');
    }

     
    // Prüfe, ob das Passwort geändert wurde oder neu eingegeben wurde
    $aktuellesPasswort = $this->benutzerRepository->findByUid($benutzer->getUid())->getBenutzerPassword();
    if (!password_verify($benutzer->getBenutzerPassword(), $aktuellesPasswort)) {
        // Wenn das Passwort geändert wurde oder im Klartext vorliegt, hashe es
        $benutzer->setBenutzerPassword(password_hash($benutzer->getBenutzerPassword(), PASSWORD_DEFAULT));
    }

    // Aktualisiere das Benutzerobjekt
    $this->benutzerRepository->update($benutzer);
    $this->persistenceManager->persistAll();

    $this->addFlashMessage('Der Benutzer wurde erfolgreich aktualisiert.');
    $this->redirect('dashboard');
}


    /**
     * action delete
     * Löscht einen Benutzer
     */
    public function deleteAction(int $benutzerUid): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_recipemanagement_domain_model_benutzer');
    
        $queryBuilder = $connection->createQueryBuilder();
    
        // 🛠 Alle Rezepte des Benutzers abrufen
        $recipeQueryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_recipemanagement_domain_model_recipe');
    
            $recipes = $recipeQueryBuilder
            ->select('uid')
            ->from('tx_recipemanagement_domain_model_recipe')
            ->where(
                $recipeQueryBuilder->expr()->eq('benutzer', $recipeQueryBuilder->createNamedParameter($benutzerUid, \PDO::PARAM_INT))
            )
            ->execute()
            ->fetchAll(); // Kompatibel mit älteren TYPO3-Versionen
    
        //Debugging: Prüfen, ob Rezept-IDs vorhanden sind
        $recipeUids = array_column($recipes, 'uid');
        
        if (empty($recipeUids)) {
            $this->addFlashMessage('Kein Rezept gefunden – Löschen nur für Benutzer.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        } else {
            // 🛠 Zutaten der Rezepte löschen
            $ingredientQueryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_recipemanagement_domain_model_ingredientinrecipe');
    
            $ingredientQueryBuilder
                ->delete('tx_recipemanagement_domain_model_ingredientinrecipe')
                ->where(
                    $ingredientQueryBuilder->expr()->in('recipe', $recipeUids)
                )
                ->execute();
    
            // 🛠 Rezepte löschen
            $recipeQueryBuilder
                ->delete('tx_recipemanagement_domain_model_recipe')
                ->where(
                    $recipeQueryBuilder->expr()->in('uid', $recipeUids)
                )
                ->execute();
        }
    
        //  Benutzer löschen
        $affectedRows = $queryBuilder
            ->delete('tx_recipemanagement_domain_model_benutzer')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($benutzerUid, \PDO::PARAM_INT))
            )
            ->execute();
    
        if ($affectedRows > 0) {
            $this->addFlashMessage('Ihr Konto und alle zugehörigen Rezepte wurden erfolgreich gelöscht.');
        } else {
            $this->addFlashMessage('Das Konto konnte nicht gefunden oder gelöscht werden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        }
    
        //  Weiterleitung zur Startseite
        $this->redirect('new');
    }
    

    
    public function dashboardAction(): void
    {
        $userId = $GLOBALS['TSFE']->fe_user->getKey('ses', 'loggedInUser');
        if (!$userId) {
            $this->redirect('login'); // Umleitung zur Login-Seite
        }
    
        $benutzer = $this->benutzerRepository->findByUid($userId);
    
        if ($benutzer) {
            $recipes = $this->recipeRepository->findByBenutzer($benutzer);
    
            $this->view->assignMultiple([
                'benutzer' => $benutzer,
                'recipes' => $recipes,
            ]);
        } else {
            $this->addFlashMessage('Benutzer nicht gefunden. Bitte erneut anmelden.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
            $this->redirect('login'); // Umleitung zurück zur Login-Seite
        }
    }

    public function logoutAction(): void
    {
        // Session löschen
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'loggedInUser', null);
        $GLOBALS['TSFE']->fe_user->storeSessionData();
    
        // Weiterleitung zur Registrierungs-/Startseite
        $this->redirect('login');
    }
    


}
