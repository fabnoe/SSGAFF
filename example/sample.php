<?php 
	/**
	 * Server Side Google Analytics for Facebook
	 * @author     Fabian Nšthe (hello@insnet.de)
	 */
?>

<?php 
	/**
	 * Move the next lines into your own framework implementation
	 */

	/**
	 * Config
	 */
	$accountId = 'UA-1231213-1';
	$domainName = 'facebook.com';
	$rootPath = 'http://www.yourdomain.de/pathToSSGA/';

	/**
	 * Detects if the user is fan, nonfan or not logged in
	 * @return const Userstatus
	 */
	function getUserStatus() {

		// This only works inside Facebook
		if(isset($_REQUEST["fb_sig_is_fan"]) && $_REQUEST["fb_sig_is_fan"] == 1) {
			// User ist fan
			$userStatus = 'fan';
		} else {
			// User is logged in, but not fan:
			$userStatus = 'nofan';
		}
		return $userStatus;
	}

	/**
	 * Inserts image to track initial page view
	 * @param string $path pagelink
	 * @param string $title pagetitle
	 * @param string $userStatus fan, nonfan, logout
	 * @return void
	 */
	function trackPageView($path, $title, $userStatus) {
		global $accountId, $domainName, $rootPath;
		echo '<img src='.$rootPath.'ssga/ssga_page.php?accountId='.$accountId.'&domainName='.$domainName.'&userstatus='.getUserStatus().'&path='.$path.'&title='.$title.'" />';
	}
?>
<script type="text/javascript">
/**
 * Serverside Google Analytics
 */
var ssga = {
		/**
		 * Google vars
		 */
		accountId : '<?=$accountId?>',
		domainName : '<?=$domainName?>',

		/**
		 * Canvas wrapper element
		 */
		wrapper: document.getElementById('wrapper'),
		
		/**
		 * Paths to ssga facades
		 */
		facadePagePath : '<?=$rootPath."ssga/ssga_page.php?accountId=".$accountId."&domainName=".$domainName?>',
		facadeEventPath : '<?=$rootPath."ssga/ssga_event.php?accountId=".$accountId."&domainName=".$domainName?>',
		
		
		/**
		 * Userstatus (custom var)
		 */
		userstatus : '<?=getUserStatus()?>',

		/**
		 * Appends the tracker .gif at the end of the page
		 * @return void
		 */
		addTrackerWithUrl: function(url) {
			 tracker = document.createElement('img');
			 tracker.setSrc(url);
			 this.wrapper.appendChild(tracker);	
		},
		
		/**
		 * Invokes a page view
		 * @return boolean
		 */
		trackPageview: function (path, title) {
			url = this.facadePagePath+'&path='+escape(path)+'&title='+escape(title)+'&userstatus='+this.userstatus;
			this.addTrackerWithUrl(url);
			
			return false;
		},

		/**
		 * Invokes a event 
		 * @return boolean
		 */
		trackEvent: function (category, action, label) {
			url = this.facadeEventPath+'&category='+escape(category)+'&label='+escape(label)+'&action='+escape(action)+'&userstatus='+this.userstatus;
			this.addTrackerWithUrl(url);
			
			return false;
		},
		
		/**
		 * Invokes a outboud link via event
		 * @return boolean
		 */
		trackOutboundLink: function (obj, title) {
			url = obj.getHref();
			this.trackEvent('outbound', title, url)
			
			return false;
		}
}
</script>
<style>
h2 {
	margin-top: 20px;
}
</style>
</head>
<div id="wrapper">
	<h1>Sample SSGA for Facebook</h1>
	
	<h2>Track initial pageview</h2>
	<?=trackPageView('/home', 'home' , getUserStatus())?>
	
	<h2>Track pageview with FBJS</h2>
	<a href="#" onclick="ssga.trackPageview('/page', 'Another page')">trackPageview</a>
	
	<h2>Track event with FBJS</h2>
	<a href="#" onclick="ssga.trackEvent('permission', 'publish_stream', 'granted')">trackEvent</a>
	
	<h2>Track outbound link with FBJS</h2>
	<a href="http://www.insnet.de" onclick="ssga.trackOutboundLink(this, 'insnet');">insnet</a>
</div>
