<?php

class ControllerExtensionModuleBlogfeatured extends Controller {

	public function index($setting) {

		$this->load->language('extension/module/blogfeatured');



		$data['heading_title'] = $this->language->get('heading_title');



		$data['text_tax'] = $this->language->get('text_tax');



		$data['button_cart'] = $this->language->get('button_cart');

		$data['button_wishlist'] = $this->language->get('button_wishlist');

		$data['button_compare'] = $this->language->get('button_compare');



		$this->load->model('catalog/blogmega');



		$this->load->model('tool/image');



		$data['blogmegas'] = array();



		if (!$setting['limit']) {

			$setting['limit'] = 4;

		}



		if (!empty($setting['blogmega'])) {

			$blogmegas = array_slice($setting['blogmega'], 0, (int)$setting['limit']);



			foreach ($blogmegas as $blogmega_id) {

				$blogmega_info = $this->model_catalog_blogmega->getBlogmega($blogmega_id);



				if ($blogmega_info) {

					if ($blogmega_info['image']) {

						$image = $this->model_tool_image->resize($blogmega_info['image'], $setting['width'], $setting['height']);

					} else {

						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);

					}



					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {

						$price = $this->currency->format($this->tax->calculate($blogmega_info['price'], $blogmega_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

					} else {

						$price = false;

					}



					if ((float)$blogmega_info['special']) {

						$special = $this->currency->format($this->tax->calculate($blogmega_info['special'], $blogmega_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

					} else {

						$special = false;

					}



					if ($this->config->get('config_tax')) {

						$tax = $this->currency->format((float)$blogmega_info['special'] ? $blogmega_info['special'] : $blogmega_info['price'], $this->session->data['currency']);

					} else {

						$tax = false;

					}



					if ($this->config->get('config_review_status')) {

						$rating = $blogmega_info['rating'];

					} else {

						$rating = false;

					}



					$data['blogmegas'][] = array(

						'blogmega_id'  => $blogmega_info['blogmega_id'],

						'thumb'       => $image,

						'name'        => $blogmega_info['name'],

						'description' => utf8_substr(strip_tags(html_entity_decode($blogmega_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_blogmega_description_length')) . '..',

						'price'       => $price,

						'special'     => $special,

						'tax'         => $tax,

						'rating'      => $rating,

						'href'        => $this->url->link('product/blogmega', 'blogmega_id=' . $blogmega_info['blogmega_id'])

					);

				}

			}

		}



		if ($data['blogmegas']) {

			return $this->load->view('extension/module/blogfeatured', $data);

		}

	}

}