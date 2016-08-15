<?php
class ModelCatalogBlogmega extends Model {
	public function addBlogmega($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");

		$blogmega_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "blogmega SET image = '" . $this->db->escape($data['image']) . "' WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		}

		foreach ($data['blogmega_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_description SET blogmega_id = '" . (int)$blogmega_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['blogmega_store'])) {
			foreach ($data['blogmega_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_to_store SET blogmega_id = '" . (int)$blogmega_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['blogmega_attribute'])) {
			foreach ($data['blogmega_attribute'] as $blogmega_attribute) {
				if ($blogmega_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_attribute WHERE blogmega_id = '" . (int)$blogmega_id . "' AND attribute_id = '" . (int)$blogmega_attribute['attribute_id'] . "'");

					foreach ($blogmega_attribute['blogmega_attribute_description'] as $language_id => $blogmega_attribute_description) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_attribute WHERE blogmega_id = '" . (int)$blogmega_id . "' AND attribute_id = '" . (int)$blogmega_attribute['attribute_id'] . "' AND language_id = '" . (int)$language_id . "'");

						$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_attribute SET blogmega_id = '" . (int)$blogmega_id . "', attribute_id = '" . (int)$blogmega_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($blogmega_attribute_description['text']) . "'");
					}
				}
			}
		}

		if (isset($data['blogmega_option'])) {
			foreach ($data['blogmega_option'] as $blogmega_option) {
				if ($blogmega_option['type'] == 'select' || $blogmega_option['type'] == 'radio' || $blogmega_option['type'] == 'checkbox' || $blogmega_option['type'] == 'image') {
					if (isset($blogmega_option['blogmega_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_option SET blogmega_id = '" . (int)$blogmega_id . "', option_id = '" . (int)$blogmega_option['option_id'] . "', required = '" . (int)$blogmega_option['required'] . "'");

						$blogmega_option_id = $this->db->getLastId();

						foreach ($blogmega_option['blogmega_option_value'] as $blogmega_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_option_value SET blogmega_option_id = '" . (int)$blogmega_option_id . "', blogmega_id = '" . (int)$blogmega_id . "', option_id = '" . (int)$blogmega_option['option_id'] . "', option_value_id = '" . (int)$blogmega_option_value['option_value_id'] . "', quantity = '" . (int)$blogmega_option_value['quantity'] . "', subtract = '" . (int)$blogmega_option_value['subtract'] . "', price = '" . (float)$blogmega_option_value['price'] . "', price_prefix = '" . $this->db->escape($blogmega_option_value['price_prefix']) . "', points = '" . (int)$blogmega_option_value['points'] . "', points_prefix = '" . $this->db->escape($blogmega_option_value['points_prefix']) . "', weight = '" . (float)$blogmega_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($blogmega_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_option SET blogmega_id = '" . (int)$blogmega_id . "', option_id = '" . (int)$blogmega_option['option_id'] . "', value = '" . $this->db->escape($blogmega_option['value']) . "', required = '" . (int)$blogmega_option['required'] . "'");
				}
			}
		}

		if (isset($data['blogmega_discount'])) {
			foreach ($data['blogmega_discount'] as $blogmega_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_discount SET blogmega_id = '" . (int)$blogmega_id . "', customer_group_id = '" . (int)$blogmega_discount['customer_group_id'] . "', quantity = '" . (int)$blogmega_discount['quantity'] . "', priority = '" . (int)$blogmega_discount['priority'] . "', price = '" . (float)$blogmega_discount['price'] . "', date_start = '" . $this->db->escape($blogmega_discount['date_start']) . "', date_end = '" . $this->db->escape($blogmega_discount['date_end']) . "'");
			}
		}

		if (isset($data['blogmega_special'])) {
			foreach ($data['blogmega_special'] as $blogmega_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_special SET blogmega_id = '" . (int)$blogmega_id . "', customer_group_id = '" . (int)$blogmega_special['customer_group_id'] . "', priority = '" . (int)$blogmega_special['priority'] . "', price = '" . (float)$blogmega_special['price'] . "', date_start = '" . $this->db->escape($blogmega_special['date_start']) . "', date_end = '" . $this->db->escape($blogmega_special['date_end']) . "'");
			}
		}

		if (isset($data['blogmega_image'])) {
			foreach ($data['blogmega_image'] as $blogmega_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_image SET blogmega_id = '" . (int)$blogmega_id . "', image = '" . $this->db->escape($blogmega_image['image']) . "', sort_order = '" . (int)$blogmega_image['sort_order'] . "'");
			}
		}

		if (isset($data['blogmega_download'])) {
			foreach ($data['blogmega_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_to_download SET blogmega_id = '" . (int)$blogmega_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		if (isset($data['blogmega_blogcategory'])) {
			foreach ($data['blogmega_blogcategory'] as $blogcategory_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_to_blogcategory SET blogmega_id = '" . (int)$blogmega_id . "', blogcategory_id = '" . (int)$blogcategory_id . "'");
			}
		}

		if (isset($data['blogmega_filter'])) {
			foreach ($data['blogmega_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_filter SET blogmega_id = '" . (int)$blogmega_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['blogmega_related'])) {
			foreach ($data['blogmega_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_related WHERE blogmega_id = '" . (int)$blogmega_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_related SET blogmega_id = '" . (int)$blogmega_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_related WHERE blogmega_id = '" . (int)$related_id . "' AND related_id = '" . (int)$blogmega_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_related SET blogmega_id = '" . (int)$related_id . "', related_id = '" . (int)$blogmega_id . "'");
			}
		}

		if (isset($data['blogmega_reward'])) {
			foreach ($data['blogmega_reward'] as $customer_group_id => $blogmega_reward) {
				if ((int)$blogmega_reward['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_reward SET blogmega_id = '" . (int)$blogmega_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$blogmega_reward['points'] . "'");
				}
			}
		}

		if (isset($data['blogmega_layout'])) {
			foreach ($data['blogmega_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_to_layout SET blogmega_id = '" . (int)$blogmega_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'blogmega_id=" . (int)$blogmega_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		if (isset($data['blogmega_recurring'])) {
			foreach ($data['blogmega_recurring'] as $recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "blogmega_recurring` SET `blogmega_id` = " . (int)$blogmega_id . ", customer_group_id = " . (int)$recurring['customer_group_id'] . ", `recurring_id` = " . (int)$recurring['recurring_id']);
			}
		}

		$this->cache->delete('blogmega');

		return $blogmega_id;
	}

	public function editBlogmega($blogmega_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "blogmega SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "blogmega SET image = '" . $this->db->escape($data['image']) . "' WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_description WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		foreach ($data['blogmega_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_description SET blogmega_id = '" . (int)$blogmega_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_to_store WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		if (isset($data['blogmega_store'])) {
			foreach ($data['blogmega_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_to_store SET blogmega_id = '" . (int)$blogmega_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_attribute WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		if (!empty($data['blogmega_attribute'])) {
			foreach ($data['blogmega_attribute'] as $blogmega_attribute) {
				if ($blogmega_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_attribute WHERE blogmega_id = '" . (int)$blogmega_id . "' AND attribute_id = '" . (int)$blogmega_attribute['attribute_id'] . "'");

					foreach ($blogmega_attribute['blogmega_attribute_description'] as $language_id => $blogmega_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_attribute SET blogmega_id = '" . (int)$blogmega_id . "', attribute_id = '" . (int)$blogmega_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($blogmega_attribute_description['text']) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_option WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_option_value WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		if (isset($data['blogmega_option'])) {
			foreach ($data['blogmega_option'] as $blogmega_option) {
				if ($blogmega_option['type'] == 'select' || $blogmega_option['type'] == 'radio' || $blogmega_option['type'] == 'checkbox' || $blogmega_option['type'] == 'image') {
					if (isset($blogmega_option['blogmega_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_option SET blogmega_option_id = '" . (int)$blogmega_option['blogmega_option_id'] . "', blogmega_id = '" . (int)$blogmega_id . "', option_id = '" . (int)$blogmega_option['option_id'] . "', required = '" . (int)$blogmega_option['required'] . "'");

						$blogmega_option_id = $this->db->getLastId();

						foreach ($blogmega_option['blogmega_option_value'] as $blogmega_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_option_value SET blogmega_option_value_id = '" . (int)$blogmega_option_value['blogmega_option_value_id'] . "', blogmega_option_id = '" . (int)$blogmega_option_id . "', blogmega_id = '" . (int)$blogmega_id . "', option_id = '" . (int)$blogmega_option['option_id'] . "', option_value_id = '" . (int)$blogmega_option_value['option_value_id'] . "', quantity = '" . (int)$blogmega_option_value['quantity'] . "', subtract = '" . (int)$blogmega_option_value['subtract'] . "', price = '" . (float)$blogmega_option_value['price'] . "', price_prefix = '" . $this->db->escape($blogmega_option_value['price_prefix']) . "', points = '" . (int)$blogmega_option_value['points'] . "', points_prefix = '" . $this->db->escape($blogmega_option_value['points_prefix']) . "', weight = '" . (float)$blogmega_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($blogmega_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_option SET blogmega_option_id = '" . (int)$blogmega_option['blogmega_option_id'] . "', blogmega_id = '" . (int)$blogmega_id . "', option_id = '" . (int)$blogmega_option['option_id'] . "', value = '" . $this->db->escape($blogmega_option['value']) . "', required = '" . (int)$blogmega_option['required'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_discount WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		if (isset($data['blogmega_discount'])) {
			foreach ($data['blogmega_discount'] as $blogmega_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_discount SET blogmega_id = '" . (int)$blogmega_id . "', customer_group_id = '" . (int)$blogmega_discount['customer_group_id'] . "', quantity = '" . (int)$blogmega_discount['quantity'] . "', priority = '" . (int)$blogmega_discount['priority'] . "', price = '" . (float)$blogmega_discount['price'] . "', date_start = '" . $this->db->escape($blogmega_discount['date_start']) . "', date_end = '" . $this->db->escape($blogmega_discount['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_special WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		if (isset($data['blogmega_special'])) {
			foreach ($data['blogmega_special'] as $blogmega_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_special SET blogmega_id = '" . (int)$blogmega_id . "', customer_group_id = '" . (int)$blogmega_special['customer_group_id'] . "', priority = '" . (int)$blogmega_special['priority'] . "', price = '" . (float)$blogmega_special['price'] . "', date_start = '" . $this->db->escape($blogmega_special['date_start']) . "', date_end = '" . $this->db->escape($blogmega_special['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_image WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		if (isset($data['blogmega_image'])) {
			foreach ($data['blogmega_image'] as $blogmega_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_image SET blogmega_id = '" . (int)$blogmega_id . "', image = '" . $this->db->escape($blogmega_image['image']) . "', sort_order = '" . (int)$blogmega_image['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_to_download WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		if (isset($data['blogmega_download'])) {
			foreach ($data['blogmega_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_to_download SET blogmega_id = '" . (int)$blogmega_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_to_blogcategory WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		if (isset($data['blogmega_blogcategory'])) {
			foreach ($data['blogmega_blogcategory'] as $blogcategory_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_to_blogcategory SET blogmega_id = '" . (int)$blogmega_id . "', blogcategory_id = '" . (int)$blogcategory_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_filter WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		if (isset($data['blogmega_filter'])) {
			foreach ($data['blogmega_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_filter SET blogmega_id = '" . (int)$blogmega_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_related WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_related WHERE related_id = '" . (int)$blogmega_id . "'");

		if (isset($data['blogmega_related'])) {
			foreach ($data['blogmega_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_related WHERE blogmega_id = '" . (int)$blogmega_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_related SET blogmega_id = '" . (int)$blogmega_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_related WHERE blogmega_id = '" . (int)$related_id . "' AND related_id = '" . (int)$blogmega_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_related SET blogmega_id = '" . (int)$related_id . "', related_id = '" . (int)$blogmega_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_reward WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		if (isset($data['blogmega_reward'])) {
			foreach ($data['blogmega_reward'] as $customer_group_id => $value) {
				if ((int)$value['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_reward SET blogmega_id = '" . (int)$blogmega_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_to_layout WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		if (isset($data['blogmega_layout'])) {
			foreach ($data['blogmega_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "blogmega_to_layout SET blogmega_id = '" . (int)$blogmega_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'blogmega_id=" . (int)$blogmega_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'blogmega_id=" . (int)$blogmega_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "blogmega_recurring` WHERE blogmega_id = " . (int)$blogmega_id);

		if (isset($data['blogmega_recurring'])) {
			foreach ($data['blogmega_recurring'] as $blogmega_recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "blogmega_recurring` SET `blogmega_id` = " . (int)$blogmega_id . ", customer_group_id = " . (int)$blogmega_recurring['customer_group_id'] . ", `recurring_id` = " . (int)$blogmega_recurring['recurring_id']);
			}
		}

		$this->cache->delete('blogmega');
	}

	public function copyBlogmega($blogmega_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "blogmega p WHERE p.blogmega_id = '" . (int)$blogmega_id . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data['blogmega_attribute'] = $this->getBlogmegaAttributes($blogmega_id);
			$data['blogmega_description'] = $this->getblogmegaDescriptions($blogmega_id);
			$data['blogmega_discount'] = $this->getBlogmegaDiscounts($blogmega_id);
			$data['blogmega_filter'] = $this->getBlogmegaFilters($blogmega_id);
			$data['blogmega_image'] = $this->getBlogmegaImages($blogmega_id);
			$data['blogmega_option'] = $this->getBlogmegaOptions($blogmega_id);
			$data['blogmega_related'] = $this->getBlogmegaRelated($blogmega_id);
			$data['blogmega_reward'] = $this->getBlogmegaRewards($blogmega_id);
			$data['blogmega_special'] = $this->getBlogmegaSpecials($blogmega_id);
			$data['blogmega_blogcategory'] = $this->getBlogmegaBlogategories($blogmega_id);
			$data['blogmega_download'] = $this->getBlogmegaDownloads($blogmega_id);
			$data['blogmega_layout'] = $this->getBlogmegaLayouts($blogmega_id);
			$data['blogmega_store'] = $this->getBlogmegaStores($blogmega_id);
			$data['blogmega_recurrings'] = $this->getRecurrings($blogmega_id);

			$this->addBlogmega($data);
		}
	}

	public function deleteBlogmega($blogmega_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_attribute WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_description WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_discount WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_filter WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_image WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_option WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_option_value WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_related WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_related WHERE related_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_reward WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_special WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_to_blogcategory WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_to_download WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_to_layout WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_to_store WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "blogmega_recurring WHERE blogmega_id = " . (int)$blogmega_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE blogmega_id = '" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'blogmega_id=" . (int)$blogmega_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_blogmega WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		$this->cache->delete('blogmega');
	}

	public function getBlogmega($blogmega_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'blogmega_id=" . (int)$blogmega_id . "') AS keyword FROM " . DB_PREFIX . "blogmega p LEFT JOIN " . DB_PREFIX . "blogmega_description pd ON (p.blogmega_id = pd.blogmega_id) WHERE p.blogmega_id = '" . (int)$blogmega_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getBlogmegas($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "blogmega p LEFT JOIN " . DB_PREFIX . "blogmega_description pd ON (p.blogmega_id = pd.blogmega_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_image']) && !is_null($data['filter_image'])) {
			if ($data['filter_image'] == 1) {
				$sql .= " AND (p.image IS NOT NULL AND p.image <> '' AND p.image <> 'no_image.png')";
			} else {
				$sql .= " AND (p.image IS NULL OR p.image = '' OR p.image = 'no_image.png')";
			}
		}

		$sql .= " GROUP BY p.blogmega_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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

	public function getBlogmegasByCategoryId($blogcategory_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega p LEFT JOIN " . DB_PREFIX . "blogmega_description pd ON (p.blogmega_id = pd.blogmega_id) LEFT JOIN " . DB_PREFIX . "blogmega_to_blogcategory p2c ON (p.blogmega_id = p2c.blogmega_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.blogcategory_id = '" . (int)$blogcategory_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getBlogmegaDescriptions($blogmega_id) {
		$blogmega_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_description WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		foreach ($query->rows as $result) {
			$blogmega_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $blogmega_description_data;
	}

	public function getBlogmegaBlogcategories($blogmega_id) {
		$blogmega_blogcategory_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_to_blogcategory WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		foreach ($query->rows as $result) {
			$blogmega_blogcategory_data[] = $result['blogcategory_id'];
		}

		return $blogmega_blogcategory_data;
	}

	public function getBlogmegaFilters($blogmega_id) {
		$blogmega_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_filter WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		foreach ($query->rows as $result) {
			$blogmega_filter_data[] = $result['filter_id'];
		}

		return $blogmega_filter_data;
	}

	public function getBlogmegaAttributes($blogmega_id) {
		$blogmega_attribute_data = array();

		$blogmega_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "blogmega_attribute WHERE blogmega_id = '" . (int)$blogmega_id . "' GROUP BY attribute_id");

		foreach ($blogmega_attribute_query->rows as $blogmega_attribute) {
			$blogmega_attribute_description_data = array();

			$blogmega_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_attribute WHERE blogmega_id = '" . (int)$blogmega_id . "' AND attribute_id = '" . (int)$blogmega_attribute['attribute_id'] . "'");

			foreach ($blogmega_attribute_description_query->rows as $blogmega_attribute_description) {
				$blogmega_attribute_description_data[$blogmega_attribute_description['language_id']] = array('text' => $blogmega_attribute_description['text']);
			}

			$blogmega_attribute_data[] = array(
				'attribute_id'                  => $blogmega_attribute['attribute_id'],
				'blogmega_attribute_description' => $blogmega_attribute_description_data
			);
		}

		return $blogmega_attribute_data;
	}

	public function getBlogmegaOptions($blogmega_id) {
		$blogmega_option_data = array();

		$blogmega_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blogmega_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.blogmega_id = '" . (int)$blogmega_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($blogmega_option_query->rows as $blogmega_option) {
			$blogmega_option_value_data = array();

			$blogmega_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON(pov.option_value_id = ov.option_value_id) WHERE pov.blogmega_option_id = '" . (int)$blogmega_option['blogmega_option_id'] . "' ORDER BY ov.sort_order ASC");

			foreach ($blogmega_option_value_query->rows as $blogmega_option_value) {
				$blogmega_option_value_data[] = array(
					'blogmega_option_value_id' => $blogmega_option_value['blogmega_option_value_id'],
					'option_value_id'         => $blogmega_option_value['option_value_id'],
					'quantity'                => $blogmega_option_value['quantity'],
					'subtract'                => $blogmega_option_value['subtract'],
					'price'                   => $blogmega_option_value['price'],
					'price_prefix'            => $blogmega_option_value['price_prefix'],
					'points'                  => $blogmega_option_value['points'],
					'points_prefix'           => $blogmega_option_value['points_prefix'],
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

	public function getBlogmegaOptionValue($blogmega_id, $blogmega_option_value_id) {
		$query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "blogmega_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.blogmega_id = '" . (int)$blogmega_id . "' AND pov.blogmega_option_value_id = '" . (int)$blogmega_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getBlogmegaImages($blogmega_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_image WHERE blogmega_id = '" . (int)$blogmega_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getBlogmegaDiscounts($blogmega_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_discount WHERE blogmega_id = '" . (int)$blogmega_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getBlogmegaSpecials($blogmega_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_special WHERE blogmega_id = '" . (int)$blogmega_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getBlogmegaRewards($blogmega_id) {
		$blogmega_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_reward WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		foreach ($query->rows as $result) {
			$blogmega_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $blogmega_reward_data;
	}

	public function getBlogmegaDownloads($blogmega_id) {
		$blogmega_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_to_download WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		foreach ($query->rows as $result) {
			$blogmega_download_data[] = $result['download_id'];
		}

		return $blogmega_download_data;
	}

	public function getBlogmegaStores($blogmega_id) {
		$blogmega_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_to_store WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		foreach ($query->rows as $result) {
			$blogmega_store_data[] = $result['store_id'];
		}

		return $blogmega_store_data;
	}

	public function getBlogmegaLayouts($blogmega_id) {
		$blogmega_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_to_layout WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		foreach ($query->rows as $result) {
			$blogmega_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $blogmega_layout_data;
	}

	public function getBlogmegaRelated($blogmega_id) {
		$blogmega_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blogmega_related WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		foreach ($query->rows as $result) {
			$blogmega_related_data[] = $result['related_id'];
		}

		return $blogmega_related_data;
	}

	public function getRecurrings($blogmega_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "blogmega_recurring` WHERE blogmega_id = '" . (int)$blogmega_id . "'");

		return $query->rows;
	}

	public function getTotalBlogmegas($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.blogmega_id) AS total FROM " . DB_PREFIX . "blogmega p LEFT JOIN " . DB_PREFIX . "blogmega_description pd ON (p.blogmega_id = pd.blogmega_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_image']) && !is_null($data['filter_image'])) {
			if ($data['filter_image'] == 1) {
				$sql .= " AND (p.image IS NOT NULL AND p.image <> '' AND p.image <> 'no_image.png')";
			} else {
				$sql .= " AND (p.image IS NULL OR p.image = '' OR p.image = 'no_image.png')";
			}
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalBlogmegasByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogmega WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalBlogmegasByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogmega WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalBlogmegasByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogmega WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalBlogmegasByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogmega WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalBlogmegasByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogmega_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalBlogmegasByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogmega WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalBlogmegasByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogmega_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalBlogmegasByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogmega_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalBlogmegasByProfileId($recurring_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogmega_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		return $query->row['total'];
	}

	public function getTotalBlogmegasByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blogmega_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
