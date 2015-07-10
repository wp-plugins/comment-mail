<?php
/**
 * Post Deletion Handler
 *
 * @since 141111 First documented version.
 * @copyright WebSharks, Inc. <http://www.websharks-inc.com>
 * @license GNU General Public License, version 3
 */
namespace comment_mail // Root namespace.
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	if(!class_exists('\\'.__NAMESPACE__.'\\post_delete'))
	{
		/**
		 * Post Deletion Handler
		 *
		 * @since 141111 First documented version.
		 */
		class post_delete extends abs_base
		{
			/**
			 * @var integer Post ID.
			 *
			 * @since 141111 First documented version.
			 */
			protected $post_id;

			/**
			 * Class constructor.
			 *
			 * @since 141111 First documented version.
			 *
			 * @param integer|string $post_id Post ID.
			 */
			public function __construct($post_id)
			{
				parent::__construct();

				$this->post_id = (integer)$post_id;

				$this->maybe_purge_subs();
			}

			/**
			 * Purges subscriptions.
			 *
			 * @since 141111 First documented version.
			 */
			protected function maybe_purge_subs()
			{
				if(!$this->post_id)
					return; // Nothing to do.

				new sub_purger($this->post_id);
			}
		}
	}
}