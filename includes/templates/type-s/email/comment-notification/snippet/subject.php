[if is_digest]

	[if sub_comment]
		[if subscribed_to_own_comment]
			New Replies to your Comment on "[sub_post_title_clip]"
		[else]
			New Replies to Comment ID #[sub_comment_id] on "[sub_post_title_clip]"
		[endif]
	[else]
		New Comments on "[sub_post_title_clip]"
	[endif]

[else]

	[if sub_comment]
		[if subscribed_to_own_comment]
			New Reply to your Comment on "[sub_post_title_clip]"
		[else]
			New Reply to Comment ID #[sub_comment_id] on "[sub_post_title_clip]"
		[endif]
	[else]
		New Comment on "[sub_post_title_clip]"
	[endif]

[endif]
