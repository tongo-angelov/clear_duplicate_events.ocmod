<?php

namespace Opencart\Admin\Controller\Extension\RemoveDuplicateEvents\Module;

/**
 * Class Filter
 *
 * @package Opencart\Admin\Controller\Extension\RemoveDuplicateEvents\Module
 */
class ClearEvents extends \Opencart\System\Engine\Controller
{
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void
	{
		$this->load->language('extension/remove_duplicate_events/module/clear_events');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/remove_duplicate_events/module/clear_events', 'user_token=' . $this->session->data['user_token'])
		];

		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');
		$data['clear_duplicates'] = $this->url->link('extension/remove_duplicate_events/module/clear_events.clearDuplicates', 'user_token=' . $this->session->data['user_token']);
		$data['can_clear_duplicates'] = $this->user->hasPermission('modify', 'extension/remove_duplicate_events/module/clear_events');

		$query = $this->db->query("SELECT e.*,
			CASE WHEN e.`event_id` > (
				SELECT MIN(earlier_event.`event_id`)
				FROM `" . DB_PREFIX . "event` AS earlier_event
				WHERE earlier_event.`trigger` = e.`trigger`
				AND earlier_event.`action` = e.`action`
			) THEN 1 ELSE 0 END AS `is_later_duplicate`
			FROM `" . DB_PREFIX . "event` AS e
			WHERE (e.`trigger`, e.`action`) IN (
				SELECT `trigger`, `action`
				FROM `" . DB_PREFIX . "event`
				GROUP BY `trigger`, `action`
				HAVING COUNT(*) > 1
			)
			ORDER BY e.`trigger`, e.`action`, e.`event_id`");

		$data['duplicate_events'] = $query->rows;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/remove_duplicate_events/module/clear_events', $data));
	}

	public function clearDuplicates(): void
	{
		$route = 'extension/remove_duplicate_events/module/clear_events';

		if ($this->user->hasPermission('modify', $route) && $this->request->server['REQUEST_METHOD'] === 'POST') {
			$this->db->query("DELETE later_event
				FROM `" . DB_PREFIX . "event` AS later_event
				INNER JOIN `" . DB_PREFIX . "event` AS earlier_event
					ON later_event.`trigger` = earlier_event.`trigger`
					AND later_event.`action` = earlier_event.`action`
					AND later_event.`event_id` > earlier_event.`event_id`");
		}

		$this->response->redirect($this->url->link($route, 'user_token=' . $this->session->data['user_token']));
	}
}
