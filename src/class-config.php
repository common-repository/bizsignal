<?php
namespace kumapiyo\bizsignal;

/**
 * It's preset data.
 * 
 * @author sungohan
 * @package bizsignal
 * @since 0.9
 */
final class Config {
	public const VERSION = '1.0';
	public const PLUGIN_NAME = "kumapiyo_bzsgnl";
	public const PLUGIN_ID = self::PLUGIN_NAME . "_";
	public const PREFIX_FIELD_NAME = self::PLUGIN_ID. "option";
	public const DS = DIRECTORY_SEPARATOR;

	public static function get_preset_signals() {
		return array(
			array(
				'name' => 'standard1',
				'dispname' => '',
				'set' => array(
					array('slug' => 'bblue', 'image_filename' => 'blinkblue.gif', 'dispname' => '今空いてます', 'short_desc' => ''),
					array('slug' => 'blue', 'image_filename' => 'blue.gif', 'dispname' => '本日空きあります', 'short_desc' => ''),
					array('slug' => 'byellow', 'image_filename' => 'blinkyellow.gif', 'dispname' => '少々混雑しています', 'short_desc' => ''),
					array('slug' => 'red', 'image_filename' => 'blinkred.gif', 'dispname' => '本日は終了しました', 'short_desc' => ''),
					array('slug' => 'rred', 'image_filename' => 'red.gif', 'dispname' => '本日定休日です', 'short_desc' => ''),
					array('slug' => 'off', 'image_filename' => 'off.gif', 'dispname' => '更新をお待ちください。よろしくね。まってるよ。', 'short_desc' => ''),
					array('slug' => 'nigiyaka', 'image_filename' => 'nigiyaka.gif', 'dispname' => '-', 'short_desc' => ''),
				)
			),
			array(
				'name' => 'standard2',
				'dispname' => '',
				'set' => array(
					array('slug' => 'bblue', 'image_filename' => 'blinkblue.gif', 'dispname' => 'すぐ〜10分程度', 'short_desc' => ''),
					array('slug' => 'blue', 'image_filename' => 'blue.gif', 'dispname' => '20分〜30分程度', 'short_desc' => ''),
					array('slug' => 'yellow', 'image_filename' => 'yellow.gif', 'dispname' => '45分〜1時間程度', 'short_desc' => ''),
					array('slug' => 'yy', 'image_filename' => 'blinkyellow.gif', 'dispname' => '1時間半〜', 'short_desc' => ''),
					array('slug' => 'rr', 'image_filename' => 'blinkred.gif', 'dispname' => '2時間〜', 'short_desc' => ''),
					array('slug' => 'red', 'image_filename' => 'red.gif', 'dispname' => '本日の受付は終了しました', 'short_desc' => ''),
					array('slug' => 'off', 'image_filename' => 'off.gif', 'dispname' => '更新をお待ちください', 'short_desc' => ''),
					array('slug' => 'off', 'image_filename' => 'off.gif', 'dispname' => '-', 'short_desc' => ''),
				)
			),
			array(
				'name' => 'nomitsusan',
				'dispname' => '',
				'set' => array(
					array(
						'slug' => 'nigiyaka',
						'image_filename' => 'nigiyaka.gif',
						//もっと混んでほしいわ。
						'dispname' => 'Come here, World!',
						'short_desc' => ''
					),
					array(
						'slug' => 'bblue',
						'image_filename' => 'blue_special.gif',
						//もっと来てほしいわ。
						'dispname' => 'We want more to come to us.',
						'short_desc' => ''
					),
					array(
						'slug' => 'blue',
						'image_filename' => 'blue_all.gif',
						// 少しも混んでないわ。
						//We are not in the least bit crowded.
						'dispname' => 'We look forward to welcoming you.',
						'short_desc' => ''
					),
					array(
						'slug' => 'yellow',
						'image_filename' => 'yyblue.gif',
						//少し混んできたわ
						'dispname' => 'We have a lot of customers!',
						'short_desc' => ''
					),
					array(
						'slug' => 'yy',
						'image_filename' => 'redyellow2blue.gif',
						// けっこう混んできたわ。
						'dispname' => 'We are getting very crowded.',
						'short_desc' => ''
					),
					array(
						'slug' => 'rr',
						'image_filename' => 'redredy.gif',
						//少しも空いてないわ。
						'dispname' => 'We are full. Thank you!',
						'short_desc' => ''
					),
					array(
						'slug' => 'red',
						'image_filename' => 'fastred.gif',
						//本日は終了したわ
						'dispname' => 'We are closed.',
						'short_desc' => ''
					),
					array('slug' => 'off', 'image_filename' => 'off.gif', 'dispname' => '-', 'short_desc' => ''),
				)
			),
			array(
				'name' => 'konzatsukun',
				'dispname' => '',
				'set' => array(
					array('slug' => 'bblue', 'image_filename' => 'blinkblue.gif', 'dispname' => '気にせずどかん来いよ', 'short_desc' => ''),
					array('slug' => 'blue', 'image_filename' => 'blue.gif', 'dispname' => '少しも混んでないぜ', 'short_desc' => ''),
					array('slug' => 'yellow', 'image_filename' => 'yellow.gif', 'dispname' => '少し混んできたぜ', 'short_desc' => ''),
					array('slug' => 'yy', 'image_filename' => 'blinkyellow.gif', 'dispname' => 'けっこう混んできたぜ', 'short_desc' => ''),
					array('slug' => 'rr', 'image_filename' => 'blinkred.gif', 'dispname' => '少しも空いてないぜ', 'short_desc' => ''),
					array('slug' => 'red', 'image_filename' => 'red.gif', 'dispname' => '本日は終了したぜ', 'short_desc' => ''),
					array('slug' => 'off', 'image_filename' => 'off.gif', 'dispname' => '更新をお待ちください', 'short_desc' => ''),
					array('slug' => 'off', 'image_filename' => 'off.gif', 'dispname' => '-', 'short_desc' => ''),
				)
			),		
		);
	}
}
