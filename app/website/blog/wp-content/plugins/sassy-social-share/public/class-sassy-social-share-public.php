<?php

/**
 * Contains functions responsible for functionality at front-end of website
 *
 * @since      1.0.0
 *
 */

/**
 * This class defines all code necessary for functionality at front-end of website
 *
 * @since      1.0.0
 *
 */
class Sassy_Social_Share_Public {

	/**
	 * Options saved in database.
	 *
	 * @since    1.0.0
	 */
	private $options;

	/**
	 * Current version of the plugin.
	 *
	 * @since    1.0.0
	 */
	private $version;

	/**
	 * Variable to track number of times 'the_content' hook called at homepage.
	 *
	 * @since    1.0.0
	 */
	private $vertical_home_count = 0;

	/**
	 * Variable to track number of times 'the_content' hook called at excerpts.
	 *
	 * @since    1.0.0
	 */
	private $vertical_excerpt_count = 0;

	/**
	 * Short urls calculated for current webpage.
	 *
	 * @since    1.0.0
	 */
	private $short_urls = array();

	/**
	 * Share Count Transient ID
	 *
	 * @since    1.7
	 */
	public $share_count_transient_id = '';

	/**
	 * Get saved options.
	 *
	 * @since    1.0.0
     * @param    array     $options    Plugin options saved in database
     * @param    string    $version    Current version of the plugin
	 */
	public function __construct( $options, $version ) {

		$this->options = $options;
		$this->version = $version;

	}

	/**
	 * Hook the plugin function on 'init' event.
	 *
	 * @since    1.0.0
	 */
	public function init() {

		// Javascript for front-end of website
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		// inline style for front-end of website
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_inline_style' ) );
		// stylesheet files for front-end of website
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_css' ) );

		if ( isset( $this->options['amp_enable'] ) ) {
			// Javascript for AMP pages
			add_action( 'amp_post_template_head', array( $this, 'frontend_scripts' ) );
			// inline style for AMP pages
			add_action( 'amp_post_template_css', array( $this, 'frontend_inline_style' ) );
			// stylesheet files for AMP pages
			add_action( 'amp_post_template_css', array( $this, 'frontend_amp_css' ) );
		}
	
	}

	/**
	 * Javascript files to load at front end.
	 *
	 * @since    1.0.0
	 */
	public function frontend_scripts() {

		$in_footer = isset( $this->options['footer_script'] ) ? true : false;
		$inline_script = 'function heateorSssLoadEvent(e) {var t=window.onload;if (typeof window.onload!="function") {window.onload=e}else{window.onload=function() {t();e()}}};	';
		global $post;
		if ( $post ) {
			$sharing_meta = get_post_meta( $post->ID, '_heateor_sss_meta', true );
			if ( is_front_page() || ! isset( $sharing_meta['sharing'] ) || $sharing_meta['sharing'] != 1 || ! isset( $sharing_meta['vertical_sharing'] ) || $sharing_meta['vertical_sharing'] != 1 ) {
				$inline_script .= 'var heateorSssSharingAjaxUrl = \''. get_admin_url() .'admin-ajax.php\', heateorSssCloseIconPath = \''. plugins_url( '../images/close.png', __FILE__ ) .'\', heateorSssPluginIconPath = \''. plugins_url( '../images/logo.png', __FILE__ ) .'\', heateorSssHorizontalSharingCountEnable = '. ( isset( $this->options['hor_enable'] ) && ( isset( $this->options['horizontal_counts'] ) || isset( $this->options['horizontal_total_shares'] ) ) ? 1 : 0 ) .', heateorSssVerticalSharingCountEnable = '. ( isset( $this->options['vertical_enable'] ) && ( isset( $this->options['vertical_counts'] ) || isset( $this->options['vertical_total_shares'] ) ) ? 1 : 0 ) .', heateorSssSharingOffset = '. ( isset( $this->options['alignment'] ) && $this->options['alignment'] != '' && isset( $this->options[$this->options['alignment'].'_offset'] ) && $this->options[$this->options['alignment'].'_offset'] != '' ? $this->options[$this->options['alignment'].'_offset'] : 0 ) . ';';
				if ( isset( $this->options['horizontal_counts'] ) && isset( $this->options['horizontal_counter_position'] ) ) {
					$inline_script .= in_array( $this->options['horizontal_counter_position'], array( 'inner_left', 'inner_right' ) ) ? 'var heateorSssReduceHorizontalSvgWidth = true;' : '';
					$inline_script .= in_array( $this->options['horizontal_counter_position'], array( 'inner_top', 'inner_bottom' ) ) ? 'var heateorSssReduceHorizontalSvgHeight = true;' : '';
				}
				if ( isset( $this->options['vertical_counts'] ) ) {
					$inline_script .= isset( $this->options['vertical_counter_position'] ) && in_array( $this->options['vertical_counter_position'], array( 'inner_left', 'inner_right' ) ) ? 'var heateorSssReduceVerticalSvgWidth = true;' : '';
					$inline_script .= ! isset( $this->options['vertical_counter_position'] ) || in_array( $this->options['vertical_counter_position'], array( 'inner_top', 'inner_bottom' ) ) ? 'var heateorSssReduceVerticalSvgHeight = true;' : '';
				}
				$inline_script .= 'var heateorSssUrlCountFetched = [], heateorSssSharesText = \''. htmlspecialchars(__('Shares', 'sassy-social-share'), ENT_QUOTES) .'\', heateorSssShareText = \''. htmlspecialchars(__('Share', 'sassy-social-share'), ENT_QUOTES) .'\';';
				$inline_script .= 'function heateorSssPopup(e) {window.open(e,"popUpWindow","height=400,width=600,left=400,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes")}';
				if ( $this->facebook_like_recommend_enabled() || $this->facebook_share_enabled() ) {
					$inline_script .= 'function heateorSssInitiateFB() {FB.init({appId:"",channelUrl:"",status:!0,cookie:!0,xfbml:!0,version:"v2.5"})}window.fbAsyncInit=function() {heateorSssInitiateFB(),' . ( defined( 'HEATEOR_SOCIAL_SHARE_MYCRED_INTEGRATION_VERSION' ) && $this->facebook_like_recommend_enabled() ? 1 : 0 ) . '&&(FB.Event.subscribe("edge.create",function(e) {heateorSsmiMycredPoints("Facebook_like_recommend","",e?e:"")}),FB.Event.subscribe("edge.remove",function(e) {heateorSsmiMycredPoints("Facebook_like_recommend","",e?e:"","Minus point(s) for undoing Facebook like-recommend")}) ),'. ( defined( 'HEATEOR_SHARING_GOOGLE_ANALYTICS_VERSION' ) ? 1 : 0 ) .'&&(FB.Event.subscribe("edge.create",function(e) {heateorSsgaSocialPluginsTracking("Facebook","Like",e?e:"")}),FB.Event.subscribe("edge.remove",function(e) {heateorSsgaSocialPluginsTracking("Facebook","Unlike",e?e:"")}) )},function(e) {var n,i="facebook-jssdk",o=e.getElementsByTagName("script")[0];e.getElementById(i)||(n=e.createElement("script"),n.id=i,n.async=!0,n.src="//connect.facebook.net/'. ( $this->options['language'] ? $this->options['language'] : 'en_US' ) .'/sdk.js",o.parentNode.insertBefore(n,o) )}(document);';
				}
				if ( current_filter() == 'amp_post_template_head' ) {
					// default post url
					global $post;
					$post_url = html_entity_decode( esc_url( $this->get_http_protocol() . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) );
					if ( $this->options['horizontal_target_url'] == 'default' ) {
						$post_url = get_permalink( $post->ID );
						if ( $post_url == '' ) {
							$post_url = html_entity_decode( esc_url( $this->get_http_protocol() . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) );
						}
					} elseif ( $this->options['horizontal_target_url'] == 'home' ) {
						$post_url = home_url();
					} elseif ( $this->options['horizontal_target_url'] == 'custom' ) {
						$post_url = $this->options['horizontal_target_url_custom'] ? $this->options['horizontal_target_url_custom'] : get_permalink( $post->ID );
					}
					
					$sharing_url = $this->get_short_url( $post_url, $post->ID );

					$post_title = $post->post_title;
					
					$post_title = $this->sanitize_post_title( $post_title );
					
					$inline_script .= 'var heateorSssAmpTargetUrl = \''. $post_url .'\';
					heateorSssLoadEvent(
						function(){
							var moreIcons = document.getElementsByClassName("heateorSssMoreBackground");
							for(var i = 0; i < moreIcons.length; i++){
								moreIcons[i].onclick = function(){
									heateorSssMoreSharingPopup(null, \''. ( $sharing_url ? $sharing_url : $post_url) .'\', \''. $post_title .'\', \''. $this->sanitize_post_title( $this->wpseo_twitter_title( $post ) ) .'\')
								}
							}

							var printIcons = document.getElementsByClassName("heateorSssPrintBackground");
							for(var i = 0; i < printIcons.length; i++){
								printIcons[i].onclick = function(){
									window.print();
								}
							}

							var pinterestIcons = document.getElementsByClassName("heateorSssPinterestBackground");
							for(var i = 0; i < pinterestIcons.length; i++){
								pinterestIcons[i].onclick = function(){
									var e = document.createElement(\'script\');e.setAttribute(\'type\',\'text/javascript\');e.setAttribute(\'charset\',\'UTF-8\');e.setAttribute(\'src\',\'//assets.pinterest.com/js/pinmarklet.js?r=\'+Math.random()*99999999);document.body.appendChild(e);
								}
							}
						}
					);
					
					function heateorSssCapitaliseFirstLetter(e) {
					    return e.charAt(0).toUpperCase() + e.slice(1)
					}

					/**
					 * Search sharing services
					 */
					function heateorSssFilterSharing(val) {
						var sharingServices = document.getElementById(\'heateor_sss_sharing_more_content\').getElementsByTagName(\'a\');
						for(var i = 0; i < sharingServices.length; i++){
							if (sharingServices[i].innerText.toLowerCase().indexOf(val.toLowerCase()) != -1) {
								sharingServices[i].parentNode.style.display = \'block\';
								//jQuery(this).parent().css(\'display\', \'block\');
							} else {
								sharingServices[i].parentNode.style.display = \'none\';
							}
						}
					};

					function heateorSssMoreSharingPopup(elem, postUrl, postTitle, twitterTitle){
						concate = \'</ul></div><div class="footer-panel"><p></p></div></div>\';
						var heateorSssMoreSharingServices = {
						  facebook: {
							title: "Facebook",
							locale: "en-US",
							redirect_url: "http://www.facebook.com/sharer.php?u=" + postUrl + "&t=" + postTitle + "&v=3",
						  },
						  twitter: {
							title: "Twitter",
							locale: "en-US",
							redirect_url: "http://twitter.com/intent/tweet?text=" + (twitterTitle ? twitterTitle : postTitle) + " " + postUrl,
						  },
						  google: {
							title: "Google plus",
							locale: "en-US",
							redirect_url: "https://plus.google.com/share?url=" + postUrl,
						  },
						  linkedin: {
							title: "Linkedin",
							locale: "en-US",
							redirect_url: "http://www.linkedin.com/shareArticle?mini=true&url=" + postUrl + "&title=" + postTitle,
						  },
						  pinterest: {
							title: "Pinterest",
							locale: "en-US",
							redirect_url: "https://pinterest.com/pin/create/button/?url=" + postUrl + "&media=${media_link}&description=" + postTitle,
							bookmarklet_url: "javascript:void((function(){var e=document.createElement(\'script\');e.setAttribute(\'type\',\'text/javascript\');e.setAttribute(\'charset\',\'UTF-8\');e.setAttribute(\'src\',\'//assets.pinterest.com/js/pinmarklet.js?r=\'+Math.random()*99999999);document.body.appendChild(e)})());"
						  },
						  yahoo_bookmarks: {
							title: "Yahoo",
							locale: "en-US",
							redirect_url: "http://bookmarks.yahoo.com/toolbar/savebm?u=" + postUrl + "&t=" + postTitle,
						  },
						  email: {
							title: "Email",
							locale: "en-US",
							redirect_url: "mailto:?subject=" + postTitle + "&body=Link: " + postUrl,
						  },
						  delicious: {
							title: "Delicious",
							locale: "en-US",
							redirect_url: "http://delicious.com/save?url=" + postUrl + "&title=" + postTitle,
						  },
						  reddit: {
							title: "Reddit",
							locale: "en-US",
							redirect_url: "http://reddit.com/submit?url=" + postUrl + "&title=" + postTitle,
						  },
						  float_it: {
							title: "Float it",
							locale: "en-US",
							redirect_url: "http://www.designfloat.com/submit.php?url=" + postUrl + "&title=" + postTitle,
						  },
						  google_mail: {
							title: "Google Gmail",
							locale: "en-US",
							redirect_url: "https://mail.google.com/mail/?ui=2&view=cm&fs=1&tf=1&su=" + postTitle + "&body=Link: " + postUrl,
						  },
						  google_bookmarks: {
							title: "Google Bookmarks",
							locale: "en-US",
							redirect_url: "http://www.google.com/bookmarks/mark?op=edit&bkmk=" + postUrl + "&title=" + postTitle,
						  },
						  digg: {
							title: "Digg",
							locale: "en-US",
							redirect_url: "http://digg.com/submit?phase=2&url=" + postUrl + "&title=" + postTitle,
						  },
						  stumbleupon: {
							title: "Stumbleupon",
							locale: "en-US",
							redirect_url: "http://www.stumbleupon.com/submit?url=" + postUrl + "&title=" + postTitle,
						  },
						  printfriendly: {
							title: "PrintFriendly",
							locale: "en-US",
							redirect_url: "http://www.printfriendly.com/print?url=" + postUrl,
						  },
						  print: {
							title: "Print",
							locale: "en-US",
							redirect_url: "http://www.printfriendly.com/print?url=" + postUrl,
						  },
						  tumblr: {
							title: "Tumblr",
							locale: "en-US",
							redirect_url: "http://www.tumblr.com/share?v=3&u=" + postUrl + "&t=" + postTitle,
							bookmarklet_url: "javascript:var d=document,w=window,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),f=\'http://www.tumblr.com/share\',l=d.location,e=encodeURIComponent,p=\'?v=3&u=\'+e(l.href) +\'&t=\'+e(d.title) +\'&s=\'+e(s),u=f+p;try{if(!/^(.*\\.)?tumblr[^.]*$/.test(l.host))throw(0);tstbklt();}catch(z){a =function(){if(!w.open(u,\'t\',\'toolbar=0,resizable=0,status=1,width=450,height=430\'))l.href=u;};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else a();}void(0);"
						  },
						  vk: {
							title: "Vkontakte",
							locale: "ru",
							redirect_url: "https://vk.com/share.php?url=" + postUrl + "&title=" + postTitle,
						  },
						  evernote: {
							title: "Evernote",
							locale: "en-US",
							redirect_url: "https://www.evernote.com/clip.action?url=" + postUrl + "&title=" + postTitle,
							bookmarklet_url: "javascript:(function(){EN_CLIP_HOST=\'http://www.evernote.com\';try{var x=document.createElement(\'SCRIPT\');x.type=\'text/javascript\';x.src=EN_CLIP_HOST+\'/public/bookmarkClipper.js?\'+(new Date().getTime()/100000);document.getElementsByTagName(\'head\')[0].appendChild(x);}catch(e){location.href=EN_CLIP_HOST+\'/clip.action?url=\'+encodeURIComponent(location.href)+\'&title=\'+encodeURIComponent(document.title);}})();"
						  },
						  amazon_us_wish_list: {
							title: "Amazon Wish List",
							locale: "en-US",
							redirect_url: "http://www.amazon.com/wishlist/add?u=" + postUrl + "&t=" + postTitle,
							bookmarklet_url: "javascript:(function(){var w=window,l=w.location,d=w.document,s=d.createElement(\'script\'),e=encodeURIComponent,x=\'undefined\',u=\'http://www.amazon.com/gp/wishlist/add\';if(typeof s!=\'object\')l.href=u+\'?u=\'+e(l)+\'&t=\'+e(d.title);function g(){if(d.readyState&&d.readyState!=\'complete\'){setTimeout(g,200);}else{if(typeof AUWLBook==x)s.setAttribute(\'src\',u+\'.js?loc=\'+e(l)),d.body.appendChild(s);function f(){(typeof AUWLBook==x)?setTimeout(f,200):AUWLBook.showPopover();}f();}}g();}())"
						  },
						  wordpress_blog: {
							title: "WordPress",
							locale: "en-US",
							redirect_url: "http://www.addtoany.com/ext/wordpress/press_this?linkurl=" + postUrl + "&linkname=" + postTitle,
						  },
						  whatsapp: {
							title: "Whatsapp",
							locale: "en-US",
							redirect_url: "whatsapp://send?text=" + postTitle + " " + postUrl,
						  },
						  diigo: {
							title: "Diigo",
							locale: "en-US",
							redirect_url: "http://www.diigo.com/post?url=" + postUrl + "&title=" + postTitle,
						  },
						  yc_hacker_news: {
							title: "Hacker News",
							locale: "en-US",
							redirect_url: "http://news.ycombinator.com/submitlink?u=" + postUrl + "&t=" + postTitle,
						  },
						  box_net: {
							title: "Box.net",
							locale: "en-US",
							redirect_url: "https://www.box.net/api/1.0/import?url=" + postUrl + "&name=" + postTitle + "&import_as=link",
						  },
						  aol_mail: {
							title: "AOL Mail",
							locale: "en-US",
							redirect_url: "http://webmail.aol.com/25045/aol/en-us/Mail/compose-message.aspx?subject=" + postTitle + "&body=" + postUrl,
						  },
						  yahoo_mail: {
							title: "Yahoo Mail",
							locale: "en-US",
							redirect_url: "http://compose.mail.yahoo.com/?Subject=" + postTitle + "&body=Link: " + postUrl,
						  },
						  instapaper: {
							title: "Instapaper",
							locale: "en-US",
							redirect_url: "http://www.instapaper.com/edit?url=" + postUrl + "&title=" + postTitle,
						  },
						  plurk: {
							title: "Plurk",
							locale: "en-US",
							redirect_url: "http://www.plurk.com/m?content=" + postUrl + "&qualifier=shares",
						  },
						  wanelo: {
							title: "Wanelo",
							locale: "en-US",
							redirect_url: "http://wanelo.com/p/post?bookmarklet=&images%5B%5D=&url=" + postUrl + "&title=" + postTitle + "&price=&shop=",
							bookmarklet_url: "javascript:void ((function(url){if(!window.waneloBookmarklet){var productURL=encodeURIComponent(url),cacheBuster=Math.floor(Math.random()*1e3),element=document.createElement(\'script\');element.setAttribute(\'src\',\'//wanelo.com/bookmarklet/3/setup?*=\'+cacheBuster+\'&url=\'+productURL),element.onload=init,element.setAttribute(\'type\',\'text/javascript\'),document.getElementsByTagName(\'head\')[0].appendChild(element)}else init();function init(){window.waneloBookmarklet()}})(window.location.href))"
						  },
						  aim: {
							title: "AIM",
							locale: "en-US",
							redirect_url: "http://share.aim.com/share/?url=" + postUrl + "&title=" + postTitle,
						  },
						  stumpedia: {
							title: "Stumpedia",
							locale: "en-US",
							redirect_url: "http://www.stumpedia.com/submit?url=" + postUrl + "&title=" + postTitle,
						  },
						  viadeo: {
							title: "Viadeo",
							locale: "en-US",
							redirect_url: "http://www.viadeo.com/shareit/share/?url=" + postUrl + "&title=" + postTitle,
						  },
						  yahoo_messenger: {
							title: "Yahoo Messenger",
							locale: "en-US",
							redirect_url: "ymsgr:sendim?m=" + postUrl,
						  },
						  pinboard_in: {
							title: "Pinboard",
							locale: "en-US",
							redirect_url: "http://pinboard.in/add?url=" + postUrl + "&title=" + postTitle,
						  },
						  blogger_post: {
							title: "Blogger Post",
							locale: "en-US",
							redirect_url: "http://www.blogger.com/blog_this.pyra?t=&u=" + postUrl + "&l&n=" + postTitle,
						  },
						  typepad_post: {
							title: "TypePad Post",
							locale: "en-US",
							redirect_url: "http://www.typepad.com/services/quickpost/post?v=2&qp_show=ac&qp_title=" + postTitle + "&qp_href=" + postUrl + "&qp_text=" + postTitle,
						  },
						  buffer: {
							title: "Buffer",
							locale: "en-US",
							redirect_url: "http://bufferapp.com/add?url=" + postUrl + "&text=" + postTitle,
						  },
						  flipboard: {
							title: "Flipboard",
							locale: "en-US",
							redirect_url: "https://share.flipboard.com/bookmarklet/popout?v=2&url=" + postUrl + "&title=" + postTitle,
						  },
						  mail: {
							title: "Email",
							locale: "en-US",
							redirect_url: "mailto:?subject=" + postTitle + "&body=Link: " + postUrl,
						  },
						  pocket: {
							title: "Pocket",
							locale: "en-US",
							redirect_url: "https://readitlaterlist.com/save?url=" + postUrl + "&title=" + postTitle,
						  },
						  fark: {
							title: "Fark",
							locale: "en-US",
							redirect_url: "http://cgi.fark.com/cgi/fark/submit.pl?new_url=" + postUrl,
						  },
						  yummly: {
							title: "Yummly",
							locale: "en-US",
							redirect_url: "http://www.yummly.com/urb/verify?url=" + postUrl + "&title=" + postTitle,
						  },
						  app_net: {
							title: "App.net",
							locale: "en-US",
							redirect_url: "https://account.app.net/login/",
						  },
						  baidu: {
							title: "Baidu",
							locale: "en-US",
							redirect_url: "http://cang.baidu.com/do/add?it=" + postTitle + "&iu=" + postUrl,
						  },
						  balatarin: {
							title: "Balatarin",
							locale: "en-US",
							redirect_url: "https://www.balatarin.com/login",
						  },
						  bibSonomy: {
							title: "BibSonomy",
							locale: "en-US",
							redirect_url: "http://www.bibsonomy.org/login",
						  },
						  Bitty_Browser: {
							title: "Bitty Browser",
							locale: "en-US",
							redirect_url: "http://www.bitty.com/manual/?contenttype=&contentvalue=" + postUrl,
						  },
						  Blinklist: {
							title: "Blinklist",
							locale: "en-US",
							redirect_url: "http://blinklist.com/blink?t=" + postTitle + "&d=&u=" + postUrl,
						  },
						  BlogMarks: {
							title: "BlogMarks",
							locale: "en-US",
							redirect_url: "http://blogmarks.net/my/new.php?mini=1&simple=1&title=" + postTitle + "&url=" + postUrl,
						  },
						  Bookmarks_fr: {
							title: "Bookmarks.fr",
							locale: "en-US",
							redirect_url: "http://www.bookmarks.fr/Connexion/?action=add&address=" + postUrl + "&title=" + postTitle,
						  },
						  BuddyMarks: {
							title: "BuddyMarks",
							locale: "en-US",
							redirect_url: "http://buddymarks.com/login.php?bookmark_title=" + postTitle + "&bookmark_url=" + postUrl + "&bookmark_desc=&bookmark_tags=",
						  },
						  Care2_news: {
							title: "Care2 News",
							locale: "en-US",
							redirect_url: "http://www.care2.com/passport/login.html?promoID=10&pg=http://www.care2.com/news/compose?sharehint=news&share[share_type]news&bookmarklet=Y&share[title]=" + postTitle + "&share[link_url]=" + postUrl + "&share[content]=",
						  },
						  CiteULike: {
							title: "Cite U Like",
							locale: "en-US",
							redirect_url: "http://www.citeulike.org/posturl?url=" + postUrl + "&title=" + postTitle,
						  },
						  Diary_Ru: {
							title: "Diary.Ru",
							locale: "en-US",
							redirect_url: "http://www.diary.ru/?newpost&title=" + postTitle + "&text=" + postUrl,
						  },
						  diHITT: {
							title: "diHITT",
							locale: "en-US",
							redirect_url: "http://www.dihitt.com/submit?url=" + postUrl + "&title=" + postTitle,
						  },
						  dzone: {
							title: "DZone",
							locale: "en-US",
							redirect_url: "http://www.dzone.com/links/add.html?url=" + postUrl + "&title=" + postTitle,
						  },
						  Folkd: {
							title: "Folkd",
							locale: "en-US",
							redirect_url: "http://www.folkd.com/page/social-bookmarking.html?addurl=" + postUrl,
						  },
						  Hatena: {
							title: "Hatena",
							locale: "en-US",
							redirect_url: "http://b.hatena.ne.jp/bookmarklet?url=" + postUrl + "&btitle=" + postTitle,
						  },
						  Jamespot: {
							title: "Jamespot",
							locale: "en-US",
							redirect_url: "//my.jamespot.com/",
						  },
						  Kakao: {
							title: "Kakao",
							locale: "en-US",
							redirect_url: "https://story.kakao.com/share?url=" + postUrl,
						  },
						  Kindle_It: {
							title: "Kindle_It",
							locale: "en-US",
							redirect_url: "//fivefilters.org/kindle-it/send.php?url=" + postUrl,
						  },
						  Known: {
							title: "Known",
							locale: "en-US",
							redirect_url: "https://withknown.com/share/?url=" + postUrl + "&title=" + postTitle,
						  },
						  Line: {
							title: "Line",
							locale: "en-US",
							redirect_url: "line://msg/text/" + postTitle + "! " + postUrl,
						  },
						  LiveJournal: {
							title: "LiveJournal",
							locale: "en-US",
							redirect_url: "http://www.livejournal.com/update.bml?subject=" + postTitle + "&event=" + postUrl,
						  },
						  Mail_Ru: {
							title: "Mail.Ru",
							locale: "en-US",
							redirect_url: "http://connect.mail.ru/share?share_url=" + postUrl,
						  },
						  Mendeley: {
							title: "Mendeley",
							locale: "en-US",
							redirect_url: "https://www.mendeley.com/sign-in/",
						  },
						  Meneame: {
							title: "Meneame",
							locale: "en-US",
							redirect_url: "https://www.meneame.net/submit.php?url=" + postUrl,
						  },
						  Mixi: {
							title: "Mixi",
							locale: "en-US",
							redirect_url: "https://mixi.jp/share.pl?mode=login&u=" + postUrl,
						  },
						  MySpace: {
							title: "MySpace",
							locale: "en-US",
							redirect_url: "https://myspace.com/post?u=" + encodeURIComponent(postUrl) + "&t=" + postTitle + "&l=3&c=" + postTitle,
						  },
						  Netlog: {
							title: "Netlog",
							locale: "en-US",
							redirect_url: "http://www.netlog.com/go/manage/links/view=save&origin=external&url=" + postUrl + "&title=" + postTitle + "&description=",
						  },
						  Netvouz: {
							title: "Netvouz",
							locale: "en-US",
							redirect_url: "http://www.netvouz.com/action/submitBookmark?url=" + postUrl + "&title=" + postTitle + "&popup=no&description=",
						  },
						  NewsVine: {
							title: "NewsVine",
							locale: "en-US",
							redirect_url: "http://www.newsvine.com/_tools/seed?popoff=0&u=" + postUrl + "&h=" + postTitle,
						  },
						  NUjij: {
							title: "NUjij",
							locale: "en-US",
							redirect_url: "http://www.nujij.nl/nieuw-bericht.2051051.lynkx?title=" + postTitle + "&url=" + postUrl + "&bericht=&topic=",
						  },
						  Odnoklassniki: {
							title: "Odnoklassniki",
							locale: "en-US",
							redirect_url: "https://connect.ok.ru/dk?cmd=WidgetSharePreview&st.cmd=WidgetSharePreview&st.shareUrl=" + postUrl + "&st.client_id=-1",
						  },
						  Oknotizie: {
							title: "Oknotizie",
							locale: "en-US",
							redirect_url: "//oknotizie.virgilio.it/post?url=" + postUrl + "&title=" + postTitle,
						  },
						  Outlook_com: {
							title: "Outlook.com",
							locale: "en-US",
							redirect_url: "https://mail.live.com/default.aspx?rru=compose?subject=" + postTitle + "&body=" + postUrl + "&lc=1033&id=64855&mkt=en-us&cbcxt=mai",
						  },
						  Protopage_Bookmarks: {
							title: "Protopage_Bookmarks",
							locale: "en-US",
							redirect_url: "http://www.protopage.com/add-button-site?url=" + postUrl + "&label=&type=page",
						  },
						  Pusha: {
							title: "Pusha",
							locale: "en-US",
							redirect_url: "//www.pusha.se/posta?url=" + postUrl,
						  },
						  Qzone: {
							title: "Qzone",
							locale: "en-US",
							redirect_url: "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=" + postUrl,
						  },
						  Rediff_MyPage: {
							title: "Rediff MyPage",
							locale: "en-US",
							redirect_url: "//share.rediff.com/bookmark/addbookmark?bookmarkurl=" + postUrl + "&title=" + postTitle,
						  },
						  Renren: {
							title: "Renren",
							locale: "en-US",
							redirect_url: "//www.connect.renren.com/share/sharer?url=" + postUrl + "&title=" + postTitle,
						  },
						  Segnalo: {
							title: "Segnalo",
							locale: "en-US",
							redirect_url: "http://segnalo.virgilio.it/post.html.php?url=" + postUrl + "&title=" + postTitle,
						  },
						  Sina_Weibo: {
							title: "Sina Weibo",
							locale: "en-US",
							redirect_url: "//service.weibo.com/share/share.php?url=" + postUrl + "&title=" + postTitle,
						  },
						  SiteJot: {
							title: "SiteJot",
							locale: "en-US",
							redirect_url: "http://www.sitejot.com/loginform.php?iSiteAdd=&iSiteDes=",
						  },
						  Slashdot: {
							title: "Slashdot",
							locale: "en-US",
							redirect_url: "//slashdot.org/submission?url=" + postUrl,
						  },
						  Svejo: {
							title: "Svejo",
							locale: "en-US",
							redirect_url: "https://svejo.net/story/submit_by_url?url=" + postUrl + "&title=" + postTitle + "&summary=",
						  },
						  Symbaloo_Feeds: {
							title: "Symbaloo_Feeds",
							locale: "en-US",
							redirect_url: "//www.symbaloo.com/",
						  },
						  Tuenti: {
							title: "Tuenti",
							locale: "en-US",
							redirect_url: "https://www.tuenti.com/share?p=b5dd6602&url=" + postUrl,
						  },
						  Twiddla: {
							title: "Twiddla",
							locale: "en-US",
							redirect_url: "//www.twiddla.com/New.aspx?url=" + postUrl + "&title=" + postTitle,
						  },
						  Webnews: {
							title: "Webnews",
							locale: "en-US",
							redirect_url: "//www.webnews.de/login",
						  },
						  Wykop: {
							title: "Wykop",
							locale: "en-US",
							redirect_url: "//www.wykop.pl/dodaj?url=" + postUrl + "&title=" + postTitle,
						  },
						  Yoolink: {
							title: "Yoolink",
							locale: "en-US",
							redirect_url: "//yoolink.to/addorshare?url_value=" + postUrl + "&title=" + postTitle,
						  },
						  YouMob: {
							title: "YouMob",
							locale: "en-US",
							redirect_url: "//youmob.com/startmob.aspx?cookietest=true&mob=" + postUrl,
						  }
						}
						var heateorSssMoreSharingServicesHtml = \'<button id="heateor_sss_sharing_popup_close" class="close-button separated"><img src="\'+ heateorSssCloseIconPath +\'" /></button><div id="heateor_sss_sharing_more_content"><div class="filter"><input type="text" onkeyup="heateorSssFilterSharing(this.value.trim())" placeholder="Search" class="search"></div><div class="all-services"><ul class="mini">\';
						for(var i in heateorSssMoreSharingServices){
							var tempTitle = heateorSssCapitaliseFirstLetter(heateorSssMoreSharingServices[i].title.replace(/[_. ]/g, ""));
							heateorSssMoreSharingServicesHtml += \'<li><a rel="nofollow" title="\'+ heateorSssMoreSharingServices[i].title +\'" alt="\'+ heateorSssMoreSharingServices[i].title +\'" \';
							if(heateorSssMoreSharingServices[i].bookmarklet_url){
								heateorSssMoreSharingServicesHtml += \'href="\' + heateorSssMoreSharingServices[i].bookmarklet_url + \'" \';
							}else{
								heateorSssMoreSharingServicesHtml += \'onclick="heateorSssPopup(\'\' + heateorSssMoreSharingServices[i].redirect_url + \'\')" href="javascript:void(0)" \';
							}
							heateorSssMoreSharingServicesHtml += \'"><i style="width:22px;height:22px" title="\'+ heateorSssMoreSharingServices[i].title +\'" class="heateorSssSharing heateorSss\' + tempTitle + \'Background"><ss style="display:block;width:100%;height:100%;" class="heateorSssSharingSvg heateorSss\' + tempTitle + \'Svg"></ss></i>\' + heateorSssMoreSharingServices[i].title + \'</a></li>\';
						}
						heateorSssMoreSharingServicesHtml += concate;
						
						var mainDiv = document.createElement(\'div\');
						mainDiv.innerHTML = heateorSssMoreSharingServicesHtml;
						mainDiv.setAttribute(\'id\', \'heateor_sss_sharing_more_providers\');
						var bgDiv = document.createElement(\'div\');
						bgDiv.setAttribute(\'id\', \'heateor_sss_popup_bg\');
						document.body.appendChild(mainDiv);
						document.body.appendChild(bgDiv);
						document.getElementById(\'heateor_sss_sharing_popup_close\').onclick = function(){
							mainDiv.parentNode.removeChild(mainDiv);
							bgDiv.parentNode.removeChild(bgDiv);
						}
					}';
				}
				echo '<script type="text/javascript">' . $inline_script . '</script>';
				wp_enqueue_script( 'heateor_sss_sharing_js', plugins_url( 'js/sassy-social-share-public.js', __FILE__ ), array( 'jquery' ), $this->version, $in_footer );
			}
		}
	}

	/**
	 * Check if Facebook Like/Recommend is enabled
	 *
	 * @since    1.0.0
	 */
	private function facebook_like_recommend_enabled() {
		
		if ( ( isset( $this->options['hor_enable'] ) && isset( $this->options['horizontal_re_providers'] ) && ( in_array( 'facebook_like', $this->options['horizontal_re_providers'] ) || in_array( 'facebook_recommend', $this->options['horizontal_re_providers'] ) ) ) || ( isset( $this->options['vertical_enable'] ) && isset( $this->options['vertical_re_providers'] ) && ( in_array( 'facebook_like', $this->options['vertical_re_providers'] ) || in_array( 'facebook_recommend', $this->options['vertical_re_providers'] ) ) ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Check if Facebook Share is enabled
	 *
	 * @since    2.4
	 */
	private function facebook_share_enabled() {
		
		if ( ( isset( $this->options['hor_enable'] ) && isset( $this->options['horizontal_re_providers'] ) && ( in_array( 'facebook_share', $this->options['horizontal_re_providers'] ) ) ) || ( isset( $this->options['vertical_enable'] ) && isset( $this->options['vertical_re_providers'] ) && ( in_array( 'facebook_share', $this->options['vertical_re_providers'] ) ) ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Generate bitly short url for sharing buttons
	 *
	 * @since    1.0.0
	 */
	public function generate_bitly_url( $url, $post_id = 0 ) {
	    
	    $bitlyUrl = get_post_meta( $post_id, '_heateor_sss_bitly_url', true );
	    if ( $bitlyUrl ) {
	    	return $bitlyUrl;
	    } else {
		    //generate the URL
		    $bitly = 'http://api.bit.ly/v3/shorten?format=txt&login=' . $this->options['bitly_username'] . '&apiKey=' . $this->options['bitly_key'] . '&longUrl=' . urlencode( $url );
			$response = wp_remote_get( $bitly,  array( 'timeout' => 15 ) );
			if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) && 200 === $response['response']['code'] ) {
				$short_url = trim( wp_remote_retrieve_body( $response ) );
				update_post_meta( $post_id, '_heateor_sss_bitly_url', $short_url );
				return $short_url;
			}
		}
		return false;

	}

	/**
	 * Get short url.
	 *
	 * @since    1.0.0
	 */
	public function get_short_url( $url, $post_id ) {

		$short_url = '';
		
		if ( isset( $this->short_urls[$url] ) ) {
			// short url already calculated for this post ID
			$short_url = $this->short_urls[$url];
		} elseif ( isset( $this->options['use_shortlinks'] ) && function_exists( 'wp_get_shortlink' ) ) {
			$short_url = wp_get_shortlink();
			if ( $short_url ) {
				$this->short_urls[$url] = $short_url;
			}
			// if bit.ly integration enabled, generate bit.ly short url
		} elseif ( isset( $this->options['bitly_enable'] ) && $this->options['bitly_username'] != '' && $this->options['bitly_key'] != '' ) {
			$short_url = $this->generate_bitly_url( $url, $post_id );
			if ( $short_url ) {
				$this->short_urls[$url] = $short_url;
			}
		}

		return $short_url;

	}

	/**
	 * Check if current page is AMP
	 *
	 * @since    2.1
	 */
	public function is_amp_page() {

		$current_page_url = html_entity_decode( esc_url( $this->get_http_protocol() . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) );
		if ( strpos( $current_page_url, '/amp' ) !== false || strpos( $current_page_url, '?amp=1' ) !== false ) {
			return true;
		}
		return false;

	}

	/**
	 * Sanitize post title
	 *
	 * @since    2.5.1
	 */
	public function sanitize_post_title( $post_title ) {

		$post_title = html_entity_decode( $post_title, ENT_QUOTES, 'UTF-8' );
	    $post_title = rawurlencode( $post_title );
	    $post_title = str_replace( '#', '%23', $post_title );
	    $post_title = esc_html( $post_title );

	    return $post_title;

	}

	/**
	 * Get Yoast SEO post meta Twitter title
	 *
	 * @since    2.5.1
	 */
	public function wpseo_twitter_title( $post ) {

		if ( $post && $this->is_plugin_active( 'wordpress-seo/wp-seo.php' ) && ( $wpseo_twitter_title = WPSEO_Meta::get_value( 'twitter-title', $post->ID ) ) ) {
			return $wpseo_twitter_title;
		}
		return '';

	}

	/**
	 * Render sharing interface html.
	 *
	 * @since    1.0.0
	 */
	public function prepare_sharing_html( $post_url, $sharing_type = 'horizontal', $display_count, $total_shares ) {
	
		global $post;

		if ( $sharing_type == 'vertical' && ( is_front_page() || is_home() ) ) {
			$post_title = get_bloginfo( 'name' ) . " - " . get_bloginfo( 'description' );
		} else {
			$post_title = $post->post_title;
		}
		$original_post_title = html_entity_decode( $post_title, ENT_QUOTES, 'UTF-8' );
		$post_title = $this->sanitize_post_title( $post_title );

		$output = apply_filters( 'heateor_sss_sharing_interface_filter', '', $this, $post_title, $original_post_title, $post_url, $sharing_type, $this->options, $post, $display_count, $total_shares );
		if ( $output != '' ) {
			return $output;
		}
		$html = '';
		$sharing_meta = get_post_meta( $post->ID, '_heateor_sss_meta', true );

		if ( isset( $this->options[$sharing_type.'_re_providers'] ) ) {

			$sharing_networks_object = new Sassy_Social_Share_Sharing_Networks( $this->options );
			
			$sharing_networks = $sharing_networks_object->fetch_sharing_networks();

			$html = '<ul ' . ( $sharing_type == 'horizontal' && $this->options['hor_sharing_alignment'] == "center" ? "style='list-style: none;position: relative;left: 50%;'" : "" ) .' class="heateor_sss_sharing_ul">';
			$icon_height = $this->options[$sharing_type . '_sharing_shape'] != 'rectangle' ? $this->options[$sharing_type . '_sharing_size'] : $this->options[$sharing_type . '_sharing_height'];
			$style = 'style="width:' . ( $this->options[$sharing_type . '_sharing_shape'] != 'rectangle' ? $this->options[$sharing_type . '_sharing_size'] : $this->options[$sharing_type . '_sharing_width'] ) . 'px;height:' . $icon_height . 'px;';
			$counter_container_init_html = '<ss class="heateor_sss_square_count';
			$counter_container_end_html = '</ss>';
			$inner_style = 'display:block;';
			$li_class = 'heateorSssSharingRound';
			if ( $this->options[$sharing_type . '_sharing_shape'] == 'round' ) {
				$style .= 'border-radius:999px;';
				$inner_style .= 'border-radius:999px;';
			} elseif ( $this->options[$sharing_type . '_border_radius'] != '' ) {
				$style .= 'border-radius:' . $this->options[$sharing_type . '_border_radius'] . 'px;';
			}
			if ( $sharing_type == 'vertical' && $this->options[$sharing_type . '_sharing_shape'] == 'square' ) {
				$style .= 'margin:0;';
				$li_class = '';
			}
			$style .= '"';
			$li_items = '';
			$language = $this->options['language'] != '' ? $this->options['language'] : '';
			$like_button_count_container = '';
			if ( $display_count ) {
				$like_button_count_container = $counter_container_init_html . '">&nbsp;' . $counter_container_end_html;
			}

			// share count
			if ( false !== ( $cached_share_count = $this->get_cached_share_count( $this->share_count_transient_id ) ) ) {
			    $share_counts = $cached_share_count;
			} else {
				$share_counts = '&nbsp;';
			}

			$counter_placeholder = '';
			$counter_placeholder_value = '';
			$inner_style_conditional = '';

			if ( $display_count ) {
				if ( ! isset( $this->options[$sharing_type . '_counter_position'] ) ) {
					$counter_position = $sharing_type == 'horizontal' ? 'top' : 'inner_top';
				} else {
					$counter_position = $this->options[$sharing_type . '_counter_position'];
				}
				
				switch ( $counter_position ) {
					case 'left':
						$inner_style_conditional = 'display:block;';
						$counter_placeholder = '><i';
						break;
					case 'top':
						$counter_placeholder = '><i';
						break;
					case 'right':
						$inner_style_conditional = 'display:block;';
						$counter_placeholder = 'i><';
						break;
					case 'bottom':
						$inner_style_conditional = 'display:block;';
						$counter_placeholder = 'i><';
						break;
					case 'inner_left':
						$inner_style_conditional = 'float:left;';
						$counter_placeholder = '><ss';
						break;
					case 'inner_top':
						$inner_style_conditional = 'margin-top:0;';
						$counter_placeholder = '><ss';
						break;
					case 'inner_right':
						$inner_style_conditional = 'float:left;';
						$counter_placeholder = 'ss><';
						break;
					case 'inner_bottom':
						$inner_style_conditional = 'margin-top:0;';
						$counter_placeholder = 'ss><';
						break;
					default:
				}

				$counter_placeholder_value = str_replace( '>', '>' . $counter_container_init_html . ' heateor_sss_%network%_count">&nbsp;' . $counter_container_end_html, $counter_placeholder );
			}
			
			$twitter_username = $this->options['twitter_username'] != '' ? $this->options['twitter_username'] : '';
			$total_share_count = 0;
			
			$share_count = array();
			$to_be_replaced = array();
			$replace_by = array();
			if ( $this->is_amp_page() ) {
				$counter_placeholder_value = str_replace( array( '<ss', '/ss>' ), array( '<i', '/i>' ), $counter_placeholder_value );
				$counter_container_init_html = str_replace( '<ss', '<i', $counter_container_init_html );
				$counter_container_end_html = str_replace( '/ss', '/i', $counter_container_end_html );

				$to_be_replaced[] = '<i';
				$to_be_replaced[] = '/i>';
				$to_be_replaced[] = '<ss';
				$to_be_replaced[] = '/ss>';
				$to_be_replaced[] = 'onclick=\'heateorSssPopup("';
				$to_be_replaced[] = '")\'';
				$to_be_replaced[] = '%amp_email%';
				$to_be_replaced[] = '%amp_whatsapp%';
				$to_be_replaced[] = 'alt="%title%"';

				$replace_by[] = '<a target="_blank"';
				$replace_by[] = '/a>';
				$replace_by[] = '<i';
				$replace_by[] = '/i>';
				$replace_by[] = 'href="';
				$replace_by[] = '"';
				$replace_by[] = 'href="mailto:?subject=' . urldecode( rawurlencode( $post_title ) ) . '&body=' . urlencode( $post_url ) . '"';
				$replace_by[] = 'class="heateorSssSharing heateorSssAmpWhatsapp" Title="Whatsapp"';
				$replace_by[] = '';
			}
			$wpseo_post_title = $post_title;
			$decoded_post_title = esc_html( str_replace( array( '%23', '%27', '%22', '%21', '%3A' ), array( '#', "'", '"', '!', ':' ), urlencode( $original_post_title ) ) );
			if ( $wpseo_twitter_title = $this->wpseo_twitter_title( $post ) ) {
				$wpseo_post_title = $this->sanitize_post_title( $wpseo_twitter_title );
				$decoded_post_title = esc_html( str_replace( array( '%23', '%27', '%22', '%21', '%3A' ), array( '#', "'", '"', '!', ':' ), urlencode( html_entity_decode( $wpseo_twitter_title, ENT_QUOTES, 'UTF-8' ) ) ) );
			}

			foreach ( $this->options[$sharing_type.'_re_providers'] as $provider ) {
				$share_count[$provider] = $share_counts == '&nbsp;' ? '' : ( isset( $share_counts[$provider] ) ? $share_counts[$provider] : '' );
				$isset_starting_share_count = isset( $sharing_meta[$provider . '_' . $sharing_type . '_count'] ) && $sharing_meta[$provider . '_' . $sharing_type . '_count'] != '' ? true : false;
				$total_share_count += intval( $share_count[$provider] ) + ( $isset_starting_share_count ? $sharing_meta[$provider . '_' . $sharing_type . '_count'] : 0) ;
				$sharing_networks[$provider] = str_replace( $to_be_replaced, $replace_by, $sharing_networks[$provider] );
				$li_items .= str_replace(
					array(
						'%padding%',
						'%network%',
						'%ucfirst_network%',
						'%like_count_container%',
						'%post_url%',
						'%post_title%',
						'%wpseo_post_title%',
						'%decoded_post_title%',
						'%twitter_username%',
						'%via_twitter_username%',
						'%language%',
						'%buffer_username%',
						'%style%',
						'%inner_style%',
						'%li_class%',
						$counter_placeholder,
						'%title%',
						'%amp_email%',
						'%amp_whatsapp%'
					),
					array(
						( $this->options[$sharing_type . '_sharing_shape'] == 'rectangle' ? $this->options[$sharing_type . '_sharing_height'] : $this->options[$sharing_type . '_sharing_size'] ) * 21/100,
						$provider,
						ucfirst( str_replace( array( ' ', '_', '.' ), '', $provider ) ),
						$like_button_count_container,
						$post_url,
						$post_title,
						$wpseo_post_title,
						$decoded_post_title,
						$twitter_username,
						$twitter_username ? 'via=' . $twitter_username . '&' : '',
						$language,
						$this->options['buffer_username'] != '' ? $this->options['buffer_username'] : '',
						$style,
						$inner_style . ( $share_count[$provider] || ( $isset_starting_share_count && $share_counts != '&nbsp;' ) ? $inner_style_conditional : '' ),
						$li_class,
						str_replace( '%network%', $provider, $isset_starting_share_count ? str_replace( '>&nbsp;', ' sss_st_count="' . $sharing_meta[$provider . '_' . $sharing_type . '_count'] . '"' . ( $share_counts == '&nbsp;' ? '>&nbsp;' : ' style="visibility:visible;' . ( $inner_style_conditional ? 'display:block;' : '' ) . '">' . $this->round_off_counts( $share_count[$provider] + $sharing_meta[$provider . '_' . $sharing_type . '_count'] ) ) , $counter_placeholder_value ) : str_replace( '>&nbsp;', $share_count[$provider] ? ' style="visibility:visible;' . ( $inner_style_conditional ? 'display:block;' : '' ) . '">' . $this->round_off_counts( $share_count[$provider] ) : '>&nbsp;', $counter_placeholder_value ) ),
						ucfirst( str_replace( '_', ' ', $provider ) ),
						'',
						''
					),
					$sharing_networks[$provider]
				);
			}
			
			if ( isset( $this->options[$sharing_type . '_more'] ) ) {
				$li_items .= '<li class="' . ( $li_class != '' ? $li_class : '' ) . '">';
				if ( $display_count ) {
					$li_items .= $counter_container_init_html . '">&nbsp;' . $counter_container_end_html;
				}
				if ( $this->is_amp_page() ) {
					$li_items .= '<i title="More" class="heateorSssSharing heateorSssMoreBackground"><i class="heateorSssSharingSvg heateorSssMoreSvg"></i></i></li>';
				} else {
					$li_items .= '<i ' . $style . ' title="More" alt="More" class="heateorSssSharing heateorSssMoreBackground" onclick="heateorSssMoreSharingPopup(this, \'' . $post_url . '\', \'' . $post_title . '\', \'' . $this->sanitize_post_title( $this->wpseo_twitter_title( $post ) ) . '\' )" ><ss style="display:block" class="heateorSssSharingSvg heateorSssMoreSvg"></ss></i></li>';
				}
			}
			
			$total_shares_html = '';
			if ( $total_shares ) {
				$total_shares_html = '<li class="' . $li_class . '">';
				if ( $display_count) {
					$total_shares_html .= $counter_container_init_html . '">&nbsp;' . $counter_container_end_html;
				}
				if ( $sharing_type == 'horizontal' ) {
					$add_style = ';margin-left:9px !important;';
				} else {
					$add_style = ';margin-bottom:9px !important;';
				}
				$add_style .= ( $total_share_count && $share_counts != '&nbsp;' ? 'visibility:visible;' : '' ) . '"';
				$style = str_replace( ';"', $add_style, $style );
				$total_shares_html .= '<i ' . $style . ' title="Total Shares" class="heateorSssSharing heateorSssTCBackground">' . ( $total_share_count ? '<div class="heateorSssTotalShareCount" style="font-size: ' . ( $icon_height * 62/100 ) . 'px">' . $this->round_off_counts( $total_share_count ) . '</div><div class="heateorSssTotalShareText" style="font-size: ' . ( $icon_height * 38/100 ) . 'px">' . ( $total_share_count < 2 ? __( 'Share', 'sassy-social-share' ) : __( 'Shares', 'sassy-social-share' ) ) . '</div>' : '' ) . '</i></li>';
			}

			if ( $sharing_type == 'vertical' ) {
				$html .= $total_shares_html . $li_items;
			} else {
				$html .= $li_items . $total_shares_html;
			}

			$html .= '</ul><div class="heateorSssClear"></div>';
		}
		return $html;
	}

	/**
	 * Roud off share counts
	 *
	 * @since    1.7
	 * @param    integer    $sharingCount    Share counts
	 */
	public function round_off_counts( $sharing_count ) {

		if ( $sharing_count > 999 && $sharing_count < 10000 ) {
			$sharing_count = round( $sharing_count/1000 ) . 'K';
		} elseif ( $sharing_count > 9999 && $sharing_count < 100000 ) {
			$sharing_count = round( $sharing_count/1000 ) . 'K';
		} else if ( $sharing_count > 99999 && $sharing_count < 1000000 ) {
			$sharing_count = round( $sharing_count/1000 ) . 'K';
		} else if ( $sharing_count > 999999 ) {
			$sharing_count = round( $sharing_count/1000000 ) . 'M';
		}

		return $sharing_count;
	
	}

	/**
	 * Get cached share counts for given post ID
	 *
	 * @since    1.7
	 * @param    integer    $post_id    ID of the post to fetch cached share counts for
	 */
	public function get_cached_share_count( $post_id ) {

		$share_count_transient = get_transient( 'heateor_sss_share_count_' . $post_id );
		do_action( 'heateor_sss_share_count_transient_hook', $share_count_transient, $post_id );
		return $share_count_transient;
	
	}

	/**
	 * Get http/https protocol at the website
	 *
	 * @since    1.0.0
	 */
	public function get_http_protocol() {
		
		if ( isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) {
			return "https://";
		} else {
			return "http://";
		}
	
	}

	/**
	 * Remove render sharing action from Excerpts, as it gets nasty due to strip_tags()
	 *
	 * @since    2.0
	 */
	public function remove_render_sharing( $content ) {
		
		if ( is_home() ) {
			remove_action( 'the_content', array( $this, 'render_sharing' ) );
		}
		return $content;

	}

	/**
	 * Enable sharing interface at selected areas.
	 *
	 * @since    1.0.0
	 */
	public function render_sharing( $content ) {
		
		// if sharing is disabled on AMP, return content as is
		if ( ! isset( $this->options['amp_enable'] ) && $this->is_amp_page() ) {
			return $content;
		}

		global $post;
		// hook to bypass sharing
		$disable_sharing = apply_filters( 'heateor_sss_disable_sharing', $post, $content );
		// if $disable_sharing value is 1, return content without sharing interface
		if ( $disable_sharing === 1 ) {
			return $content;
		}
		$sharing_meta = get_post_meta( $post->ID, '_heateor_sss_meta', true );
		
		$sharing_bp_activity = false;

		if ( current_filter() == 'bp_activity_entry_meta' ) {
			if ( isset( $this->options['bp_activity'] ) ) {
				$sharing_bp_activity = true;
			}
		}
		
		$post_types = get_post_types( array( 'public' => true ), 'names', 'and' );
		$post_types = array_diff( $post_types, array( 'post', 'page' ) );

		// sharing interface
		if ( isset( $this->options['hor_enable'] ) && ! ( isset( $sharing_meta['sharing'] ) && $sharing_meta['sharing'] == 1 && ( ! is_front_page() || ( is_front_page() && 'page' == get_option( 'show_on_front' ) ) ) ) ) {
			$post_id = $post -> ID;
			// default post url
			$post_url = get_permalink( $post->ID );
			if ( $sharing_bp_activity ) {
				$post_url = bp_get_activity_thread_permalink();
				$post_id = 0;
			} else {
				if ( $this->options['horizontal_target_url'] == 'default' ) {
					$post_url = get_permalink( $post->ID );
					if ( ( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] ) || $post_url == '' ) {
						$post_url = html_entity_decode( esc_url( $this->get_http_protocol() . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) );
					}
				} elseif ( $this->options['horizontal_target_url'] == 'home' ) {
					$post_url = home_url();
					$post_id = 0;
				} elseif ( $this->options['horizontal_target_url'] == 'custom' ) {
					$post_url = $this->options['horizontal_target_url_custom'] ? $this->options['horizontal_target_url_custom'] : get_permalink( $post->ID );
					$post_id = 0;
				}
			}
			
			$sharing_url = $this->get_short_url( $post_url, $post->ID );
			// share count transient ID
			$this->share_count_transient_id = $this->get_share_count_transient_id( $post_url );
			$sharing_div = $this->prepare_sharing_html( $sharing_url ? $sharing_url : $post_url, 'horizontal', isset( $this->options['horizontal_counts'] ), isset( $this->options['horizontal_total_shares'] ) );
			$sharing_container_style = '';
			$sharing_title_style = 'style="font-weight:bold"';
			
			if ( $this->options['hor_sharing_alignment'] == 'right' ) {
				$sharing_container_style = 'style="float: right"';
			} elseif ( $this->options['hor_sharing_alignment'] == 'center' ) {
				$sharing_container_style = 'style="float: right;position: relative;left: -50%;text-align: left;"';
				$sharing_title_style = 'style="font-weight: bold;list-style: none;position: relative;left: 50%;"';
			}
			
			$horizontal_div = "<div class='heateorSssClear'></div><div ". $sharing_container_style ." class='heateor_sss_sharing_container heateor_sss_horizontal_sharing' heateor-sss-data-href='" . $post_url . "'" . ( ( $this->get_cached_share_count( $this->share_count_transient_id ) !== false || $this->is_amp_page() ) ? 'heateor-sss-no-counts="1"' : "" ) . "><div class='heateor_sss_sharing_title' " . $sharing_title_style . " >" . ucfirst( $this->options['title'] ) . "</div>" . $sharing_div . "</div><div class='heateorSssClear'></div>";
			if ( $sharing_bp_activity ) {
				echo $horizontal_div;
			}
			// show horizontal sharing
			if ( ( isset( $this->options['home'] ) && is_front_page() ) || ( isset( $this->options['category'] ) && is_category() ) || ( isset( $this->options['archive'] ) && is_archive() ) || ( isset( $this->options['post'] ) && is_single() && isset( $post -> post_type ) && $post -> post_type == 'post' ) || ( isset( $this->options['page'] ) && is_page() && isset( $post -> post_type ) && $post -> post_type == 'page' ) || ( isset( $this->options['excerpt'] ) && (is_home() || current_filter() == 'the_excerpt' ) ) || ( isset( $this->options['bb_reply'] ) && current_filter() == 'bbp_get_reply_content' ) || ( isset( $this->options['bb_forum'] ) && ( isset( $this->options['top'] ) && current_filter() == 'bbp_template_before_single_forum' || isset( $this->options['bottom'] ) && current_filter() == 'bbp_template_after_single_forum' ) ) || ( isset( $this->options['bb_topic'] ) && ( isset( $this->options['top'] ) && in_array( current_filter(), array( 'bbp_template_before_single_topic', 'bbp_template_before_lead_topic' ) ) || isset( $this->options['bottom'] ) && in_array( current_filter(), array( 'bbp_template_after_single_topic', 'bbp_template_after_lead_topic' ) ) ) ) || ( isset( $this->options['woocom_shop'] ) && current_filter() == 'woocommerce_after_shop_loop_item' ) || ( isset( $this->options['woocom_product'] ) && current_filter() == 'woocommerce_share' ) || ( isset( $this->options['woocom_thankyou'] ) && current_filter() == 'woocommerce_thankyou' ) || (current_filter() == 'bp_before_group_header' && isset( $this->options['bp_group'] ) ) ) {
				if ( in_array( current_filter(), array( 'bbp_template_before_single_topic', 'bbp_template_before_lead_topic', 'bbp_template_before_single_forum', 'bbp_template_after_single_topic', 'bbp_template_after_lead_topic', 'bbp_template_after_single_forum', 'woocommerce_after_shop_loop_item', 'woocommerce_share', 'woocommerce_thankyou', 'bp_before_group_header' ) ) ) {
					echo '<div class="heateorSssClear"></div>' . $horizontal_div . '<div class="heateorSssClear"></div>';
				} else {
					if ( isset( $this->options['top'] ) && isset( $this->options['bottom'] ) ) {
						$content = $horizontal_div . '<br/>' . $content . '<br/>' . $horizontal_div;
					} else {
						if ( isset( $this->options['top'] ) ) {
							$content = $horizontal_div.$content;
						} elseif ( isset( $this->options['bottom'] ) ) {
							$content = $content.$horizontal_div;
						}
					}
				}
			} elseif ( count( $post_types ) ) {
				foreach ( $post_types as $post_type ) {
					if ( isset( $this->options[$post_type] ) && ( is_single() || is_page() ) && isset( $post -> post_type ) && $post -> post_type == $post_type ) {
						if ( isset( $this->options['top'] ) && isset( $this->options['bottom'] ) ) {
							$content = $horizontal_div . '<br/>' . $content.'<br/>'.$horizontal_div;
						} else {
							if ( isset( $this->options['top'] ) ) {
								$content = $horizontal_div.$content;
							} elseif ( isset( $this->options['bottom'] ) ) {
								$content = $content.$horizontal_div;
							}
						}
					}
				}
			}
		}
		if ( isset( $this->options['vertical_enable'] ) && ! ( isset( $sharing_meta['vertical_sharing'] ) && $sharing_meta['vertical_sharing'] == 1 && ( ! is_front_page() || ( is_front_page() && 'page' == get_option( 'show_on_front' ) ) ) ) ) {
			$post_id = $post -> ID;
			$post_url = get_permalink( $post->ID );
			
			if ( $this->options['vertical_target_url'] == 'default' ) {
				$post_url = get_permalink( $post->ID );
				if ( ( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] ) || $post_url == '' ) {
					$post_url = html_entity_decode( esc_url( $this->get_http_protocol() . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ) );
				}
			} elseif ( $this->options['vertical_target_url'] == 'home' ) {
				$post_url = home_url();
				$post_id = 0;
			} elseif ( $this->options['vertical_target_url'] == 'custom' ) {
				$post_url = $this->options['vertical_target_url_custom'] ? $this->options['vertical_target_url_custom'] : get_permalink( $post->ID );
				$post_id = 0;
			}
			
			$sharing_url = $this->get_short_url( $post_url, $post->ID );

			$vertical_sharing_width = ( $this->options['vertical_sharing_shape'] == 'rectangle' ? $this->options['vertical_sharing_width'] : $this->options['vertical_sharing_size'] );
			if ( isset( $this->options['vertical_counts'] ) && isset( $this->options['vertical_counter_position'] ) && in_array( $this->options['vertical_counter_position'], array( 'left', 'right' ) ) ) {
				$vertical_sharing_width += $vertical_sharing_width*60/100;
			}
			// share count transient ID
			$this->share_count_transient_id = $this->get_share_count_transient_id( $post_url );
			$sharing_div = $this->prepare_sharing_html( $sharing_url ? $sharing_url : $post_url, 'vertical', isset( $this->options['vertical_counts'] ), isset( $this->options['vertical_total_shares'] ) );
			$offset = ( $this->options['alignment'] != '' && $this->options[$this->options['alignment'].'_offset'] != '' ? $this->options['alignment'] . ': ' . $this->options[$this->options['alignment'].'_offset'] . 'px;' : '' ) . ( $this->options['top_offset'] != '' ? 'top: '.$this->options['top_offset'] . 'px;' : '' );
			$vertical_div = "<div class='heateor_sss_sharing_container heateor_sss_vertical_sharing" . ( isset( $this->options['bottom_mobile_sharing'] ) ? ' heateor_sss_bottom_sharing' : '' ) . "' style='width:" . ( $vertical_sharing_width + 4 ) . "px;" . $offset . ( $this->options['vertical_bg'] != '' ? 'background-color: '.$this->options['vertical_bg'] : '-webkit-box-shadow:none;box-shadow:none;' ) . "' heateor-sss-data-href='" . $post_url . "'" . ( ( $this->get_cached_share_count( $this->share_count_transient_id ) !== false || $this->is_amp_page() ) ? 'heateor-sss-no-counts="1"' : "" ) . ">" . $sharing_div . "</div>";
			// show vertical sharing
			if ( ( isset( $this->options['vertical_home'] ) && is_front_page() ) || ( isset( $this->options['vertical_category'] ) && is_category() ) || ( isset( $this->options['vertical_archive'] ) && is_archive() ) || ( isset( $this->options['vertical_post'] ) && is_single() && isset( $post -> post_type ) && $post -> post_type == 'post' ) || ( isset( $this->options['vertical_page'] ) && is_page() && isset( $post -> post_type ) && $post -> post_type == 'page' ) || ( isset( $this->options['vertical_excerpt'] ) && (is_home() || current_filter() == 'the_excerpt' ) ) || ( isset( $this->options['vertical_bb_forum'] ) && current_filter() == 'bbp_template_before_single_forum' ) || ( isset( $this->options['vertical_bb_topic'] ) && in_array( current_filter(), array( 'bbp_template_before_single_topic', 'bbp_template_before_lead_topic' ) ) ) || (current_filter() == 'bp_before_group_header' && isset( $this->options['vertical_bp_group'] ) ) ) {
				if ( in_array( current_filter(), array( 'bbp_template_before_single_topic', 'bbp_template_before_lead_topic', 'bbp_template_before_single_forum', 'bp_before_group_header' ) ) ) {
					echo $vertical_div;
				} else {
					if ( is_front_page() ) {
						if ( current_filter() == 'the_content' ) {
							$var = $this->vertical_home_count;
						} elseif ( is_home() || current_filter() == 'the_excerpt' ) {
							$var = $this->vertical_excerpt_count;
						}
						if ( $var == 0 ) {
							if ( $this->options['vertical_target_url'] == 'default' ) {
								$post_url = home_url();
								$sharing_url = $this->get_short_url( $post_url, 0 );
								// share count transient ID
								$this->share_count_transient_id = 0;
								$sharing_div = $this->prepare_sharing_html( $sharing_url ? $sharing_url : $post_url, 'vertical', isset( $this->options['vertical_counts'] ), isset( $this->options['vertical_total_shares'] ) );
								$vertical_div = "<div class='heateor_sss_sharing_container heateor_sss_vertical_sharing" . ( isset( $this->options['bottom_mobile_sharing'] ) ? ' heateor_sss_bottom_sharing' : '' ) . "' style='width:" . ( $vertical_sharing_width + 4 ) . "px;" . $offset . ( $this->options['vertical_bg'] != '' ? 'background-color: ' . $this->options['vertical_bg'] : '-webkit-box-shadow:none;box-shadow:none;' ) . "' heateor-sss-data-href='" . $post_url . "'" . ( ( $this->get_cached_share_count( 0 ) !== false || $this->is_amp_page() ) ? 'heateor-sss-no-counts="1"' : '' ) . ">" . $sharing_div . "</div>";
							}
							$content = $content . $vertical_div;
							if ( current_filter() == 'the_content' ) {
								$this->vertical_home_count++;
							} elseif ( is_home() || current_filter() == 'the_excerpt' ) {
								$this->vertical_excerpt_count++;
							}
						}
					} else {
						$content = $content . $vertical_div;
					}
				}
			} elseif ( count( $post_types ) ) {
				foreach ( $post_types as $post_type ) {
					if ( isset( $this->options['vertical_' . $post_type] ) && ( is_single() || is_page() ) && isset( $post -> post_type ) && $post -> post_type == $post_type ) {
						$content = $content . $vertical_div;
					}
				}
			}
		}
		return $content;
	}

	/**
	 * Return ajax response
	 *
	 * @since    1.0.0
	 */
	private function ajax_response( $response ) {
		
		$response = apply_filters( 'heateor_sss_ajax_response_filter', $response );
		die( json_encode( $response ) );

	}

	/**
	 * Get sharing count for sharing networks
	 *
	 * @since    1.0.0
	 */
	public function fetch_share_counts() {

		if ( isset( $_GET['urls'] ) && count( $_GET['urls'] ) > 0 ) {
			$target_urls = array_unique( $_GET['urls'] );
			foreach ( $target_urls as $k => $v ) {
				$target_urls[$k] = esc_attr( $v );
			}
		} else {
			$this->ajax_response( array( 'status' => 0, 'message' => __( 'Invalid request' ) ) );
		}
		$horizontal_sharing_networks = $this->options['horizontal_re_providers'] ? $this->options['horizontal_re_providers'] : array();
		$vertical_sharing_networks = $this->options['vertical_re_providers'] ? $this->options['vertical_re_providers'] : array();
		$sharing_networks = array_unique( array_merge( $horizontal_sharing_networks, $vertical_sharing_networks ) );
		if ( count( $sharing_networks ) == 0 ) {
			$this->ajax_response( array( 'status' => 0, 'message' => __( 'Providers not selected' ) ) );
		}
		
		$tweet_count_service = 'newsharecounts';
		if ( isset( $this->options['tweet_count_service'] ) ) {
			$tweet_count_service = $this->options['tweet_count_service'];
		} elseif ( isset( $this->options['vertical_tweet_count_service'] ) ) {
			$tweet_count_service = $this->options['vertical_tweet_count_service'];
		}

		if ( $tweet_count_service == 'opensharecount' ) {
			$twitter_count_api = 'http://opensharecount.com/count.json?url=';
		} elseif ( $tweet_count_service == 'newsharecounts' ) {
			$twitter_count_api = 'http://public.newsharecounts.com/count.json?url=';
		}
		
		$response_data = array();
		$ajax_response = array();
		
		if ( in_array( 'facebook', $sharing_networks ) ) {
			$ajax_response['facebook'] = 1;
		}

		$multiplier = 60;
		switch ( $this->options['share_count_cache_refresh_unit'] ) {
			case 'seconds':
				$multiplier = 1;
				break;

			case 'minutes':
				$multiplier = 60;
				break;
			
			case 'hours':
				$multiplier = 3600;
				break;

			case 'days':
				$multiplier = 3600*24;
				break;

			default:
				$multiplier = 60;
				break;
		}
		$transient_expiration_time = $multiplier * $this->options['share_count_cache_refresh_count'];

		$target_urls_array = array();
		$target_urls_array[] = $target_urls;
		$target_urls_array = apply_filters( 'heateor_sss_target_share_urls', $target_urls_array );
		$share_count_transient_array = array();
		if ( in_array( 'facebook', $sharing_networks ) ) {
			$ajax_response['facebook_urls'] = $target_urls_array;
		}
		
		foreach ( $target_urls_array as $target_urls ) {
			$share_count_transients = array();
			foreach ( $target_urls as $target_url ) {
				$share_count_transient = array();
				foreach ( $sharing_networks as $provider ) {
					switch ( $provider ) {
						case 'twitter':
							$url = $twitter_count_api . $target_url;
							break;
						case 'linkedin':
							$url = 'http://www.linkedin.com/countserv/count/share?url=' . $target_url . '&format=json';
							break;
						case 'reddit':
							$url = 'http://www.reddit.com/api/info.json?url=' . $target_url;
							break;
						case 'delicious':
							$url = 'http://feeds.delicious.com/v2/json/urlinfo/data?url=' . $target_url;
							break;
						case 'pinterest':
							$url = 'http://api.pinterest.com/v1/urls/count.json?callback=heateorSss&url=' . $target_url;
							break;
						case 'buffer':
							$url = 'https://api.bufferapp.com/1/links/shares.json?url=' . $target_url;
							break;
						case 'stumbleupon':
							$url = 'http://www.stumbleupon.com/services/1.01/badge.getinfo?url=' . $target_url;
							break;
						case 'google_plus':
							$url = 'http://share.yandex.ru/gpp.xml?url=' . $target_url;
							break;
						case 'vkontakte':
							$url = 'https://vk.com/share.php?act=count&url=' . $target_url;
							break;
						default:
							$url = '';
					}
					if ( $url == '' ) { continue; }
					$response = wp_remote_get( $url,  array( 'timeout' => 15 ) );
					if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) && 200 === $response['response']['code'] ) {
						$body = wp_remote_retrieve_body( $response );
						if ( $provider == 'pinterest' ) {
							$body = str_replace( array( 'heateorSss(', ')' ), '', $body );
						}
						if ( ! in_array( $provider, array( 'google_plus', 'vkontakte' ) ) ) {
							$body = json_decode( $body );
						}
						switch ( $provider ) {
							case 'twitter':
								if ( ! empty( $body -> count ) ) {
									$share_count_transient['twitter'] = $body -> count;
								} else {
									$share_count_transient['twitter'] = 0;
								}
								break;
							case 'linkedin':
								if ( ! empty( $body -> count ) ) {
									$share_count_transient['linkedin'] = $body -> count;
								} else {
									$share_count_transient['linkedin'] = 0;
								}
								break;
							case 'reddit':
								$share_count_transient['reddit'] = 0;
								if ( ! empty( $body -> data -> children ) ) {
									$children = $body -> data -> children;
									$ups = $downs = 0;
									foreach ( $children as $child ) {
						                $ups += ( int ) $child->data->ups;
						                $downs += ( int ) $child->data->downs;
						            }
						            $score = $ups - $downs;
						            if ( $score < 0 ) {
						            	$score = 0;
						            }
									$share_count_transient['reddit'] = $score;
								}
								break;
							case 'delicious':
								if ( ! empty( $body[0] -> total_posts ) ) {
									$share_count_transient['delicious'] = $body[0] -> total_posts;
								} else {
									$share_count_transient['delicious'] = 0;
								}
								break;
							case 'pinterest':
								if ( ! empty( $body -> count ) ) {
									$share_count_transient['pinterest'] = $body -> count;
								} else {
									$share_count_transient['pinterest'] = 0;
								}
								break;
							case 'buffer':
								if ( ! empty( $body -> shares ) ) {
									$share_count_transient['buffer'] = $body -> shares;
								} else {
									$share_count_transient['buffer'] = 0;
								}
								break;
							case 'stumbleupon':
								if ( ! empty( $body -> result ) && isset( $body -> result -> views ) ) {
									$share_count_transient['stumbleupon'] = $body -> result -> views;
								} else {
									$share_count_transient['stumbleupon'] = 0;
								}
								break;
							case 'google_plus':
								if ( ! empty( $body ) ) {
									$share_count_transient['google_plus'] = (int) str_replace( '"', '', $body );
								} else {
									$share_count_transient['google_plus'] = 0;
								}
								break;
							case 'vkontakte':
								if ( ! empty( $body ) ) {
									$share_count_transient['vkontakte'] = (int) str_replace(array( 'VK.Share.count(0, ', ' );' ), '', $body);
								} else {
									$share_count_transient['vkontakte'] = 0;
								}
								break;
						}
					}
				}
				$share_count_transients[] = $share_count_transient;
			}
			$share_count_transient_array[] = $share_count_transients;
		}
		$final_share_count_transient = array();
		for ( $i = 0; $i < count( $target_urls_array[0] ); $i++ ) {
			$final_share_count_transient = $share_count_transient_array[0][$i];
			for ( $j = 1; $j < count( $share_count_transient_array ); $j++ ) {
				foreach ( $final_share_count_transient as $key => $val ) {
					$final_share_count_transient[$key] += $share_count_transient_array[$j][$i][$key];
				}
			}
			$response_data[$target_urls_array[0][$i]] = $final_share_count_transient;
			set_transient( 'heateor_sss_share_count_' . $this->get_share_count_transient_id( $target_urls_array[0][$i] ), $final_share_count_transient, $transient_expiration_time );
		}
		do_action( 'heateor_sss_share_count_ajax_hook', $response_data );
		
		$ajax_response['status'] = 1;
		$ajax_response['message'] = $response_data;

		$this->ajax_response( $ajax_response );

	}

	/**
	 * Save Facebook share counts in transient
	 *
	 * @since    2.4.2
	 */
	public function save_facebook_shares() {
		
		if ( isset( $_GET['share_counts'] ) && is_array( $_GET['share_counts'] ) && count( $_GET['share_counts'] ) > 0 ) {
			$target_urls = $_GET['share_counts'];
			foreach ( $target_urls as $k => $v ) {
				$target_urls[$k] = esc_attr( trim( $v ) );
			}
		} else {
			$this->ajax_response( array( 'status' => 0, 'message' => __( 'Invalid request' ) ) );
		}

		$multiplier = 60;
		switch ( $this->options['share_count_cache_refresh_unit'] ) {
			case 'seconds':
				$multiplier = 1;
				break;

			case 'minutes':
				$multiplier = 60;
				break;
			
			case 'hours':
				$multiplier = 3600;
				break;

			case 'days':
				$multiplier = 3600*24;
				break;

			default:
				$multiplier = 60;
				break;
		}
		$transient_expiration_time = $multiplier * $this->options['share_count_cache_refresh_count'];
		
		foreach ( $target_urls as $key => $value ) {
			$transient_id = $this->get_share_count_transient_id( $key );
			$share_count_transient = get_transient( 'heateor_sss_share_count_' . $transient_id );
			if ( $share_count_transient !== false ) {
				$share_count_transient['facebook'] = $value;
				set_transient( 'heateor_sss_share_count_' . $transient_id, $share_count_transient, $transient_expiration_time );
			}
		}
		die;

	}

	/**
	 * Get ID of the share count transient
	 *
	 * @since    1.0.0
	 */
	public function get_share_count_transient_id( $target_url ) {

		if ( $this->options['horizontal_target_url_custom'] == $target_url || $this->options['vertical_target_url_custom'] == $target_url ) {
			$post_id = 'custom';
		} else {
			$post_id = url_to_postid( $target_url );
		}
		return $post_id;

	}

	/**
	 * Check if plugin is active
	 *
	 * @since    2.5.1
	 */
	private function is_plugin_active( $plugin_file ) {
		return in_array( $plugin_file, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
	}

	/**
	 * Inline style to load at front end.
	 *
	 * @since    2.0
	 */
	public function frontend_inline_style() {
		
		if ( current_filter() == 'wp_enqueue_scripts' ) {
			$important = '';
			?>
			<style type="text/css">
			<?php
		} else {
			$important = '!important';
		}
		?>
		.heateor_sss_horizontal_sharing .heateorSssSharing{
			<?php if ( $this->options['horizontal_bg_color_default'] != '' ) { ?>
				background-color: <?php echo $this->options['horizontal_bg_color_default'] ?>;
			<?php  } ?>
				color: <?php echo $this->options['horizontal_font_color_default'] ? $this->options['horizontal_font_color_default'] : '#fff' ?>;
			<?php
			$border_width = 0;
			if ( $this->options['horizontal_border_width_default'] != '' ) {
				$border_width = $this->options['horizontal_border_width_default'];
			} elseif ( $this->options['horizontal_border_width_hover'] != '' ) {
				$border_width = $this->options['horizontal_border_width_hover'];
			}
			?>
			border-width: <?php echo $border_width . 'px' . $important ?>;
			border-style: solid<?php echo $important ?>;
			border-color: <?php echo $this->options['horizontal_border_color_default'] != '' ? $this->options['horizontal_border_color_default'] : 'transparent'; echo $important; ?>;
		}
		<?php if ( $this->options['horizontal_font_color_default'] == '' ) { ?>
		.heateor_sss_horizontal_sharing .heateorSssTCBackground{
			color:#666;
		}
		<?php } ?>
		.heateor_sss_horizontal_sharing .heateorSssSharing:hover{
			<?php if ( $this->options['horizontal_bg_color_hover'] != '' ) { ?>
				background-color: <?php echo $this->options['horizontal_bg_color_hover'] ?>;
			<?php }
			if ( $this->options['horizontal_font_color_hover'] != '' ) { ?>
				color: <?php echo $this->options['horizontal_font_color_hover'] ?>;
			<?php  } ?>
			border-color: <?php echo $this->options['horizontal_border_color_hover'] != '' ? $this->options['horizontal_border_color_hover'] : 'transparent'; echo $important; ?>;
		}
		.heateor_sss_vertical_sharing .heateorSssSharing{
			<?php if ( $this->options['vertical_bg_color_default'] != '' ) { ?>
				background-color: <?php echo $this->options['vertical_bg_color_default'] ?>;
			<?php } ?>
				color: <?php echo $this->options['vertical_font_color_default'] ? $this->options['vertical_font_color_default'] : '#fff' ?>;
			<?php
			$verticalBorderWidth = 0;
			if ( $this->options['vertical_border_width_default'] != '' ) {
				$verticalBorderWidth = $this->options['vertical_border_width_default'];
			} elseif ( $this->options['vertical_border_width_hover'] != '' ) {
				$verticalBorderWidth = $this->options['vertical_border_width_hover'];
			}
			?>
			border-width: <?php echo $verticalBorderWidth ?>px<?php echo $important ?>;
			border-style: solid<?php echo $important ?>;
			border-color: <?php echo $this->options['vertical_border_color_default'] != '' ? $this->options['vertical_border_color_default'] : 'transparent'; ?><?php echo $important ?>;
		}
		<?php if ( $this->options['horizontal_font_color_default'] == '' ) { ?>
		.heateor_sss_vertical_sharing .heateorSssTCBackground{
			color:#666;
		}
		<?php } ?>
		.heateor_sss_vertical_sharing .heateorSssSharing:hover{
			<?php if ( $this->options['vertical_bg_color_hover'] != '' ) { ?>
				background-color: <?php echo $this->options['vertical_bg_color_hover'] ?>;
			<?php }
			if ( $this->options['vertical_font_color_hover'] != '' ) { ?>
				color: <?php echo $this->options['vertical_font_color_hover'] ?>;
			<?php  } ?>
			border-color: <?php echo $this->options['vertical_border_color_hover'] != '' ? $this->options['vertical_border_color_hover'] : 'transparent'; echo $important; ?>;
		}
		<?php
		if ( isset( $this->options['horizontal_counts'] ) ) {
			$svg_height = $this->options['horizontal_sharing_shape'] == 'rectangle' ? $this->options['horizontal_sharing_height'] : $this->options['horizontal_sharing_size'];
			if ( isset( $this->options['horizontal_counter_position'] ) && in_array( $this->options['horizontal_counter_position'], array( 'inner_top', 'inner_bottom' ) ) ) {
				$line_height_percent = $this->options['horizontal_counter_position'] == 'inner_top' ? 38 : 19;
				?>
				div.heateor_sss_horizontal_sharing .heateorSssSharingSvg{height:70%;margin-top:<?php echo $svg_height*15/100 ?>px}div.heateor_sss_horizontal_sharing .heateor_sss_square_count{line-height:<?php echo $svg_height*$line_height_percent/100 ?>px;}
				<?php
			} elseif ( isset( $this->options['horizontal_counter_position'] ) && in_array( $this->options['horizontal_counter_position'], array( 'inner_left', 'inner_right' ) ) ) { ?>
				div.heateor_sss_horizontal_sharing .heateorSssSharingSvg{width:50%;margin:auto;}div.heateor_sss_horizontal_sharing .heateor_sss_square_count{float:left;width:50%;line-height:<?php echo $svg_height; ?>px;}
				<?php
			} elseif ( isset( $this->options['horizontal_counter_position'] ) && in_array( $this->options['horizontal_counter_position'], array( 'left', 'right' ) ) ) { ?>
				div.heateor_sss_horizontal_sharing .heateor_sss_square_count{float:<?php echo $this->options['horizontal_counter_position'] ?>;margin:0 8px;line-height:<?php echo $svg_height + 2 * $border_width; ?>px;}
				<?php
			} elseif ( ! isset( $this->options['horizontal_counter_position'] ) || $this->options['horizontal_counter_position'] == 'top' ) { ?>
				div.heateor_sss_horizontal_sharing .heateor_sss_square_count{display: block}
				<?php
			}

		}
		if ( isset( $this->options['vertical_counts'] ) ) {
			$vertical_svg_height = $this->options['vertical_sharing_shape'] == 'rectangle' ? $this->options['vertical_sharing_height'] : $this->options['vertical_sharing_size'];
			$vertical_svg_width = $this->options['vertical_sharing_shape'] == 'rectangle' ? $this->options['vertical_sharing_width'] : $this->options['vertical_sharing_size'];
			if ( ( isset( $this->options['vertical_counter_position'] ) && in_array( $this->options['vertical_counter_position'], array( 'inner_top', 'inner_bottom' ) ) ) || ! isset( $this->options['vertical_counter_position'] ) ) {
				$vertical_line_height_percent = ! isset( $this->options['vertical_counter_position'] ) || $this->options['vertical_counter_position'] == 'inner_top' ? 38 : 19;
				?>
				div.heateor_sss_vertical_sharing .heateorSssSharingSvg{height:70%;margin-top:<?php echo $vertical_svg_height*15/100 ?>px}div.heateor_sss_vertical_sharing .heateor_sss_square_count{line-height:<?php echo $vertical_svg_height*$vertical_line_height_percent/100; ?>px;}
				<?php
			} elseif ( isset( $this->options['vertical_counter_position'] ) && in_array( $this->options['vertical_counter_position'], array( 'inner_left', 'inner_right' ) ) ) { ?>
				div.heateor_sss_vertical_sharing .heateorSssSharingSvg{width:50%;margin:auto;}div.heateor_sss_vertical_sharing .heateor_sss_square_count{float:left;width:50%;line-height:<?php echo $vertical_svg_height; ?>px;}
				<?php
			}  elseif ( isset( $this->options['vertical_counter_position'] ) && in_array( $this->options['vertical_counter_position'], array( 'left', 'right' ) ) ) { ?>
				div.heateor_sss_vertical_sharing .heateor_sss_square_count{float:<?php echo $this->options['vertical_counter_position'] ?>;margin:0 8px;line-height:<?php echo $vertical_svg_height; ?>px; <?php echo $this->options['vertical_counter_position'] == 'left' ? 'min-width:' . $vertical_svg_width*30/100 . 'px;display: block' : '';?>}
				<?php
			} elseif ( isset( $this->options['vertical_counter_position'] ) && $this->options['vertical_counter_position'] == 'top' ) { ?>
				div.heateor_sss_vertical_sharing .heateor_sss_square_count{display: block}
				<?php
			}
		}
		echo isset( $this->options['hide_mobile_sharing'] ) && $this->options['vertical_screen_width'] != '' ? '@media screen and (max-width:' . $this->options['vertical_screen_width'] . 'px) {.heateor_sss_vertical_sharing{display:none!important}}' : '';
		$bottom_sharing_postion_inverse = $this->options['bottom_sharing_alignment'] == 'left' ? 'right' : 'left';
		echo isset( $this->options['bottom_mobile_sharing'] ) && $this->options['horizontal_screen_width'] != '' ? '@media screen and (max-width:' . $this->options['horizontal_screen_width'] . 'px) {.heateor_sss_bottom_sharing{' . ( $this->options['bottom_sharing_position'] != '' ? $this->options['bottom_sharing_alignment'] . ':' . $this->options['bottom_sharing_position'] . 'px!important;' . $bottom_sharing_postion_inverse . ':auto!important;' : '' ) . 'display:block!important;width: auto!important;bottom: -10px!important;top: auto!important;}.heateor_sss_bottom_sharing .heateor_sss_square_count{line-height: inherit;}.heateor_sss_bottom_sharing .heateorSssSharingArrow{display:none;}.heateor_sss_bottom_sharing .heateorSssTCBackground{margin-right: 1.1em !important}}' : '';
		echo $this->options['custom_css'];
		if ( current_filter() == 'wp_enqueue_scripts' ) {
			?>
			</style>
			<?php
		}
	}

	/**
	 * Stylesheets to load at front end.
	 *
	 * @since    1.0.0
	 */
	public function frontend_css() {
		
		wp_enqueue_style( 'heateor_sss_frontend_css', plugins_url( 'css/sassy-social-share-public.css', __FILE__ ), false, $this->version );
		$default_svg = false;
		if ( isset( $this->options['hor_enable'] ) ) {
			if ( isset( $this->options['horizontal_more'] ) ) {
				$default_svg = true;
			}
			if ( $this->options['horizontal_font_color_default'] != '' ) {
				wp_enqueue_style( 'heateor_sss_sharing_svg', plugins_url( '../admin/css/sassy-social-share-default-svg-horizontal.css', __FILE__ ), false, $this->version );
			} else {
				$default_svg = true;
			}
			if ( $this->options['horizontal_font_color_hover'] != '' ) {
				wp_enqueue_style( 'heateor_sss_sharing_svg_hover', plugins_url( '../admin/css/sassy-social-share-hover-svg-horizontal.css', __FILE__ ), false, $this->version );
			}
		}
		if ( isset( $this->options['vertical_enable'] ) ) {
			if ( isset( $this->options['vertical_more'] ) ) {
				$default_svg = true;
			}
			if ( $this->options['vertical_font_color_default'] != '' ) {
				wp_enqueue_style( 'heateor_sss_vertical_sharing_svg', plugins_url( '../admin/css/sassy-social-share-default-svg-vertical.css', __FILE__ ), false, $this->version );
			} else {
				$default_svg = true;
			}
			if ( $this->options['vertical_font_color_hover'] != '' ) {
				wp_enqueue_style( 'heateor_sss_vertical_sharing_svg_hover', plugins_url( '../admin/css/sassy-social-share-hover-svg-vertical.css', __FILE__ ), false, $this->version );
			}
		}
		if ( $default_svg ) {
			wp_enqueue_style( 'heateor_sss_sharing_default_svg', plugins_url( '../admin/css/sassy-social-share-svg.css', __FILE__ ), false, $this->version );
		}
	
	}

	/**
	 * Stylesheets to load at front end for AMP.
	 *
	 * @since    2.0
	 */
	public function frontend_amp_css() {
		
		$horizontal_icon_height = $this->options['horizontal_sharing_shape'] != 'rectangle' ? $this->options['horizontal_sharing_size'] : $this->options['horizontal_sharing_height'];
		$vertical_icon_height = $this->options['vertical_sharing_shape'] != 'rectangle' ? $this->options['vertical_sharing_size'] : $this->options['vertical_sharing_height'];

		// css for horizontal sharing bar
		$css = '.heateor_sss_horizontal_sharing a, .heateor_sss_horizontal_sharing i{width:' . ( $this->options['horizontal_sharing_shape'] != 'rectangle' ? $this->options['horizontal_sharing_size'] : $this->options['horizontal_sharing_width'] ) . 'px;height:' . $horizontal_icon_height . 'px;';
		if ( $this->options['horizontal_sharing_shape'] == 'round' ) {
			$css .= 'border-radius:999px;';
		} elseif ( $this->options['horizontal_border_radius'] != '' ) {
			$css .= 'border-radius:' . $this->options['horizontal_border_radius'] . 'px;';
		}
		$css .= '}';

		// css for universal sharing popup
		$css .= '#heateor_sss_popup_bg{background:url(' . plugins_url( '../images/transparent_bg.png', __FILE__ ) . ')!important}.heateorSssAmpWhatsapp{display:inline-block!important;background-color:#55EB4C}.heateorSssWhatsappBackground{display:none!important;}.heateor_sss_square_count{display:none!important}';

		// css for total shares
		$css .= '.heateor_sss_horizontal_sharing .heateorSssTCBackground{margin-left:9px !important;visibility:visible;border:none!important;}.heateor_sss_horizontal_sharing .heateorSssTotalShareCount{font-size:' . $horizontal_icon_height*62/100 . 'px;}.heateor_sss_horizontal_sharing .heateorSssTotalShareText{font-size:' . $horizontal_icon_height*38/100 . 'px;}';
		$css .= '.heateor_sss_vertical_sharing .heateorSssTCBackground{margin-bottom:9px !important;visibility:visible;border:none!important;}.heateor_sss_vertical_sharing .heateorSssTotalShareCount{font-size:' . $vertical_icon_height*62/100 . 'px;}.heateor_sss_vertical_sharing .heateorSssTotalShareText{font-size:' . $vertical_icon_height*38/100 . 'px;}';

		// css for vertical sharing bar
		$css .= '.heateor_sss_vertical_sharing a, .heateor_sss_vertical_sharing i{width:' . ( $this->options['vertical_sharing_shape'] != 'rectangle' ? $this->options['vertical_sharing_size'] : $this->options['vertical_sharing_width'] ) . 'px;height:' . $vertical_icon_height . 'px;';
		if ( $this->options['vertical_sharing_shape'] == 'round' ) {
			$css .= 'border-radius:999px;';
		} elseif ( $this->options['vertical_border_radius'] != '' ) {
			$css .= 'border-radius:' . $this->options['vertical_border_radius'] . 'px;';
		}
		if ( $this->options['vertical_sharing_shape'] == 'square' ) {
			$css .= 'margin:0;';
		}
		$css .= '}';

		$vertical_sharing_width = $this->options['vertical_sharing_shape'] == 'rectangle' ? $this->options['vertical_sharing_width'] : $this->options['vertical_sharing_size'];
		$offset = ( $this->options['alignment'] != '' && $this->options[$this->options['alignment'].'_offset'] != '' ? $this->options['alignment'] . ': ' . $this->options[$this->options['alignment'].'_offset'] . 'px;' : '' ) . ( $this->options['top_offset'] != '' ? 'top: '.$this->options['top_offset'] . 'px;' : '' );
		$css .= ".heateor_sss_vertical_sharing{width:" . ( $vertical_sharing_width + 4 ) . "px;" . $offset . ( $this->options['vertical_bg'] != '' ? 'background-color: '.$this->options['vertical_bg'] : '-webkit-box-shadow:none!important;box-shadow:none!important;' ) . "}";

		$css .= '.heateor_sss_sharing_container i{display:block;}';

		$css .= file_get_contents( HEATEOR_SSS_PLUGIN_DIR . '/public/css/sassy-social-share-public.css' );
		$default_svg = false;
		if ( isset( $this->options['hor_enable'] ) ) {
			if ( isset( $this->options['horizontal_more'] ) ) {
				$default_svg = true;
			}
			if ( $this->options['horizontal_font_color_default'] != '' ) {
				$css .= file_get_contents( HEATEOR_SSS_PLUGIN_DIR . '/admin/css/sassy-social-share-default-svg-horizontal.css' );
			} else {
				$default_svg = true;
			}
			if ( $this->options['horizontal_font_color_hover'] != '' ) {
				$css .= file_get_contents( HEATEOR_SSS_PLUGIN_DIR . '/admin/css/sassy-social-share-hover-svg-horizontal.css' );
			}
		}
		if ( isset( $this->options['vertical_enable'] ) ) {
			if ( isset( $this->options['vertical_more'] ) ) {
				$default_svg = true;
			}
			if ( $this->options['vertical_font_color_default'] != '' ) {
				$css .= file_get_contents( HEATEOR_SSS_PLUGIN_DIR . '/admin/css/sassy-social-share-default-svg-vertical.css' );
			} else {
				$default_svg = true;
			}
			if ( $this->options['vertical_font_color_hover'] != '' ) {
				$css .= file_get_contents( HEATEOR_SSS_PLUGIN_DIR . '/admin/css/sassy-social-share-hover-svg-vertical.css' );
			}
		}
		if ( $default_svg ) {
			$css .= file_get_contents( HEATEOR_SSS_PLUGIN_DIR . '/admin/css/sassy-social-share-svg.css' );
		}
		echo $css;
	
	}

	public function update_db_check() {

		$current_version = get_option( 'heateor_sss_version' );
		if ( $current_version != $this->version ) {
			if ( $this->options['horizontal_sharing_replace_color'] != '#fff' ) {
				heateor_sss_update_svg_css( $this->options['horizontal_sharing_replace_color'], 'sassy-social-share-default-svg-horizontal' );
			}
			if ( $this->options['horizontal_font_color_hover'] != '#fff' ) {
				heateor_sss_update_svg_css( $this->options['horizontal_font_color_hover'], 'sassy-social-share-hover-svg-horizontal' );
			}
			if ( $this->options['vertical_font_color_default'] != '#fff' ) {
				heateor_sss_update_svg_css( $this->options['vertical_font_color_default'], 'sassy-social-share-default-svg-vertical' );
			}
			if ( $this->options['vertical_font_color_hover'] != '#fff' ) {
				heateor_sss_update_svg_css( $this->options['vertical_font_color_hover'], 'sassy-social-share-hover-svg-vertical' );
			}
			
			if ( version_compare( '1.7', $current_version ) > 0 ) {
				$this->options['share_count_cache_refresh_count'] = '10';
				$this->options['share_count_cache_refresh_unit'] = 'minutes';
				update_option( 'heateor_sss', $this->options );
			}

			if ( version_compare( '2.3', $current_version ) > 0 ) {
				$this->options['amp_enable'] = '1';
				update_option( 'heateor_sss', $this->options );
			}

			if ( version_compare( '2.4', $current_version ) > 0 ) {
				$this->options['instagram_username'] = '';
				$this->options['vertical_instagram_username'] = '';
				update_option( 'heateor_sss', $this->options );
			}

			// update plugin version in database
			update_option( 'heateor_sss_version', $this->version );
		}
	
	}

}
