Mini projet sous Symfony


## Installation du projet

    - En ligne de commande, il faut utiliser lacommande suivante : 
          
           


## Création d'un utilisateur

    - Pour créer un mot de passe encrypté, utiliser en ligne de commande la commande suivante : 
         
         php bin/console security:hash-password

    Entrer le mot de passe désiré, puis copié la valeur retourné

    Créer ensuite un nouvel utilisateur dans la table 'User', soit directment dans mySQL avec la synthaxe suivante : 

        INSERT INTO `user`(`id`,`username`,`roles`,`password`,`email`) VALUES(1,'tata','[]',    '$2y$13$BpGoUkKuXfWQBZSYC3VXCOVFmZnT1mXM5a4dHGcS1fKKDAc25FdUe','tata@tata.fr');
    
    soit depuis phpMyAdmin, soit depuis l'outil de gestion de base de données de VIsual Studio Code.