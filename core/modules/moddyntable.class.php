<?php
/**
* \defgroup	lead	Lead module
* \brief		Lead module descriptor.
* \file		core/modules/modLead.class.php
* \ingroup	lead
* \brief		Description and activation file for module Lead
*/
include_once DOL_DOCUMENT_ROOT . "/core/modules/DolibarrModules.class.php";

/**
 * Description and activation class for module Lead
 */
class moddyntable extends DolibarrModules
{
	public function __construct($db)
	{
		global $langs, $conf;

		$this->db = $db;
		$this->numero = 250909;
		$this->rights_class = 'dyntable';
		$this->family = "technic";
		$this->name = preg_replace('/^mod/i', '', get_class($this));
		$this->description = "Module de création de liste a partir de requete SQL";
		$this->version = '1.0';
		$this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
		$this->special = 1;
		$this->picto = 'list@dyntable';
		$this->module_parts = array(
				// 'triggers' => 1,
				// 'login' => 0,
				// 'substitutions' => 0,
				// 'menus' => 0,
				// 'barcode' => 0,
				//'models' => 1,
				//'tpl' => 1,
				// 'css' => '/lead/css/mycss.css.php',
				//'js'=>'/volvo/js/jquery.flot.orderBars.js',
				//'hooks' => array('ordercard','ordersuppliercard','thirdpartycard'),
				// 'workflow' => array('order' => array('WORKFLOW_ORDER_AUTOCREATE_INVOICE'))
		);

		$this->dirs = array(
				'/dyntable',
		);

		$this->config_page_url = array(
				"index.php@dyntable"
		);

		$this->depends = array();
		$this->requiredby = array();
		$this->need_dolibarr_version = array(
				4,
				0
		);

		$this->langfiles = array(
			"dyntable@dyntable"
		);

		$this->const = array(
// 				0 => array(
// 						'MAIN_CAN_HIDE_EXTRAFIELDS',
// 						'chaine',
// 						'1',
// 						'can hiden extrafiled',
// 						0,
// 						'current',
// 						1
// 				),
// 				1 => array(
// 						'COMMANDE_ADDON_PDF',
// 						'chaine',
// 						'analysevolvo',
// 						'',
// 						1,
// 						'current',
// 						1
// 				),
		);

		$this->tabs = array(

		);

		$this->dictionnaries = array(
				'langs' => 'volvo@volvo',
				'tabname' => array(
// 						MAIN_DB_PREFIX . "c_volvo_bv",
// 						MAIN_DB_PREFIX . "c_volvo_cabine",

				),
				'tablib' => array(
// 						"Volvo -- boites de vitesse",
// 						"Volvo -- Types de cabines",
				),
				'tabsql' => array(
// 						'SELECT f.rowid as rowid, f.bv as nom, f.active FROM ' . MAIN_DB_PREFIX . 'c_volvo_bv as f',
// 						'SELECT f.rowid as rowid, f.cabine as nom, f.active FROM ' . MAIN_DB_PREFIX . 'c_volvo_cabine as f',
				),
				'tabsqlsort' => array(
// 						'rowid ASC',
// 						'rowid ASC',
				),
				'tabfield' => array(
// 						"nom",
// 						"nom,labelexcel",
				),
				'tabfieldvalue' => array(
// 						"nom",
// 						"nom,labelexcel",
				),
				'tabfieldinsert' => array(
// 						"bv",
// 						"cabine",
				),
				'tabrowid' => array(
// 						"rowid",
// 						"rowid",
				),
				'tabcond' => array(
// 						'$conf->volvo->enabled',
// 						'$conf->volvo->enabled',
				)
		);


		$this->boxes = array(); // Boxes list
		$r = 0;

// 		$this->boxes[$r][1] = "box_pdmsoltrs_indiv@volvo";
// 		$r ++;

		// Permissions
		$this->rights = array(); // Permission array used by this module
		$r = 0;
		$this->rights[$r][0] = 2509090;
		$this->rights[$r][1] = 'Administration des listes';
		$this->rights[$r][3] = 1;
		$this->rights[$r][4] = 'admin';
		$r ++;

		$this->menus = array(); // List of menus to add
		$r = 0;

		$this->menu[$r] = array(
				'fk_menu' => 0,
				'type' => 'top',
				'titre' => 'Tableaux',
				'mainmenu' => 'dyntable',
				'url' => '/dyntable/dyntable_list.php',
				'langs' => 'dyntable@dyntable',
				'position' => 100,
				'enabled' => '1',
				'perms' => '1',
				'target' => '',
				'user' => 0
		);
		$r ++;

	}


	/**
	 * Function called when module is enabled.
	 * The init function add constants, boxes, permissions and menus
	 * (defined in constructor) into Dolibarr database.
	 * It also creates data directories
	 *
	 * @param string $options Enabling module ('', 'noboxes')
	 * @return int if OK, 0 if KO
	 */
	public function init($options = '')
	{
		global $conf;

		$sql = array();

		$result = $this->loadTables();

		return $this->_init($sql, $options);
	}

	/**
	 * Function called when module is disabled.
	 * Remove from database constants, boxes and permissions from Dolibarr database.
	 * Data directories are not deleted
	 *
	 * @param string $options Enabling module ('', 'noboxes')
	 * @return int if OK, 0 if KO
	 */
	public function remove($options = '')
	{
		$sql = array();

		return $this->_remove($sql, $options);
	}

	/**
	 * Create tables, keys and data required by module
	 * Files llx_table1.sql, llx_table1.key.sql llx_data.sql with create table, create keys
	 * and create data commands must be stored in directory /lead/sql/
	 * This function is called by this->init
	 *
	 * @return int if KO, >0 if OK
	 */
	private function loadTables()
	{
		return $this->_load_tables('/dyntable/sql/');
	}
}