<?php

extract( $parametersArray, EXTR_PREFIX_ALL, '_param' );	//  an underscore is added at the end of prefix :-(

 //<b>$_param_someParam</b>

// CAUTION: The corresponding $_param_ values must be one of the keys of the following array
$_preFragmentsArray =
array(
);

$_fragmentsArray =
	array(
		'tableTooltips' => <<<EOH
			<script>
				var tableTooltips = [
					{ 
						"id": "tableName", 
						"tooltipText": "Name of the table", 
						"options": {
							"placement": "right"
						} 
					},
					{ 
						"id": "description", 
						"tooltipText": "Descriptive label for administration menu" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "subtableDescription", 
						"tooltipText": "Descriptive label for when it is used as a subtable" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "orderingColumnForListings", 
						"tooltipText": "Column(s) to use for ordering in administration listings" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "appearsInAdminMenu", 
						"tooltipText": "Should appear in administration menu" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "adminPresentation", 
						"tooltipText": "Type of presentation in administration" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "adminItemsPerPage", 
						"tooltipText": "Items per page in administration list" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "adminListMarkingCondition", 
						"tooltipText": "Condition to be used for highlighting when presenting as a list in administration" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "adminListMarkedStyle", 
						"tooltipText": "Extra css class to add for highlighting when presenting as a list in administration" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "groupedByTable", 
						"tooltipText": "Parent table" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "remoteGroupColumn", 
						"tooltipText": "Remote column to use as a foreign key" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "localGroupColumn", 
						"tooltipText": "Local column to store foreign key" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "tablesGroupedByThis", 
						"tooltipText": "Space separated list of subtables" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "hasActivationFlag", 
						"tooltipText": "Rows should have activation switch" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "availableForSearching", 
						"tooltipText": "This table should be searchable in administration" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "showIdInAdminLists", 
						"tooltipText": "Should show ID of rows in administration lists" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "showIdInAdminForms", 
						"tooltipText": "Should show ID while editing row in administration" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "hasGhostTable", 
						"tooltipText": "Should store all changes and deletions in a separate history table (AKA ghost table)" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "hasDeletedColumn", 
						"tooltipText": "Should implement soft deletion i.e. rows are not deleted, rather marked as deleted" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "hasEmbededPictures", 
						"tooltipText": "Should have image adding facilities while editing rows" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "columnForMultipleTemplates", 
						"tooltipText": "Column to use for determining which template to use" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "dbEngine", 
						"tooltipText": "InnoDB (default), MyISAM" ,
			        	"options": {
							"placement": "right"
						}
					}
				];
			        		
			    $(document).ready(function() {
					readTooltips(tableTooltips);
				});
			</script>	
EOH
		,
		'columnTooltips' => <<<SOMEHTMLSTRING
			<script>
				var columnTooltips = [
					{ 
						"id": "name", 
						"tooltipText": "The name of the column. Use camel case convention (e.g. someKindOfField).<br>Use the 'Id' suffix for foreign key fields (e.g. someFieldId). <br>Make it empty in Edit Column in order to drop the column", 
						"options": {
							"placement": "right", "html": "true"
						} 
					},
					{ 
						"id": "description", 
						"tooltipText": "Also used as the field's Label in wooof_administration." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "type", 
						"tooltipText": "Choose the Database Type" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "length", 
						"tooltipText": "The length of the column (relevant to its Type above)." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "defaultValue", 
						"tooltipText": "Provide a value to be used as the Default for this column. Alternatively, leave it empty" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "notNull", 
						"tooltipText": "Do not allow NULLs in DB if checked. Currently '' (empty string) can be saved though!" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "orderingMirror", 
						"tooltipText": "orderingMirror. Ignore for now." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "searchingMirror", 
						"tooltipText": "searchingMirror. Ignore for now." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "ordering", 
						"tooltipText": "Used for ordering columns in presentations (e.g. in wooof_administration)." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "presentationType", 
						"tooltipText": "Choose one of the WOOOF provided values. For some selections, 'Type' and 'Length' will be completed automatically." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "resizeWidth", 
						"tooltipText": "resizeWidth. For Pictures only." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "resizeHeight", 
						"tooltipText": "resizeHeight. For Pictures only." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "midSizeWidth", 
						"tooltipText": "midSizeWidth. For Pictures only." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "midSizeHeight", 
						"tooltipText": "midSizeHeight. For Pictures only." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "midSizeColumn", 
						"tooltipText": "midSizeColumn. For Pictures only." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "thumbnailWidth", 
						"tooltipText": "thumbnailWidth. For Pictures only." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "thumbnailHeight", 
						"tooltipText": "thumbnailHeight. For Pictures only." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "thumbnailColumn", 
						"tooltipText": "thumbnailColumn. For Pictures only." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "presentationParameters", 
						"tooltipText": "Optional. String here can be used as a 'class' specifier, etc." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "adminCSS", 
						"tooltipText": "adminCSS" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "isReadOnlyAfterFirstUpdate", 
						"tooltipText": "Check to make this field Read Only for updates." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "isReadOnly", 
						"tooltipText": "Check to make this field Read Only in all cases." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "isInvisible", 
						"tooltipText": "Check to hide this field from all presentations." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "appearsInLists", 
						"tooltipText": "Check to show this field in (wooof_administration) list presentations." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "isASearchableProperty", 
						"tooltipText": "isASearchableProperty. Ignore for now." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "isForeignKey", 
						"tooltipText": "Check to create a Database Foreign key constraint for this field." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "valuesTable", 
						"tooltipText": "Name of Table to which this column is a Foreign Key (Referencing Table)" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "columnToShow", 
						"tooltipText": "Name of column to show from relevant Referencing Table. Usually smg like 'name' or 'code' or 'description'." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "columnToStore", 
						"tooltipText": "Name of column from Referencing Table the value of which will be stored in this column. In most cases it should be 'id'." ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "indexParticipation", 
						"tooltipText": "Comma separated list of Index participation(s) for this column. Each element starts with the character 'i'(ndex) or 'u'(nique) or 't' (fullText), followed by a single upper case char (A-Z) denoting an index, followed by a number (1-9) denoting the order in which the column participates in the index. E.g. 'iA1, uB2'" ,
			        	"options": {
							"placement": "right"
						}
					},
					{ 
						"id": "colCollation", 
						"tooltipText": "Collation to use for this column. Automatically set to 'ascii_bin' for WOOOF specific cases. Leave empty for the default 'utf8_general_ci'. " ,
			        	"options": {
							"placement": "right"
						}
					}
				];
			        		
			    $(document).ready(function() {
					readTooltips(columnTooltips);
				});
			</script>
SOMEHTMLSTRING
		,

	);

	return $_fragmentsArray;
