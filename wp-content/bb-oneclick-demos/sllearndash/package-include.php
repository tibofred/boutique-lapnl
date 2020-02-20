<?php
/*
 * Include Engine for BuddyBoss One Click
 * Uses bb-oneclick translate slug from one click plugin.
 * This code can be only executed via one click only.
 * Warning :- Take Backup Before Doing Anything,
 **/

// Exit if accessed directly
if (!defined('ABSPATH'))
  exit;

error_reporting(0);

define('buddyboss_exporter_default_domain','demo.example.com');

class buddyboss_importer_package_installer {

    private $api = "https://www.buddyboss.com/wp-admin/admin-ajax.php?action=one_click_api";

    function __construct(){
        $this->hooks();
        $this->includes();
    }

    function hooks() {

        add_action("admin_init",array($this,"init"));
        add_action("init",array($this,"init"));

        add_action("init",array($this,"force_login"));
        add_action("admin_head",array($this,"admin_head"));
        add_action('admin_menu', array($this,"admin_menu"),20);
        add_action( 'wp_ajax_bb_importor_admin_action', array($this,"the_action") );
        add_action( 'wp_ajax_nopriv_bb_importor_admin_action', array($this,"the_action") );

        if(isset($_GET["page"] ) AND $_GET["page"] == "buddyboss-one-click-importer") {
            wp_enqueue_style( 'one-click-style', $GLOBALS["bb_oneclick_workingurl"] . '/assets/style.css' );
            wp_enqueue_script( 'circle-progress', $GLOBALS["bb_oneclick_workingurl"] . '/assets/circle-progress.js', array( 'jquery' ) );
        }
        add_action("admin_init",array($this,"the_page_actions"));
        add_filter("wp_redirect",array($this,"stop_wp_redirect"),999);

        add_filter("buddyboss_importer_table_data",array($this,"buddyboss_importer_table_data"),10,2);

    }

    function init() {
        $this->api = apply_filters("buddyboss_importer_api",$this->api);
    }

    function admin_head() {
        ?>
        <style>
        a[href$='buddyboss-one-click-importer'] {
            display: none!important;
        }
        </style>
        <?php
    }

    function admin_menu() {

        if(function_exists("register_buddyboss_menu_page")) {

            add_submenu_page(
              'buddyboss-settings'
            , __('BuddyBoss One Click',"bb-oneclick")
            , __('BuddyBoss One Click',"bb-oneclick")
            , 'manage_options'
            , 'buddyboss-one-click-importer'
            , array($this,"page_ui")
            );

        } else {

            add_submenu_page(
              'admin.php'
            , __('BuddyBoss One Click',"bb-oneclick")
            , __('BuddyBoss One Click',"bb-oneclick")
            , 'manage_options'
            , 'buddyboss-one-click-importer'
            , array($this,"page_ui")
            );

        }

    }

    function includes() {
        require_once(dirname(__FILE__)."/functions.php");
    }

    function the_page_actions() {

        if(isset($_GET["page"] ) AND $_GET["page"] == "buddyboss-one-click-importer") {

            buddyboss_importer_variables_restore();

            global $bb_importer_process;

            $this->load_configuration();
            $this->load_package();

            /*
             * Toc actions.
             **/
            if(isset($_POST["buddyboss_oneclick_toc"]) AND wp_verify_nonce($_POST["buddyboss_oneclick_toc"],"buddyboss_oneclick_toc")) {
                $bb_importer_process["is_tos_accepted"] = "1";
            }
            /**
             *  Installation Page Action
             * */
            if(isset($_POST["bb_importer_plugin_install"]) AND wp_verify_nonce($_POST["bb_importer_plugin_install"],"bb_importer_plugin_install")) {
                if($this->is_required_plugins_installed()) {
                    $bb_importer_process["process_data_install"]= "1";
                }
            }

            if(isset($_GET["cancel_installation"]) AND wp_verify_nonce( $_GET["cancel_installation"],"cancel_installation" )) {
                unset($bb_importer_process);

                // Call delete packages files action
                if(function_exists("buddyboss_oneclick_installer_delete_packages")) {
                    buddyboss_oneclick_installer_delete_packages();
                }

                $_SESSION["bb_oneclick_installing_package"] = null;
                $_SESSION["bb_importer_process"] = null;

                wp_redirect(admin_url("/"));
                exit;
            }


            buddyboss_importer_variables_save();


        }

    }

    function page_ui() {

        if(!current_user_can('manage_options')) {
            wp_die(__("You don't have permission to access this page.","bb-oneclick"));
        }

        $this->load_configuration();
        $this->load_package();

    ?>

        <div class="wrap bboneclick-importer">
            <h2><img src="data:image/png;base64,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" style=" width: 30px; height: 30px; vertical-align: middle; "/> <?php _e("BuddyBoss One Click Installer","bb-oneclick"); ?></h2>

            <?php
                $this->cancel_installation_button();
            ?>

            <?php

            if(!$this->is_tos_accepted()) {

                $this->tos_screen();

            }
            else if(!$this->is_required_plugins_installed() OR !$this->can_process_data_install()) {

                $this->plugin_installer_screen();

            } else {

               $this->data_installer_screen();

            }

            ?>

        </div>

        <script>

            jQuery(document).ready(function(){

                jQuery(document).on("submit",".bboneclick-importer form",function(e){
                    e.preventDefault();
                    _this = jQuery(this);
                    _this.find("[type='submit']").first().prop("disabled",true);
                    _this.find("[type='submit']").data("beingsubmit",'1');

                    post = jQuery.post(window.location.pathname+window.location.search,jQuery(_this).serialize());

                    post.done(function(d){
                        res = jQuery(d);
                        if(res.find(".bboneclick-importer").length > 0) {
                            jQuery(".bboneclick-importer").replaceWith(res.find(".bboneclick-importer"));
                        } else { //looks like redirect happens.
                            _this.find("[type='submit']").first().prop("disabled",false);
                            _this.trigger("click");
                        }
                    });

                    post.fail(function(){
                        alert("There is error while connecting to internet.");
                    });

                    post.always(function(){
                        _this.find("[type='submit']").data("beingsubmit",'0');
                        _this.find("[type='submit']").first().prop("disabled",false);
                    });

                });

            });

        </script>
        <?php

    }

    function tos_screen() {

        ?>
        <div class="bb_before_use_context">
            <h2><?php _e("Warning! Please read carefully before proceeding.","bb-oneclick"); ?></h2>

            <form target="_self" method="post">
            <div class="content">

                <p>This installer is going to delete all existing database tables on this site, and replace them with new data from our demo. Once you begin, you cannot turn back.</p>
                <p>If you will ever need the data on this site, <a href="https://codex.wordpress.org/WordPress_Backups" target="_blank">create a backup</a> before proceeding! Do not run this on a live website.</p>
                <p>BuddyBoss is not responsible for data loss, server errors, or any other damages that occur when running the installer.</p>

                <div class="action submit">

                    <a class="exit" href="<?php echo admin_url(); ?>"><?php _e("Exit","bb-oneclick"); ?></a>

                    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e("I Agree to the Terms","bb-oneclick"); ?>">

                    <?php wp_nonce_field("buddyboss_oneclick_toc","buddyboss_oneclick_toc"); ?>

                </div>
            </div>
            </form>

        </div>
        <?php

    }

    function cancel_installation_button() {
        global $bb_importer_process;

        if(isset($bb_importer_process["is_tos_accepted"])) {

            echo '<a class="button cancel_install" href="'.wp_nonce_url(admin_url('admin.php?page=buddyboss-one-click-importer'), 'cancel_installation', 'cancel_installation').'">'.__("Cancel Installation","bb-oneclick").'</a><div style="clear:both"></div>';

        }

    }

    /*
     * Outputs the plugin installer screen
     **/
    function plugin_installer_screen() {
        global $bb_importer_process;

        error_reporting(0);

        $required_plugins   = $bb_importer_process["package"]->required_plugins();
        $optional_plugins   = $bb_importer_process["package"]->optional_plugins();
        $required_themes   = $bb_importer_process["package"]->required_theme();

        ?>

        <div id="message" class="updated startinstallbtn-container">
            <form method="post">

                <?php
                wp_nonce_field( 'bb_importer_plugin_install','bb_importer_plugin_install' );
                ?>

                <p><?php _e("Make sure all required themes and plugins are activated first.","bb-oneclick"); ?></p>

                <button type="submit" class="button startinstallbtn"><?php _e("One Click Install","bb-oneclick"); ?></button>

            </form>
        </div>


        <div class="fullspinner"><div class="spinnerr"></div></div>
        <h2><span class="dashicons dashicons-admin-appearance"></span> <?php _e("Required Themes","bb-oneclick"); ?></h2>
        <p><?php echo sprintf(__("You need to activate the themes below before running the One Click Installer.","bb-oneclick"),$bb_importer_process["package_name"]); ?></p>

        <div class="required_plugins plugins_list">

            <?php foreach($required_themes as $slug => $plugin):

            $plugin["is_theme"] = true;
            ?>

                <div class="plugin">

                    <div class="plugin_icon">
                        <?php
                        if($plugin["hosting"] == "buddyboss") {
                            echo '<span class="buddyboss"></span>';
                        } else {
                            echo '<span class="noimage" style="background-color:'.sprintf('#%06X', mt_rand(0, 0xFFFFFF)).'"></span>';
                        }
                        ?>
                    </div>
                    <div class="plugin_content">
                        <h4><a href="<?php echo $plugin["url"]; ?>" target="_blank"><?php echo $plugin["name"]; ?></a></h4>
                        <div class="premium <?php echo ($plugin["premium"])?'premium':'free'; ?>">
                            <?php echo ($plugin["premium"])?__('Premium',"bb-oneclick"):__('Free',"bb-oneclick"); ?>
                        </div>

                        <div class="action">
                        <?php echo $this->plugin_action_button($plugin); ?>
                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div style="clear:both"></div>

        <h2><span class="dashicons dashicons-admin-plugins"></span> <?php _e("Required Plugins","bb-oneclick"); ?></h2>
        <p><?php echo sprintf(__("You need to activate the plugins below before running the One Click Installer.","bb-oneclick"),$bb_importer_process["package_name"]); ?></p>

        <div class="required_plugins plugins_list">

            <?php
            if(empty($required_plugins)) {

                echo '<p>'.__("No plugins are required list.","bb-onclick").'</p>';

            }
            foreach($required_plugins as $slug => $plugin): ?>

                <div class="plugin">

                    <div class="plugin_icon">
                        <?php
                        if($plugin["hosting"] == "wordpress") {
                            $image_url = "https://ps.w.org/{$slug}/assets/icon-256x256.png";

                            if (false !== file_get_contents($image_url,0,null,0,1)) {
                                echo '<img src="'.$image_url.'" />';
                            } else {
                                 echo '<span class="noimage" style="background-color:'.sprintf('#%06X', mt_rand(0, 0xFFFFFF)).'"></span>';
                            }
                        }
                        elseif($plugin["hosting"] == "buddyboss") {
                            echo '<span class="buddyboss"></span>';
                        } else {
                            echo '<span class="noimage" style="background-color:'.sprintf('#%06X', mt_rand(0, 0xFFFFFF)).'"></span>';
                        }
                        ?>
                    </div>
                    <div class="plugin_content">


                        <h4><a href="<?php echo $plugin["url"]; ?>" target="_blank"><?php echo $plugin["name"]; ?></a></h4>
                        <div class="premium <?php echo ($plugin["premium"])?'premium':'free'; ?>">
                            <?php echo ($plugin["premium"])?__('Premium',"bb-oneclick"):__('Free',"bb-oneclick"); ?>
                        </div>

                        <div class="action">
                        <?php echo $this->plugin_action_button($plugin); ?>
                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div style="clear:both"></div>

        <h2><span class="dashicons dashicons-admin-plugins"></span> <?php _e("Optional Plugins","bb-oneclick"); ?></h2>
        <p><?php echo (__("The plugins below are optional, but recommended.","bb-oneclick")); ?></p>
        <div class="optional_plugins plugins_list">

            <?php

            if(empty($optional_plugins)) {

                echo '<p>'.__("There are no recommended plugins for this package.","bb-onclick").'</p>';

            }

            foreach($optional_plugins as $slug => $plugin): ?>

                <div class="plugin">

                    <div class="plugin_icon">
                        <?php
                        if($plugin["hosting"] == "wordpress") {
                            $image_url = "https://ps.w.org/{$slug}/assets/icon-256x256.png";
                            if (false !== file_get_contents($image_url,0,null,0,1)) {
                                echo '<img src="'.$image_url.'" />';
                            } else {
                                 echo '<span class="noimage" style="background-color:'.sprintf('#%06X', mt_rand(0, 0xFFFFFF)).'"></span>';
                            }
                        }
                        elseif($plugin["hosting"] == "buddyboss") {
                            echo '<span class="buddyboss"></span>';
                        } else {
                            echo '<span class="noimage" style="background-color:'.sprintf('#%06X', mt_rand(0, 0xFFFFFF)).'"></span>';
                        }
                        ?>
                    </div>
                    <div class="plugin_content">
                        <h4><a href="<?php echo $plugin["url"]; ?>" target="_blank"><?php echo $plugin["name"]; ?></a></h4>

                        <div class="premium <?php echo ($plugin["premium"])?'premium':'free'; ?>">
                            <?php echo ($plugin["premium"])?__('Premium',"bb-oneclick"):__('Free',"bb-oneclick"); ?>
                        </div>

                        <div class="action optional">
                        <?php echo $this->plugin_action_button($plugin); ?>
                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

 <div style="clear:both"></div>


          <div class="css-popup-container bbpromopopup" style="display:none">
            <div class="css-popup">
              <span class="close" onclick="jQuery('.bbpromopopup').hide();"></span>
                <p class="text"></p>
                <a href="#" class="button" target="_blank"><?php _e("Buy Now","bb-oneclick"); ?></a>
                <p class="info"><?php _e("Once you purchase the product come back and try again.","bb-oneclick"); ?></p>
            </div>
          </div>




<div class="auth_login_tmp" style="display: none">
    <div class="auth_login">
    <h4>
        <?php _e("Login to BuddyBoss to install this product.","bb-oneclick"); ?>
    </h4>
    <a href=""><?php _e("Login","bb-oneclick"); ?></a>
    </div>
</div>
        <script>

            function update_install_data_button() {

                if(jQuery(".startinstallbtn").data("beingsubmit") == "1") {
                    return false;
                }

                jQuery(".startinstallbtn").prop("disabled",false);

                jQuery(".install-plugin").not(".manual").each(function(){

                    if(jQuery(this).parent().hasClass("optional")) { //Skip optional
                        return true;
                    }

                    if(!jQuery(this).hasClass("button-disabled")) {
                        jQuery(".startinstallbtn").prop("disabled",true);
                        return false;
                    }

                });

            }

            function update_plugin_action_button() {
                jQuery(".required_plugins.plugins_list a.install-plugin").each(function() {
                    jQuery(this).addClass("disabled");
                });

                jQuery(".required_plugins.plugins_list").find("a.install-plugin:visible").first().removeClass("disabled");
            }

            jQuery(document).ready(function(){

                    setInterval(function(){ update_install_data_button() },500);
                    setInterval(function(){ update_plugin_action_button() },500);

                    jQuery(document).on("click",".plugin-install-btn",function(e){

                            e.preventDefault();

                            // if button is disabled then alert reason.
                            if(jQuery(this).hasClass("disabled")) {
                                var plugin_to_install_name = jQuery(".required_plugins.plugins_list").find("a.install-plugin:visible").first().parent().parent().find('h4').text();
                                alert("Please install "+plugin_to_install_name+" first.");
                                return;
                            }

                            var _this = jQuery(this);

                            if (_this.find(".spinner").hasClass("is-active")) { /* Don't Proceed If already on progress.*/
                                return false;
                            }

                            _this.find(".spinner").addClass("is-active");
                            _post_data = {action:'bb_importor_admin_action','do_action':'install_plugin','nonce':_this.data("nonce"),'plugin':_this.data("plugin")};
                            var post = jQuery.post(ajaxurl,_post_data,function(){},'json');

                            post.done(function(d){
                              //  console.log(_this);
                                if(!d || d==""){

                                    _this.find(".spinner").removeClass("is-active");

                                    if(jQuery(_this).data("try") > 2) {
                                        alert("Error while completing this request try this action again.");
                                        return false;
                                    }

                                    trys = parseInt(jQuery(_this).data("try"));
                                    trys++;
                                    jQuery(_this).data("try",trys);

                                    jQuery(_this).trigger("click");

                                    return false;

                                }

                                if(!d.success) {
                                    _this.find(".spinner").removeClass("is-active");
                                    alert(d.data.text);
                                } else {

                                    if(typeof d.data == 'undefined') {
                                        d.data = [];
                                    }

                                    if(typeof d.data.oauth_url != 'undefined') {

                                        var auth_div = jQuery(jQuery(".auth_login_tmp").html());
                                        jQuery(auth_div).find("a").click(function(e){
                                            auth_div.remove();
                                            e.preventDefault();
                                            var left = Number((screen.width/2)-(390/2));
                                            var top = Number((screen.height/2)-(555/2));
                                            window.open(d.data.oauth_url, "", "width=390,height=555,top="+top+",left="+left+"");
                                            jQuery(".fullspinner").show();
                                            _this.find(".spinner").removeClass("is-active");
                                            window.last_action_btn = _this;
                                        });
                                        jQuery(_this).parent().parent().append(auth_div);

                                        return false;
                                    }
                                    if(typeof d.data.product_promo != 'undefined') {
                                        jQuery(".bbpromopopup").find(".text").html(d.data.text);
                                        jQuery(".bbpromopopup").find(".button").attr("href",d.data.product_url);
                                        jQuery(".bbpromopopup").fadeIn();
                                        _this.find(".spinner").removeClass("is-active");
                                        return false;
                                    }
                                    _this.parent().find(".installed").fadeIn();
                                    _this.remove();
                                }
                            });

                            post.fail(function(e){
                                console.log("Oneclick Debug : "+e);
                                alert("Unknown error while installing plugin.");
                            });

                    });

            });


           window.retrigger_last_action = function() {
                jQuery(window.last_action_btn).trigger("click");
                jQuery(".fullspinner").hide();
            }

           window.oauth_deny = function() {
                jQuery(".fullspinner").hide();
            }


        </script>

        <?php

    }



    /*
     * return the plugin action button
     **/
    function plugin_action_button($plugin) {
        global $bb_importer_process;
        $plugin_dir = buddyboss_importer_get_plugin_dir();
        $theme_dir = buddyboss_importer_get_themes_dir();

        $text = "";


        if(!in_array($plugin["hosting"], array("wordpress","buddyboss")) AND !file_exists($plugin_dir.$plugin["plugin_path"])) {



            $text = __("Install Manually","bb-oneclick");
            $return .= '<a href="'.$plugin["url"].'" target="_blank" class="install-plugin manual button"><span class="spinner"></span>'.$text.' </a>';




        } else {

            if(@$plugin["is_theme"] === true) {
                if(!file_exists($theme_dir.$plugin["slug"])) {
                    $text = __("Install Now","bb-oneclick");
                }
            } else {
                if(!file_exists($plugin_dir.$plugin["plugin_path"])) {
                    $text = __("Install Now","bb-oneclick");
                }
            }

            if(@$plugin["is_theme"] === true) {

                if(get_stylesheet() != $plugin["slug"] AND file_exists($theme_dir.$plugin["slug"])) {
                    if($bb_importer_process["package"]->theme() == $plugin["slug"]){ // only show it if perticuler theme need to update
                        $text = __("Activate","bb-oneclick");
                    }
                }

            } else {
                $is_plugin_activated = is_plugin_active($plugin["plugin_path"]);

                if(!$is_plugin_activated AND file_exists($plugin_dir.$plugin["plugin_path"])) {
                    $text = __("Activate","bb-oneclick");
                }
            }

            $wp_nonce = wp_create_nonce( 'bboneclick_install_plugin' );

            $extras = "";

            if(@$plugin["is_theme"] === true) {
                    $extras = 'data-is_theme="1"';
            }

            if(!empty($text)) {
                $return .= '<a href="#" class="install-plugin button plugin-install-btn" '.$extras.' data-plugin="'.$plugin["slug"].'" data-nonce="'.$wp_nonce.'"><span class="spinner"></span>'.$text.' </a>';

            }

            $return .= '<span class="install-plugin installed button button-disabled" style="'.((!empty($text))?'display:none':'').'">Installed</span>';


        }

        return $return;
    }

    /*
     * Outputs the data installer screen.
     **/
    function data_installer_screen() {

    ?>
        <div class="process_list">
            <div class="inner">
            <ul>
               <?php
               $tmp_data = array();
               $config_tasks = $this->config_tasks();
               foreach($this->process_tasks() as $process):

                    $parameters = $process["parameters"];
                    $process_name      = $process["label"];
                    $process_id      = $process["id"];
                    $process    = $process["process"];

                    $extra_class = "";
                    $search = buddyboss_array_earch($config_tasks,"process",$process);
                    if(!empty($search)) {
                        if(empty($tmp_data["is_config_warped"])) {
                            $tmp_data["is_config_warped"] = "1";
                            echo '<label class="header">'.__("Configurations","bb-oneclick").'</label>';
                        }
                    }

                    if(!empty($tmp_data["is_config_warped"])) {
                            $extra_class .= " configuration";
                    }

                    echo '<li class="process-'.$process_id.' '.$extra_class.'">'.$process_name.' <span class="spinner"></span> <span class="dashicons dashicons-yes"></span></li>';



               endforeach;
               ?>
            </ul>
            </div>

            </div>

            <div class="process_log">


            <div class="progressbar meter">
                <span style="width: 0%"><span>0%</span></span>
            </div>

            <div class="process_mini_log">
                <div id="wrappert">
                </div>
                <div class="inner">
                </div>
                <div id="wrapperd">
                </div>
            </div>

            <div class="second circle"><canvas width="100" height="100"></canvas>
                <strong><span class="count">0</span></strong>
                <span class="process_name"></span>
            </div>


            </div>

            <div class="css-popup-container bbcompletepopup" style="display:none">
                <div class="css-popup">
                  <span class="close" onclick="jQuery('.bbcompletepopup').hide();"></span>
                    <p class="text"><?php _e("Success! The demo import is complete.","bb-oneclick"); ?></p>
                    <a href="<?php echo get_home_url(); ?>" class="button" target="_blank"><?php _e("View Site","bb-oneclick"); ?></a>
                    <div class="extra_text"></div>
                </div>
            </div>


            <script>

                (function($) {
                    $.fn.counter = function(options) {
                       // Set default values
                       var defaults = {
                           start: 0,
                           end: 10,
                           time: 10,
                           step: 1000,
                           callback: function() { }
                       }
                       var options = $.extend(defaults, options);
                       // The actual function that does the counting
                       var counterFunc = function(el, increment, end, step) {
                           var value = parseInt(el.html(), 10) + increment;
                           if(value >= end) {
                               el.html(Math.round(end)+"<i>%</i>");
                               options.callback();
                           } else {
                               el.html(Math.round(value)+"<i>%</i>");
                               setTimeout(counterFunc, step, el, increment, end, step);
                           }
                       }
                       // Set initial value
                       $(this).html(Math.round(options.start));
                       // Calculate the increment on each step
                       var increment = (options.end - options.start) / ((1000 / options.step) * options.time);
                       // Call the counter function in a closure to avoid conflicts
                       (function(e, i, o, s) {
                           setTimeout(counterFunc, s, e, i, o, s);
                       })($(this), increment, options.end, options.step);
                   }
               })(jQuery);


                jQuery(document).ready(function(){

                    window.current_cirle_value = 0;

                    jQuery('.second.circle').circleProgress({
                        value: 0.0,
                        size:200
                    }).on('circle-animation-progress', function(event, progress) {
                       // jQuery(this).find('strong').html('<span class="count">'+parseInt(100 * progress)+'</span>' + '<i>%</i>');
                    });

                    jQuery(".process_list").find("li").first().find(".spinner").addClass("is-active");
                    buddyboss_importer_task();


                });

                function buddyboss_importer_task() {
                    $post = jQuery.post(ajaxurl,{'action':'bb_importor_admin_action','nonce':'<?php echo wp_create_nonce( 'buddyboss-importer' ); ?>'});


                    $post.fail(function(){
                        add_log("There is some network error while exporting data.",true);
                    });

                    $post.done(function(d){
                        try {
                            if (!d.success) {
                                if(d.data == "reload") {
                                    location.reload();
                                    return false;
                                }
                                add_log(d.data,true);
                            } else {

                                if(typeof d.data.reload !== 'undefined') {
                                   location.reload();
                                   return false;
                                }

                                if (d.data.is_process_completed) {
                                   jQuery(".process-"+d.data.process).addClass("done");
                                   jQuery(".process-"+d.data.process).nextAll("li").first().find(".spinner").addClass("is-active");
                                }

                                // Mark all completed process
                                if(typeof d.data.completed_processes !== 'undefined') {
                                    jQuery.each(d.data.completed_processes,function(i,d){
                                      jQuery(".process-"+d).addClass("done");
                                    });

                                    jQuery(".process_list").find(".done").last().nextAll("li").first().find(".spinner").addClass("is-active");
                                }

                                add_log(d.data.log);

                                percentage_point = parseFloat(d.data.percentage) / 100.0;

                                jQuery('.second.circle').circleProgress({
                                    value: percentage_point,
                                    size:200,
                                    animationStartValue:window.current_cirle_value
                                }).on('circle-animation-progress', function(event, progress) {
                                });

                                old_per = jQuery(".second.circle").data("percentage");

                                jQuery(".second.circle").find(".count").counter({
                                    start: old_per,
                                    end: d.data.percentage,
                                    time: 2,
                                    step: 150
                                });

                                jQuery(".second.circle").data("percentage",d.data.percentage);

                                jQuery(".process_name").text(jQuery(".process_list").find(".done").last().nextAll("li").first().text());

                                window.current_cirle_value = percentage_point;

                                update_overall_progressbar(d.data.percentage);

                                try {
                                //scroll
                                jQuery(".process_list ul").scrollTop(0);
                                jQuery(".process_list ul").scrollTop(jQuery(".process_list").find(".done").last().nextAll("li").first().offset().top - jQuery(".process_list ul").offset().top - 200);
                                } catch(e) {

                                }

                                if (d.data.process != "done") {
                                    setTimeout(function() { buddyboss_importer_task();  },1000); //run next task
                                } else {
                                    jQuery(".progressbar").addClass("done");
                                    // make sure it goes 100%
                                    jQuery(".progressbar").find("span").text('100%');
                                    jQuery(".bbcompletepopup").fadeIn();
                                    jQuery(".bbcompletepopup").find(".extra_text").html(d.data.extra);
                                    jQuery("a.button.cancel_install").hide();
                                }

                            }
                        } catch(e) {
                            console.log(e);
                            add_log("There is some unknown error while exporting data.",true);
                        }
                    });
                }

                function update_overall_progressbar(subpercentage) {

                    total_tasks = jQuery(".process_list").find("li").length;
                    done_tasks = jQuery(".process_list").find("li.done").length;
                    done_tasks_percentage = (done_tasks/total_tasks)*100;
                    one_task_overall_percentage = 100/total_tasks;
                    console.log(one_task_overall_percentage);
                    subper_percentage = (subpercentage/100)*one_task_overall_percentage;
                    //append sub percentage
                    percentage = done_tasks_percentage;
                    if (done_tasks_percentage != one_task_overall_percentage) {
                        percentage = subper_percentage+done_tasks_percentage;
                    }
                    if (percentage > 100) {
                        percentage = 100;
                    }

                    jQuery(".progressbar").find("span").first().css("width",percentage+"%");
                    old_per = jQuery(".progressbar").data("percentage");
                    jQuery(".progressbar").data("percentage",percentage);
                    jQuery(".progressbar").find("span").first().find("span").counter({
                        start: old_per,
                        end: percentage,
                        time: 2,
                        step: 200
                    });
                }

                function add_log(data,fatal) {

                    if(typeof data == 'undefined') {
                        data = "Unknown Response.";
                    }

                    if (fatal == true) {
                        data = '<span class="error">Stopping - '+data+'<span>';
                    }

                    log = jQuery("<p>"+data+"</p>");
                    log.hide();
                    jQuery(".process_mini_log .inner").prepend(log);
                    log.slideDown();

                }

            </script>
            <?php

    }

    function process_tasks() {
        global $bb_importer_process;

        $process_tasks[] = $this->the_process("initialization",__("Initialization","bb-oneclick"));

        // lets add all table data import tasks
        $create_tables = $bb_importer_process["settings"]["create_table"];

        foreach($create_tables as $table => $q) {
            $process_tasks[] = $this->the_process('tables',$this->table_label($table),array($table));
        }

        $process_tasks = apply_filters("buddyboss_importer_process_tasks",$process_tasks);

        $process_tasks[] = $this->the_process('files',__("Files","bb-oneclick"));

        $config_tasks = $this->config_tasks();
        $process_tasks = array_merge($process_tasks,$config_tasks);

        return $process_tasks;

    }


    function config_tasks() {

        $process_tasks  =  array();
        $process_tasks[] = $this->the_process("configuration",__("Finalizing","bb-oneclick"));

        $process_tasks = apply_filters("buddyboss_importer_config_process_tasks",$process_tasks);

        $process_tasks[] = $this->the_process("done",__("Done","bb-oneclick"));


        return $process_tasks;

    }


    /*
     * Handles Install Plugins Things.
     **/
    function the_action_install_plugin() {
        global $bb_importer_process;

        error_reporting(0);
        // ini_set("display_errors", "1");
        // error_reporting(E_ALL);

        $this->load_configuration();
        $this->load_package();

        $required_plugins = $bb_importer_process["package"]->required_plugins();
        $optional_plugins = $bb_importer_process["package"]->optional_plugins();
        $required_themes = $bb_importer_process["package"]->required_theme();

        foreach($required_themes as $k => $v) {
            $required_themes[$k]["is_theme"] = "1";
        }

        $all_plugins = array_merge($required_plugins,$optional_plugins);
        $all_plugins = array_merge($all_plugins,$required_themes); //merge themes.

        $current_plugin = @$all_plugins[$_POST["plugin"]];

        global $being_activate;

        $being_activate = $current_plugin;


        if(!empty($current_plugin)) {

            $plugin_dir = buddyboss_importer_get_plugin_dir();
            $theme_dir = buddyboss_importer_get_themes_dir();

            // If its Theme
            // Currently we only support buddyboss themes only.
            if($current_plugin["is_theme"] == "1") {

               if(!file_exists($theme_dir.$current_plugin["slug"]."/style.css")) { # if file don't exists and need to download.

                    if(!in_array($current_plugin["hosting"], array("buddyboss"))) {
                        wp_send_json_error( array(  'text' => __("Theme doesnt support auto install feature.","bb-onclick")) );
                    }

                    include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

                    if($current_plugin["hosting"] == "wordpress") {

                        $api = plugins_api( 'plugin_information', array( 'slug' => $current_plugin["slug"], 'fields' => array( 'sections' => false ) ) );

                        if ( is_wp_error( $api ) ) {
                            wp_send_json_error( array(  'text' => sprintf( __( 'ERROR: Failed to install plugin: %s', 'boss' ), $api->get_error_message() ) ) );
                        }

                        $download_link = $api->download_link ;

                    }

                    if($current_plugin["hosting"] == "buddyboss") {


                            //fetch theme details from server
                            $plugin_data = $this->buddyboss_api_call(array(
                                          "do"              =>  "data",
                                          "type"            =>  "plugin",
                                          "site_url"        =>  get_home_url(),
                                          "slug"            =>  $current_plugin["slug"]
                                          ));

                            $plugin_data = (array) json_decode($plugin_data,true);

                            if(empty($plugin_data["success"])) {
                                $error = (empty($plugin_data["data"]["response"]))?__("Server is currently down, try later","bb-oneclick"):$plugin_data["data"]["response"];

                                wp_send_json_error( array(  'text' => $error ) );
                                exit;
                            }


                            $download_link = $plugin_data["data"]["download_url"];

                    }

                    $upgrader = new Theme_Upgrader( new bb_oneclick_Plugin_Upgrader_Skin( array(
                        'nonce'  => 'install-plugin_' . $current_plugin["slug"], 'plugin' => $current_plugin["slug"], 'api'  => $api,
                    ) ) );

                    $install_result = $upgrader->install( $download_link );

                    if ( !$install_result || is_wp_error( $install_result ) ) {
                        /* $install_result can be false if the file system isn't writable. */
                        $error_message = __( 'Please ensure the file system is writable', 'boss' );

                        if ( is_wp_error( $install_result ) ) {
                            $error_message = $install_result->get_error_message();
                        }

                        wp_send_json_error( array( 'text' => sprintf( __( 'ERROR: Failed to install theme: %s', 'boss' ), $error_message ) ) );

                    } else { //let activate it.

                        @switch_theme($current_plugin["slug"],$current_plugin["slug"]);

                        wp_send_json_success();

                    }

                } else { # if just need to activate the plugin nothing else.

                    @switch_theme($current_plugin["slug"],$current_plugin["slug"]);

                    wp_send_json_success();

                }

                exit;
            }

            // Else if its Plugin

            if(!file_exists($plugin_dir.$current_plugin["plugin_path"])) { # if file don't exists and need to download.

                if(!in_array($current_plugin["hosting"], array("wordpress","buddyboss"))) {
                    wp_send_json_error( array(  'text' => __("Plugin doesnt support auto install feature.","bb-onclick")) );
                }

                include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

                if($current_plugin["hosting"] == "wordpress") {

                    $api = plugins_api( 'plugin_information', array( 'slug' => $current_plugin["slug"], 'fields' => array( 'sections' => false ) ) );

                    if ( is_wp_error( $api ) ) {
                        wp_send_json_error( array(  'text' => sprintf( __( 'ERROR: Failed to install plugin: %s', 'boss' ), $api->get_error_message() ) ) );
                    }

                    $download_link = $api->download_link ;

                }

                if($current_plugin["hosting"] == "buddyboss") {

                        //fetch plugin details from server
                        $plugin_data = $this->buddyboss_api_call(array(
                                      "do"              =>  "data",
                                      "type"            =>  "plugin",
                                      "site_url"        =>  get_home_url(),
                                      "slug"            =>  $current_plugin["slug"]
                                      ));

                        $plugin_data = (array) json_decode($plugin_data,true);

                        if(empty($plugin_data["success"])) {
                            $error = (empty($plugin_data["data"]["response"]))?__("Server is currently down, try later","bb-oneclick"):$plugin_data["data"]["response"];
                            wp_send_json_error( array(  'text' => $error ) );
                            exit;
                        }


                        $download_link = $plugin_data["data"]["download_url"];

                }

                $upgrader = new Plugin_Upgrader( new bb_oneclick_Plugin_Upgrader_Skin( array(
                    'nonce'  => 'install-plugin_' . $current_plugin["slug"], 'plugin' => $current_plugin["slug"], 'api'  => $api,
                ) ) );

                $install_result = $upgrader->install( $download_link );

                if ( !$install_result || is_wp_error( $install_result ) ) {
                    /* $install_result can be false if the file system isn't writable. */
                    $error_message = __( 'Please ensure the file system is writable', 'boss' );

                    if ( is_wp_error( $install_result ) ) {
                        $error_message = $install_result->get_error_message();
                    }

                    wp_send_json_error( array( 'text' => sprintf( __( 'ERROR: Failed to install plugin: %s', 'boss' ), $error_message ) ) );

                } else { //let activate it.

                    $this->safe_activate_plugin($current_plugin["plugin_path"]);

                    wp_send_json_success();

                }

            } else { # if just need to activate the plugin nothing else.

                $this->safe_activate_plugin($current_plugin["plugin_path"]);

                wp_send_json_success();

            }




        }

        exit;
    }

    function the_action() {
        global $wpdb;

        @session_start();

        buddyboss_importer_variables_restore();

        if(!is_user_logged_in()) {
            if(!empty($_SESSION["buddyboss_oneclick_force_login"])){

                @wp_set_auth_cookie($_SESSION["buddyboss_oneclick_force_login"]);

                 wp_send_json_error("reload");
            }
        }

        // check if user has permission to do all this things.
        if(!current_user_can('manage_options')) {
            wp_send_json_error(array("text"=>__("You don't have permission to do this action.","bb-oneclick")));
            exit;
        }


        if(isset($_POST["do_action"]) AND $_POST["do_action"] == "install_plugin") {

            $this->the_action_install_plugin();
            exit;
        }

        if(isset($_GET["do_action"]) AND $_GET["do_action"] == "bb_callback_deny") {
            echo '
                <script>
                if(window.opener) {
                window.opener.oauth_deny();
                }
                window.close();
                </script>
            ';
            exit;
        }
        if(isset($_GET["do_action"]) AND $_GET["do_action"] == "bb_callback") {

            $oneclick_tokens = get_option("bb_oneclick_buddyboss_token");

            if($oneclick_tokens["request_token"] == $_POST["request_token"]) {
                $oneclick_tokens["access_token"] = $_POST["access_token"];
            }


            update_option("bb_oneclick_buddyboss_token",$oneclick_tokens);

            echo '
                <script>
                if(window.opener) {
                window.opener.retrigger_last_action();
                }
                window.close();
                </script>
            ';
            exit;
        }


        if(!wp_verify_nonce($_POST["nonce"],"buddyboss-importer")) {
            if(!isset($GLOBALS["bb_importer_process"]["configuration"]["left"]) AND !empty($GLOBALS["bb_importer_process"]["configuration"]["left"])) {
                wp_send_json_error(__("Cannot verify the nonce security","bb-oneclick"));
                exit;
            }
        }


        $includes = array(
                          dirname(__FILE__)."/type/initialization.php", // initialization handler.
                          dirname(__FILE__)."/type/tables.php", // tables handler.
                          dirname(__FILE__)."/type/files.php", // tables handler.
                          dirname(__FILE__)."/type/configuration.php", // configuration handler.
                          );


       //Only Uncomment for debug. not for production use.
       // ini_set("display_errors", "1");
       // error_reporting(E_ALL);

        foreach($includes as $include) {
            require_once($include);
        }


        $bb_process = $GLOBALS["bb_importer_process"];

        //empty db if first.
        if(empty($bb_process)) {
            $delete = $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}bb_importer");
        }

        if(!current_user_can("manage_options")) {
            wp_send_json_error(__("You are not allowed to run importer","bb-oneclick"));
            exit;
        }

        $this->load_configuration();

        $this->load_package();


        $export_process = $this->process_tasks();

        $completed_processes = array();

        foreach($export_process as $process) {

            $parameters = $process["parameters"];
            $label      = $process["label"];
            $process_id = $process["id"];
            $process_name = isset( $process["label"] ) ? $process["label"] : '';
            $process    = $process["process"];

            /** Reset All Process Variables */
            if($process == "done") {
                $GLOBALS["bb_importer_process"] = null;
                unset($GLOBALS["bb_importer_process"]);
                break;
            }

            $class_name = "buddyboss_importer_".$process;
            $class = new $class_name;

            if(method_exists($class,"set_params")) {
                $class->set_params($parameters);
            }

            $default_limits = 1;

            if(method_exists($class,"load_limits")) {
                $default_limits = $class->load_limits();
            }

            $i = 0;
            while($class->has_content()):
                $i++;

                //things will import here.
                $class->import();

                $is_process_completed = false;

                if(!$class->has_content()) {
                      $is_process_completed = true;
                }

                $percentage = 0;

                $percentage = $class->get_percentage();

                buddyboss_importer_variables_save();

                if($default_limits <= $i) {

                    if($process == "configuration" AND $percentage >= 100) {
                        $log = $class->log();
                        continue;
                    } else  {
                        wp_send_json_success(array(
                                               "process" => $process,
                                               "process_name" => $process_name,
                                               "is_process_completed" => $is_process_completed,
                                               "percentage" => $percentage,
                                               "log"   => $class->log(),
                                               "completed_processes" => $completed_processes
                                               ));

                    }

                    break; //this will break after task one files.
                }

            endwhile;

            $completed_processes[] = $process_id;
            $GLOBALS["bb_importer_process"]["completed_processes"] = $completed_processes;

        }

        $user_login_changed = @$bb_importer_process["user_login_changed"];

        buddyboss_importer_variables_save();

        // Call delete packages files action
        if(function_exists("buddyboss_oneclick_installer_delete_packages")) {
            buddyboss_oneclick_installer_delete_packages();
        }

        $extra_text = "";


        if(!empty($bb_process["user_login_changed"])) {
            $extra_text = sprintf(__("<p>Your username has been changed to <b>%s</b></p>","bb-oneclick"),$bb_process["user_login_changed"]);
        }

        if ( !function_exists('is_plugin_active') )
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        
        if( is_plugin_active( 'buddyboss-updater/buddyboss-updater.php' ) ){
            //save licenses
            $licenses = get_option( '_bboneclick_license_details' );
            if( !empty( $licenses ) && 'nothing' != $licenses ){
                //update licenses
                //refer plugins/buddyboss-updater/includes/admin.php
                buddyboss_updater_plugin()->admin()->process_bulk( $licenses );
            }
        }
        

        wp_send_json_success(array(
                                           "process" => 'done',
                                           "process_name" => 'Done',
                                           "is_process_completed" => true,
                                           "percentage" => '100',
                                          // "log"   => __("Site demo content and plugins are successfully installed and configured Enjoy.","bb-oneclick"),
                                           "log"   => $log,
                                           "completed_processes" => $completed_processes,
                                           "extra" => $extra_text
                                           ));

        exit;

    }


    static function stop_wp_redirect($value) {
        if(defined( 'DOING_AJAX' ) && DOING_AJAX && $_POST["action"] == "bb_importor_admin_action") {
            return false;
        }
        return $value;
    }

    function safe_activate_plugin($path) {

        if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
            ob_start(array($this,"replace_buffer"), 0, PHP_OUTPUT_HANDLER_STDFLAGS ^
              PHP_OUTPUT_HANDLER_REMOVABLE);
          } else {
            ob_start(array($this,"replace_buffer"), 0, false);
        }


        register_shutdown_function(array($this,'safe_activate_plugin_shutdown'));

        $result = @activate_plugin( $path );

        ob_end_clean();

        if ( is_wp_error( $result ) ) {
                wp_send_json_error(array(
                                 "text" => $result->get_error_message()
                                 ));
        }

    }

    static public function replace_buffer($buffer) {
        global $being_activate;

        if(buddyboss_importer_is_json_string($buffer)) {
            return $buffer;
        }

        $message = sprintf(__("There was a problem while activating, details given below. \n\nPlugin: %s \n\nMessage: ","bb-oneclick"),$being_activate["name"]).$buffer;
        return wp_json_encode(array(
                                    'success'=>false,
                                    'data'=>array(
                                            'text' => strip_tags($message)
                                        )
                                    ));
    }

    static public function safe_activate_plugin_shutdown() {
        ob_end_flush();
        //wp_send_json_error(array("text"=>strip_tags($content)));
        exit;
    }

    function load_configuration() {


        $working_dir = $GLOBALS["bb_oneclick_workingdir"]; //dir where the data exists.

        $GLOBALS["bb_importer_process"]["working_dir"] = $working_dir;

        //load the settings
        $settings = file_get_contents($GLOBALS["bb_importer_process"]["working_dir"]."/data/settings.json");
        $settings = json_decode($settings,true);

        $GLOBALS["bb_importer_process"]["settings"] = $settings;

    }

    function load_package() {


        $package = $GLOBALS["bb_importer_process"]["settings"]["package"];

        require_once(dirname(__FILE__)."/importer_package.php"); //Parent Class.
        require_once(dirname(__FILE__)."/".$package.".php");  // Package Class.

        $class_name = "buddyboss_importer_package_{$package}";
        $GLOBALS["bb_importer_process"]["package"] = new $class_name;
        $GLOBALS["bb_importer_process"]["package_name"] = $package;

        // Include all plugins supported needed by package.
        foreach($GLOBALS["bb_importer_process"]["package"]->supported_plugins() as $plug) {
            require_once(dirname(__FILE__)."/type/{$plug}/load.php");
        }

    }

    /**
     * Will clear access token when expire.
     **/
    function clear_buddyboss_access_token() {
        $oneclick_tokens = get_option("bb_oneclick_buddyboss_token");
        $oneclick_tokens["access_token"] = "";
        update_option("bb_oneclick_buddyboss_token",$oneclick_tokens);
    }

    /*
     * Will get request token.
     **/
    function buddyboss_api_get_request_token() {

        $token = $this->buddyboss_api_call(array(
                                          "do"          => "request_token",
                                          "site_url"    => home_url(),
                                          ));

        $token = (array) json_decode($token);

        if(empty($token["success"])) {
            $error = (!empty($token["data"]["response"]))?$token["data"]["response"]:'Server Responding Unknown Error.';
            return array("error"=>$error);
        }

        return (array) $token["data"];

    }


    function get_buddyboss_auth_login_url() {

        $request_tokens = $this->buddyboss_api_get_request_token();

        if(isset($request_tokens["error"])) {
            return $request_tokens;
        }

        $oneclick_tokens = get_option("bb_oneclick_buddyboss_token");
        if(empty($oneclick_tokens)){ $oneclick_tokens = array(); }
        $oneclick_tokens["consumer_key"] = $request_tokens["consumer_key"];
        $oneclick_tokens["request_token"] = $request_tokens["request_token"];
        update_option("bb_oneclick_buddyboss_token",$oneclick_tokens);

        return ($this->api."&do=allow&token=".$oneclick_tokens["request_token"]);

    }

    function get_buddyboss_access_token() {

        $oneclick_tokens = get_option("bb_oneclick_buddyboss_token");
        if(empty($oneclick_tokens)){ $oneclick_tokens = array(); }

        return @$oneclick_tokens["access_token"];

    }


    function get_buddyboss_consumer_key() {

        $oneclick_tokens = get_option("bb_oneclick_buddyboss_token");
        if(empty($oneclick_tokens)){ $oneclick_tokens = array(); }

        return @$oneclick_tokens["consumer_key"];

    }

    function buddyboss_api_call($data){
        $curl = curl_init($this->api);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        do_action_ref_array('buddyboss_impoter_buddyboss_api_call', array(&$curl) );

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /*
     * Check Weather Required Plugins are installed and activated or not.
     **/
    function is_required_plugins_installed() {
        global $bb_importer_process;


        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $required_plugins   = $bb_importer_process["package"]->required_plugins();
        $required_themes   = $bb_importer_process["package"]->required_theme();

        $return = true;

        $plugin_dir = buddyboss_importer_get_plugin_dir();
        foreach($required_plugins as $plugin) {

            if(!file_exists($plugin_dir.$current_plugin["plugin_path"]) OR !is_plugin_active($plugin["plugin_path"])) {
                $return = false;
                break;

            }


        }
        $theme_dir = buddyboss_importer_get_themes_dir();
        foreach($required_themes as $theme) {

            if(!file_exists($theme_dir.$theme["slug"]."/style.css") OR get_stylesheet() != $theme["slug"]) {
                if($theme["slug"] == $bb_importer_process["package"]->theme()) {
                    $return = false;
                }
            }

        }

        return $return;
    }

    function is_tos_accepted() {
        global $bb_importer_process;

        if(isset($bb_importer_process["is_tos_accepted"]) AND $bb_importer_process["is_tos_accepted"] == "1") {
            return true;
        }

        return false;

    }

    /*
     * Will tell if can process data install or not.
     **/
    function can_process_data_install() {
        global $bb_importer_process;

        if(isset($bb_importer_process["process_data_install"]) AND $bb_importer_process["process_data_install"] == "1") {
            return true;
        }

        return false;
    }


    function the_process($process_name,$label,$params=array()) {
        $id = $process_name.implode("-",$params);
        return array("process"=>$process_name,"label"=>$label,"parameters"=>$params,"id"=>$id);
    }

    public static function table_label($table) {

        $labels = array(
                    "posts" => __("Posts Types","bb-oneclick"),
                    "postmeta" => __("Post Metas","bb-oneclick"),
                    "options" => __("Options","bb-oneclick"),
                    );


        if(isset($labels[$table])) {

            return $labels[$table];

        } else { // Automatic butiefire.

            $table = str_replace("_"," ",$table);
            $table = ucfirst($table);

            return $table;

        }

    }

    /**
     * Filter the data of tables before adding
     */
    function buddyboss_importer_table_data($itemdata,$table) {
        global $wpdb,$bb_importer_process;

        $prefix = $bb_importer_process["settings"]["prefix"];

        if($table == "options") {

            // fix the prefix.
            if(substr($itemdata["option_name"], 0, strpos($itemdata["option_name"],"_")+1) == $prefix) {
                $itemdata["option_name"] = str_replace($prefix,$wpdb->prefix,$itemdata["option_name"]);
            }

        }

        if($table == "usermeta") {

            // fix the prefix.
            if(substr($itemdata["meta_key"], 0, strpos($itemdata["meta_key"],"_")+1) == $prefix) {
                $itemdata["meta_key"] = str_replace($prefix,$wpdb->prefix,$itemdata["meta_key"]);
            }

        }

        if($table == "users") {
            // Just change it for security reason.
            $itemdata["user_pass"] = md5(uniqid().time()).md5($itemdata["user_pass"]);

        }

        return $itemdata;
    }

    function force_login() {
        global $bb_importer_process;
        buddyboss_importer_variables_restore();

        if(!empty($_SESSION["buddyboss_oneclick_force_login"])) {

            @wp_set_auth_cookie($_SESSION["buddyboss_oneclick_force_login"]);

            if(is_user_logged_in()) {
                $_SESSION["buddyboss_oneclick_force_login"] = false;
            }

            buddyboss_importer_variables_save();

            wp_redirect(home_url());
            exit;
        }

    }
}

include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

if ( !class_exists( 'bb_oneclick_Plugin_Upgrader_Skin' ) ) {

    class bb_oneclick_Plugin_Upgrader_Skin extends WP_Upgrader_Skin {

        function __construct( $args = array() ) {
            $defaults    = array( 'type' => 'web', 'url' => '', 'plugin' => '', 'nonce' => '', 'title' => '' );
            $args        = wp_parse_args( $args, $defaults );

            $this->type  = $args[ 'type' ];
            $this->api   = isset( $args[ 'api' ] ) ? $args[ 'api' ] : array();

            parent::__construct( $args );
        }

        public function request_filesystem_credentials( $error = false, $context = false,
                                                  $allow_relaxed_file_ownership = false ) {
            return true;
        }

        public function error( $errors ) {
            die( '-1' );
        }

        public function header() {

        }

        public function footer() {

        }

        public function feedback( $string ) {

        }

    }

}
