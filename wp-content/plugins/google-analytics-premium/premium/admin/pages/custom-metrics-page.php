<div id="custommetrics" class="gatab">
<?php
echo '<h2>' . __( 'Custom metrics', 'google-analytics-for-wordpress' ) . '</h2>';

// Showing checkbox for enabling/disabling adsense tracking
echo $yoast_ga_admin->input(
	'checkbox',
	__( 'Google metrics', 'yoast-google-analytics-premium', 'google-analytics-for-wordpress' ),
	'track_adsense',
	null,
	__( 'This requires integration of your Analytics and AdSense account, for help, <a href="https://support.google.com/adsense/answer/94743?hl=en&ref_topic=23415" target="_blank">look here</a>.', 'yoast-google-analytics-premium', 'google-analytics-for-wordpress' )
);

?>
</div>