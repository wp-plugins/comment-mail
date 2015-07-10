<?php
/**
 * Queue Event Log Inserter
 *
 * @since 141111 First documented version.
 * @copyright WebSharks, Inc. <http://www.websharks-inc.com>
 * @license GNU General Public License, version 3
 */
namespace comment_mail // Root namespace.
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	if(!class_exists('\\'.__NAMESPACE__.'\\queue_event_log_inserter'))
	{
		/**
		 * Queue Event Log Inserter
		 *
		 * @since 141111 First documented version.
		 */
		class queue_event_log_inserter extends abs_base
		{
			/**
			 * @var array Log entry data.
			 *
			 * @since 141111 First documented version.
			 */
			protected $entry;

			/**
			 * Class constructor.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param array $entry Log entry data.
			 *
			 * @throws \exception If `$entry` is missing required keys.
			 */
			public function __construct(array $entry)
			{
				parent::__construct();

				$defaults = array(
					'queue_id'          => 0,
					'dby_queue_id'      => 0,

					'sub_id'            => 0,

					'user_id'           => 0,
					'post_id'           => 0,
					'comment_parent_id' => 0,
					'comment_id'        => 0,

					'fname'             => '',
					'lname'             => '',
					'email'             => '',

					'ip'                => '',
					'region'            => '',
					'country'           => '',

					'status'            => '',

					'event'             => '',
					'note_code'         => '',

					'time'              => time(),
				);
				# IP, region, country; auto-fill from subscription data.

				foreach(array('ip', 'region', 'country') as $_key)
				{
					if(empty($entry[$_key])) // Coalesce; giving precedence to the `last_` value.
						$entry[$_key] = $this->not_empty_coalesce($entry['last_'.$_key], $entry['insertion_'.$_key]);
				}
				unset($_key); // Just a little housekeeping.

				$this->entry = array_merge($defaults, $entry);
				$this->entry = array_intersect_key($this->entry, $defaults);
				$this->entry = $this->plugin->utils_db->typify_deep($this->entry);

				$this->maybe_insert(); // Record event; if applicable.
			}

			/**
			 * Record event; if applicable.
			 *
			 * @since 141111 First documented version.
			 *
			 * @throws \exception If an insertion failure occurs.
			 */
			protected function maybe_insert()
			{
				if(!$this->entry['queue_id'])
					return; // Not applicable.

				if(!$this->entry['event'])
					return; // Not applicable.

				if(!$this->entry['time'])
					return; // Not applicable.

				if(!$this->plugin->utils_db->wp->insert($this->plugin->utils_db->prefix().'queue_event_log', $this->entry))
					throw new \exception(__('Insertion failure.', $this->plugin->text_domain));
			}
		}
	}
}