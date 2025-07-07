<?php
defined('TYPO3_MODE') || die();

(static function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'RecipeManagement',
        'Recipes',
        [
            // BenutzerController: Cachebare Aktionen
           \Recipes\RecipeManagement\Controller\BenutzerController::class => 'login, new, register, create, logout, show, edit, update, dashboard, delete',
            // RecipeController: Cachebare Aktionen
            \Recipes\RecipeManagement\Controller\RecipeController::class => 'listRecipe, newRecipe, createRecipe, editRecipe, updateRecipe, deleteRecipe, showRecipe, listIngredient, addIngredient, removeIngredient, listIngredientEditRecipe, addIngredientEditRecipe, removeIngredientEditRecipe',
        ],
        // Non-cacheable actions
        [
            // BenutzerController: Nicht-cachebare Aktionen
            \Recipes\RecipeManagement\Controller\BenutzerController::class => 'login, new, register, create, logout, edit, update, dashboard, delete',
            // RecipeController: Nicht-cachebare Aktionen
            \Recipes\RecipeManagement\Controller\RecipeController::class => 'updateRecipe, deleteRecipe, showRecipe, removeIngredien, listIngredientEditRecipe, addIngredientEditRecipe, removeIngredientEditRecipe',
        ]

        // [
        //     \Recipes\RecipeManagement\Controller\BenutzerController::class => 'register, login, logout, dashboard, list, show, new, create, edit, update, delete, newRecipe, createRecipe, editRecipe, updateRecipe, deleteRecipe',                                                                  
        //     \Recipes\RecipeManagement\Controller\RecipeController::class =>  'newRecipe, createRecipe, editRecipe, updateRecipe, deleteRecipe, showRecipe, showRecipeList, listRecipe',
        // ],
        // // Non-cacheable actions
        // [
        //     \Recipes\RecipeManagement\Controller\BenutzerController::class => 'register, login, logout, dashboard, list, show, new, create, edit, update, delete, newRecipe, createRecipe, editRecipe, updateRecipe, deleteRecipe',                                                               
        //     \Recipes\RecipeManagement\Controller\RecipeController::class => 'newRecipe, createRecipe, editRecipe, updateRecipe, deleteRecipe, showRecipe, showRecipeList, listRecipe',
        // ]
    );

    // Wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    recipes {
                        iconIdentifier = recipe_management-plugin-recipes
                        title = LLL:EXT:recipe_management/Resources/Private/Language/locallang_db.xlf:tx_recipe_management_recipes.name
                        description = LLL:EXT:recipe_management/Resources/Private/Language/locallang_db.xlf:tx_recipe_management_recipes.description
                        tt_content_defValues {
                            CType = list
                            list_type = recipemanagement_recipes
                        }
                    }
                }
                show = *
            }
        }'
    );

    // Icon Registration
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'recipe_management-plugin-recipes',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:recipe_management/Resources/Public/Icons/user_plugin_recipes.svg']
    );
})();
