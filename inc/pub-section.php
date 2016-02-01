<?php
/**
 * Publish box template
 * @since 0.3
 */

 /**
  * Filterable label text for checkbox. (Skipped i18n for now.)
  * @since 0.3
  */
 $label_text = apply_filters(
 	'dewp_planet__pub_section_label',
	'Im DEWP-Planet-Feed anzeigen'
);

/**
 * Filterable notice text for checkbox. (Skipped i18n for now.)
 * @since 0.3
 */
$notice_text = apply_filters(
	'dewp_planet__pub_section_notice',
	'<span class="dashicons dashicons-warning" aria-hidden="true"></span><strong>Erscheint in allen deutschsprachigen WordPress-Dashboards!</strong>'
);
?>
<div class="misc-pub-section dewp-planet">
	<label for="dewp-planet__add-to-feed">
		<input type="checkbox" id="dewp-planet__add-to-feed" name="dewp-planet__add-to-feed" class="dewp-planet__add-to-feed" <?php checked( $value ); disabled( $maybe_enabled, false ); ?> value="1" />
		<span class="dewp-planet__label-text">
			<?php echo $label_text; ?>
			<a href="https://github.com/deworg/dewp-planet-feed/blob/master/ABOUT.md" class="dewp-planet__help-link dashicons-before dashicons-editor-help hide-if-no-js" target="_blank">
				<span class="screen-reader-text">Was ist das?</span>
			</a>
			<span class="dewp-planet__label-notice">
				<?php echo $notice_text; ?>
			</span>
		</span>
	</label>
</div>
