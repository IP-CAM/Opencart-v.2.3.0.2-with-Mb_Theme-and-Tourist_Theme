<?php

class ModelCatalogBlogmega extends Model {

	public function updateViewed($blogmega_id) {

		$this->db->query("UPDATE " . DB_PREFIX . "blogmega SET viewed = (viewed + 1) WHERE blogmega_id = '" . (int)$blogmega_id . "'");

	}



	public function getBlogmega($blogmega_id) {

		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "blogmega_discount pd2 WHERE pd2.blogmega_id = p.blogmega_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "blogmega_special ps WHERE ps.blogmega_id = p.blogmega_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "blogmega_reward pr WHERE pr.blogmega_id = p.blogmega_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.blogmega_id = p.blogmega_id AND r1.status = '1' GROUP BY r1.blogmega_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.blogmega_id = p.blogmega_id AND r2.status = '1' GROUP BY r2.blogmega_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "blogmega p LEFT JOIN " . DB_PREFIX . "blogmega_description pd ON (p.blogmega_id = pd.blogmega_id) LEFT JOIN " . DB_PREFIX . "blogmega_to_store p2s ON (p.blogmega_id = p2s.blogmega_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.blogmega_id = '" . (int)$blogmega_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");



		if ($query->num_rows) {

			return array(

				'blogmega_id'       => $query->row['blogmega_id'],

				'name'             => $query->row['name'],

				'description'      => $query->row['description'],

				'meta_title'       => $query->row['meta_title'],

				'meta_description' => $query->row['meta_description'],

				'meta_keyword'     => $query->row['meta_keyword'],

				'tag'              => $query->row['tag'],

				'model'            => $query->row['model'],

				'sku'              => $query->row['sku'],

				'upc'              => $query->row['upc'],

				'ean'              => $query->row['ean'],

				'jan'              => $query->row['jan'],

				'isbn'             => $query->row['isbn'],

				'mpn'              => $query->row['mpn'],

				'location'         => $query->row['location'],

				'quantity'         => $query->row['quantity'],

				'stock_status'     => $query->row['stock_status'],

				'image'            => $query->row['image'],

				'manufacturer_id'  => $query->row['manufacturer_id'],

				'manufacturer'     => $query->row['manufacturer'],

				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),

				'special'          => $query->row['special'],

				'reward'           => $query->row['reward'],

				'points'           => $query->row['points'],

				'tax_class_id'     => $query->row['tax_class_id'],

				'date_available'   => $query->row['date_available'],

				'weight'           => $query->row['weight'],

				'weight_class_id'  => $query->row['weight_class_id'],

				'length'           => $query->row['length'],

				'width'            => $query->row['width'],

				'height'           => $query->row['height'],

				'length_class_id'  => $query->row['length_class_id'],

				'subtract'         => $query->row['subtract'],

				'rating'           => round($query->row['rating']),

				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,

				'minimum'          => $query->row['minimum'],

				'sort_order'       => $query->row['sort_order'],

				'status'           => $query->row['status'],

				'date_added'       => $query->row['date_added'],

				'date_modified'    => $query->row['date_modified'],

				'viewed'           => $query->row['viewed']

			);

		} else {

			return false;

		}

	}



	public function getBlogmegas($data = array()) {

		$sql = "SELECT p.blogmega_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.blogmega_id = p.blogmega_id AND r1.status = '1' GROUP BY r1.blogmega_id) AS rating, (SELECT price FROM " . DB_PREFIX . "blogmega_discount pd2 WHERE pd2.blogmega_id = p.blogmega_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "blogmega_special ps WHERE ps.blogmega_id = p.blogmega_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";



		if (!empty($data['filter_category_id'])) {

			if (!empty($data['filter_sub_category'])) {

				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "blogmega_to_category p2c ON (cp.category_id = p2c.category_id)";

			} else {

				$sql .= " FROM " . DB_PREFIX . "blogmega_to_category p2c";

			}



			if (!empty($data['filter_filter'])) {

				$sql .= " LEFT JOIN " . DB_PREFIX . "blogmega_filter pf ON (p2c.blogmega_id = pf.blogmega_id) LEFT JOIN " . DB_PREFIX . "blogmega p ON (pf.blogmega_id = p.blogmega_id)";

			} else {

				$sql .= " LEFT JOIN " . DB_PREFIX . "blogmega p ON (p2c.blogmega_id = p.blogmega_id)";

			}

		} else {

			$sql .= " FROM " . DB_PREFIX . "blogmega p";

		}



		$sql .= " LEFT JOIN " . DB_PREFIX . "blogmega_description pd ON (p.blogmega_id = pd.blogmega_id) LEFT JOIN " . DB_PREFIX . "blogmega_to_store p2s ON (p.blogmega_id = p2s.blogmega_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";



		if (!empty($data['filter_category_id'])) {

			if (!empty($data['filter_sub_category'])) {

				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";

			} else {

				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";

			}



			if (!empty($data['filter_filter'])) {

				$implode = array();



				$filters = explode(',', $data['filter_filter']);



				foreach ($filters as $filter_id) {

					$implode[] = (int)$filter_id;

				}



				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";

			}

		}



		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {

			$sql .= " AND (";



			if (!empty($data['filter_name'])) {

				$implode = array();



				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));



				foreach ($words as $word) {

					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";

				}



				if ($implode) {

					$sql .= " " . implode(" AND ", $implode) . "";

				}



				if (!empty($data['filter_description'])) {

					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";

				}

			}



			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {

				$sql .= " OR ";

			}



			if (!empty($data['filter_tag'])) {

				$implode = array();



				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));



				foreach ($words as $word) {

					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";

				}



				if ($implode) {

					$sql .= " " . implode(" AND ", $implode) . "";

				}

			}



			if (!empty($data['filter_name'])) {

				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

			}



			$sql .= ")";

		}



		if (!empty($data['filter_manufacturer_id'])) {

			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";

		}



		$sql .= " GROUP BY p.blogmega_id";



		$sort_data = array(

			'pd.name',

			'p.model',

			'p.quantity',

			'p.price',

			'rating',

			'p.sort_order',

			'p.date_added'

		);



		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {

			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {

				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";

			} elseif ($data['sort'] == 'p.price') {

				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";

			} else {

				$sql .= " ORDER BY " . $data['sort'];

			}

		} else {

			$sql .= " ORDER BY p.sort_order";

		}



		if (isset($data['order']) && ($data['order'] == 'DESC')) {

			$sql .= " DESC, LCASE(pd.name) DESC";

		} else {

			$sql .= " ASC, LCASE(pd.name) ASC";

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



		$blogmega_data = array();



		$query = $this->db->query($sql);



		foreach ($query->rows as $result) {

			$blogmega_data[$result['blogmega_id']] = $this->getBlogmega($result['blogmega_id']);

		}



		return $blogmega_data;

	}



	public function getBlogmegaSpecials($data = array()) {

		$sql = "SELECT DISTINCT ps.blogmega_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.blogmega_id = ps.blogmega_id AND r1.status = '1' GROUP BY r1.blogmega_id) AS rating FROM " . DB_PREFIX . "blogmega_special ps LEFT JOIN " . DB_PREFIX . "blogmega p ON (ps.blogmega_id = p.blogmega_id) LEFT JOIN " . DB_PREFIX . "blogmega_description pd ON (p.blogmega_id = pd.blogmega_id) LEFT JOIN " . DB_PREFIX . "blogmega_to_store p2s ON (p.blogmega_id = p2s.blogmega_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.blogmega_id";



		$sort_data = array(

			'pd.name',

			'p.model',

			'ps.price',

			'rating',

			'p.sort_order'

		);



		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {

			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {

				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";

			} else {

				$sql .= " ORDER BY " . $data['sort'];

			}

		} else {

			$sql .= " ORDER BY p.sort_order";

		}



		if (isset($data['order']) && ($data['order'] == 'DESC')) {

			$sql .= " DESC, LCASE(pd.name) DESC";

		} else {

			$sql .= " ASC, LCASE(pd.name) ASC";

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



		$blogmega_data = array();



		$query = $this->db->query($sql);



		foreach ($query->rows as $result) {

			$blogmega_data[$result['blogmega_id']] = $this->getBlogmega($result['blogmega_id']);

		}



		return $blogmega_data;

	}



	public function getLatestBlogmegas($limit) {

		$blogmega_data = $this->cache->get('blogmega.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);



		if (!$blogmega_data) {

			$query = $this->db->query("SELECT p.blogmega_id FROM " . DB_PREFIX . "blogmega p LEFT JOIN " . DB_PREFIX . "blogmega_to_store p2s ON (p.blogmega_id = p2s.blogmega_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);



			foreach ($query->rows as $result) {

				$blogmega_data[$result['blogmega_id']] = $this->getBlogmega($result['blogmega_id']);

			}



			$this->cache->set('blogmega.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $blogmega_data);

		}



		return $blogmega_data;

	}



	public function getPopularBlogmegas($limit) {

		$blogmega_data = $this->cache->get('blogmega.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);

	

		if (!$blogmega_data) {

			$query = $this->db->query("SELECT p.blogmega_id FROM " . DB_PREFIX . "blogmega p LEFT JOIN " . DB_PREFIX . "blogmega_to_store p2s ON (p.blogmega_id = p2s.blogmega_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed DESC, p.date_added DESC LIMIT " . (int)$limit);

	

			foreach ($query->rows as $result) {

				$blogmega_data[$result['blogmega_id']] = $this->getBlogmega($result['blogmega_id']);

			}

			

			$this->cache->set('blogmega.popular.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $blogmega_data);

		}

		

		return $blogmega_data;

	}



	public function getBestSellerBlogmegas($limit) {

		$blogmega_data = $this->cache->get('blogmega.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);



		if (!$blogmega_data) {

			$blogmega_data = array();



			$query = $this->db->query("SELECT op.blogmega_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_blogmega op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "blogmega` p ON (op.blogmega_id = p.blogmega_id) LEFT JOIN " . DB_PREFIX . "blogmega_to_store p2s ON (p.blogmega_id = p2s.blogmega_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.blogmega_id ORDER BY total DESC LIMIT " . (int)$limit);



			foreach ($query->rows as $result) {

				$blogmega_data[$result['blogmega_id']] = $this->getBlogmega($result['blogmega_id']);

			}



			$this->cache->set('blogmega.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $blogmega_data);

		}



		return $blogmega_data;

	}



	public function getBlogmegaAttributes($blogmega_id) {

		$blogmega_attribute_group_data = array();



		$blogmega_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "blogmega_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.blogmega_id = '" . (int)$blogmega_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");



		foreach ($blogmega_attribute_group_query->rows as $blogmega_attribute_group) {

			$blogmega_attribute_data = array();



			$blogmega_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "blogmega_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.blogmega_id = '" . (int)$blogmega_id . "' AND a.attribute_group_id = '" . (int)$blogmega_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");



			foreach ($blogmega_attribute_query->rows as $blogmega_attribute) {

				$blogmega_attribute_data[] = array(

					'attribute_id' => $blogmega_attribute['attribute_id'],

					'name'         => $blogmega_attribute['name'],

					'text'         => $blogmega_attribute['text']

				);

			}



			$blogmega_attribute_group_data[] = array(

				'attribute_group_id' => $blogmega_attribute_group['attribute_group_id'],

				'name'               => $blogmega_attribute_group['name'],

				'attribute'          => $blogmega_attribute_data

			);

		}



		return $blogmega_attribute_group_data;

	}



	public function getBlogmegaOptions($blogmega_id) {

		$blogmega_option_data = array();



		$blogmega_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.blogmega_id = '" . (int)$blogmega_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");



		foreach ($blogmega_option_query->rows as $blogmega_option) {

			$blogmega_option_value_data = array();



			$blogmega_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.blogmega_id = '" . (int)$blogmega_id . "' AND pov.blogmega_option_id = '" . (int)$blogmega_option['blogmega_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");



			foreach ($blogmega_option_value_query->rows as $blogmega_option_value) {

				$blogmega_option_value_data[] = array(

					'blogmega_option_value_id' => $blogmega_option_value['blogmega_option_value_id'],

					'option_value_id'         => $blogmega_option_value['option_value_id'],

					'name'                    => $blogmega_option_value['name'],

					'image'                   => $blogmega_option_value['image'],

					'quantity'                => $blogmega_option_value['quantity'],

					'subtract'                => $blogmega_option_value['subtract'],

					'price'                   => $blogmega_option_value['price'],

					'price_prefix'            => $blogmega_option_value['price_prefix'],

					'weight'                  => $blogmega_option_value['weight'],

					'weight_prefix'           => $blogmega_option_value['weight_prefix']

				);

			}



			$blogmega_option_data[] = array(

				'blogmega_option_id'    => $blogmega_option['blogmega_option_id'],

				'blogmega_option_value' => $blogmega_option_value_data,

				'option_id'            => $blogmega_option['option_id'],

				'name'                 => $blogmega_option['name'],

				'type'                 => $blogmega_option['type'],

				'value'                => $blogmega_option['value'],

				'required'             => $blogmega_option['required']

			);

		}



		return $blogmega_option_data;

	}



	public function getBlogmegaDiscounts($blogmega_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_discount WHERE blogmega_id = '" . (int)$blogmega_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity > 1 AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity ASC, priority ASC, price ASC");



		return $query->rows;

	}



	public function getBlogmegaImages($blogmega_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_image WHERE blogmega_id = '" . (int)$blogmega_id . "' ORDER BY sort_order ASC");



		return $query->rows;

	}



	public function getBlogmegaRelated($blogmega_id) {

		$blogmega_data = array();



		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_related pr LEFT JOIN " . DB_PREFIX . "blogmega p ON (pr.related_id = p.blogmega_id) LEFT JOIN " . DB_PREFIX . "blogmega_to_store p2s ON (p.blogmega_id = p2s.blogmega_id) WHERE pr.blogmega_id = '" . (int)$blogmega_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");



		foreach ($query->rows as $result) {

			$blogmega_data[$result['related_id']] = $this->getblogmega($result['related_id']);

		}



		return $blogmega_data;

	}



	public function getBlogmegaLayoutId($blogmega_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_to_layout WHERE blogmega_id = '" . (int)$blogmega_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");



		if ($query->num_rows) {

			return $query->row['layout_id'];

		} else {

			return 0;

		}

	}



	public function getCategories($blogmega_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_to_category WHERE blogmega_id = '" . (int)$blogmega_id . "'");



		return $query->rows;

	}



	public function getTotalBlogmegas($data = array()) {

		$sql = "SELECT COUNT(DISTINCT p.blogmega_id) AS total";



		if (!empty($data['filter_category_id'])) {

			if (!empty($data['filter_sub_category'])) {

				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "blogmega_to_category p2c ON (cp.category_id = p2c.category_id)";

			} else {

				$sql .= " FROM " . DB_PREFIX . "blogmega_to_category p2c";

			}



			if (!empty($data['filter_filter'])) {

				$sql .= " LEFT JOIN " . DB_PREFIX . "blogmega_filter pf ON (p2c.blogmega_id = pf.blogmega_id) LEFT JOIN " . DB_PREFIX . "blogmega p ON (pf.blogmega_id = p.blogmega_id)";

			} else {

				$sql .= " LEFT JOIN " . DB_PREFIX . "blogmega p ON (p2c.blogmega_id = p.blogmega_id)";

			}

		} else {

			$sql .= " FROM " . DB_PREFIX . "blogmega p";

		}



		$sql .= " LEFT JOIN " . DB_PREFIX . "blogmega_description pd ON (p.blogmega_id = pd.blogmega_id) LEFT JOIN " . DB_PREFIX . "blogmega_to_store p2s ON (p.blogmega_id = p2s.blogmega_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";



		if (!empty($data['filter_category_id'])) {

			if (!empty($data['filter_sub_category'])) {

				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";

			} else {

				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";

			}



			if (!empty($data['filter_filter'])) {

				$implode = array();



				$filters = explode(',', $data['filter_filter']);



				foreach ($filters as $filter_id) {

					$implode[] = (int)$filter_id;

				}



				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";

			}

		}



		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {

			$sql .= " AND (";



			if (!empty($data['filter_name'])) {

				$implode = array();



				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));



				foreach ($words as $word) {

					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";

				}



				if ($implode) {

					$sql .= " " . implode(" AND ", $implode) . "";

				}



				if (!empty($data['filter_description'])) {

					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";

				}

			}



			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {

				$sql .= " OR ";

			}



			if (!empty($data['filter_tag'])) {

				$implode = array();



				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));



				foreach ($words as $word) {

					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";

				}



				if ($implode) {

					$sql .= " " . implode(" AND ", $implode) . "";

				}

			}



			if (!empty($data['filter_name'])) {

				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";

			}



			$sql .= ")";

		}



		if (!empty($data['filter_manufacturer_id'])) {

			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";

		}



		$query = $this->db->query($sql);



		return $query->row['total'];

	}



	public function getProfile($blogmega_id, $recurring_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r JOIN " . DB_PREFIX . "blogmega_recurring pr ON (pr.recurring_id = r.recurring_id AND pr.blogmega_id = '" . (int)$blogmega_id . "') WHERE pr.recurring_id = '" . (int)$recurring_id . "' AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");



		return $query->row;

	}



	public function getProfiles($blogmega_id) {

		$query = $this->db->query("SELECT rd.* FROM " . DB_PREFIX . "blogmega_recurring pr JOIN " . DB_PREFIX . "recurring_description rd ON (rd.language_id = " . (int)$this->config->get('config_language_id') . " AND rd.recurring_id = pr.recurring_id) JOIN " . DB_PREFIX . "recurring r ON r.recurring_id = rd.recurring_id WHERE pr.blogmega_id = " . (int)$blogmega_id . " AND status = '1' AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' ORDER BY sort_order ASC");



		return $query->rows;

	}



	public function getTotalBlogmegaSpecials() {

		$query = $this->db->query("SELECT COUNT(DISTINCT ps.blogmega_id) AS total FROM " . DB_PREFIX . "blogmega_special ps LEFT JOIN " . DB_PREFIX . "blogmega p ON (ps.blogmega_id = p.blogmega_id) LEFT JOIN " . DB_PREFIX . "blogmega_to_store p2s ON (p.blogmega_id = p2s.blogmega_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");



		if (isset($query->row['total'])) {

			return $query->row['total'];

		} else {

			return 0;

		}

	}

}

