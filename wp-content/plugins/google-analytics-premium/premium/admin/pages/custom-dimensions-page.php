<div id="ga-promote">
	<p class="ga-topdescription">
		<?php echo sprintf(
			__( 'Please visit our knowledge base for more information about %1$show to setup%3$s and %2$show to use%3$s custom dimensions in Google Analytics.', 'google-analytics-for-wordpress' ),
			"<a href='http://kb.yoast.com/article/179-how-do-i-setup-custom-dimensions' target='_blank'>",
			"<a href='http://kb.yoast.com/article/178-where-can-i-find-the-custom-dimension-reports' target='_blank'>",
			'</a>'
		); ?>
	</p>
</div>

<div class="ga-form ga-form-input">
	<table id="yoast-ga-form-label-table-settings-custom_dimensions" class="widefat fixed" width="100%">
		<thead>
		<th width="45%"><?php _e( 'Type', 'google-analytics-for-wordpress' ); ?></th>
		<th width="45%"><?php _e( 'Custom Dimension ID', 'google-analytics-for-wordpress' ); ?></th>
		<th width="10%">&nbsp;</th>
		</thead>
		<tbody>
		<?php $total = 1;
		$custom_ids  = array( 0 );
		foreach ( $custom_dimensions as $custom ):
			$custom_ids[] = $custom['id'];
			?>
			<tr id="yst_ga-<?php echo $total; ?>">
				<td><select name="custom_dimensions[<?php echo $total; ?>][type]">
						<?php foreach ( $custom_dimensions_types as $key => $dimension ):
							if ( $custom['type'] == $key ) {
								echo '<option value="' . $key . '" SELECTED>' . $dimension . '</option>';
							} else {
								echo '<option value="' . $key . '">' . $dimension . '</option>';
							}
						endforeach; ?>
					</select></td>
				<td align="left">
					<input type="text" name="custom_dimensions[<?php echo $total; ?>][id]" value="<?php echo $custom['id']; ?>" style="width: 50px;" />
				</td>
				<td>
					<a href="#" id="yst-ga_remove_<?php echo $total; ?>"><?php _e( 'Delete', 'google-analytics-for-wordpress' ); ?></a>
				</td>
			</tr>
			<?php $total ++; endforeach; ?>
		</tbody>
		<tfoot>
		<th colspan="1" id="yst_add_cd_holder">
			<strong><a href="#" id="yst-ga_add_row">+ <?php _e( 'Add new custom dimension', 'google-analytics-for-wordpress' ); ?></a>&nbsp;
			</strong></th>
		<th align="left" colspan="2">
			<i><?php printf( __( 'You are using <span id="yst-ga_limit">%1$s</span> out of %2$s custom dimensions', 'google-analytics-for-wordpress' ), $custom_dimensions_usage, $custom_dimensions_limit ); ?></i>
		</th>
		</tfoot>
	</table>
</div>

<script type="text/javascript">
	var total = <?php echo max($custom_ids); ?>;
	var limit = <?php echo $custom_dimensions_limit; ?>;
	var tmp_total = <?php echo $custom_dimensions_usage; ?>;
	var translate_delete = '<?php _e('Delete', 'google-analytics-for-wordpress'); ?>';
	var options_to_add = '';

	<?php
	$options_to_add = '';
	foreach($custom_dimensions_types as $key => $dimension):
		$options_to_add .= '<option value="' . $key . '">' . $dimension . '</option>';
	endforeach;
	echo "options_to_add = '{$options_to_add}';";
	?>

	jQuery(document).ready(function () {
		custom_dimensions.init();

		<?php if( $universal == false ): ?>
		jQuery("#yst_add_cd_holder").html('<strong><i style="color: red;"><?php printf( __('%1$sUniversal tracking%2$s is not enabled'), '<a style="color: red;text-decoration: underline; cursor: pointer;" onclick="custom_dimensions.open_universal();">', '</a>'); ?></i></strong>');
		<?php endif; ?>
	});

</script>