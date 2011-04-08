<?php
/** 
 * As configurações básicas do WordPress.
 *
 * Esse arquivo contém as seguintes configurações: configurações de MySQL, Prefixo de Tabelas,
 * Chaves secretas, Idioma do WordPress, e ABSPATH. Você pode encontrar mais informações
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. Você pode obter as configuraçções de MySQL de seu servidor de hospedagem.
 *
 * Esse arquivo é usado pelo script ed criação wp-config.php durante a
 * instalação. Você não precisa usar o site, você pode apenas salvar esse arquivo
 * como "wp-config.php" e preencher os valores.
 *
 * @package WordPress
 */

define('DOMAIN_CURRENT_SITE', 'localhost/toquenobrasil');

// ** Configurações do MySQL - Você pode pegar essas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'toquenobrasil');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', '');

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Conjunto de caracteres do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8');

/** O tipo de collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

########################################
## NÂO OUSE TIRAR ISSO DAQUI!! ##
#define('COOKIE_DOMAIN', '.tnb.art.br');
########################################

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer cookies existentes. Isto irá forçar todos os usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'd8oJSPz~A8Mg@!|:GEl8P`[I+Ld.V,r;^q{^jlcp$7wOM*yQj_xL#5R[/Bqc+2+x');
define('SECURE_AUTH_KEY',  'Pm*PPh3b&*%<4RXo0}32}MHsRpjuad%PZ-;(sHr(t JD-c[&4G~&&`p8l&tK%R;I');
define('LOGGED_IN_KEY',    'AfJhx-Fin!3X^iUOkJ~Urr$<Qw@<!vx]?,nR6nnOC:Nu~)P$Y/3>P_vtclq&F%yi');
define('NONCE_KEY',        '}7CIV,T^=LpLHI9]Az!,Le@(8);of51j:fP/)3D ZS}jD8jU*d:(Xf_`x`Z:K<;4');
define('AUTH_SALT',        'k/6+V-S$K-}^@,zjfx-F~X$*u50^R69>-ZGQBa$-h9dCKLrLgOOw4>mp1|!?f$8T');
define('SECURE_AUTH_SALT', '=rv{>U$G!4Bg/{=:Mj07R({J$o_aSt,cJ|_g7-&YaYN[H!O|-b?9+2,ROm]Q<?|D');
define('LOGGED_IN_SALT',   '!NNyuS[d>.Y#V5qcZcX~W:|({|ylq!,m=1DV(mzd_jq6/>zFq|X:0>;(m{VbRr_x');
define('NONCE_SALT',       ';_w(@v|DVt0-+%0W$<0G=^u+U^UiEyI9k@pBc1OCsX}|$.vY</9w_K#1{b+_ZCTw');


/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der para cada um um único
 * prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';

/**
 * O idioma localizado do WordPress é o inglês por padrão.
 *
 * Altere esta definição para localizar o WordPress. Um arquivo MO correspondente a
 * língua escolhida deve ser instalado em wp-content/languages. Por exemplo, instale
 * pt_BR.mo em wp-content/languages e altere WPLANG para 'pt_BR' para habilitar o suporte
 * ao português do Brasil.
 */
define ('WPLANG', 'pt_BR');

/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * altere isto para true para ativar a exibição de avisos durante o desenvolvimento.
 * é altamente recomendável que os desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seuas ambientes de desenvolvimento.
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
/** Configura as variáveis do WordPress e arquivos inclusos. */
require_once(ABSPATH . 'wp-settings.php');
