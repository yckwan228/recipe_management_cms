<?php
defined('TYPO3_MODE') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'RecipeManagement',
        'web',
        'recipes',
        '',
        [
            \Recipes\RecipeManagement\Controller\BenutzerController::class => 'list',
        ],
        [
            'access' => 'user,group',
            'icon'   => 'EXT:recipe_management/Resources/Public/Icons/user_mod_recipes.svg',
            'labels' => 'LLL:EXT:recipe_management/Resources/Private/Language/locallang_recipes.xlf',
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_recipemanagement_domain_model_benutzer', 'EXT:recipe_management/Resources/Private/Language/locallang_csh_tx_recipemanagement_domain_model_benutzer.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_recipemanagement_domain_model_benutzer');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_recipemanagement_domain_model_recipe', 'EXT:recipe_management/Resources/Private/Language/locallang_csh_tx_recipemanagement_domain_model_recipe.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_recipemanagement_domain_model_recipe');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_recipemanagement_domain_model_ingredientinrecipe', 'EXT:recipe_management/Resources/Private/Language/locallang_csh_tx_recipemanagement_domain_model_ingredientinrecipe.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_recipemanagement_domain_model_ingredientinrecipe');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_recipemanagement_domain_model_ingredientgeneral', 'EXT:recipe_management/Resources/Private/Language/locallang_csh_tx_recipemanagement_domain_model_ingredientgeneral.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_recipemanagement_domain_model_ingredientgeneral');
})();
