<?php

class ModelCatalogBlogcategory extends Model {

	public function addBlogcategory($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "blogcategory SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");



		$blogcategory_id = $this->db->getLastId();



		if (isset($data['image'])) {

			$this->db->query("UPDATE " . DB_PREFIX . "blogcategory SET image = '" . $this->db->escape($data['image']) . "' WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");

		}



		foreach ($data['blogcategory_description'] as $language_id => $value) {

			$this->db->query("INSERT INTO " . DB_PREFIX . "blogcategory_description SET blogcategory_id = '" . (int)$blogcategory_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");

		}



		// MySQL Hierarchical Data Closure Table Pattern

		$level = 0;



		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blogcategory_path` WHERE blogcategory_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");



		foreach ($query->rows as $result) {

			$this->db->query("INSERT INTO `" . DB_PREFIX . "blogcategory_path` SET `blogcategory_id` = '" . (int)$blogcategory_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");



			$level++;

		}



		$this->db->query("INSERT INTO `" . DB_PREFIX . "blogcategory_path` SET `blogcategory_id` = '" . (int)$blogcategory_id . "', `path_id` = '" . (int)$blogcategory_id . "', `level` = '" . (int)$level . "'");



		if (isset($data['blogcategory_filter'])) {

			foreach ($data['blogcategory_filter'] as $filter_id) {

				$this->db->query("INSERT INTO " . DB_PREFIX . "blogcategory_filter SET blogcategory_id = '" . (int)$blogcategory_id . "', filter_id = '" . (int)$filter_id . "'");

			}

		}



		if (isset($data['blogcategory_store'])) {

			foreach ($data['blogcategory_store'] as $store_id) {

				$this->db->query("INSERT INTO " . DB_PREFIX . "blogcategory_to_store SET blogcategory_id = '" . (int)$blogcategory_id . "', store_id = '" . (int)$store_id . "'");

			}

		}



		// Set which layout to use with this category

		if (isset($data['blogcategory_layout'])) {

			foreach ($data['blogcategory_layout'] as $store_id => $layout_id) {

				$this->db->query("INSERT INTO " . DB_PREFIX . "blogcategory_to_layout SET blogcategory_id = '" . (int)$blogcategory_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");

			}

		}



		if (isset($data['keyword'])) {

			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'blogcategory_id=" . (int)$blogcategory_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");

		}



		$this->cache->delete('blogcategory');



		return $blogcategory_id;

	}



	public function editBlogcategory($blogcategory_id, $data) {

		$acb = $this->db->query("UPDATE " . DB_PREFIX . "blogcategory SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");

		

		if (isset($data['image'])) {

			$this->db->query("UPDATE " . DB_PREFIX . "blogcategory SET image = '" . $this->db->escape($data['image']) . "' WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");

		}



		$this->db->query("DELETE FROM " . DB_PREFIX . "blogcategory_description WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");



		foreach ($data['blogcategory_description'] as $language_id => $value) {

			$this->db->query("INSERT INTO " . DB_PREFIX . "blogcategory_description SET blogcategory_id = '" . (int)$blogcategory_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");

		}



		// MySQL Hierarchical Data Closure Table Pattern

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blogcategory_path` WHERE path_id = '" . (int)$blogcategory_id . "' ORDER BY level ASC");



		if ($query->rows) {

			foreach ($query->rows as $blogcategory_path) {

				// Delete the path below the current one

				$this->db->query("DELETE FROM `" . DB_PREFIX . "blogcategory_path` WHERE blogcategory_id = '" . (int)$blogcategory_path['blogcategory_id'] . "' AND level < '" . (int)$blogcategory_path['level'] . "'");



				$path = array();



				// Get the nodes new parents

				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blogcategory_path` WHERE blogcategory_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");



				foreach ($query->rows as $result) {

					$path[] = $result['path_id'];

				}



				// Get whats left of the nodes current path

				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blogcategory_path` WHERE blogcategory_id = '" . (int)$blogcategory_path['blogcategory_id'] . "' ORDER BY level ASC");



				foreach ($query->rows as $result) {

					$path[] = $result['path_id'];

				}



				// Combine the paths with a new level

				$level = 0;



				foreach ($path as $path_id) {

					$this->db->query("REPLACE INTO `" . DB_PREFIX . "blogcategory_path` SET blogcategory_id = '" . (int)$blogcategory_path['blogcategory_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");



					$level++;

				}

			}

		} else {

			// Delete the path below the current one

			$this->db->query("DELETE FROM `" . DB_PREFIX . "blogcategory_path` WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");



			// Fix for records with no paths

			$level = 0;



			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blogcategory_path` WHERE blogcategory_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");



			foreach ($query->rows as $result) {

				$this->db->query("INSERT INTO `" . DB_PREFIX . "blogcategory_path` SET blogcategory_id = '" . (int)$blogcategory_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");



				$level++;

			}



			$this->db->query("REPLACE INTO `" . DB_PREFIX . "blogcategory_path` SET blogcategory_id = '" . (int)$blogcategory_id . "', `path_id` = '" . (int)$blogcategory_id . "', level = '" . (int)$level . "'");

		}



		$this->db->query("DELETE FROM " . DB_PREFIX . "blogcategory_filter WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");



		if (isset($data['blogcategory_filter'])) {

			foreach ($data['blogcategory_filter'] as $filter_id) {

				$this->db->query("INSERT INTO " . DB_PREFIX . "blogcategory_filter SET blogcategory_id = '" . (int)$blogcategory_id . "', filter_id = '" . (int)$filter_id . "'");

			}

		}



		$this->db->query("DELETE FROM " . DB_PREFIX . "blogcategory_to_store WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");



		if (isset($data['blogcategory_store'])) {

			foreach ($data['blogcategory_store'] as $store_id) {

				$this->db->query("INSERT INTO " . DB_PREFIX . "blogcategory_to_store SET blogcategory_id = '" . (int)$blogcategory_id . "', store_id = '" . (int)$store_id . "'");

			}

		}



		$this->db->query("DELETE FROM " . DB_PREFIX . "blogcategory_to_layout WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");



		if (isset($data['blogcategory_layout'])) {

			foreach ($data['blogcategory_layout'] as $store_id => $layout_id) {

				$this->db->query("INSERT INTO " . DB_PREFIX . "blogcategory_to_layout SET blogcategory_id = '" . (int)$blogcategory_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");

			}

		}



		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'blogcategory_id=" . (int)$blogcategory_id . "'");



		if ($data['keyword']) {

			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'blogcategory_id=" . (int)$blogcategory_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");

		}



		$this->cache->delete('blogcategory');

	}



	public function deleteBlogcategory($blogcategory_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogcategory_path WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");



		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogcategory_path WHERE path_id = '" . (int)$blogcategory_id . "'");



		foreach ($query->rows as $result) {

			$this->deleteCategory($result['blogcategory_id']);

		}



		$this->db->query("DELETE FROM " . DB_PREFIX . "blogcategory WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogcategory_description WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogcategory_filter WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogcategory_to_store WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogcategory_to_layout WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_to_blogcategory WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'blogcategory_id=" . (int)$blogcategory_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_blogcategory WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");



		$this->cache->delete('blogcategory');

	}



	public function repairBlogcategories($parent_id = 0) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogcategory WHERE parent_id = '" . (int)$parent_id . "'");



		foreach ($query->rows as $blogcategory) {

			// Delete the path below the current one

			$this->db->query("DELETE FROM `" . DB_PREFIX . "blogcategory_path` WHERE blogcategory_id = '" . (int)$blogcategory['blogcategory_id'] . "'");



			// Fix for records with no paths

			$level = 0;



			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blogcategory_path` WHERE blogcategory_id = '" . (int)$parent_id . "' ORDER BY level ASC");



			foreach ($query->rows as $result) {

				$this->db->query("INSERT INTO `" . DB_PREFIX . "blogcategory_path` SET blogcategory_id = '" . (int)$blogcategory['blogcategory_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");



				$level++;

			}



			$this->db->query("REPLACE INTO `" . DB_PREFIX . "blogcategory_path` SET blogcategory_id = '" . (int)$blogcategory['blogcategory_id'] . "', `path_id` = '" . (int)$blogcategory['blogcategory_id'] . "', level = '" . (int)$level . "'");



			$this->repairBlogcategories($blogcategory['blogcategory_id']);

		}

	}



	public function getBlogcategory($blogcategory_id) {

		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "blogcategory_path cp LEFT JOIN " . DB_PREFIX . "blogcategory_description cd1 ON (cp.path_id = cd1.blogcategory_id AND cp.blogcategory_id != cp.path_id) WHERE cp.blogcategory_id = c.blogcategory_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.blogcategory_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'blogcategory_id=" . (int)$blogcategory_id . "') AS keyword FROM " . DB_PREFIX . "blogcategory c LEFT JOIN " . DB_PREFIX . "blogcategory_description cd2 ON (c.blogcategory_id = cd2.blogcategory_id) WHERE c.blogcategory_id = '" . (int)$blogcategory_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");



		return $query->row;

	}



	public function getBlogcategories($data = array()) {

		$sql = "SELECT cp.blogcategory_id AS blogcategory_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "blogcategory_path cp LEFT JOIN " . DB_PREFIX . "blogcategory c1 ON (cp.blogcategory_id = c1.blogcategory_id) LEFT JOIN " . DB_PREFIX . "blogcategory c2 ON (cp.path_id = c2.blogcategory_id) LEFT JOIN " . DB_PREFIX . "blogcategory_description cd1 ON (cp.path_id = cd1.blogcategory_id) LEFT JOIN " . DB_PREFIX . "blogcategory_description cd2 ON (cp.blogcategory_id = cd2.blogcategory_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";



		if (!empty($data['filter_name'])) {

			$sql .= " AND cd2.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";

		}



		$sql .= " GROUP BY cp.blogcategory_id";



		$sort_data = array(

			'name',

			'sort_order'

		);



		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {

			$sql .= " ORDER BY " . $data['sort'];

		} else {

			$sql .= " ORDER BY sort_order";

		}



		if (isset($data['order']) && ($data['order'] == 'DESC')) {

			$sql .= " DESC";

		} else {

			$sql .= " ASC";

		}



		if (isset($data['start']) || isset($data['limit'])) {

			if ($data['start'] < 0) {

				$data['start'] = 0;

			}



			if ($data['limit'] < 1) {

				$data['limit'] = 20;

			}



			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];

		}



		$query = $this->db->query($sql);



		return $query->rows;

	}



	public function getBlogcategoryDescriptions($blogcategory_id) {

		$blogcategory_description_data = array();



		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogcategory_description WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");



		foreach ($query->rows as $result) {

			$blogcategory_description_data[$result['language_id']] = array(

				'name'             => $result['name'],

				'meta_title'       => $result['meta_title'],

				'meta_description' => $result['meta_description'],

				'meta_keyword'     => $result['meta_keyword'],

				'description'      => $result['description']

			);

		}



		return $blogcategory_description_data;

	}

	

	public function getBlogcategoryPath($blogcategory_id) {

		$query = $this->db->query("SELECT blogcategory_id, path_id, level FROM " . DB_PREFIX . "blogcategory_path WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");



		return $query->rows;

	}

	

	public function getBlogcategoryFilters($blogcategory_id) {

		$blogcategory_filter_data = array();



		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogcategory_filter WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");



		foreach ($query->rows as $result) {

			$blogcategory_filter_data[] = $result['filter_id'];

		}



		return $blogcategory_filter_data;

	}



	public function getBlogcategoryStores($blogcategory_id) {

		$blogcategory_store_data = array();



		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogcategory_to_store WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");



		foreach ($query->rows as $result) {

			$blogcategory_store_data[] = $result['store_id'];

		}



		return $blogcategory_store_data;

	}



	public function getBlogcategoryLayouts($blogcategory_id) {

		$blogcategory_layout_data = array();



		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogcategory_to_layout WHERE blogcategory_id = '" . (int)$blogcategory_id . "'");



		foreach ($query->rows as $result) {

			$blogcategory_layout_data[$result['store_id']] = $result['layout_id'];

		}



		return $blogcategory_layout_data;

	}



	public function getTotalBlogcategories() {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogcategory");



		return $query->row['total'];

	}

	

	public function getTotalBlogcategoriesByLayoutId($layout_id) {

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogcategory_to_layout WHERE layout_id = '" . (int)$layout_id . "'");



		return $query->row['total'];

	}	

}

