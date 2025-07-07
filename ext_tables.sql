CREATE TABLE tx_recipemanagement_domain_model_benutzer (
    uid int(11) NOT NULL AUTO_INCREMENT,
    benutzer_name varchar(255) NOT NULL DEFAULT '',
    benutzer_password varchar(255) NOT NULL DEFAULT '',
    benutzer_email varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (uid)
);

CREATE TABLE tx_recipemanagement_domain_model_recipe (
    uid int(11) NOT NULL AUTO_INCREMENT,
    recipe_name varchar(255) NOT NULL DEFAULT '',
    total_calories int(11) NOT NULL DEFAULT '0',
    benutzer int(11) unsigned DEFAULT NULL,
    PRIMARY KEY (uid),
    CONSTRAINT fk_recipe_benutzer FOREIGN KEY (benutzer) 
        REFERENCES tx_recipemanagement_domain_model_benutzer(uid) 
        ON DELETE CASCADE
);

CREATE TABLE tx_recipemanagement_domain_model_ingredientinrecipe (
    uid int(11) NOT NULL AUTO_INCREMENT,
    recipe int(11) DEFAULT NULL,
    ingredient int(11) DEFAULT NULL,
    quantity_in_gram int(11) DEFAULT '0' NOT NULL,
    PRIMARY KEY (uid),
    CONSTRAINT fk_ingredient_recipe FOREIGN KEY (recipe) 
        REFERENCES tx_recipemanagement_domain_model_recipe(uid) 
        ON DELETE CASCADE,
    CONSTRAINT fk_ingredient_general FOREIGN KEY (ingredient) 
        REFERENCES tx_recipemanagement_domain_model_ingredientgeneral(uid) 
        ON DELETE CASCADE
);

CREATE TABLE tx_recipemanagement_domain_model_ingredientgeneral (
    uid int(11) NOT NULL AUTO_INCREMENT,
    ingredient_name varchar(255) NOT NULL DEFAULT '',
    calories_per_gram int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (uid)
);
