<?php
/* ----------------------------------------------------------------------
 * themes/default/views/bundles/ca_collections_default_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2013-2022 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */

$t_item = $this->getVar("item");
$va_comments = $this->getVar("comments");
$vn_comments_enabled = 	$this->getVar("commentsEnabled");
$vn_share_enabled = 	$this->getVar("shareEnabled");
$vn_pdf_enabled = 		$this->getVar("pdfEnabled");

# --- get collections configuration
$o_collections_config = caGetCollectionsConfig();
$vb_show_hierarchy_viewer = true;
if ($o_collections_config->get("do_not_display_collection_browser")) {
	$vb_show_hierarchy_viewer = false;
}
# --- get the collection hierarchy parent to use for exportin finding aid
$vn_top_level_collection_id = array_shift($t_item->get('ca_collections.hierarchy.collection_id', array("returnWithStructure" => true)));

# Prepare values for search inside collection

$va_access_values = caGetUserAccessValues($this->request);

$o_browse = caGetBrowseInstance("ca_objects");
$o_browse->addCriteria("collection_facet", $t_item->get("ca_collections.collection_id"));
$o_browse->execute(array('checkAccess' => $va_access_values));
$vb_show_objects_link = false;
# include conditional to display only where is not hierarchical tree
if ($o_browse->numResults() && !$t_item->get("ca_collections.children.collection_id", array("checkAccess" => $va_access_values))) {
	$vb_show_objects_link = true;
}
$vb_show_collections_link = false;
if ($t_item->get("ca_collections.children.collection_id", array("checkAccess" => $va_access_values))) {
	$vb_show_collections_link = true;
}

# --------------------
# Mimetypes
#$mimetypes = $this->render("Details/data/mimetypes.php");
require_once(__CA_THEMES_DIR__ . "/imagining/views/Details/data/mimetypes.php");

# --------------------

?>
<div class="row">
	<div class='col-xs-12 navTop'><!--- only shown at small screen size -->
		{{{previousLink}}}{{{resultsLink}}}{{{nextLink}}}
	</div><!-- end detailTop -->
	<div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgLeft">
			{{{previousLink}}}{{{resultsLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
	<div class='col-xs-12 col-sm-10 col-md-10 col-lg-10'>

		<div class="row">


			<?php
			
			$mimes = new MimeTypes();
			$mimetypes = $mimes->mimetypes();
			?>

			<div class='col-md-12 col-lg-12'>
				<H1>{{{^ca_collections.preferred_labels.name}}}</H1>
				<H2>{{{^ca_collections.type_id}}}{{{<ifdef code="ca_collections.idno">, ^ca_collections.idno</ifdef>}}}</H2>
				{{{<ifdef code="ca_collections.parent_id"><div class="unit">Part of: <unit relativeTo="ca_collections.hierarchy" delimiter=" &gt; "><l>^ca_collections.preferred_labels.name</l></unit></div></ifdef>}}}

				<?php
				if ($vn_pdf_enabled) {
					print "<div class='exportCollection'><i class='far fa-file-pdf' aria-label='" . _t("Download") . "'></i> " . caDetailLink($this->request, "Download as PDF", "", "ca_collections",  $vn_top_level_collection_id, array('view' => 'pdf', 'export_format' => '_pdf_ca_collections_summary')) . "</div>";
				}
				?>
			</div><!-- end col -->
		</div><!-- end row -->

		<?php
			// Initialize the database connection and get the collection ID
			$o_db = new Db();
			$s_object = new ObjectSearch();
			$s_place = new PlaceSearch();
			$object_id_array = explode(";", $t_item->get("ca_objects.object_id"));
			
		?>

		<?php
			$labels_places = [];

			$places = $o_db->query("SELECT object_id, place_id FROM ca_objects_x_places WHERE object_id IN (?)", array($object_id_array));
			
				while ($places->nextRow()) {
					$place_id = $places->get("place_id");
					
			
					$places_ids = $s_place->search("ca_places.place_id:$place_id");
					while ($places_ids->nextHit()) {
						$place_coords = $places_ids->get("ca_places.coordinates");
					}
					
					if($place_coords){ 
						$object_id = $places->get("object_id");

						$items_labels = $s_object->search("ca_objects.object_id:$object_id");
						while ($items_labels->nextHit()) {
							$item_label = $items_labels->get("ca_objects.preferred_labels");
						}
			
						$labels_places[$item_label] = $place_coords;
					}
				}
			
			if($labels_places){
			// Initialize arrays for georeference and titles
			$georeference = array();
			$titles = array();

			// Iterate through the original array to populate the new arrays
			foreach ($labels_places as $label => $coordinate) {
				$georeference[] = $coordinate;
				$titles[] = $label;
			}
			
				print "<div id='map' style='height: 400px;'></div>";
			}
			

		?>

		

		<div class="row">
			<div class='col-sm-8 col-md-8 col-lg-8'>
				<label>People</label>
			</div>
		</div>

		<?php
		$v_ents = 0;

		$qr_entities = $t_item->get('ca_entities_x_collections.entity_id', array('returnAsArray' => true));
		#var_dump($qr_entities);
		$o_entities = new EntitySearch();
		foreach ($qr_entities as $qr_entity) {
			if ($v_ents == 0) {
				print "<div class='row'>";
			}
			$q_res = $o_entities->search("ca_entities.entity_id:" . $qr_entity);
			print "<div class='col-4 col-xs-4 col-sm-4 col-md-2'>";
			while ($q_res->nextHit()) {

				print "<div class='entitiesTile'>";
				$ca_entity_media = $q_res->get('ca_object_representations.media.preview170');

				if (!$ca_entity_media) {
					$ca_entity_media = caGetThemeGraphic($this->request, "people.png");
				}
				print "<div class='entitiesThumbnail'>" . $ca_entity_media . "</div>";
				print caDetailLink($this->request, "<div class='entityName'>" . $q_res->get('ca_entities.preferred_labels') . "</div>", "", "ca_entities", $q_res->get('ca_entities.entity_id'));
				print "</div>";
			}
			print "</div>";
			$v_ents++;
			if ($v_ents == 4) {
				print "</div><!-- end row -->\n";
				$v_ents = 0;
			}
		}
		if ($v_ents > 0) {
			print "</div><!-- end row -->\n";
		}

		?>
		<div class="col-sm-8 col-md-8 col-lg-8">
			<div class="row">

				<div class='col-sm-8 col-md-8 col-lg-8'>
					{{{<ifdef code="ca_collections.description"><label>About</label>^ca_collections.description<br/></ifdef>}}}
					
				
					<?php
					# Comment and Share Tools
					if ($vn_comments_enabled | $vn_share_enabled) {

						print '<div id="detailTools">';
						if ($vn_comments_enabled) {
					?>
							<div class="detailTool"><a href='#' onclick='jQuery("#detailComments").slideToggle(); return false;'><i class="far fa-comment-dots" aria-label="<?php print _t("Comments and tags"); ?>"></i>Comments (<?php print sizeof($va_comments); ?>)</a></div><!-- end detailTool -->
							<div id='detailComments'><?php print $this->getVar("itemComments"); ?></div><!-- end itemComments -->
					<?php
						}
						if ($vn_share_enabled) {
							print '<div class="detailTool"><i class="fas fa-share" aria-label="' . _t("Share") . '"></i>' . $this->getVar("shareLink") . '</div><!-- end detailTool -->';
						}
						print '</div><!-- end detailTools -->';
					}
					?>

				</div><!-- end col -->
				<div class='col-sm-8 col-md-8 col-lg-8'>
					{{{<ifcount code="ca_collections.related" min="1" max="1"><label>Related collection</label></ifcount>}}}
					{{{<ifcount code="ca_collections.related" min="2"><label>Related collections</label></ifcount>}}}
					{{{<unit relativeTo="ca_collections" delimiter="<br/>"><l>^ca_collections.related.preferred_labels.name</l> ^relationship_typename</unit>}}}


					{{{<ifcount code="ca_occurrences" min="1" max="1"><label>Related occurrence</label></ifcount>}}}
					{{{<ifcount code="ca_occurrences" min="2"><label>Related occurrences</label></ifcount>}}}
					{{{<unit relativeTo="ca_occurrences" delimiter="<br/>"><l>^ca_occurrences.preferred_labels.name</l> ^relationship_typename</unit>}}}

					{{{<ifcount code="ca_places" min="1" max="1"><label>Related place</label></ifcount>}}}
					{{{<ifcount code="ca_places" min="2"><label>Related places</label></ifcount>}}}
					{{{<unit relativeTo="ca_places" delimiter="<br/>"><l>^ca_places.preferred_labels.name</l> ^relationship_typename</unit>}}}
				</div><!-- end col -->
			</div><!-- end row -->
			<?php

			// Fetch representation IDs for the objects
			$representation_ids = [];
			foreach ($object_id_array as $object_id) {
				$representations = $o_db->query("SELECT representation_id FROM ca_objects_x_object_representations WHERE object_id = $object_id");
				while ($representations->nextRow()) {
					$representation_ids[] = $representations->get("representation_id");
				}
			}

			// Fetch mimetypes and count them
			$mimetype_counts = [];
			foreach ($representation_ids as $representation_id) {
				$mimetype_result = $o_db->query("SELECT mimetype FROM ca_object_representations WHERE representation_id = $representation_id");
				while ($mimetype_result->nextRow()) {
					$mimetype = $mimetype_result->get("mimetype");
					$mimetype_counts[$mimetype] = isset($mimetype_counts[$mimetype]) ? $mimetype_counts[$mimetype] + 1 : 1;
				}
			}
			// Initialize an array to store counts for each category
			$category_counts = [];

			// Iterate through the mimetype counts and categorize them
			foreach ($mimetype_counts as $mimetype => $count) {
				foreach ($mimetypes as $category => $mimetype_data) {
					if (in_array($mimetype, $mimetype_data['types'])) {
						$category_counts[$category] = isset($category_counts[$category]) ? $category_counts[$category] + $count : $count;
						break;
					}
				}
			}
			?>

		</div><!-- end container -->
		<div class="col-sm-4 col-md-3 col-lg-3">
			<div class="row">
				<div class='col-sm-12'>

					<?php
					if (count($object_id_array) > 0) {

						# search for case studies or words into actions objects types.

						$case_study_id = $o_db->query("SELECT item_id, name_singular FROM ca_list_item_labels WHERE name_singular = 'Case Study' OR name_singular = 'Words into Action'");

						# get the label_id for cases or words
						$works_ids = [];
						while ($case_study_id->nextRow()) {
							$works_ids[] = [$case_study_id->get("name_singular") => $case_study_id->get("item_id")];
						}

						# construct the panel (bootstrap 3)

						if ($works_ids) {
							$works_for_search = [];

							foreach ($works_ids as $work) {
								foreach ($work as $item_id) {
									$works_for_search[] = $item_id;
								}
							}

							$works_cases = $o_db->query("SELECT object_id FROM ca_objects WHERE object_id IN (?) AND type_id IN (?)", array($object_id_array, $works_for_search));
							while ($works_cases->nextRow()) {
								$works_objects = $s_object->search("ca_objects.object_id:" . $works_cases->get('ca_objects.object_id'));
								while ($works_objects->nextHit()) {
									foreach ($works_ids as $work) {
										foreach ($work as $key => $value) {
											if ($value == $works_objects->get("ca_objects.type_id")) {
												$label = $key;
												break;
											}
										}
									}
									print "<div class='panel panel-primary'>";
									print "<div class='panel-heading'>";
									print "<h3 class='panel-title'>" . $label . "</h3></div>";
									print "<div class='panel-body'>";
									print caDetailLink($this->request, $works_objects->get("ca_objects.preferred_labels") . " <i class='fas fa-file-pdf'></i>", "", "ca_objects", $works_objects->get("ca_objects.object_id"));
									print("</div></div>");
								}
							}
						}
					}

					?>
					<?php
					if ($vb_show_objects_link || $vb_show_collections_link) {
					?>
						<div class='collectionBrowseItems'>

							<?php
							if ($vb_show_objects_link) {
								print caNavLink($this->request, "<button type='button' class='btn btn-default btn-sm'><i class='far fa-eye' aria-label='Search'></i> Look inside the Collection</button>", "browseRemoveFacet", "", "browse", "objects", array("facet" => "collection_facet", "id" => $t_item->get("ca_collections.collection_id")));
							}
							if ($vb_show_collections_link) {
								print caNavLink($this->request, "<button type='button' class='btn btn-default btn-sm'><i class='fas fa-eye' aria-label='Search'></i> Look in all collection</button>", "browseRemoveFacet", "", "browse", "objects", array("facet" => "collection_facet", "id" => $t_item->get("ca_collections.collection_id")));
							}
							?>

						</div>
					<?php
					}

					if ($vb_show_hierarchy_viewer) {
					?>
						<div id="collectionHierarchy"><?php print caBusyIndicatorIcon($this->request) . ' ' . addslashes(_t('Loading...')); ?></div>
						<script>
							$(document).ready(function() {
								$('#collectionHierarchy').load("<?php print caNavUrl($this->request, '', 'Collections', 'collectionHierarchy', array('collection_id' => $t_item->get('collection_id'))); ?>");
							})
						</script>
					<?php
					}
					?>


					<div class='counter'>
						<?php
						if (!$category) {
							echo "<div class='mimetypeCat  col-4 col-xs-4 col-sm-4 col-md-2'><i class='fas fa-folder-minus'></i><div class='value'>0</div><div class='mimeLabel'>No items yet,<br>but not for long! </div></div>";
						}
						$colors = ['first', 'second', 'third', 'fourth'];

						$counter = 0;
						foreach ($category_counts as $category => $count) {
							$catData = $mimetypes[$category];
							$colorClass = 'value ' . $colors[$counter % count($colors)];
							if ($count > 1) {
								$cat_label = $catData['label'] . 's';
							} else {
								$cat_label = $catData['label'];
							}
							echo "<div class='mimetypeCat'><i class='fas fa-" . strtolower($category) . "'></i><div class='$colorClass' akhi='$count'>0</div><div class='mimeLabel'>" . strtoupper($cat_label) . "</div></div>";

							if ($counter == 4) {
								$counter = 0;
							} else {
								$counter++;
							}
						}
						?>
					</div>

				</div><!-- end col -->
			</div><!-- end row -->

		</div>

	</div><!-- end col -->
	<div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgRight">
			{{{nextLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
</div><!-- end row -->


<script>
	const counters = document.querySelectorAll('.value');
	const speed = 200;

	counters.forEach(counter => {
		const animate = () => {
			const value = +counter.getAttribute('akhi');
			const data = +counter.innerText;

			const time = value / speed;
			if (data < value) {
				counter.innerText = Math.ceil(data + time);
				setTimeout(animate, 1);
			} else {
				counter.innerText = value;
			}
		}

		animate();
	});
</script>

<script>
    var georeference = <?php echo json_encode($georeference); ?>;
    var titles = <?php echo json_encode($titles); ?>;
 
    // Initialize variables for calculating the average coordinates
    var totalLat = 0;
    var totalLon = 0;

    // Initialize the Leaflet map
    var map = L.map('map').setView([0, 0], 6);
    
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    georeference.forEach(function(coordinate, index) {
        // Split the coordinate into latitude and longitude
        var [lat, lon] = coordinate.replace('[', '').replace(']', '').split(',');
 
        // Convert string values to numbers
        lat = parseFloat(lat);
        lon = parseFloat(lon);

        // Add the converted values to the totals
        totalLat += lat;
        totalLon += lon;
 
        // Get the title for the current object
        var title = titles[index];
 
        // Customize the popup content with the retrieved title
        var popupContent = "Title: " + title;
 
        // Create markers and popups, and add them to the map
        var marker = L.marker([lat, lon]).addTo(map);
        marker.bindPopup(popupContent);
    });

    // Calculate the average coordinates
    var avgLat = totalLat / georeference.length;
    var avgLon = totalLon / georeference.length;

    // Set the center of the map based on the average coordinates
    map.setView([avgLat, avgLon]);

	// Fit the map to contain all the markers
    var bounds = L.latLngBounds(georeference.map(function(coordinate) {
        var [lat, lon] = coordinate.replace('[', '').replace(']', '').split(',');
        return [parseFloat(lat), parseFloat(lon)];
    }));

	map.fitBounds(bounds);
</script>
