
</main>

<!--  contacts  -->
<section id="contacts">
	<div class="container">
		<div class="heading center">
			<h2>Контакты</h2>
		</div>
		<div class="row">
			
			<div class="col-lg-6 contact__box">
				<div class="box__inner">
					<a href="tel:<?php the_field('main_phone_code', 8); ?>" class="phone"><img src="<?php echo get_template_directory_uri(); ?>/img/phone.svg" alt=""><span>Телефон</span><?php the_field('main_phone', 8); ?></a>
					<a href="mailto:<?php the_field('main_email', 8); ?>" class="email"><img src="<?php echo get_template_directory_uri(); ?>/img/mail.svg" alt=""><span>Почта</span><?php the_field('main_email', 8); ?></a>
				</div>
			</div>

			<div class="col-lg-6 contact__box">
				<div class="box__inner">
					<p class="ip"><img src="<?php echo get_template_directory_uri(); ?>/img/user_circle.svg" alt=""><span>Самозанятый</span><?php the_field('main_ip', 8); ?></p>
					<div class="social">

						<?php if ( get_field( 'telegram', 8 )) : ?>
							<a href="<?php the_field('telegram', 8); ?>" target="_blank">
								<svg class="icon_1">
									<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/svg/symbols.svg#icon_icon_1"></use>
								</svg>
							</a>
						<?php endif; ?>

						<?php if ( get_field( 'facebook', 8 )) : ?>
							<a href="<?php the_field('facebook', 8); ?>" target="_blank">
								<svg class="icon_1">
									<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/svg/symbols.svg#icon_icon_2"></use>
								</svg>
							</a>
						<?php endif; ?>

						<?php if ( get_field( 'vk', 8 )) : ?>
							<a href="<?php the_field('vk', 8); ?>" target="_blank">
								<svg class="icon_1">
									<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/svg/symbols.svg#icon_icon_3"></use>
								</svg>
							</a>
						<?php endif; ?>

						<?php if ( get_field( 'instagram', 8 )) : ?>
							<a href="<?php the_field('instagram', 8); ?>" target="_blank">
								<svg class="icon_1">
									<use xlink:href="<?php echo get_template_directory_uri(); ?>/img/svg/symbols.svg#icon_icon_4"></use>
								</svg>
							</a>
						<?php endif; ?>

					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<!--  FOOTER  -->
<footer>
	<section id="footer">
		<div class="container">
			<a class="logo" href="<?php echo home_url(''); ?>">
				<img src="<?php echo get_template_directory_uri(); ?>/img/logo2.png" alt="">
			</a>
		</div>
	</section>
</footer>

<!--  MODAL  -->

<!--  modal_request  -->
<div style="display: none;" id="modal_callback" class="modal">
	<h2 class="modal__title center">Заказать звонок</h2>
	<p class="modal__subtitle center">Наш менеджер перезвонит вам в течение нескольких минут и ответит на любой ваш вопрос</p>
	<div class="form">

		<?php echo do_shortcode( '[contact-form-7 id="11" title="Заказать звонок"]' ); ?>

	</div>
</div>

<!--  modal_ok  -->
<div style="display: none;" id="modal_ok" class="modal">
	<h2 class="modal__title center">Спасибо за заказ</h2>
	<p class="modal__subtitle center">Наш менеджер перезвонит вам в течение нескольких минут</p>
	<div class="modal__btn center">
		<a href="#maincatalog" onclick="$.fancybox.close()" class="btn">Перейти в каталог</a>
	</div>
</div>

<!--  modal_request  -->
<div style="display: none;" id="modal_thanks" class="modal">
	<h2 class="modal__title center">Спасибо за заказ</h2>
	<p class="modal__subtitle center">Наш менеджер перезвонит вам в течение нескольких минут</p>
	<div class="modal__btn center">
		<button onclick="$.fancybox.close()" class="btn">Закрыть</button>
	</div>
</div>


<?php wp_footer(); ?>

</body>
</html>
