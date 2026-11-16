<?php
/** Google Map of the motel (no API key needed). */
$q = rawurlencode( elroi_opt( 'map_query' ) );
?>
<iframe title="Map showing El Roi Khadoshi Motel, Kyenjojo" src="https://maps.google.com/maps?q=<?php echo esc_attr( $q ); ?>&amp;z=16&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
