<?php
/**
 * Front Scripts
 *
 * @since 141111 First documented version.
 * @copyright WebSharks, Inc. <http://www.websharks-inc.com>
 * @license GNU General Public License, version 3
 */
namespace comment_mail // Root namespace.
{
	if(!defined('WPINC')) // MUST have WordPress.
		exit('Do NOT access this file directly: '.basename(__FILE__));

	if(!class_exists('\\'.__NAMESPACE__.'\\front_scripts'))
	{
		/**
		 * Front Scripts
		 *
		 * @since 141111 First documented version.
		 */
		class front_scripts extends abs_base
		{
			/**
			 * Class constructor.
			 *
			 * @since 141111 First documented version.
			 */
			public function __construct()
			{
				parent::__construct();

				if(is_admin())
					return; // Not applicable.

				$this->maybe_enqueue_comment_form_sub_scripts();
			}

			/**
			 * Enqueue front-side scripts for comment form subs.
			 *
			 * @since 141111 First documented version.
			 */
			protected function maybe_enqueue_comment_form_sub_scripts()
			{
				if(!$this->plugin->options['enable'])
					return; // Nothing to do.

				if(!$this->plugin->options['new_subs_enable'])
					return; // Nothing to do.

				if(!$this->plugin->options['comment_form_sub_scripts_enable'])
					if(!$this->plugin->options['comment_form_sub_template_enable'])
						return; // Nothing to do here.

				if(!is_singular() || !comments_open())
					return; // Not applicable.

				wp_enqueue_script('jquery'); // Need jQuery.

				add_action('wp_footer', function ()
				{
					$template = new template('site/comment-form/sub-op-scripts.php');
					echo $template->parse(); // Inline `<script></script>`.

				}, PHP_INT_MAX - 10); // Very low priority; after footer scripts!
			}
		}
	}
}
