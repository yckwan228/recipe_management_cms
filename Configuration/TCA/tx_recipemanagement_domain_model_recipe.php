<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:recipe_management/Resources/Private/Language/locallang_db.xlf:tx_recipemanagement_domain_model_recipe',
        'label' => 'recipe_name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'recipe_name',
        'iconfile' => 'EXT:recipe_management/Resources/Public/Icons/tx_recipemanagement_domain_model_recipe.gif',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                recipe_name, 
                total_calories, 
                benutzer, 
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, 
                sys_language_uid, l10n_parent, l10n_diffsource, 
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, 
                hidden, starttime, endtime
            ',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple',
                    ],
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_recipemanagement_domain_model_recipe',
                'foreign_table_where' => 'AND {#tx_recipemanagement_domain_model_recipe}.{#pid}=###CURRENT_PID### AND {#tx_recipemanagement_domain_model_recipe}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'recipe_name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:recipe_management/Resources/Private/Language/locallang_db.xlf:tx_recipemanagement_domain_model_recipe.recipe_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
                'default' => '',
            ],
        ],
        'total_calories' => [
            'exclude' => true,
            'label' => 'LLL:EXT:recipe_management/Resources/Private/Language/locallang_db.xlf:tx_recipemanagement_domain_model_recipe.total_calories',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'benutzer' => [
            'exclude' => true,
            'label' => 'LLL:EXT:recipe_management/Resources/Private/Language/locallang_db.xlf:tx_recipemanagement_domain_model_recipe.benutzer',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_recipemanagement_domain_model_benutzer',
                'default' => 0,
                'minitems' => 1,
                'maxitems' => 1,
            ],
        ],

        'portions' => [
    'exclude' => 0,
    'label' => 'LLL:EXT:recipe_management/Resources/Private/Language/locallang_db.xlf:tx_recipemanagement_domain_model_recipe.portions',
    'config' => [
        'type' => 'input',
        'size' => 5,
        'eval' => 'int',
        'default' => 1
    ],
],

    ],
];
