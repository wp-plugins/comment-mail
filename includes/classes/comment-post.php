<?php
/**
 * Comment Post
 *
 * @since 141111 First documented version.
 * @copyright WebSharks, Inc. <http://www.websharks-inc.com>
 * @license GNU General Public License, version 3
 */
namespace comment_mail // Root namespace.
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	if(!class_exists('\\'.__NAMESPACE__.'\\comment_post'))
	{
		/**
		 * Comment Post
		 *
		 * @since 141111 First documented version.
		 */
		class comment_post extends abs_base
		{
			/**
			 * @var integer Comment ID.
			 *
			 * @since 141111 First documented version.
			 */
			protected $comment_id;

			/**
			 * @var string Current/initial comment status.
			 *    One of: `approve`, `hold`, `trash`, `spam`, `delete`.
			 *
			 * @since 141111 First documented version.
			 */
			protected $comment_status;

			/**
			 * Class constructor.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param integer|string $comment_id Comment ID.
			 *
			 * @param integer|string $comment_status Initial comment status.
			 *
			 *    One of the following:
			 *       - `0` (aka: ``, `hold`, `unapprove`, `unapproved`, `moderated`),
			 *       - `1` (aka: `approve`, `approved`),
			 *       - or `trash`, `post-trashed`, `spam`, `delete`.
			 */
			public function __construct($comment_id, $comment_status)
			{
				parent::__construct();

				$this->comment_id     = (integer)$comment_id;
				$this->comment_status = $this->plugin->utils_db->comment_status__($comment_status);

				$this->maybe_inject_sub();
				$this->maybe_inject_queue();
				$this->maybe_process_queue_in_realtime();
			}

			/**
			 * Inject subscription.
			 *
			 * @since 141111 First documented version.
			 */
			protected function maybe_inject_sub()
			{
				if(!$this->plugin->options['enable'])
					return; // Disabled currently.

				if(!$this->plugin->options['new_subs_enable'])
					return; // Disabled currently.

				if(!$this->comment_id)
					return; // Not applicable.

				if(empty($_POST[__NAMESPACE__.'_sub_type']))
					return; // Not applicable.

				$sub_type = (string)$_POST[__NAMESPACE__.'_sub_type'];
				if(!($sub_type = $this->plugin->utils_string->trim_strip($sub_type)))
					return; // Not applicable.

				$sub_deliver = !empty($_POST[__NAMESPACE__.'_sub_deliver'])
					? (string)$_POST[__NAMESPACE__.'_sub_deliver']
					: $this->plugin->options['comment_form_default_sub_deliver_option'];

				new sub_injector(wp_get_current_user(), $this->comment_id, array(
					'type'           => $sub_type,
					'deliver'        => $sub_deliver,
					'user_initiated' => TRUE,
					'keep_existing'  => TRUE,
				));
			}

			/**
			 * Inject/queue emails.
			 *
			 * @since 141111 First documented version.
			 */
			protected function maybe_inject_queue()
			{
				if(!$this->comment_id)
					return; // Not applicable.

				if($this->comment_status !== 'approve')
					return; // Not applicable.

				new queue_injector($this->comment_id);
			}

			/**
			 * Process queued emails in real-time.
			 *
			 * @since 141111 First documented version.
			 */
			protected function maybe_process_queue_in_realtime()
			{
				if(!$this->comment_id)
					return; // Not applicable.

				if($this->comment_status !== 'approve')
					return; // Not applicable.

				if(($realtime_max_limit = (integer)$this->plugin->options['queue_processor_realtime_max_limit']) <= 0)
					return; // Real-time queue processing is not enabled right now.

				$upper_max_limit = (integer)apply_filters(__CLASS__.'_upper_max_limit', 100);
				if($realtime_max_limit > $upper_max_limit) $realtime_max_limit = $upper_max_limit;

				new queue_processor(FALSE, 10, 0, $realtime_max_limit); // No delay.
			}
		}
	}
}
