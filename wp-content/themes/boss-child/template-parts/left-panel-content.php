
			<?php $current_user = wp_get_current_user();$logi = $current_user->user_login;?>
			<div id="nav-menu" class="menu-sidebar-droite-container">
				<ul id="menu-sidebar-droite" class="menu">
					<?php
					if(is_super_admin()) {
					?>
					<li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="https://boutique.lapnl.ca/wp-admin/" class="fa-file">Admin</a>
					</li>
					<?php
					}
					?>
					<?php
						if ( in_array( 'abonne', (array) $current_user->roles ) ||  in_array( 'subscriber', (array) $current_user->roles ) || is_admin() || is_super_admin()  ) {
					?>
					<li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="javascript:void(0)" class="fa-file show_sub">Abonné</a>
						<ul class="submenu">
							<li>
								<a href="<?php echo get_site_url();?>/materiels-gratuits/">Matériels gratuits</a>
							</li>
							<li>
								<a href="<?php echo get_site_url();?>/calendrier-2019-2/">Calendrier</a>
							</li>
							<li>
								<a href="<?php echo get_site_url();?>/idcom-int-2/">IDCom int.</a>
							</li>
							<!--li>
								<a href="<?php echo get_site_url();?>/tirage/">Tirage</a>
							</li-->
						</ul>
					</li>
						<?php
						}
					?>
					<li class="menu-item menu-item-type-post_type menu-item-object-page">
					    
						<a href="<?php echo get_site_url();?>/my-account/" class="fa-file">Mon profil</a>
						
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="javascript:void(0)" class="fa-file show_sub">Affiliés</a>
						<ul class="submenu">
							<li><a href="<?php echo get_site_url();?>/connexion-affilie/">Tableau de bord</a></li>
							<li><div class="ab-item" style="color: #FFF;">Produits</div></li>
							<?php
							$var_aff = do_shortcode( '[affiliate_content]<li><a href="https://boutique.lapnl.ca/materiel/pochettes-de-cd-2/">CD Authohypnose</a></li>[/affiliate_content]' );
							$var_aff2 = do_shortcode( '[affiliate_content]<li><a href="https://boutique.lapnl.ca/affilie-general/">Formation en ligne</a></li>[/affiliate_content]' );
							echo $var_aff.$var_aff2;
						    if(empty($var_aff) && (is_admin() || is_super_admin())) {
						    	?><li><a href="https://boutique.lapnl.ca/materiel/pochettes-de-cd-2/">Affilié Pochettes de CD</a></li>
						    	<li><a href="https://boutique.lapnl.ca/affilie-general/">Affilié général</a></li><?php
						    }
							?>
						</ul>
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page">
						<!--a href="<?php echo get_site_url();?>/membres/<?php echo $logi;?>/courses/" class="fa-file">Mes formations</a-->
						<a href="<?php echo get_site_url();?>/les-formations/" class="fa-file">Mes formations</a>
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="javascript:void(0)" class="fa-file show_sub">Matériels</a>
						<?php
							$author_id = $current_user->ID;
							$field_pnl = get_field('pnl', 'user_'. $author_id );
							$field_hypnose = get_field('hypnose', 'user_'. $author_id );
							$field_coaching = get_field('coaching', 'user_'. $author_id );
							$field_developpement_personnel = get_field('developpement_personnel', 'user_'. $author_id );
							$field_mouvement_oculaires = get_field('mouvement_oculaires', 'user_'. $author_id );
						?>
						<ul class="submenu">
							<?php
								$lpuser_id            = get_current_user_id();
								$lpatts               = apply_filters( 'bp_learndash_user_courses_atts', array() );
								$lpuser_courses       = apply_filters( 'bp_learndash_user_courses', ld_get_mycourses( $lpuser_id,  $lpatts ) );
								$arr_res = array();
								if(!empty($lpuser_courses)) {
							        foreach($lpuser_courses as $lpcourse_id) {
							        	if($lpcourse_id == 1824) {
							        		$arr_res[0]='<li><a href="https://boutique.lapnl.ca/materiels-de-la-formation-pnl-de-base/">PNL de Base</a></li>';
					        			}
							        	if($lpcourse_id == 13281) {
							        		$arr_res[1]='<li><a href="https://boutique.lapnl.ca/materiels-de-la-formation-pnl-praticien/">PNL Praticien</a></li>';
					        			}
					        			if($lpcourse_id == 16100) {
					        				$arr_res[2]='<li><a href="https://boutique.lapnl.ca/materiels-de-la-formation-maitre-praticien-pnl/">Maître Praticien PNL</a></li>';
					        			}	
							        	if($lpcourse_id == 13269) {
							        		$arr_res[3]='<li><a href="https://boutique.lapnl.ca/materiels-de-la-formation-hypnose-ericksonnienne-de-base/">Hypnose de Base</a></li>';
					        			}		
							        	if($lpcourse_id == 17099) {			        			
					        				$arr_res[4]='<li><a href="https://boutique.lapnl.ca/materiels-de-la-formation-hypnose-praticien">Hypnose Praticien</a></li>';
					        			}
							        	if($lpcourse_id == 17100) {		
					        				$arr_res[5]='<li><a href="https://boutique.lapnl.ca/materiels-de-la-formation-hypnose-maitre-praticien">Hypnose Maître Praticien</a></li>';
					        			}	
							        	if($lpcourse_id == 13257) {
							        		$arr_res[6]='<li><a href="https://boutique.lapnl.ca/materiels-de-la-formation-ema-amo-niveau-1/">EMA / AMO™ niveau 1</a></li>';
					        			}
							        	if($lpcourse_id == 14351) {
							        		$arr_res[7]='<li><a href="https://boutique.lapnl.ca/materiels-de-la-formation-ema-amo-niveau-2/">EMA / AMO™ niveau 2</a></li>';
					        			}
							        }    	
							        if(sizeof($arr_res)>0) {
							        	for($i=0;$i<sizeof($arr_res);$i++) {
							        		echo $arr_res[$i];
							        	}
							        }
							    }
							?>
						</ul>
					</li>					
					<!--li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="<?php echo get_site_url();?>/membres/<?php echo $logi;?>/membership/" class="fa-file">Membre</a>
					</li-->
					<!--li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="<?php echo get_site_url();?>/membres/<?php echo $logi;?>/forums/" class="fa-file">Forum</a>
					</li-->
					<li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="<?php echo get_site_url();?>/soutien-pedagogique/" class="fa-file">Soutien pédagogique</a>
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="<?php echo get_site_url();?>/soutien-technique/" class="fa-file">Soutien technique</a>
					</li>
					<li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="javascript:void(0);" class="fa-file link_faq">FAQ</a>
						<ul class="links_faq">
					<?php
						$lpuser_id            = get_current_user_id();
						$lpatts               = apply_filters( 'bp_learndash_user_courses_atts', array() );
						$lpuser_courses       = apply_filters( 'bp_learndash_user_courses', ld_get_mycourses( $lpuser_id,  $lpatts ) );
						if(!empty($lpuser_courses)) {
					        foreach($lpuser_courses as $lpcourse_id) {
					        	$lpcourse = get_post($lpcourse_id);
					        	?><li><a href="<?php echo get_site_url();?>/faq-formation/?form=<?php echo $lpcourse_id;?>"><?php echo $lpcourse->post_title; ?></a></li><?php
					        }    	
					    }        	
					?>
						</ul>
					</li>
					<!--li class="menu-item menu-item-type-post_type menu-item-object-page">
						<a href="javascript:void(0);" class="fa-file show_sub">Forums</a>
						<ul class="submenu">
					<?php
						if(!empty($lpuser_courses)) {
					        foreach($lpuser_courses as $lpcourse_id) {
					        	if($lpcourse_id == 1824) {
					        		?><li><a href="https://boutique.lapnl.ca/forums/forum/base-en-ligne/">PNL de Base</a></li><?php
			        			}
					        }    	
					    }        	
					?>
						</ul>
					</li-->
					<!--li class="menu-item menu-item-type-custom menu-item-object-custom">
						<a href="<?php echo get_site_url();?>/abonnement-gratuit-salon/" class="fa-file">Abonnement</a>
					</li-->
					
					<li class="menu-item menu-item-type-custom menu-item-object-custom">
						<a href="<?php echo wp_logout_url();?>" class="fa-file">Déconnexion</a>
					</li>
				</ul>
			</div>
			<?php
			if ( !is_page_template( 'page-no-buddypanel.php' ) && boss_get_option( 'boss_layout_style' ) == 'fluid' ) {
				echo wp_nav_menu(
				array( 'theme_location' => 'left-panel-menu',
					'container_id'	 => 'nav-menu',
					'fallback_cb'	 => '',
					'depth'			 => 2,
					'echo'			 => false,
					'walker'		 => new BuddybossWalker
				)
				);
			}
			?>

			<!-- Adminbar -->
			<div class="bp_components mobile">
				<?php buddyboss_adminbar_myaccount(); ?>

				<?php
				if ( !is_page_template( 'page-no-buddypanel.php' ) && !(!boss_get_option( 'boss_panel_hide' ) && !is_user_logged_in()) ) {
					wp_nav_menu( array( 'theme_location' => 'header-my-account', 'container_class' => 'boss-mobile-porfile-menu', 'fallback_cb' => '', 'menu_class' => 'links', 'depth' => 2, 'walker' => new BuddybossWalker ) );
				}
				?>

				<!-- Register/Login links for logged out users -->
				<?php if ( !is_user_logged_in() && buddyboss_is_bp_active() ) : ?>

					<?php if ( buddyboss_is_bp_active() && bp_get_signup_allowed() ) : ?>
						<a href="<?php echo bp_get_signup_page(); ?>" class="register-link screen-reader-shortcut"><?php _e( 'Register', 'boss' ); ?></a>
					<?php endif; ?>

					<a href="<?php echo wp_login_url(); ?>" class="login-link screen-reader-shortcut"><?php _e( 'Login', 'boss' ); ?></a>

				<?php endif; ?>
				
				<?php if(is_user_logged_in()): ?>
				    <a href="<?php echo wp_logout_url(); ?>" class="logout-link screen-reader-shortcut"><?php _e( 'Logout', 'boss' ); ?></a>
				<?php endif; ?>
			</div>
			<div class="articles_bar">
				<h3><a href="https://boutique.lapnl.ca/blog/">Articles</a></h3>
				<?php
				$args = array(
				    'posts_per_page'   => 3,
				    'orderby'          => 'date',
				    'order'            => 'DESC',
				);

				$articles = get_posts($args);
				if(sizeof($articles)>0) {
					foreach($articles as $art) {
						
						?>
					<div class="ab_one">
						<div class="ab_title"><a href="<?php echo get_permalink($art->ID);?>"><?php echo $art->post_title;?></a></div>
						<div class="ab_content"><?php echo wp_trim_words(wp_strip_all_tags(strip_shortcodes($art->post_content)), 15);?><br/><a href="<?php echo get_permalink($art->ID);?>">En savoir plus</a></div>
					</div>	
						<?php
					}
				}
				?>
			</div>
