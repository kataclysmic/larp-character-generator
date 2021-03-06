<?php

	/**************************************************************
	NAME: 	charDetails.php
	NOTES: 	Displays details about a specific character.
	TO DO:	  
	**************************************************************/
	
	$pageAccessLevel = 'User';
	
	require_once('../includes/config.php');
	require_once(LOCATION . 'includes/library.php');
	require(LOCATION . 'class/classloader.php');
	require_once(LOCATION . 'includes/authenticate.php');
	
	if (!isset($_GET['characterID'])) {
	  header('Location: index.php'); // Take user to main page
	  exit();   
	}
		
	$character = new Character();
	
	if (!$character->checkCharacterAccess($_GET['characterID'], $_SESSION['playerID'])) {
		header('Location: index.php'); // Take user to main page
	  	exit();	
	}
	
	$charDetails = $character->getCharDetails($_GET['characterID']);
	$charHeaders = $character->getCharHeaders($_GET['characterID']);
	$charTraits = $character->getCharTraits($_GET['characterID']);
	$charFeats = $character->getCharFeats($_GET['characterID']);
	
	$charTotalCP = $character->getTotalCharCP($_GET['characterID']);
	$charFreeCP = $character->getCharFreeCP($_GET['characterID']);
	
	$title = "Character Details | " . $_SESSION['campaignName'] . " Character Generator";

	include('../includes/header.php');

?>

<body id="charDetailsPage">

    <?php include('../includes/mastNav.php'); ?>
    
    <div id="content">
        
        
        <div id="main">
			
			<?php 
				while ($row = $charDetails->fetch_assoc()) {
			?>
			
			<h2><?php echo $row['charName']; ?></h2>
			<a href="wizard.php?characterID=<?php echo $row['characterID']; ?>" class="editLink">Edit Character</a>

			<div id="charStats">
				<div class="statBox">
					<p><?php echo $charTotalCP; ?></p>
					<p class="lbl">Total CP</p>
				</div>
				<div class="statBox">
					<p><?php echo $charFreeCP; ?></p>
					<p class="lbl">Unspent CP</p>
				</div>
				<div class="statBox">
					<p><?php echo $row['vitality']; ?></p>
					<p class="lbl"><?php echo $_SESSION['vitalityLabel']; ?></p>
				</div>
			</div>
			
			
			<div id="basics">
				<table id="basicsTable" cellpadding="0" cellspacing="0">
					<tbody>
						<tr class="odd">
							<th class="col1"><?php echo $_SESSION['attribute1Label']; ?></th>
							<td class="col2"><?php echo $row['attribute1']; ?></td>
							<th class="col3">Native Country</th>
							<td class="col4"><?php echo $row['countryName']; ?></td>
						</tr>
						<tr class="even">
							<th class="col1"><?php echo $_SESSION['attribute2Label']; ?></th>
							<td class="col2"><?php echo $row['attribute2']; ?></td>
							<th class="col3"><?php echo $_SESSION['communityLabel']; ?></th>
							<td class="col4"><?php echo $row['communityName']; ?></td>
						</tr>
						<tr class="odd">
							<th class="col1"><?php echo $_SESSION['attribute3Label']; ?></th>
							<td class="col2"><?php echo $row['attribute3']; ?></td>
							<th class="col3">&nbsp;</th>
							<td class="col4">&nbsp;</td>
						</tr>
						<tr class="even">
							<th class="col1"><?php echo $_SESSION['attribute4Label']; ?></th>
							<td class="col2"><?php echo $row['attribute4']; ?></td>
							<?php
								if ($_SESSION['useRaces'] == 'yes') {
							?>
								<th class="col3">Race</th>
								<td class="col4"><?php echo $row['raceName']; ?></td>
							<?php 
								} else {
							?>
								<th class="col3">&nbsp;</th>
								<td class="col4">&nbsp;</td>
							<?php
								} // end races condition
							?>
						</tr>
						<tr class="odd">
							<th class="col1"><?php echo $_SESSION['attribute5Label']; ?></th>
							<td class="col2"><?php echo $row['attribute5']; ?></td>
							<th class="col3">&nbsp;</th>
							<td class="col4">&nbsp;</td>
						</tr>
						<tr class="even">
							<th class="col1"><?php echo $_SESSION['vitalityLabel']; ?></th>
							<td class="col2"><?php echo $row['vitality']; ?></td>
							<th class="col3">&nbsp;</th>
							<td class="col4">&nbsp;</td>
						</tr>
					</tbody>	
				</table>
			</div><!--/basics-->
            
            <div id="charDetailsTabPanel" class="tabPanel">
                <a href="#" id="summaryTab" class="selected">Summary</a>
                <a href="#" id="detailsTab">Details</a>
                <br class="clear" />
            </div>
            
            <!--**************************************************
            	SUMMARY VIEW
                **************************************************-->
			
            <div id="summaryView" class="section-tabbed">
                
                <h3>Headers &amp; Skills</h3>
                <?php 
                    while ($header = $charHeaders->fetch_assoc()) { // Loop through headers
                ?>
                <div class="header">
                    <h4><?php echo $header['headerName']; ?></h4>
                    <div class="skills">
                        
                        <?php 
                            $headerSkills = $character->getCharSkillsByHeader($header['headerID'], $_GET['characterID']);
                            while ($skill = $headerSkills->fetch_assoc()) { // Loop through skills for this header
                        ?>
                        <p><?php echo $skill['skillName']; ?> <?php if ($skill['quantity'] > 1) echo 'x ' . $skill['quantity']; ?></p>
                        <div class="spells">
                            <?php
                                $skillSpells = $character->getCharSpellsBySkill($skill['skillID'], $_GET['characterID']);
                                while ($spell = $skillSpells->fetch_assoc()) { // Loop through spells for this skill
                            ?>
                            
                            <p><?php echo $spell['spellName']; ?></p>
                            <?php 
                                } // end spells loop
                            ?>
                        </div><!--/spells-->
                        
                        <?php
                            } // end skill loop
                            if ($headerSkills->num_rows == 0) {
                                echo '<p class="empty">No skills</p>';
                            }
                        ?>
                    </div><!--/skills-->
                </div><!--/header div-->
                
                <?php 
                    } // Close headers loop
                    if ($charHeaders->num_rows == 0) {
                        echo '<div class="header"><p class="empty">None</p></div>';
                    }
                ?>        
                
            </div><!--/summaryView-->
            
            <!--**************************************************
            	DETAILED VIEW
                **************************************************-->
				
            <div id="detailedView" class="section-tabbed" style="display:none">
                <h3>Headers &amp; Skills</h3>
                <?php 
                    $charHeaders->data_seek(0); // Reset result counter to first result
                    while ($header = $charHeaders->fetch_assoc()) { // Loop through headers
                ?>
                <div class="header">
                    <h4><?php echo $header['headerName']; ?> &nbsp; <span class="cpCost">(Cost: <?php echo $header['headerCost']; ?> CP)</span></h4>
                    <div class="headerDescription">
                        <p><?php echo $header['headerDescription']; ?></p>
                    </div>
                    <div class="skills">
                        <?php 
                            $headerSkills = $character->getCharSkillsByHeader($header['headerID'], $_GET['characterID']);
                            while ($skill = $headerSkills->fetch_assoc()) { // Loop through skills for this header
                        ?>
                        <h5><?php echo $skill['skillName']; ?> <?php if ($skill['quantity'] > 1) echo 'x ' . $skill['quantity']; ?> &nbsp; <span class="cpCost">(Base cost: <?php echo $skill['skillCost']; ?> CP)</span></h5>
                        <p><?php echo $skill['skillDescription']; ?></p>
                        <div class="spells">
                            <?php
                                $skillSpells = $character->getCharSpellsBySkill($skill['skillID'], $_GET['characterID']);
                                while ($spell = $skillSpells->fetch_assoc()) { // Loop through spells for this skill
                            ?>
                            <h5><?php echo $spell['spellName']; ?> &nbsp; <span class="cpCost">(Cost: <?php echo $spell['spellCost']; ?> CP)</span></h5>
                            <p><?php echo $spell['spellDescription']; ?></p>
                            <?php
                                } // end detailedView spells loop
                            ?>
                        </div><!--/spells-->
                        <?php
                            } // end detailedView skills loop
                            if ($headerSkills->num_rows == 0) {
                                echo '<p class="empty">No skills</p>';
                            }
                        ?>
                    </div><!--/skills-->
                </div><!--/header-->
                <?php
                    } // end detailedView headers loops
                    if ($charHeaders->num_rows == 0) {
                        echo '<div class="header"><p class="empty">None</p></div>';
                    }

                ?>

            </div><!--end of detailed view-->

            <?php

				$featObj = new Feat();
				$totalFeats = $featObj->getTotalFeats();
				if ($totalFeats > 0) { // Only show feats section if there are any in the system
			?>
			
				<h3>Feats</h3>
                <?php 
                    while ($feat = $charFeats->fetch_assoc()) { // Loop through feats
                ?>
                <div class="feat">
                    <p><?php echo $feat['featName']; ?></p>
                  
                </div><!--/feat div-->
                
            <?php 
                    } // Close feats loop
                    if ($charFeats->num_rows == 0) {
                        echo '<p class="empty">None</p>';
                    }
                } // End feats condition

            ?> 
				
			<div id="traits">
				<h3>Traits</h3>
				<?php 
					while ($trait = $charTraits->fetch_assoc()) { // Loop through traits for this character
				?>
					<p><?php echo $trait['traitName']; ?></p>
				<?php
					} // Close traits loop
					if ($charTraits->num_rows == 0) {
						echo '<p class="empty">None</p>';
					}
				?>
				
			</div><!--end of traits-->
			
			<?php 
				} // end of result loop
			?>
            
        </div> <!--/main-->
        
        <br class="clear" />
    </div><!--/content-->
    
<?php include('../includes/footer.php'); ?>
