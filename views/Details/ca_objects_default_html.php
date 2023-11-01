<?php
/* ----------------------------------------------------------------------
 * themes/default/views/bundles/ca_objects_default_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2013-2018 Whirl-i-Gig
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

$t_object = 			$this->getVar("item");
$va_comments = 			$this->getVar("comments");
$va_tags = 				$this->getVar("tags_array");
$vn_comments_enabled = 	$this->getVar("commentsEnabled");
$vn_share_enabled = 	$this->getVar("shareEnabled");
$vn_pdf_enabled = 		$this->getVar("pdfEnabled");
$vn_id =				$t_object->get('ca_objects.object_id');


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
		<div class="container">
			<div class="row">

				{{{representationViewer}}}

			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					{{{
					<ifdef code="ca_objects.idno"><div class="unit">Object ID: ^ca_objects.idno
		<button class="btn btn-default btn-xs pull-left button-circled" id="togglePanel" data-toggle="tooltip" data-placement="top" title="Media info">
                    <span class="glyphicon glyphicon-info-sign"></span>
					</button></div></ifdef>
				}}}
				</div>
				<div class="panel-body" id="panelContent">
					<!-- Content to be shown/hidden goes here -->
					<!-- Content and Scope -->
					<h3>Media information</h3>
					{{{
					<ifdef code="ca_object_representations.media_class"><div class="unit"><unit relativeTo="ca_object_representations.media_class" delimiter="<br/>"><b>Format:</b> ^ca_object_representations.media_class</unit></div></ifdef>
					}}}

					{{{
					<ifdef code="ca_object_representations.media_filesize"><div class="unit"><unit relativeTo="ca_object_representations.media_filesize" delimiter="<br/>"><b>Extent:</b> ^ca_object_representations.media_filesize</unit></div></ifdef>
					}}}

					<?php
					$media_format = $t_object->get('ca_object_representations.media_format');
					if ($media_format != "PDF") {
						echo "{{{<ifcode code=\"ca_object_representations.media_dimensions\"><div class=\"unit\"><unit relativeTo=\"ca_object_representations.media_dimensions\" delimiter=\"<br/>\"><b>Media dimensions:</b> ^ca_object_representations.media_dimensions</unit></div></ifcode>}}}";
					} elseif ($media_format == "PDF") {
						echo "{{{<ifcode code=\"ca_object_representations.page_count\"><div class=\"unit\"><unit relativeTo=\"ca_object_representations.page_count\" delimiter=\"<br/>\"><b>Pages:</b> ^ca_object_representations.page_count</unit></div></ifcode>}}}";
					}
					?>


					{{{
					<ifcode code="ca_object_representations.media_format"><div class="unit"><unit relativeTo="ca_object_representations.media_format" delimiter="<br/>"><b>Media format:</b> ^ca_object_representations.media_format</unit></div></ifcode>
				}}}
				</div>
			</div>


			<!-- identification -->
			<div class="row">

				<H1>{{{ca_objects.preferred_labels.name}}}</H1>

				<div class='col-sm-6 col-md-6'>

					{{{
					<ifdef code="ca_objects.alternativetitle"><div class="unit">
					<ifdef code="ca_objects.ai"><button class="btn btn-warning btn-xs pull-left warning-translation-button" id="togglePanel" data-toggle="tooltip" data-placement="top" title="^ca_objects.ai">
                    <span class="glyphicon glyphicon-warning-sign"></span>
					</button></ifdef>
					<label>Translated title:</label>^ca_objects.alternativetitle
					</div><HR></ifdef>
					}}}

					{{{<unit relativeTo="ca_collections" delimiter="<br/>"><label>Is part of:</label><l>^ca_collections.preferred_labels.name</l></unit><ifcount min="1" code="ca_collections"><HR></ifcount>}}}

					{{{
					<ifdef code="ca_objects.exlink.exlink_name"><div class="unit"><label>External Link:</label><a href="^ca_objects.exlink.exlink_url" target="_blank" class="url"><i class="fa fa-external-link" aria-hidden="true"></i>^ca_objects.exlink.exlink_name</a></div><HR></ifdef>
					}}}

					{{{
					<ifdef code="ca_objects.originalid"><div class="unit"><label>Local ID:</label>^ca_objects.originalid</div></ifdef>
					}}}

					<!-- end of identification labels -->



					<!-- Scope and Content -->

					{{{
					<ifdef code="ca_objects.description"><div class="unit"><label>Description:</label>^ca_objects.description</div></ifdef>
					}}}

					{{{<ifdef code="ca_objects.descriptionalt"><div class="unit">
					<ifdef code="ca_objects.ai"><button class="btn btn-warning btn-xs pull-left warning-translation-button" id="togglePanel" data-toggle="tooltip" data-placement="top" title="^ca_objects.ai">
                    <span class="glyphicon glyphicon-warning-sign"></span>
					</button></ifdef>
					<label>Translated description:</label>^ca_objects.descriptionalt					
				</div><HR></ifdef>}}}

					{{{<ifcount code="ca_objects.langmaterial.lang" min="1"><div class="unit"><label>Language:</label><unit relativeTo="ca_objects.langmaterial" delimiter="<br/>">^ca_objects.langmaterial.langlabel: ^ca_objects.langmaterial.language</unit></div></ifcount>}}}



{{{
<ifcount code="ca_objects.themes" restrictToRelationshipTypes="themeslist" min="1">
    <div class="unit">
        <label>Schlagworte:</label>
        <unit relativeTo="ca_objects.themes" restrictToRelationshipTypes="themeslist" delimiter="<br/">
            <a href="/ifrepo/index.php/Detail/terms/^ca_objects.themes.rank">^ca_objects.themes</a>
        </unit>
    </div>
</ifcount>
}}}






					{{{
					<ifcount code="ca_objects.keyword" min="1"><div class="unit"><label>Keywords:</label><unit relativeTo="ca_objects.keyword" delimiter="<br/>">^ca_objects.keyword</unit></div></ifcount>
					}}}

					{{{
					<ifdef code="ca_objects.notes"><div class="unit"><label>Notes:</label>^ca_objects.notes</div><HR></ifdef>
					}}}

					<!-- end of Content and Scope labels -->



					<!-- Geographical Coverage -->

					{{{<ifcount code="ca_places" min="1"><div class="unit"><ifcount code="ca_places" min="1" max="1"><label>Related place</label></ifcount><ifcount code="ca_places" min="2"><label>Related places</label></ifcount><unit relativeTo="ca_places" delimiter="<br/>"><unit relativeTo="ca_places.hierarchy" delimiter=" &gt; "><l>^ca_places.preferred_labels</l></unit></unit></div></ifcount>}}}

					<br />{{{map}}}<!-- map -->

					<!-- end of Geographical Coverage labels -->



					<!-- Socio-cultural Context -->

					{{{
					<ifcount code="ca_objects.cultgroup" min="1"><div class="unit">
						<label>Cultural Group</label>
						<unit relativeTo="ca_objects.cultgroup" delimiter="<br/>">
							^ca_objects.cultgroup
						</unit>
					</div></ifcount>
					}}}

					{{{
						<ifdef code="ca_objects.cultcontext"><div class="unit"><label>Cultural Context</label>^ca_objects.cultcontext</div></ifdef>
						}}}

					{{{
						<ifdef code="ca_objects.socialgroup"><div class="unit"><label>Social Group</label>^ca_objects.socialgroup</div></ifdef>
						}}}

					<!-- end of Socio-cultural Context -->



					<!-- Technology -->

					{{{
					<ifdef code="ca_objects.prodtech"><div class="unit"><label>Production Technique</label>^ca_objects.prodtech</div></ifcode>

				}}}

					{{{
					<ifcount code="ca_objects.equipused" min="1"><div class="unit"><label>Equipment</label><unit relativeTo="ca_objects.equipused" delimiter="<br/>">^ca_objects.equipused</unit></div></ifcount>
				}}}

					<!-- End Of Technology -->



					<!-- Dates -->

					{{{<ifcount code="ca_objects.dates.dates_value" min="1"><div class="unit"><label>Dates:</label><unit relativeTo="ca_objects.dates" delimiter="<br/>">^ca_objects.dates.dates_type: ^ca_objects.dates.dates_value</unit></div></ifcount>}}}

					<!-- end of Dates labels -->



					<!-- Intellectual Property	-->

					<?php
					if ($va_entity_rels = $t_object->get('ca_objects_x_entities.relation_id', array('returnAsArray' => true))) {
						$va_entities_by_type = array();
						foreach ($va_entity_rels as $va_key => $va_entity_rel) {
							$t_rel = new ca_objects_x_entities($va_entity_rel);
							$vn_type_id = $t_rel->get('ca_relationship_types.preferred_labels');
							$va_entities_by_type[$vn_type_id][] = caNavLink($this->request, $t_rel->get('ca_entities.preferred_labels'), '', '', 'Detail', 'entities/' . $t_rel->get('ca_entities.entity_id'));
						}
						print "<div class='unit'><label>Intellectual Property:</label>";
						foreach ($va_entities_by_type as $va_type => $va_entity_id) {
							foreach ($va_entity_id as $va_key => $va_entity_link) {
								$output = $va_type . ": " . $va_entity_link . "<br/>";
								print $output;
							}
						}
						print "</div>";
					}
					?>

					<!-- end of Intellectual Property labels -->



					<!-- Access and Sensitivity -->

					<!-- Licences display. TODO: simplify this -->


			{{{<ifdef code="ca_objects.licence">
					<div class="unit">
						<label>licence</label>
						<?php
							$licences = [
								"989" => [
									"url" => "http://creativecommons.org/licenses/by/4.0/",
									"img" => "https://i.creativecommons.org/l/by/4.0/88x31.png",
									"name" => "CC BY 4.0",
								],
								"993" => [
									"url" => "http://creativecommons.org/licenses/by-nc-nd/4.0/",
									"img" => "https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png",
									"name" => "CC BY-NC-ND 4.0",
								],
								"992" => [
									"url" => "http://creativecommons.org/licenses/by-nc-sa/4.0/",
									"img" => "https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png",
									"name" => "CC BY-NC-SA 4.0",
								],
								"991" => [
									"url" => "http://creativecommons.org/licenses/by-nd/4.0/",
									"img" => "https://i.creativecommons.org/l/by-nd/4.0/88x31.png",
									"name" => "CC BY-ND 4.0",
								],
								"990" => [
									"url" => "http://creativecommons.org/licenses/by-sa/4.0/",
									"img" => "https://i.creativecommons.org/l/by-sa/4.0/88x31.png",
									"name" => "CC BY-SA 4.0",
								],
								"994" => [
									"url" => "https://creativecommons.org/publicdomain/zero/1.0/",
									"img" => "https://i.creativecommons.org/p/zero/1.0/88x31.png",
									"name" => "CC0",
								],
							];

							$licencex = $t_object->get("ca_objects.licence");

							if (isset($licences[$licencex])) {
								$licenceInfo = $licences[$licencex];
								echo "<a rel='license' href='{$licenceInfo['url']}' target='_blank'><img alt='Creative Commons license' style='border-width:0' src='{$licenceInfo['img']}' /></a>&nbsp;&nbsp;<a rel='license' href='{$licenceInfo['url']}' target='_blank'>{$licenceInfo['name']}</a>";
							}
							?>

					</div>
				</ifdef>}}}

					{{{
					<ifdef code="ca_objects.rightsstate"><div class="unit"><label>Rights Statement:</label>^ca_objects.rightsstate</div></ifdef>
				}}}

					{{{
					<ifdef code="ca_objects.cultsens"><div class="unit"><label>Cultural Sensitivity:</label>^ca_objects.cultsens</div></ifdef>
				}}}

					{{{
					<ifdef code="ca_objects.accessrest"><div class="unit"><label>Acccess Restriction:</label>^ca_objects.accessrest</div></ifdef>
				}}}

					{{{
					<ifdef code="ca_objects.reasonforrest"><div class="unit"><label>Reasons for Restriction:</label>^ca_objects.reasonforrest</div></ifdef>
				}}}

					<!-- End of Access and Sensitivity -->



				<!-- Custom labels -->

				{{{<ifdef code="ca_objects.time_period">
					<div class="unit">
						<label>Time Period</label>
						<unit relativeTo="ca_objects.time_period" delimiter="<br/>">
							^ca_objects.time_period
						</unit>
					</div>
				</ifdef>}}}

					{{{<ifdef code="ca_objects.genre">
					<div class="unit">
						<label>Genre</label>
						<unit relativeTo="ca_objects.genre" delimiter="<br/>">
							^ca_objects.genre
						</unit>
					</div>
				</ifdef>}}}

					{{{<ifdef code="ca_objects.object">
					<div class="unit">
						<label>Objects</label>
						<unit relativeTo="ca_objects.object" delimiter="<br/>">
							^ca_objects.object
						</unit>
					</div>
				</ifdef>}}}

					{{{<ifdef code="ca_objects.emotion">
					<div class="unit">
						<label>Emotions</label>
						<unit relativeTo="ca_objects.emotion" delimiter="<br/>">
							^ca_objects.emotion
						</unit>
					</div>
				</ifdef>}}}

					{{{<ifdef code="ca_objects.wayofliving">
					<div class="unit">
						<label>Emotions</label>
						<unit relativeTo="ca_objects.wayofliving" delimiter="<br/>">
							^ca_objects.wayofliving
						</unit>
					</div>
					<HR>
				</ifdef>}}}

					<!-- end of Custom labels -->


				</div><!-- end col -->

				<div class='col-sm-1 col-md-2 col-lg-2'></div><!-- end col -->
				<div class='col-sm-5 col-md-4 col-lg-4'>
					<?php
					print "<div class='inquireButton'>" . caNavLink($this->request, "<span class='glyphicon glyphicon-envelope'></span> Inquire", "btn btn-default btn-small", "", "Contact", "Form", array("table" => "ca_objects", "id" => $t_object->get("object_id"))) . "</div>";
					?>


<HR>
				<H3>History</H3>
				<?php
					$date_created = intval($t_object->get('ca_objects.created.timestamp'));
					$item_creator = $t_object->get('ca_objects.created.user');
					$date_modified = intval($t_object->get('ca_objects.lastModified.timestamp'));
					$item_modifier = $t_object->get('ca_objects.lastModified.user');

					// Format dates to display only month, day, and year
					$date_created_formatted = date("F j Y", $date_created);
					$date_modified_formatted = date("F j Y", $date_modified);

					/* if date_created and date_modified are the same, just print date_created */
					if ($date_created_formatted == $date_modified_formatted) {
						echo "<div class='unit'><label>First online date, Posted date:</label>" . $date_created_formatted . " by " . $item_creator . "</div>";
					} else {
						echo "<div class='unit'><label>First online date, Posted date:</label>" . $date_created_formatted . " by " . $item_creator . "</div>";
						echo "<div class='unit'><label>Last modified date:</label>" . $date_modified_formatted . " by " . $item_modifier . "</div>";
					}
				?>
				<HR>

				<?php
				
				if ($va_entity_rels = $t_object->get('ca_objects_x_entities.relation_id', array('returnAsArray' => true))) {
					$va_entities_by_type = array();
					foreach ($va_entity_rels as $va_key => $va_entity_rel) {
						$t_rel = new ca_objects_x_entities($va_entity_rel);
						$vn_type_id = $t_rel->get('ca_relationship_types.preferred_labels');
						$va_entities_by_type[$vn_type_id][] = caNavLink($this->request, $t_rel->get('ca_entities.preferred_labels'), '', '', 'Detail', 'entities/'.$t_rel->get('ca_entities.entity_id'));
					}}

				 $contributors = '';

				// Check if there are creators in the array
				if (isset($va_entities_by_type['had as creator'])) {
					$creators = array_unique($va_entities_by_type['had as creator']);
					$contributors = implode(', ', $creators);
				} else {
					// If no creators, check for contributors and other entity types
					$contributorString = '';

					// Check if there are contributors in the array
					if (isset($va_entities_by_type['had as contributor'])) {
						$contributorString .= implode(', ', $va_entities_by_type['had as contributor']);
					}

					// Iterate through other entity types and add them to the contributorString
					foreach ($va_entities_by_type as $type => $entities) {
						if ($type !== 'had as contributor') {
							if (!empty($contributorString)) {
								$contributorString .= ', ';
							}
							$contributorString .= implode(', ', $entities) . " ($type)";
						}
					}

					if (!empty($contributorString)) {
						$contributors = $contributorString;
					} else {
						$contributors = 'Unknown';
					}
				}

				 
				 $yearofcreation = date("Y", $date_created);
				 $title = $t_object->get('ca_objects.preferred_labels.name');
				 $object_id = $t_object->get('ca_objects.idno');
				 $collection = $t_object->get('ca_collections.preferred_labels.name');

				 $domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

				 $citation = $contributors . " (" . $yearofcreation . ") \"" . $title . ".\" " . $object_id . ". " . $collection . ". " . "Imagining Futures. " . $domain . "/Detail/objects/" . $vn_id . ". Accessed " . date("F j, Y") . ".";

				 echo "<div class='unit'><label>Use and reproduction:</label>Cite as: " . $citation . "</div>";

				?>

				<div id="detailAnnotations"></div>
				
				<?php print caObjectRepresentationThumbnails($this->request, $this->getVar("representation_id"), $t_object, array("returnAs" => "bsCols", "linkTo" => "carousel", "bsColClasses" => "smallpadding col-sm-3 col-md-3 col-xs-4", "primaryOnly" => $this->getVar('representationViewerPrimaryOnly') ? 1 : 0)); ?>
				
<?php
				# Comment and Share Tools
				if ($vn_comments_enabled | $vn_share_enabled | $vn_pdf_enabled) {
					
					print '<div id="detailTools">';
					if ($vn_comments_enabled) {
?>				
						<div class="detailTool">
							<a href='#' onclick='jQuery("#detailComments").slideToggle(); return false;'><span class="glyphicon glyphicon-comment"></span>Comments and Tags (<?php print sizeof($va_comments) + sizeof($va_tags); ?>)</a>
						</div><!-- end detailTool -->
						<div id='detailComments'><?php print $this->getVar("itemComments");?></div><!-- end itemComments -->
<?php				
					}
					if ($vn_share_enabled) {
						print '<div class="detailTool"><span class="glyphicon glyphicon-share-alt"></span>'.$this->getVar("shareLink").'</div><!-- end detailTool -->';
					}
					if ($vn_pdf_enabled) {
						print "<div class='detailTool'><span class='glyphicon glyphicon-file'></span>".caDetailLink($this->request, "Download as PDF", "faDownload", "ca_objects",  $vn_id, array('view' => 'pdf', 'export_format' => '_pdf_ca_objects_summary'))."</div>";
					}
?>
					<div class='detailTool'><a href='#' onclick='caMediaPanel.showPanel("<?= caNavUrl($this->request, '', 'Lightbox', 'addItemForm', array('object_id' => $vn_id)); ?>"); return false;' title='Add to lightbox'><span class='fa fa-suitcase'></span><?= _t('Add to favorites'); ?></a></div>
<?php
					print '</div><!-- end detailTools -->';
				}				

?>

			</div><!-- end col -->

		</div><!-- end row --></div><!-- end container -->
	</div><!-- end col -->
	<div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgRight">
			{{{nextLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
</div><!-- end row -->

<script type='text/javascript'>
	jQuery(document).ready(function() {
		$('.trimText').readmore({
		  speed: 75,
		  maxHeight: 200
		});
	});

	$(document).ready(function() {
	// Initialize Bootstrap Tooltip
    $('[data-toggle="tooltip"]').tooltip();

    // Hide the panel content initially
    $('#panelContent').hide();

    // Toggle panel content when the button is clicked
    $('#togglePanel').click(function() {
        $('#panelContent').slideToggle();
    });
});

</script>
