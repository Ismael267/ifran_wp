<?php

/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'ifran_wp' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', '' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define( 'DB_COLLATE', '' );

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'e[!8~~dKREf#TtvJMOsYRN_H3c~ed<!a(_/J(R9m3>/;B;R!5bpKs`,/k}$aLx  ' );
define( 'SECURE_AUTH_KEY',  'k;n;>P+fc^x,bXh+;>U$tsm[W;VeSs)RcIdi@n`O;ER;T6I+@+Py6n}7tPQVJ`Ur' );
define( 'LOGGED_IN_KEY',    'j64aZ?fMjMib HpS^40xGOPHg<*G_Wylu!L7`@A|Gv5|-.rWg80il:RA-CJ}(Khs' );
define( 'NONCE_KEY',        '#~>3HHHf2:d<+)u}0nh!kgH[l5LQBtXsWeq8}b[tX6u_*~RZt9R[uf+F<>2Jj.r[' );
define( 'AUTH_SALT',        'v1Vc&{gr:+y5=p?$f-6qG$;Vp~@7$ANP=+!vQWN@[9MaRAD,|S}Ul>I2@9<G4? {' );
define( 'SECURE_AUTH_SALT', 'qr.b-J`^hg4-7gPan(CleuU4R=RMlE`~B.|;cj)r)sOi>c^3 8Q}d1&`#Q!vax;5' );
define( 'LOGGED_IN_SALT',   '`RYW2!wMA@@S`mEwU%u]wdPMyZ?EV+]g-n`D%dDG.o+*7THIhtZo.6ivBYc_JfY[' );
define( 'NONCE_SALT',       'w5;I8.p2-~vYz#<f.ip*7XQpH+=GD;iDm_T#Zae>`:AU<n.j4tx<68kT6]B+Bh(x' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortement recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );


