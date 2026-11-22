<?php
/**
 * Title: El Roi — Book a Room page
 * Slug: elroi-child/page-booking
 * Categories: elroi
 * Post Types: page
 * Block Types: core/post-content
 * Description: Booking request form with live price estimate, rates and policies.
 *
 * @package elroi-child
 */

echo erk_b_banner( 'Book a Room', 'Send your request and we will confirm by phone or WhatsApp', 'room-standard' ); // phpcs:ignore WordPress.Security.EscapeOutput

echo erk_b_section( erk_b_wide( erk_b_shortcode( '[erk_booking_form]' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput
