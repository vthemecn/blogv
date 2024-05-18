<?php
/*
 * 评论模块
 */
?>

<?php
global $current_user;

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if (post_password_required()) { return; }

$comment_count = get_comments_number();
?>


<!-- <div id="comments" class="comments-area default-max-width <?php echo get_option('show_avatars') ? 'show-avatars' : ''; ?>"> -->
<div class="comments-area">
	<?php
	/**
	 * 评论表单
	 */
	$author_name = '';
	if(isset($_GET['replytocom']) && $_GET['replytocom']){
		global $wpdb;
		$sql = "SELECT comment_author FROM {$wpdb->prefix}comments WHERE comment_ID=%d";
		$comment = $wpdb->get_row($wpdb->prepare($sql, intval($_GET['replytocom']) ), ARRAY_A);
		$author_name = $comment['comment_author'];
	}

	$comments_args = array(
		'fields' => array(
			'author' => '<div class="comment-input-widget"><input id="author" class="input-field" name="author" placeholder="' .__('昵称(*)'). '" value="' . esc_attr($commenter['comment_author']) . '"></input>',
			'email' => '<input id="email" class="input-field" name="email" placeholder="' .__('邮箱(*)'). '" value="' . esc_attr($commenter['comment_author_email']) . '"></input>',
			'url' => '<input id="url" class="input-field" name="url" placeholder="' .__('网站地址'). '" value="' . esc_attr($commenter['comment_author_url']) . '"></input></div>',
			// 'cookies' => '<input type="checkbox" required>By commenting you accept the<a href="' . get_privacy_policy_url() . '">Privacy Policy</a>'
		),
		'label_submit' => __('发表评论'),
		'title_reply' => __('发表评论'),
		'title_reply_to' => __('回复') .' '. $author_name,
		'cancel_reply_link' => __('取消'),
		'comment_field' => '<div class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true" placeholder="' .__('评论内容'). '"></textarea></div>',
		'comment_notes_before' => '<div class="top-info logged-in-as">'.__('注册不是必须的').'</div>',
		'comment_notes_after' => '',
		'id_submit' => __('comment-submit'),
		'logged_in_as' => '<div class="top-info logged-in-as">当前账户: ' . $current_user->nickname . '</div>',
		'must_log_in'  => '<div class="top-info must-log-in">需要登录，这里创建登录按钮</div>'
	);
	comment_form($comments_args);
	?>


	<?php
	if (have_comments()) :;
	?>
		<h2 class="comments-title">
			<?php if ('1' === $comment_count) : ?>
				1 条评论
			<?php else : ?>
				<?php echo $comment_count; ?> 条评论
			<?php endif; ?>
		</h2>

		<ol class="comment-list">
			<?php
			$arg = array(
				'avatar_size' => 60,
				'style' => 'li',
				// 'short_ping'  => true,
				'callback' => 'rt_comment',
				// 'per_page' => get_option('comments_per_page'),
				// 'reverse_top_level' => true
				'reverse_children' => true
			);
			wp_list_comments($arg);
			?>
		</ol>

		<?php the_comments_pagination(); ?>
		
		<?php if (!comments_open()) : ?>
			<div class="no-comments"><?php esc_html_e('Comments are closed.'); ?></div>
		<?php endif; ?>
	<?php endif; ?>


	<?php
	function rt_comment($comment, $args, $depth)
	{
		$tag = 'li';
		$add_below = 'div-comment';
		?>
		
		<<?php
			echo $tag. ' ';
			comment_class(empty($args['has_children']) ? '' : 'parent'); ?> 
			id="comment-<?php comment_ID() ?>">
			<div class="comment-item" id="div-comment-<?php comment_ID() ?>">
				<div class="comment-header">
					<div class="comment-author">
						<?php
						if ($args['avatar_size'] != 0) {
							echo get_avatar($comment->user_id, $args['avatar_size']);
						}
						?>
						<span class="nickname">
							<?php
							$user_url = get_comment_author_url();
							?>
							<?php if($user_url): ?>
								<a href="javascript:;" target="_blank" data-url="<?php echo $user_url ?>">
									<?php echo get_comment_author() ?>
									<i class="iconfont">&#xe702;</i>
								</a>
							<?php else:?>
								<?php echo get_comment_author() ?>
							<?php endif ?>
						</span>
					</div>
					<div class="comment-meta">
						<?php
						// translators: 1: date, 2: time
						printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time());
						// edit_comment_link(__('(Edit)'), '  ', ''); 
						?>
					</div>
				</div>
				
				<?php if ($comment->comment_approved == '0') { ?>
					<div class="comment-status">
						<?php _e('Your comment is awaiting moderation.'); ?>
					</div>
				<?php } ?>
				<div class="comment-content">
					<?php comment_text(); ?>
				</div>
				<div class="reply">
					<?php
					$url = get_permalink();
					$url = $url . "?replytocom=" . get_comment_ID() . "#respond";
					?>
					<?php if($depth <= 1):?>
						<a href="<?php echo $url?>" <?php echo $current_user->ID ? 'login="false"' : ''?> >回复</a>
					<?php endif ?>
				</div>

				<?php if($comment->user_id == $current_user->ID):?>
					<a href="">删除</a>
				<?php endif ?>
			</div>
	<?php
	}
	?>
</div>
