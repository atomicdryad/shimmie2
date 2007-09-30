<?php
// Tagger - Advanced Tagging
// Author: Artanis (Erik Youngren <artanis.00@gmail.com>)
// Do not remove this notice.

class tagger extends Extension {
	var $theme;
	
	public function receive_event ($event) {
		if(is_null($this->theme))
			$this->theme = get_theme_object("tagger", "taggerTheme");	
			
		if(is_a($event,"InitExtEvent")) {
			global $config;
			if ($config->get_int("ext-tagger_tags-min") == -1)
				$config->set_int("ext-tagger_tags-min",2);
				
			if ($config->get_string("ext-tagger_clear-tagme") == -1)
				$config->set_bool("ext-tagger_clear-tagme",false);
				
			if ($config->get_string("ext-tagger_show-hidden") == -1)
				$config->set_bool("ext-tagger_show-hidden",false);
		}
		
		if(is_a($event,"DisplayingImageEvent")) {
			//show tagger box
			global $database;
			global $page;
			global $config;
			
			$base_href = $config->get_string('base_href');
			$tags_min = $config->get_int('ext-tagger_tags-min',2);
			$hidden = $config->get_string(
				'ext-tagger_show-hidden','N')=='N' ?
				" AND substring(tag,1,1) != '.' " : null;
				
			$tags = $database->Execute("
				SELECT tag
				FROM `tags`
				WHERE count>=?{$hidden}
				ORDER BY tag",array($tags_min));
				
			$this->theme->build($page, $tags);
		}
		
		if(is_a($event,"PageRequestEvent") && $event->page_name == "about"
			&& $event->get_arg(0) == "tagger")
		{
			global $page;
			$this->theme->show_about($page);
		}
		
		if(is_a($event, 'SetupBuildingEvent')) {
			$sb = new SetupBlock("Tagger - Power Tagging");
			$sb->add_bool_option("ext-tagger_show-hidden", "Show Hidden Tags");
			$sb->add_bool_option(
				"ext-tagger_clear-tagme",
				"<br/>Remove '<a href='".make_link("post/list/tagme/1")."'>tagme</a>' on use");
			$sb->add_int_option(
				"ext-tagger_tags-min",
				"<br/>Ignore tags used fewer than "); $sb->add_label("times.");
			$event->panel->add_block($sb);
			}
	}
}
add_event_listener( new tagger());
?>