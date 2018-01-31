<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

new AlphabetNavigation();

class AlphabetNavigation{
	
	public $newDisplayLetterArray = array();
	private $staticLettersArray1 = array('A', 'B', 'C', 'D');
	private $staticLettersArray2 = array('W', 'X', 'Y', 'Z');	
	private $dynamicLettersArray = array('K', 'L', 'M', 'N', 'O', 'P', 'Q');		
	private $lettersFillFromArray = array('E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V');			
	private $smallLetterBlockSpacingAsStr = "......&nbsp;&nbsp;&nbsp;";		
	private $smallLetterForOneBlockSpacingAsStr = ".&nbsp;&nbsp;&nbsp;";		
	private $smallLetterForTwoBlockSpacingAsStr = "..&nbsp;&nbsp;&nbsp;";			
	private $smallLetterForThreeBlockSpacingAsStr = "...&nbsp;&nbsp;&nbsp;";				
	
	public function __construct(){
		
		if ((isset($_GET['letter'])) && (!empty($_GET['letter']))){
			$this->newDisplayLetterArray = $this->doGenerateAdjcentAlphabetChain(trim($_GET['letter']));
		}else{
			$this->doGenerateNormalAlphabetChain();
		}
	}
	
	private function doGenerateNormalAlphabetChain(){ echo $this->generateCommonNavigationLinkHtml(); }
	
	private function doGenerateAdjcentAlphabetChain($letterWasPressed){
		
		$noNeedOfSmallLetterSpacing1 = NULL;
		$noNeedOfSmallLetterSpacing2 = NULL;

		if (in_array($letterWasPressed, $this->staticLettersArray1)){
			if (array_search($letterWasPressed, $this->staticLettersArray1) == 3){
				$this->dynamicLettersArray = array_slice($this->lettersFillFromArray, 0, 6);
				$noNeedOfSmallLetterSpacing1 = true;
			}
		}else if (in_array($letterWasPressed, $this->staticLettersArray2)){
			if (array_search($letterWasPressed, $this->staticLettersArray2) == 0){
				$this->dynamicLettersArray = array_slice($this->lettersFillFromArray, count($this->lettersFillFromArray) - 6);
				$noNeedOfSmallLetterSpacing2 = true;
			}
		}else if (in_array($letterWasPressed, $this->lettersFillFromArray)){
			$letterWasPressedPosition = array_search($letterWasPressed, $this->lettersFillFromArray);
			if (
				($letterWasPressedPosition == 0) ||
				($letterWasPressedPosition == 1) ||
				($letterWasPressedPosition == 2)
			   ){
					switch($letterWasPressedPosition){
						case 0: 
							$noOfLetterToBeTaken = 5; 						
							$startPosForFirstPart = 0;
							$endPosForFirstPart = 0;
							$startPosForSecondPart = $letterWasPressedPosition + 1;
							$endPosForSecondPart = $letterWasPressedPosition + $noOfLetterToBeTaken;
						break;
						case 1: 
							$noOfLetterToBeTaken = 4; 						
							$startPosForFirstPart = 0;
							$endPosForFirstPart = 1;
							$startPosForSecondPart = $letterWasPressedPosition + 1;
							$endPosForSecondPart = $noOfLetterToBeTaken;
						break;
						case 2: 
							$noOfLetterToBeTaken = 3; 						
							$startPosForFirstPart = 0;
							$endPosForFirstPart = 2;
							$startPosForSecondPart = $letterWasPressedPosition + 1;
							$endPosForSecondPart = $noOfLetterToBeTaken;
						break;												
					}
					$noNeedOfSmallLetterSpacing1 = true;
				}else if (
						  ($letterWasPressedPosition == 15) ||
						  ($letterWasPressedPosition == 16) ||
						  ($letterWasPressedPosition == 17)
						 ){
								switch($letterWasPressedPosition){
									case 15: $noOfLetterToBeTaken = 2; break;
									case 16: $noOfLetterToBeTaken = 1; break;
									case 17: $noOfLetterToBeTaken = 0; break;												
								}
								$noNeedOfSmallLetterSpacing2 = true;
								$extraChar = 1;								
						   }else{
								$noOfLetterToBeTaken = 3; 						
								$startPosForFirstPart = $letterWasPressedPosition;
								$endPosForFirstPart = $letterWasPressedPosition - $noOfLetterToBeTaken;
								$startPosForSecondPart = $letterWasPressedPosition + 1;
								$endPosForSecondPart = $noOfLetterToBeTaken;
						   }
				$firstPart = array_slice($this->lettersFillFromArray, $startPosForFirstPart - $noOfLetterToBeTaken, $startPosForFirstPart);
				$secondPart = array_slice($this->lettersFillFromArray, $startPosForSecondPart, $endPosForSecondPart);
				$this->dynamicLettersArray = array_merge($firstPart, array($letterWasPressed), $secondPart);
						   
						   /*
				$firstPart = array_slice($this->lettersFillFromArray, ($letterWasPressedPosition - $noOfLetterToBeTaken), $noOfLetterToBeTaken);
				$secondPart = array_slice($this->lettersFillFromArray, ($letterWasPressedPosition + 1), $noOfLetterToBeTaken + $extraChar);
				$this->dynamicLettersArray = array_merge($firstPart, array($letterWasPressed), $secondPart);
				*/
		}
		echo $this->generateCommonNavigationLinkHtml($noNeedOfSmallLetterSpacing1, $noNeedOfSmallLetterSpacing2);			
	}
	
	private function generateCommonNavigationLinkHtml($noNeedOfSmallLetterSpacing1 = NULL, $noNeedOfSmallLetterSpacing2 = NULL){

		$letterChainAsStr = "";
		$letterWrapperAsLink = "<a href='?letter=*'>*</a>&nbsp;&nbsp;&nbsp;";
		foreach($this->staticLettersArray1 as $eachLetter){
			$letterChainAsStr .= str_replace("*", $eachLetter, $letterWrapperAsLink);
		}
		$letterChainAsStr .= (($noNeedOfSmallLetterSpacing1 != NULL) && ($noNeedOfSmallLetterSpacing1)) ? "" : $this->smallLetterBlockSpacingAsStr;
		foreach($this->dynamicLettersArray as $eachLetter){
			$letterChainAsStr .= str_replace("*", $eachLetter, $letterWrapperAsLink);
		}
		$letterChainAsStr .= (($noNeedOfSmallLetterSpacing2 != NULL) && ($noNeedOfSmallLetterSpacing2)) ? "" : $this->smallLetterBlockSpacingAsStr;
		foreach($this->staticLettersArray2 as $eachLetter){
			$letterChainAsStr .= str_replace("*", $eachLetter, $letterWrapperAsLink);
		}
		return $letterChainAsStr;
	}
}
?>