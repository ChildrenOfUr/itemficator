<?php

/////////////////////////////////////////////////
/// Load dependencies and set error reporting ///
/////////////////////////////////////////////////

ini_set('display_errors', 1);
error_reporting(E_ALL);
libxml_use_internal_errors(TRUE);

require('vendor/autoload.php');

//////////////////////////////////
/// Change these two variables ///
//////////////////////////////////

if (isset($_GET["url"])) {
	$url = $_GET["url"];
} else {
	die("
>>> Your output will show up here.

>>> Enter a valid URL and check whether it is a tool.
");
}

if (isset($_GET["tool"]) && $_GET["tool"] == true) {
	$tool = true;
} else {
	$tool = false;
}

if (isset($_GET["category"])) {
	$category = true;
} else {
	$category = false;
}

out('{');

if ($category) {
	$doc = file_get_contents($url);
	$itemList = qp($doc, '.items-list');
	foreach ($itemList->find('a') as $item) {
		itemficate('http://www.glitchthegame.com' . $item->attr('href'), $tool);
	}
} else {
	itemficate($url, $tool);
}

function itemficate($url, $tool) {
	if (strpos($url, "http://glitchthegame.com/") === false && strpos($url, "http://www.glitchthegame.com/") === false) {
		die("ERROR >>> The URL must be from the Glitch encyclopedia and have http:// in front of it.\n");
	}

	$doc = file_get_contents($url);

	if ($doc == "") {
		die("ERROR >>> The URL was invalid.\n");
	}

	if ($tool == 'on') {
		////////////////////
		/// Collect data ///
		////////////////////
		$category = qp($doc, "h4.category-nav-back a")->text();
		$name = qp($doc, "h1.first")->text();
		$description = qp($doc, "p.enc-description")->text();
		$spriteUrl = qp($doc, 'h4:contains(Static Images)+table.asset_list')->find('tr')->find('td:contains(1)')->find('a')->attr('href');
		$animation = qp($doc, 'h4:contains(Animations)+table.asset_list')->find('tr')->find('td:contains(tool_animation)')->find('a')->attr('href');
		$iconUrl = qp($doc, 'h4:contains(Static Images)+table.asset_list')->find('tr:last-child')->find('td:contains(iconic)')->find('a')->attr('href');
		$wear = qp($doc, "li.item-wear strong")->text();
		$price = qp($doc, "li.item-price strong")->text();
		if ($spriteUrl == NULL) {
			$spriteUrl = $iconUrl;
		}
		$price = qp($doc, "li.item-price strong")->text();
		if ($price == "Vendors will not buy this item") {
			$price = -1;
		} else if ($price == NULL) {
			$price = 0;
		} else {
			$price = explode(' ', str_replace(array(','), array(''), $price));
			$price = $price[0];
		}
		$stack = qp($doc, "li.item-stack strong")->text();
		$durability = qp($doc, "li.item-wear strong")->text();
		$durability = explode(' ', $durability);
		$durability = $durability[0];

		/////////////
		/// Write ///
		/////////////
		out('	"' . str_replace(array('http://www.glitchthegame.com/items/' . strtolower($category), '/', '-'), array('', '', '_'), strtolower($url)) . '": {');
		out('		"name": "' . $name . '",');
		out('		"category": "' . $category . '",');
		out('		"toolAnimation": "' . $animation . '",');
		out('		"iconUrl": "' . $iconUrl . '",');
		out('		"spriteUrl": "' . $spriteUrl . '",');
		out('		"stacksTo": ' . $stack . ',');
		out('		"price": ' . $price . ',');
		out('		"durability": ' . $durability . ',');
		out('		"description": "' . str_replace(array('"', '\n'), array('\"', ''), $description) . '"');
		out('	},');
	} else {
		////////////////////
		/// Collect data ///
		////////////////////
		$category = qp($doc, "h4.category-nav-back a")->text();
		$name = qp($doc, "h1.first")->text();
		$description = qp($doc, "p.enc-description")->text();
		$iconUrl = qp($doc, 'h4:contains(Static Images)+table.asset_list')->find('tr:nth-child(2)')->find('td:first-child')->find('a')->attr('href');
		$spriteUrl = qp($doc, 'h4:contains(Animations)+table.asset_list')->find('tr:nth-child(2)')->find('td:first-child')->find('a')->attr('href');
		if ($spriteUrl == NULL) {
			$spriteUrl = $iconUrl;
		}
		$price = qp($doc, "li.item-price strong")->text();
		if ($price == "Vendors will not buy this item") {
			$price = -1;
		} else if ($price == NULL) {
			$price = 0;
		} else {
			$price = explode(' ', str_replace(array(','), array(''), $price));
			$price = $price[0];
		}
		$stack = qp($doc, "li.item-stack strong")->text();

		/////////////
		/// Write ///
		/////////////
		out('	"' . str_replace(array('http://www.glitchthegame.com/items/' . strtolower($category), '/', '-'), array('', '', '_'), strtolower($url)) . '": {');
		out('		"name": "' . $name . '",');
		out('		"category": "' . $category . '",');
		out('		"iconUrl": "' . $iconUrl . '",');
		out('		"spriteUrl": "' . $spriteUrl . '",');
		out('		"stacksTo": ' . $stack . ',');
		out('		"price": ' . $price . ',');
		out('		"description": "' . str_replace(array('"', '\n'), array('\"', ''), $description) . '"');
		out('	},');
	}
}

out('}');

///////////////////////////////////
/// Make outputting text easier ///
///////////////////////////////////

function out($text) {
	echo($text . "\n");
}
?>